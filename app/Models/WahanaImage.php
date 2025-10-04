<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WahanaImage extends Model
{
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
        return $query->where('is_active', true)->orderBy('order');
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }
        return Storage::url($this->image_path);
    }

    /**
     * Delete image file when model is deleted
     */
    protected static function booted()
    {
        static::deleting(function ($wahana) {
            if ($wahana->image_path && Storage::disk('public')->exists($wahana->image_path)) {
                Storage::disk('public')->delete($wahana->image_path);
            }
        });
    }
}