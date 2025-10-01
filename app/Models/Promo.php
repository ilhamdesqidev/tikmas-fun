<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'bracelet_design',
        'description',
        'terms_conditions',
        'original_price',
        'promo_price',
        'discount_percent',
        'start_date',
        'end_date',
        'quota',
        'sold_count',
        'status',
        'category',
        'featured'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'original_price' => 'decimal:2',
        'promo_price' => 'decimal:2',
        'discount_percent' => 'integer',
        'quota' => 'integer',
        'sold_count' => 'integer',
        'featured' => 'boolean'
    ];

    // =====================
    // RELATIONSHIPS
    // =====================
    
    /**
     * Relasi ke model Order
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get successful orders only
     */
    public function successfulOrders()
    {
        return $this->hasMany(Order::class)->whereIn('status', ['success', 'used']);
    }

    // =====================
    // ACCESSORS
    // =====================
    
    /**
     * Accessor untuk URL gambar
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/placeholder.jpg');
    }

    /**
     * Accessor untuk URL desain gelang
     */
    public function getBraceletDesignUrlAttribute()
    {
        return $this->bracelet_design ? asset('storage/' . $this->bracelet_design) : null;
    }

    /**
     * Get actual sold count from orders
     */
    public function getActualSoldCountAttribute()
    {
        return $this->orders()
            ->whereIn('status', ['success', 'used']) // PERBAIKAN: tambah status 'used'
            ->sum('ticket_quantity');
    }

    /**
     * Get total revenue from this promo
     */
    public function getTotalRevenueAttribute()
    {
        return $this->orders()
            ->whereIn('status', ['success', 'used']) // PERBAIKAN: tambah status 'used'
            ->sum('total_price');
    }

    /**
     * Method untuk cek apakah promo sudah expired
     */
    public function getIsExpiredAttribute()
    {
        if (!$this->end_date) {
            return false;
        }
        
        return Carbon::now()->isAfter($this->end_date);
    }

    /**
     * Method untuk cek apakah promo belum dimulai
     */
    public function getIsNotStartedAttribute()
    {
        return Carbon::now()->isBefore($this->start_date);
    }

    /**
     * Method untuk cek apakah promo sedang berjalan
     */
    public function getIsActiveAttribute()
    {
        $now = Carbon::now();
        
        if ($this->status !== 'active') {
            return false;
        }
        
        if ($this->is_not_started) {
            return false;
        }
        
        if ($this->is_expired) {
            return false;
        }
        
        // Cek quota berdasarkan actual sold count
        if ($this->quota && $this->actual_sold_count >= $this->quota) {
            return false;
        }
        
        return true;
    }

    /**
     * Method untuk mendapatkan status display
     */
    public function getStatusDisplayAttribute()
    {
        if ($this->is_expired) {
            return 'expired';
        }
        
        if ($this->is_not_started) {
            return 'not_started';
        }
        
        if ($this->quota && $this->actual_sold_count >= $this->quota) {
            return 'sold_out';
        }
        
        return $this->status;
    }

    /**
     * Method untuk mendapatkan warna badge status
     */
    public function getStatusColorAttribute()
    {
        switch ($this->status_display) {
            case 'active':
                return 'bg-green-500';
            case 'inactive':
                return 'bg-gray-500';
            case 'expired':
                return 'bg-red-500';
            case 'not_started':
                return 'bg-blue-500';
            case 'sold_out':
                return 'bg-orange-500';
            default:
                return 'bg-gray-500';
        }
    }

    /**
     * Method untuk mendapatkan text status
     */
    public function getStatusTextAttribute()
    {
        switch ($this->status_display) {
            case 'active':
                return 'Aktif';
            case 'inactive':
                return 'Tidak Aktif';
            case 'expired':
                return 'Kadaluarsa';
            case 'not_started':
                return 'Belum Dimulai';
            case 'sold_out':
                return 'Habis';
            default:
                return 'Tidak Diketahui';
        }
    }

    // =====================
    // SCOPES
    // =====================
    
    /**
     * Scope untuk promo yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', Carbon::now())
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', Carbon::now());
                    });
    }

    /**
     * Scope untuk promo yang expired
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('end_date')
                    ->where('end_date', '<', Carbon::now());
    }

    /**
     * Scope untuk promo yang belum dimulai
     */
    public function scopeNotStarted($query)
    {
        return $query->where('start_date', '>', Carbon::now());
    }

    // =====================
    // METHODS
    // =====================
    
    /**
     * Sync sold_count with actual orders
     */
    public function syncSoldCount()
    {
        $actualCount = $this->actual_sold_count;
        $this->update(['sold_count' => $actualCount]);
        return $actualCount;
    }

    /**
     * Get promo statistics
     */
    public function getStatistics()
    {
        return [
            'total_orders' => $this->orders()->count(),
            'successful_orders' => $this->orders()->where('status', 'success')->count(),
            'pending_orders' => $this->orders()->where('status', 'pending')->count(),
            'tickets_sold' => $this->actual_sold_count,
            'total_revenue' => $this->total_revenue,
            'average_order_value' => $this->successfulOrders()->count() > 0 
                ? $this->total_revenue / $this->successfulOrders()->count() 
                : 0,
        ];
    }
}