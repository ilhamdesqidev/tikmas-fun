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

    // ==================== RELASI ====================
    
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function scanner()
    {
        return $this->belongsTo(StaffCode::class, 'scanned_by');
    }

    // ==================== ATTRIBUTES ====================
    
    public function getIsExpiredAttribute()
    {
        if (!$this->voucher) {
            return false;
        }
        
        return Carbon::now()->startOfDay()->greaterThan(Carbon::parse($this->voucher->expiry_date));
    }

    public function getStatusLabelAttribute()
    {
        if ($this->is_used || $this->scanned_at) {
            return 'tergunakan';
        }
        
        if ($this->is_expired) {
            return 'kadaluarsa';
        }
        
        return 'belum_tergunakan';
    }

    public function getStatusAttribute()
    {
        return $this->status_label;
    }

    // ==================== METHODS ====================
    
    public function markAsUsed($staffId = null)
    {
        $this->update([
            'is_used' => true,
            'scanned_at' => now(),
            'scanned_by' => $staffId
        ]);
    }

    // ==================== VALIDASI NOMOR TELEPON ====================
    
    /**
     * Cek apakah nomor telepon sudah pernah claim voucher tertentu
     */
    public static function hasClaimedVoucher($voucherId, $userPhone)
    {
        return self::where('voucher_id', $voucherId)
                   ->where('user_phone', $userPhone)
                   ->exists();
    }

    /**
     * Get claim berdasarkan voucher dan nomor telepon
     */
    public static function getClaimByVoucherAndPhone($voucherId, $userPhone)
    {
        return self::where('voucher_id', $voucherId)
                   ->where('user_phone', $userPhone)
                   ->first();
    }

    /**
     * Get semua voucher yang pernah di-claim oleh nomor ini
     */
    public static function getClaimedVouchersByPhone($userPhone)
    {
        return self::where('user_phone', $userPhone)
                   ->with('voucher')
                   ->get();
    }

    /**
     * Hitung jumlah voucher yang pernah di-claim oleh nomor ini
     */
    public static function countClaimedVouchersByPhone($userPhone)
    {
        return self::where('user_phone', $userPhone)->count();
    }

    /**
     * Cek apakah nomor ini punya claim yang belum digunakan untuk voucher tertentu
     */
    public static function hasUnusedClaimForVoucher($voucherId, $userPhone)
    {
        return self::where('voucher_id', $voucherId)
                   ->where('user_phone', $userPhone)
                   ->where('is_used', false)
                   ->whereNull('scanned_at')
                   ->exists();
    }

    // ==================== SCOPES ====================
    
    public function scopeUsed($query)
    {
        return $query->where('is_used', true)->orWhereNotNull('scanned_at');
    }

    public function scopeUnused($query)
    {
        return $query->where('is_used', false)->whereNull('scanned_at');
    }

    public function scopeExpiredUnused($query)
    {
        return $query->where('is_used', false)
            ->whereNull('scanned_at')
            ->whereHas('voucher', function($q) {
                $q->where('expiry_date', '<', Carbon::now()->startOfDay());
            });
    }

    public function scopeByPhone($query, $phone)
    {
        return $query->where('user_phone', $phone);
    }

    public function scopeByVoucher($query, $voucherId)
    {
        return $query->where('voucher_id', $voucherId);
    }

    public function scopeValid($query)
    {
        return $query->where('is_used', false)
            ->whereNull('scanned_at')
            ->whereHas('voucher', function($q) {
                $q->where('expiry_date', '>=', Carbon::now()->startOfDay());
            });
    }
}