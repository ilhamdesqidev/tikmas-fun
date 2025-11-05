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
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    protected $appends = ['image_url', 'status_text', 'is_expired'];

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
        
        // Voucher expired jika hari ini > tanggal expiry
        // Contoh: expiry_date = 5 Desember
        // - Tanggal 5 Desember: masih aktif
        // - Tanggal 6 Desember: expired
        return Carbon::now()->startOfDay()->greaterThan(Carbon::parse($this->expiry_date));
    }

    // Get effective status (termasuk auto-expiry)
    public function getEffectiveStatusAttribute()
    {
        if ($this->isExpired()) {
            return 'kadaluarsa';
        }
        return $this->status;
    }

    // Get status text dengan auto-check expiry
    public function getStatusTextAttribute()
    {
        $effectiveStatus = $this->effective_status;

        return match($effectiveStatus) {
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
            'kadaluarsa' => 'Kadaluarsa',
            default => 'Tidak Diketahui'
        };
    }

    // Get is_expired attribute untuk mudah diakses di view
    public function getIsExpiredAttribute()
    {
        return $this->isExpired();
    }

    // Scope untuk voucher aktif (belum expired dan status aktif)
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif')
                    ->whereDate('expiry_date', '>=', Carbon::now()->startOfDay());
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
        return $query->where('status', 'aktif')
                    ->whereDate('expiry_date', '>=', Carbon::now()->startOfDay());
    }

    // Auto-update status jika expired (gunakan di controller)
    public function updateStatusIfExpired()
    {
        if ($this->isExpired() && $this->status !== 'kadaluarsa') {
            $this->update(['status' => 'kadaluarsa']);
            return true; // Status berubah
        }
        return false; // Status tidak berubah
    }

    // Relationship dengan claims
    public function claims()
    {
        return $this->hasMany(VoucherClaim::class);
    }

    // Cek apakah voucher masih bisa diklaim
    public function canBeClaimed()
    {
        return $this->effective_status === 'aktif';
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
}