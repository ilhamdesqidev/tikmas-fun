<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'promo_id',
        'customer_name',
        'whatsapp_number',
        'domicile',
        'branch',
        'visit_date',
        'ticket_quantity',
        'total_price',
        'status',
        'snap_token',
        'invoice_number',
        'used_at',
        'used_by_staff',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'used_at' => 'datetime',
    ];

    // Relationship dengan Promo
    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

   // Di model Order
    public function scopeValid($query)
    {
        return $query->whereIn('status', ['success', 'used']);
    }

    // Penggunaan:
    // $promo->orders()->valid()->count()

    // Scope untuk filter berdasarkan tanggal kunjungan
    public function scopeByVisitDate($query, $date)
    {
        return $query->whereDate('visit_date', $date);
    }

    // Scope untuk filter berdasarkan tanggal penggunaan
    public function scopeByUsedDate($query, $date)
    {
        return $query->whereDate('used_at', $date);
    }

    // Scope untuk tiket yang sudah digunakan hari ini
    public function scopeUsedToday($query)
    {
        return $query->where('status', 'used')
                    ->whereDate('used_at', today());
    }

    // Scope untuk tiket yang valid untuk hari ini
    public function scopeValidToday($query)
    {
        return $query->where('status', 'success')
                    ->whereDate('visit_date', today());
    }

    // Accessor untuk format tanggal Indonesia
    public function getVisitDateFormattedAttribute()
    {
        return Carbon::parse($this->visit_date)->format('d M Y');
    }

    public function getUsedAtFormattedAttribute()
    {
        return $this->used_at ? Carbon::parse($this->used_at)->format('d M Y H:i') : null;
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->created_at)->format('d M Y H:i');
    }

    // Accessor untuk format harga
    public function getTotalPriceFormattedAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    // Method untuk cek apakah tiket dapat digunakan
    public function canBeUsed()
    {
        // Hanya tiket dengan status 'success' yang bisa digunakan
        if ($this->status !== 'success') {
            return [
                'can_use' => false,
                'reason' => $this->status === 'used' 
                    ? 'Tiket sudah digunakan pada ' . $this->used_at_formatted
                    : 'Tiket belum dibayar atau tidak valid'
            ];
        }

        // Cek tanggal kunjungan
        $visitDate = Carbon::parse($this->visit_date);
        $today = Carbon::today();

        if ($visitDate->lt($today)) {
            return [
                'can_use' => false,
                'reason' => 'Tiket sudah expired. Tanggal kunjungan: ' . $this->visit_date_formatted
            ];
        }

        if ($visitDate->gt($today)) {
            return [
                'can_use' => false,
                'reason' => 'Tiket belum dapat digunakan. Tanggal kunjungan: ' . $this->visit_date_formatted
            ];
        }

        return [
            'can_use' => true,
            'reason' => 'Tiket valid dan dapat digunakan'
        ];
    }

    // Method untuk menggunakan tiket
    public function useTicket($staffName = 'Staff')
    {
        $canUse = $this->canBeUsed();
        
        if (!$canUse['can_use']) {
            return [
                'success' => false,
                'message' => $canUse['reason']
            ];
        }

        $this->update([
            'status' => 'used',
            'used_at' => now(),
            'used_by_staff' => $staffName
        ]);

        return [
            'success' => true,
            'message' => 'Tiket berhasil digunakan untuk ' . $this->customer_name
        ];
    }

    // Method untuk mendapatkan status badge color
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'success' => 'bg-green-100 text-green-800',
            'used' => 'bg-blue-100 text-blue-800',
            'expired' => 'bg-red-100 text-red-800',
            'canceled' => 'bg-gray-100 text-gray-800',
            'denied' => 'bg-red-100 text-red-800',
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    // Method untuk mendapatkan status label
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'success' => 'Lunas',
            'used' => 'Sudah Digunakan',
            'expired' => 'Kadaluarsa',
            'canceled' => 'Dibatalkan',
            'denied' => 'Ditolak',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    // Static method untuk statistik dashboard
    public static function getDashboardStats($date = null)
    {
        $date = $date ?: today();

        return [
            'total_orders_today' => self::whereDate('created_at', $date)->count(),
            'total_revenue_today' => self::whereDate('created_at', $date)
                                       ->where('status', 'success')
                                       ->sum('total_price'),
            'tickets_used_today' => self::where('status', 'used')
                                       ->whereDate('used_at', $date)
                                       ->sum('ticket_quantity'),
            'tickets_valid_today' => self::where('status', 'success')
                                        ->whereDate('visit_date', $date)
                                        ->sum('ticket_quantity'),
            'usage_percentage' => self::getUsagePercentage($date),
        ];
    }

    // Method untuk menghitung persentase penggunaan
    public static function getUsagePercentage($date = null)
    {
        $date = $date ?: today();

        $totalValid = self::where('status', 'success')
                         ->whereDate('visit_date', $date)
                         ->sum('ticket_quantity');

        $totalUsed = self::where('status', 'used')
                        ->whereDate('used_at', $date)
                        ->sum('ticket_quantity');

        return $totalValid > 0 ? round(($totalUsed / $totalValid) * 100) : 0;
    }

    // Method untuk export data scanner
    public static function getScannedTicketsReport($startDate, $endDate)
    {
        return self::where('status', 'used')
                  ->whereBetween('used_at', [$startDate, $endDate])
                  ->with('promo')
                  ->orderBy('used_at', 'desc')
                  ->get();
    }
}