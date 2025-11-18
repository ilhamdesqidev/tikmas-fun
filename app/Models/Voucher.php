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
        'syarat_ketentuan', // Tambahan
        'status',
        'image',
        'download_image',
        'expiry_date',
        'quota',
        'is_unlimited'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_unlimited' => 'boolean',
    ];

    protected $appends = ['image_url', 'download_image_url', 'status_text', 'is_expired', 'remaining_quota', 'is_available'];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return 'https://via.placeholder.com/400x300?text=No+Image';
    }

    public function getDownloadImageUrlAttribute()
    {
        if ($this->download_image) {
            return Storage::url($this->download_image);
        }
        return $this->image_url;
    }

    // Helper method untuk get syarat ketentuan dalam bentuk array
    public function getSyaratKetentuanArrayAttribute()
    {
        if (empty($this->syarat_ketentuan)) {
            // Default syarat ketentuan jika kosong
            return [
                'Voucher hanya dapat diklaim satu kali per nomor WhatsApp',
                'Tunjukkan barcode voucher saat melakukan pembayaran',
                'Voucher berlaku hingga tanggal yang tertera',
                'Voucher tidak dapat digabungkan dengan promo lain',
                'Voucher tidak dapat diuangkan'
            ];
        }
        
        // Split by newline dan filter empty
        return array_filter(
            array_map('trim', explode("\n", $this->syarat_ketentuan)),
            fn($item) => !empty($item)
        );
    }

    public function isExpired()
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return Carbon::now()->startOfDay()->greaterThan(Carbon::parse($this->expiry_date));
    }

    public function getEffectiveStatusAttribute()
    {
        if (!$this->is_unlimited && $this->remaining_quota <= 0) {
            return 'habis';
        }
        
        if ($this->isExpired()) {
            return 'kadaluarsa';
        }
        
        return $this->status;
    }

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

    public function getIsExpiredAttribute()
    {
        return $this->isExpired();
    }

    public function getRemainingQuotaAttribute()
    {
        if ($this->is_unlimited) {
            return null;
        }
        
        $claimedCount = $this->claims()->count();
        return max(0, $this->quota - $claimedCount);
    }

    public function getIsAvailableAttribute()
    {
        return $this->effective_status === 'aktif';
    }

    public function getIsSoldOutAttribute()
    {
        return !$this->is_unlimited && $this->remaining_quota <= 0;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif')
                    ->whereDate('expiry_date', '>=', Carbon::now()->startOfDay())
                    ->where(function($q) {
                        $q->where('is_unlimited', true)
                          ->orWhereRaw('quota > (SELECT COUNT(*) FROM voucher_claims WHERE voucher_claims.voucher_id = vouchers.id)');
                    });
    }

    public function scopeExpired($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'kadaluarsa')
              ->orWhereDate('expiry_date', '<', Carbon::now()->startOfDay());
        });
    }

    public function scopeValid($query)
    {
        return $this->scopeActive($query);
    }

    public function scopeSoldOut($query)
    {
        return $query->where('status', '!=', 'kadaluarsa')
                    ->whereDate('expiry_date', '>=', Carbon::now()->startOfDay())
                    ->where('is_unlimited', false)
                    ->whereRaw('quota <= (SELECT COUNT(*) FROM voucher_claims WHERE voucher_claims.voucher_id = vouchers.id)');
    }

    public function updateStatusIfNeeded()
    {
        $effectiveStatus = $this->effective_status;
        
        if ($effectiveStatus === 'habis' && $this->status !== 'habis') {
            $this->update(['status' => 'habis']);
            return 'sold_out';
        }
        
        if ($effectiveStatus === 'kadaluarsa' && $this->status !== 'kadaluarsa') {
            $this->update(['status' => 'kadaluarsa']);
            return 'expired';
        }
        
        return 'no_change';
    }

    public function claims()
    {
        return $this->hasMany(VoucherClaim::class);
    }

    public function canBeClaimed()
    {
        return $this->is_available;
    }

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