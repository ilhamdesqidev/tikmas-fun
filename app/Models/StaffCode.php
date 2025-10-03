<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'role',
        'description',
        'is_active',
        'usage_count',
        'last_used_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'usage_count' => 'integer',
        'last_used_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scope untuk kode aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk role tertentu
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // ✅ TAMBAHKAN METHOD INI
    /**
     * Record usage of staff code
     * Increment usage count and update last used timestamp
     */
    public function recordUsage()
    {
        $this->increment('usage_count');
        $this->update(['last_used_at' => now()]);
        
        return $this;
    }

    // Alternative method jika ingin lebih detail
    /**
     * Increment usage count (alternative method)
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
        $this->update(['last_used_at' => now()]);
        
        return $this;
    }

    /**
     * Verify if code is valid and active
     */
    public function isValid()
    {
        return $this->is_active;
    }

    /**
     * Get usage statistics
     */
    public function getUsageStats()
    {
        return [
            'total_usage' => $this->usage_count,
            'last_used' => $this->last_used_at ? $this->last_used_at->diffForHumans() : 'Belum pernah digunakan',
            'is_active' => $this->is_active,
        ];
    }

    // Get role badge color
    public function getRoleBadgeColorAttribute()
    {
        return match($this->role) {
            'admin' => 'bg-purple-100 text-purple-800',
            'supervisor' => 'bg-blue-100 text-blue-800',
            'scanner' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // Get status badge color
    public function getStatusBadgeColorAttribute()
    {
        return $this->is_active 
            ? 'bg-green-100 text-green-800' 
            : 'bg-red-100 text-red-800';
    }

    // Get formatted last used
    public function getFormattedLastUsedAttribute()
    {
        return $this->last_used_at 
            ? $this->last_used_at->diffForHumans() 
            : '-';
    }
}