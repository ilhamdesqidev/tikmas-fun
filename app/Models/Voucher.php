<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'deskripsi',
        'status',
        'image',
        'expiry_date',
        'quota',
        'is_unlimited'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_unlimited' => 'boolean',
    ];

    protected $appends = ['image_url', 'status_text', 'is_expired', 'remaining_quota', 'is_available'];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return 'https://via.placeholder.com/400x300?text=No+Image';
    }

    // Helper method untuk cek apakah voucher expired
    public function isExpired()
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return Carbon::now()->startOfDay()->greaterThan(Carbon::parse($this->expiry_date));
    }

    // Get effective status (termasuk auto-expiry dan kuota habis)
    public function getEffectiveStatusAttribute()
    {
        // Prioritas: Kuota habis > Expired > Status asli
        if (!$this->is_unlimited && $this->remaining_quota <= 0) {
            return 'habis';
        }
        
        if ($this->isExpired()) {
            return 'kadaluarsa';
        }
        
        return $this->status;
    }

    // Get status text dengan auto-check expiry dan kuota
    public function getStatusTextAttribute()
    {
        $effectiveStatus = $this->effective_status;

        return match($effectiveStatus) {
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
            'kadaluarsa' => 'Kadaluarsa',
            'habis' => 'Habis',
            default => 'Tidak Diketahui'
        };
    }

    // Get is_expired attribute untuk mudah diakses di view
    public function getIsExpiredAttribute()
    {
        return $this->isExpired();
    }

    // Get remaining quota
    public function getRemainingQuotaAttribute()
    {
        if ($this->is_unlimited) {
            return null; // Unlimited
        }
        
        $claimedCount = $this->claims()->count();
        return max(0, $this->quota - $claimedCount);
    }

    // Check if voucher is available for claiming
    public function getIsAvailableAttribute()
    {
        return $this->effective_status === 'aktif';
    }

    // Check if voucher is sold out
    public function getIsSoldOutAttribute()
    {
        return !$this->is_unlimited && $this->remaining_quota <= 0;
    }

    // Scope untuk voucher aktif (belum expired, status aktif, dan masih ada kuota)
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif')
                    ->whereDate('expiry_date', '>=', Carbon::now()->startOfDay())
                    ->where(function($q) {
                        $q->where('is_unlimited', true)
                          ->orWhereRaw('quota > (SELECT COUNT(*) FROM voucher_claims WHERE voucher_claims.voucher_id = vouchers.id)');
                    });
    }

    // Scope untuk voucher expired
    public function scopeExpired($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'kadaluarsa')
              ->orWhereDate('expiry_date', '<', Carbon::now()->startOfDay());
        });
    }

    // Scope untuk voucher yang masih valid (bisa diklaim)
    public function scopeValid($query)
    {
        return $this->scopeActive($query);
    }

    // Scope untuk voucher yang habis
    public function scopeSoldOut($query)
    {
        return $query->where('status', '!=', 'kadaluarsa')
                    ->whereDate('expiry_date', '>=', Carbon::now()->startOfDay())
                    ->where('is_unlimited', false)
                    ->whereRaw('quota <= (SELECT COUNT(*) FROM voucher_claims WHERE voucher_claims.voucher_id = vouchers.id)');
    }

    // Auto-update status jika expired atau habis (gunakan di controller)
    public function updateStatusIfNeeded()
    {
        $effectiveStatus = $this->effective_status;
        
        // Cek kuota habis dulu (prioritas lebih tinggi)
        if ($effectiveStatus === 'habis' && $this->status !== 'habis') {
            $this->update(['status' => 'habis']);
            return 'sold_out';
        }
        
        // Kemudian cek expired
        if ($effectiveStatus === 'kadaluarsa' && $this->status !== 'kadaluarsa') {
            $this->update(['status' => 'kadaluarsa']);
            return 'expired';
        }
        
        return 'no_change';
    }

    // Relationship dengan claims
    public function claims()
    {
        return $this->hasMany(VoucherClaim::class);
    }

    // Cek apakah voucher masih bisa diklaim
    public function canBeClaimed()
    {
        return $this->is_available;
    }

    // Format tanggal expiry untuk display
    public function getFormattedExpiryDateAttribute()
    {
        if (!$this->expiry_date) {
            return '-';
        }
        
        $expiryDate = Carbon::parse($this->expiry_date);
        $isExpired = $this->is_expired;
        
        return [
            'date' => $expiryDate->format('d M Y'),
            'is_expired' => $isExpired,
            'full_date' => $expiryDate->format('d F Y'),
        ];
    }

    // Format kuota untuk display
    public function getFormattedQuotaAttribute()
    {
        if ($this->is_unlimited) {
            return 'Unlimited';
        }
        
        $claimed = $this->claims()->count();
        $remaining = $this->remaining_quota;
        
        return "{$remaining}/{$this->quota} tersisa";
    }
}