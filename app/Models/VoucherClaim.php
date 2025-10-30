<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_id',
        'user_name',
        'user_phone',
        'unique_code',
        'is_used',
    ];

    protected $casts = [
        'is_used' => 'boolean',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
