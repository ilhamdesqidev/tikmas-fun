<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return 'https://via.placeholder.com/400x300?text=No+Image';
    }

    // Relationship dengan claims
    public function claims()
    {
        return $this->hasMany(VoucherClaim::class);
    }
}