<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VoucherClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'voucher_id', 
        'unique_code',
        'user_name',
        'user_phone',
        'scanned_at',
        'scanned_by',
        'is_used'
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'is_used' => 'boolean'
    ];

    protected $appends = ['status_label', 'is_expired'];

    // Relasi dengan voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    // Relasi dengan staff yang scan
    public function scanner()
    {
        return $this->belongsTo(StaffCode::class, 'scanned_by');
    }

    // Cek apakah voucher claim sudah expired
    public function getIsExpiredAttribute()
    {
        if (!$this->voucher) {
            return false;
        }
        
        return Carbon::now()->startOfDay()->greaterThan(Carbon::parse($this->voucher->expiry_date));
    }

    // Get status label dengan auto-check expiry
    public function getStatusLabelAttribute()
    {
        // Jika sudah digunakan
        if ($this->is_used || $this->scanned_at) {
            return 'tergunakan';
        }
        
        // Jika voucher sudah expired tapi belum digunakan
        if ($this->is_expired) {
            return 'kadaluarsa';
        }
        
        // Jika masih valid dan belum digunakan
        return 'belum_tergunakan';
    }

    // Method untuk mengecek status (backward compatibility)
    public function getStatusAttribute()
    {
        return $this->status_label;
    }

    // Method untuk menandai sebagai digunakan
    public function markAsUsed($staffId = null)
    {
        $this->update([
            'is_used' => true,
            'scanned_at' => now(),
            'scanned_by' => $staffId
        ]);
    }

    // Scope untuk query
    public function scopeUsed($query)
    {
        return $query->where('is_used', true)->orWhereNotNull('scanned_at');
    }

    public function scopeUnused($query)
    {
        return $query->where('is_used', false)->whereNull('scanned_at');
    }

    // Scope untuk expired claims yang belum digunakan
    public function scopeExpiredUnused($query)
    {
        return $query->where('is_used', false)
            ->whereNull('scanned_at')
            ->whereHas('voucher', function($q) {
                $q->where('expiry_date', '<', Carbon::now()->startOfDay());
            });
    }

    // ========== VALIDASI NOMOR TELEPON (FITUR BARU) ==========
    
    /**
     * Method untuk cek apakah nomor sudah pernah claim voucher ini
     * @param int $voucherId
     * @param string $userPhone
     * @return bool
     */
    public static function hasClaimedVoucher($voucherId, $userPhone)
    {
        return self::where('voucher_id', $voucherId)
                   ->where('user_phone', $userPhone)
                   ->exists();
    }

    /**
     * Method untuk mendapatkan semua voucher yang pernah di-claim oleh nomor ini
     * @param string $userPhone
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getClaimedVouchersByPhone($userPhone)
    {
        return self::where('user_phone', $userPhone)
                   ->with('voucher')
                   ->get();
    }

    /**
     * Method untuk mendapatkan jumlah voucher yang pernah di-claim oleh nomor ini
     * @param string $userPhone
     * @return int
     */
    public static function countClaimedVouchersByPhone($userPhone)
    {
        return self::where('user_phone', $userPhone)->count();
    }

    /**
     * Method untuk cek apakah nomor ini pernah claim voucher tertentu dan belum digunakan
     * @param int $voucherId
     * @param string $userPhone
     * @return bool
     */
    public static function hasUnusedClaimForVoucher($voucherId, $userPhone)
    {
        return self::where('voucher_id', $voucherId)
                   ->where('user_phone', $userPhone)
                   ->where('is_used', false)
                   ->whereNull('scanned_at')
                   ->exists();
    }

    // ========== SCOPE TAMBAHAN ==========

    /**
     * Scope untuk filter by phone number
     */
    public function scopeByPhone($query, $phone)
    {
        return $query->where('user_phone', $phone);
    }

    /**
     * Scope untuk filter by voucher
     */
    public function scopeByVoucher($query, $voucherId)
    {
        return $query->where('voucher_id', $voucherId);
    }

    /**
     * Scope untuk claims yang masih valid (belum digunakan dan belum expired)
     */
    public function scopeValid($query)
    {
        return $query->where('is_used', false)
            ->whereNull('scanned_at')
            ->whereHas('voucher', function($q) {
                $q->where('expiry_date', '>=', Carbon::now()->startOfDay());
            });
    }
}