<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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

    protected $appends = ['image_url', 'status_text'];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return 'https://via.placeholder.com/400x300?text=No+Image';
    }

    // Get status text dengan auto-check expiry
    public function getStatusTextAttribute()
    {
        // Auto-check dan update jika sudah expired
        if ($this->expiry_date && Carbon::now()->greaterThan($this->expiry_date)) {
            if ($this->status !== 'kadaluarsa') {
                // Update status tanpa trigger event
                $this->updateQuietly(['status' => 'kadaluarsa']);
            }
            return 'Kadaluarsa';
        }

        return match($this->status) {
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
            'kadaluarsa' => 'Kadaluarsa',
            default => 'Tidak Diketahui'
        };
    }

    // Relationship dengan claims
    public function claims()
    {
        return $this->hasMany(VoucherClaim::class);
    }
}