<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WahanaImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Scope for active images
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered wahana
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        // Jika null atau empty
        if (empty($this->image_path)) {
            return asset('images/placeholder-wahana.jpg');
        }

        // Jika sudah berupa URL lengkap
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }

        // Cek apakah file exists di storage
        if (Storage::disk('public')->exists($this->image_path)) {
            return Storage::url($this->image_path);
        }

        // Fallback ke placeholder jika file tidak ditemukan
        return asset('images/placeholder-wahana.jpg');
    }

    /**
     * Boot events
     */
    protected static function booted()
    {
        // Auto set order saat create
        static::creating(function ($wahana) {
            if (is_null($wahana->order)) {
                $maxOrder = static::max('order') ?? 0;
                $wahana->order = $maxOrder + 1;
            }

            // Set default is_active jika null
            if (is_null($wahana->is_active)) {
                $wahana->is_active = true;
            }
        });

        // Hapus file image saat model dihapus
        static::deleting(function ($wahana) {
            // Hanya hapus jika bukan URL eksternal
            if ($wahana->image_path && !filter_var($wahana->image_path, FILTER_VALIDATE_URL)) {
                if (Storage::disk('public')->exists($wahana->image_path)) {
                    Storage::disk('public')->delete($wahana->image_path);
                }
            }
        });

        // Hapus old image saat update dengan image baru
        static::updating(function ($wahana) {
            // Cek apakah image_path berubah
            if ($wahana->isDirty('image_path')) {
                $oldPath = $wahana->getOriginal('image_path');
                
                // Hapus old image jika bukan URL eksternal
                if ($oldPath && !filter_var($oldPath, FILTER_VALIDATE_URL)) {
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
            }
        });
    }
}