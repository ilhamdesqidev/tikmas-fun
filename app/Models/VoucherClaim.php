<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // Method untuk mengecek status
    public function getStatusAttribute()
    {
        if ($this->is_used) {
            return 'tergunakan';
        }
        
        if ($this->scanned_at) {
            return 'tergunakan';
        }
        
        return 'belum_tergunakan';
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
}