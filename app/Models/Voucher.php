<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'deskripsi',
        'status',
        'image',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accessor untuk URL gambar
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/placeholder.jpg'); // atau URL placeholder lainnya
        }
        
        return asset('storage_laravel/app/public/' . $this->image);
    }

    // Accessor untuk status text
    public function getStatusTextAttribute()
    {
        $statuses = [
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
            'kadaluarsa' => 'Kadaluarsa',
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    // Accessor untuk status color
    public function getStatusColorAttribute()
    {
        $colors = [
            'aktif' => 'bg-green-500',
            'tidak_aktif' => 'bg-gray-500',
            'kadaluarsa' => 'bg-red-500',
        ];
        
        return $colors[$this->status] ?? 'bg-gray-500';
    }
}