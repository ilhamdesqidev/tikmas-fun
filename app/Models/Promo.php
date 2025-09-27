<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'bracelet_design', // Tambahkan ke fillable
        'description',
        'terms_conditions',
        'original_price',
        'promo_price',
        'discount_percent',
        'start_date',
        'end_date',
        'status',
        'quota',
        'sold_count',
        'category',
        'featured'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'original_price' => 'decimal:2',
        'promo_price' => 'decimal:2',
        'featured' => 'boolean',
    ];

    // Scope untuk promo aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }

    // Hitung diskon otomatis
    public function calculateDiscountPercent()
    {
        if ($this->original_price > 0) {
            return round((($this->original_price - $this->promo_price) / $this->original_price) * 100);
        }
        return 0;
    }

    // Cek apakah promo masih berlaku
    public function getIsValidAttribute()
    {
        if ($this->status !== 'active') return false;
        
        if ($this->end_date) {
            return now()->between($this->start_date, $this->end_date);
        }
        
        return now()->gte($this->start_date);
    }

    // Cek apakah kuota masih tersedia
    public function getHasQuotaAttribute()
    {
        if (is_null($this->quota)) return true;
        return $this->sold_count < $this->quota;
    }

    // URL gambar lengkap
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-promo.jpg');
    }

    // URL desain gelang lengkap
    public function getBraceletDesignUrlAttribute()
    {
        if ($this->bracelet_design) {
            return asset('storage/' . $this->bracelet_design);
        }
        return asset('images/default-bracelet.jpg');
    }

    // Cek apakah memiliki desain gelang
    public function getHasBraceletDesignAttribute()
    {
        return !empty($this->bracelet_design);
    }
}