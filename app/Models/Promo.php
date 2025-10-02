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
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function successfulOrders()
    {
        return $this->hasMany(Order::class)->whereIn('status', ['success', 'used']);
    }

    // =====================
    // ACCESSORS
    // =====================
    
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/placeholder.jpg');
    }

    public function getBraceletDesignUrlAttribute()
    {
        return $this->bracelet_design ? asset('storage/' . $this->bracelet_design) : null;
    }

    public function getActualSoldCountAttribute()
    {
        return $this->orders()
            ->whereIn('status', ['success', 'used'])
            ->sum('ticket_quantity');
    }

    public function getTotalRevenueAttribute()
    {
        return $this->orders()
            ->whereIn('status', ['success', 'used'])
            ->sum('total_price');
    }

    public function getIsExpiredAttribute()
    {
        if (!$this->end_date) {
            return false;
        }
        
        return Carbon::now()->isAfter($this->end_date);
    }

    public function getIsNotStartedAttribute()
    {
        return Carbon::now()->isBefore($this->start_date);
    }

    public function getIsActiveAttribute()
    {
        $now = Carbon::now();
        $isAfterStart = $now->gte($this->start_date);
        $isBeforeEnd = !$this->end_date || $now->lte($this->end_date);
        
        return $this->status === 'active' && $isAfterStart && $isBeforeEnd;
    }

    public function getIsComingSoonAttribute()
    {
        return $this->status === 'coming_soon' && Carbon::now()->lt($this->start_date);
    }

    public function getCanBeOrderedAttribute()
    {
        // Hanya promo dengan status active yang bisa dipesan
        if ($this->status !== 'active') {
            return false;
        }

        // Cek tanggal
        $now = Carbon::now();
        $isAfterStart = $now->gte($this->start_date);
        $isBeforeEnd = !$this->end_date || $now->lte($this->end_date);
        
        if (!$isAfterStart || !$isBeforeEnd) {
            return false;
        }

        // Cek kuota
        if ($this->quota && $this->actual_sold_count >= $this->quota) {
            return false;
        }

        return true;
    }

    /**
     * Cek apakah promo bisa diklik (hanya yang aktif dan available)
     */
    public function getIsClickableAttribute()
    {
        return $this->can_be_ordered;
    }

    public function getStatusDisplayAttribute()
    {
        if ($this->status === 'expired' || $this->is_expired) {
            return 'expired';
        }
        
        if ($this->status === 'coming_soon' || $this->is_not_started) {
            return 'coming_soon';
        }
        
        if ($this->quota && $this->actual_sold_count >= $this->quota) {
            return 'sold_out';
        }
        
        return $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'bg-gray-500',
            'coming_soon' => 'bg-blue-500',
            'active' => 'bg-green-500',
            'inactive' => 'bg-red-500',
            'expired' => 'bg-gray-400',
            'sold_out' => 'bg-red-600'
        ];

        $displayStatus = $this->status_display;
        return $colors[$displayStatus] ?? 'bg-gray-500';
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'draft' => 'Draft',
            'coming_soon' => 'Coming Soon',
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'expired' => 'Kadaluarsa',
            'sold_out' => 'Habis'
        ];

        $displayStatus = $this->status_display;
        return $statuses[$displayStatus] ?? 'Unknown';
    }

    public function getButtonStatusAttribute()
    {
        if (!$this->can_be_ordered) {
            if ($this->status === 'coming_soon' || $this->is_not_started) {
                return [
                    'text' => 'Segera Hadir',
                    'class' => 'bg-gray-300 text-gray-500 cursor-not-allowed',
                    'clickable' => false
                ];
            } elseif ($this->status === 'expired' || $this->is_expired) {
                return [
                    'text' => 'Promo Berakhir',
                    'class' => 'bg-gray-300 text-gray-500 cursor-not-allowed',
                    'clickable' => false
                ];
            } elseif ($this->quota && $this->actual_sold_count >= $this->quota) {
                return [
                    'text' => 'Kuota Habis',
                    'class' => 'bg-gray-300 text-gray-500 cursor-not-allowed',
                    'clickable' => false
                ];
            } else {
                return [
                    'text' => 'Tidak Tersedia',
                    'class' => 'bg-gray-300 text-gray-500 cursor-not-allowed',
                    'clickable' => false
                ];
            }
        }

        return [
            'text' => 'Dapatkan Promo',
            'class' => 'bg-primary text-black hover:bg-yellow-500 cursor-pointer',
            'clickable' => true
        ];
    }

    // =====================
    // SCOPES
    // =====================
    
    public function scopeForDashboard($query)
    {
        return $query->whereIn('status', ['coming_soon', 'active', 'expired'])
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', Carbon::now()->subDays(7));
                    })
                    ->orderByRaw("
                        CASE 
                            WHEN status = 'active' AND start_date <= NOW() AND (end_date IS NULL OR end_date >= NOW()) AND (quota IS NULL OR sold_count < quota) THEN 1
                            WHEN status = 'coming_soon' THEN 2
                            WHEN status = 'expired' THEN 3
                            ELSE 4
                        END
                    ")
                    ->orderBy('featured', 'desc')
                    ->orderBy('created_at', 'desc');
    }

    public function scopeActiveAndAvailable($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', Carbon::now())
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', Carbon::now());
                    })
                    ->where(function($q) {
                        $q->whereNull('quota')
                          ->orWhereRaw('sold_count < quota');
                    });
    }

    public function scopeComingSoon($query)
    {
        return $query->where('status', 'coming_soon')
                    ->where('start_date', '>', Carbon::now());
    }

    public function scopeExpired($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'expired')
              ->orWhere(function($q2) {
                  $q2->whereNotNull('end_date')
                     ->where('end_date', '<', Carbon::now());
              });
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function updateStatusBasedOnDate()
    {
        $now = Carbon::now();
        
        if ($this->status === 'expired') {
            return;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            $this->update(['status' => 'expired']);
            return;
        }

        if ($this->status === 'coming_soon' && $now->gte($this->start_date)) {
            $this->update(['status' => 'active']);
            return;
        }

        if ($this->status === 'active' && $now->lt($this->start_date)) {
            $this->update(['status' => 'coming_soon']);
            return;
        }
    }
}