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
        'last_used_at',
        'access_permissions' // ✅ TAMBAHAN BARU
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'usage_count' => 'integer',
        'last_used_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'access_permissions' => 'array', // ✅ TAMBAHAN BARU - Cast ke array
    ];

    // ============================================
    // SCOPE METHODS
    // ============================================
    
    /**
     * Scope untuk kode aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk role tertentu
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope untuk staff dengan akses tiket
     */
    public function scopeCanScanTickets($query)
    {
        return $query->whereRaw("JSON_EXTRACT(access_permissions, '$.tickets') = true");
    }

    /**
     * Scope untuk staff dengan akses voucher
     */
    public function scopeCanScanVouchers($query)
    {
        return $query->whereRaw("JSON_EXTRACT(access_permissions, '$.vouchers') = true");
    }

    // ============================================
    // USAGE TRACKING METHODS
    // ============================================
    
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

    /**
     * Alternative method - Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
        $this->update(['last_used_at' => now()]);
        
        return $this;
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
            'has_ticket_access' => $this->canScanTickets(),
            'has_voucher_access' => $this->canScanVouchers(),
        ];
    }

    // ============================================
    // ACCESS PERMISSION METHODS (✅ BARU)
    // ============================================
    
    /**
     * Check if staff has access to scan tickets
     */
    public function canScanTickets()
    {
        $permissions = $this->access_permissions ?? [];
        return $permissions['tickets'] ?? false;
    }

    /**
     * Check if staff has access to scan vouchers
     */
    public function canScanVouchers()
    {
        $permissions = $this->access_permissions ?? [];
        return $permissions['vouchers'] ?? false;
    }

    /**
     * Check if staff has any scanner access
     */
    public function hasAnyScannerAccess()
    {
        return $this->canScanTickets() || $this->canScanVouchers();
    }

    /**
     * Check if staff has both scanner access
     */
    public function hasBothScannerAccess()
    {
        return $this->canScanTickets() && $this->canScanVouchers();
    }

    /**
     * Get access list as readable text
     */
    public function getAccessListAttribute()
    {
        $permissions = $this->access_permissions ?? [];
        $access = [];
        
        if ($permissions['tickets'] ?? false) {
            $access[] = 'Tiket';
        }
        if ($permissions['vouchers'] ?? false) {
            $access[] = 'Voucher';
        }
        
        return !empty($access) ? implode(', ', $access) : 'Tidak ada akses';
    }

    /**
     * Get access list as array with icons
     */
    public function getAccessListWithIcons()
    {
        $permissions = $this->access_permissions ?? [];
        $access = [];
        
        if ($permissions['tickets'] ?? false) {
            $access[] = [
                'type' => 'tickets',
                'label' => 'Tiket',
                'icon' => '🎫',
                'color' => 'blue'
            ];
        }
        if ($permissions['vouchers'] ?? false) {
            $access[] = [
                'type' => 'vouchers',
                'label' => 'Voucher',
                'icon' => '🏷️',
                'color' => 'yellow'
            ];
        }
        
        return $access;
    }

    /**
     * Set ticket access permission
     */
    public function setTicketAccess($hasAccess = true)
    {
        $permissions = $this->access_permissions ?? [];
        $permissions['tickets'] = (bool) $hasAccess;
        $this->access_permissions = $permissions;
        $this->save();
        
        return $this;
    }

    /**
     * Set voucher access permission
     */
    public function setVoucherAccess($hasAccess = true)
    {
        $permissions = $this->access_permissions ?? [];
        $permissions['vouchers'] = (bool) $hasAccess;
        $this->access_permissions = $permissions;
        $this->save();
        
        return $this;
    }

    /**
     * Grant all scanner access
     */
    public function grantAllAccess()
    {
        $this->access_permissions = [
            'tickets' => true,
            'vouchers' => true
        ];
        $this->save();
        
        return $this;
    }

    /**
     * Revoke all scanner access
     */
    public function revokeAllAccess()
    {
        $this->access_permissions = [
            'tickets' => false,
            'vouchers' => false
        ];
        $this->save();
        
        return $this;
    }

    // ============================================
    // VALIDATION METHODS
    // ============================================
    
    /**
     * Verify if code is valid and active
     */
    public function isValid()
    {
        return $this->is_active;
    }

    /**
     * Check if staff can access specific scanner type
     */
    public function canAccessScanner($type)
    {
        return match($type) {
            'tickets', 'ticket' => $this->canScanTickets(),
            'vouchers', 'voucher' => $this->canScanVouchers(),
            default => false,
        };
    }

    // ============================================
    // ATTRIBUTE ACCESSORS
    // ============================================
    
    /**
     * Get role badge color
     */
    public function getRoleBadgeColorAttribute()
    {
        return match($this->role) {
            'admin' => 'bg-purple-100 text-purple-800',
            'supervisor' => 'bg-blue-100 text-blue-800',
            'scanner' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return $this->is_active 
            ? 'bg-green-100 text-green-800' 
            : 'bg-red-100 text-red-800';
    }

    /**
     * Get formatted last used
     */
    public function getFormattedLastUsedAttribute()
    {
        return $this->last_used_at 
            ? $this->last_used_at->diffForHumans() 
            : '-';
    }

    /**
     * Get role name in Indonesian
     */
    public function getRoleNameAttribute()
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'supervisor' => 'Supervisor',
            'scanner' => 'Scanner',
            default => ucfirst($this->role),
        };
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Aktif' : 'Nonaktif';
    }

    // ============================================
    // HELPER METHODS
    // ============================================
    
    /**
     * Activate staff code
     */
    public function activate()
    {
        $this->is_active = true;
        $this->save();
        
        return $this;
    }

    /**
     * Deactivate staff code
     */
    public function deactivate()
    {
        $this->is_active = false;
        $this->save();
        
        return $this;
    }

    /**
     * Toggle active status
     */
    public function toggleStatus()
    {
        $this->is_active = !$this->is_active;
        $this->save();
        
        return $this;
    }

    /**
     * Get full information as array
     */
    public function getFullInfo()
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'role' => $this->role,
            'role_name' => $this->role_name,
            'is_active' => $this->is_active,
            'status_text' => $this->status_text,
            'usage_count' => $this->usage_count,
            'last_used_at' => $this->formatted_last_used,
            'access_list' => $this->access_list,
            'can_scan_tickets' => $this->canScanTickets(),
            'can_scan_vouchers' => $this->canScanVouchers(),
            'has_any_access' => $this->hasAnyScannerAccess(),
            'has_both_access' => $this->hasBothScannerAccess(),
        ];
    }

    /**
     * Convert to JSON for API response
     */
    public function toApiResponse()
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'permissions' => [
                'tickets' => $this->canScanTickets(),
                'vouchers' => $this->canScanVouchers(),
            ],
            'usage' => [
                'count' => $this->usage_count,
                'last_used' => $this->last_used_at?->toIso8601String(),
            ],
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}