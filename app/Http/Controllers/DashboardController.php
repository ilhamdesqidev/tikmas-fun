<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua promo dan urutkan berdasarkan yang diunggulkan terlebih dahulu
        // Kemudian promo aktif, lalu promo yang akan datang, dan terakhir yang expired
        $promos = Promo::orderByRaw('
            CASE 
                WHEN status = "active" AND start_date <= NOW() AND (end_date IS NULL OR end_date >= NOW()) AND (quota IS NULL OR sold_count < quota) THEN 1
                WHEN status = "active" AND start_date > NOW() THEN 2
                WHEN status = "active" AND end_date < NOW() THEN 3
                WHEN status = "active" AND quota IS NOT NULL AND sold_count >= quota THEN 4
                ELSE 5
            END
        ')
        ->orderBy('featured', 'desc')
        ->orderBy('created_at', 'desc')
        ->limit(12) // Batasi jumlah promo yang ditampilkan di slider
        ->get();
        
        return view('dashboard', compact('promos'));
    }
}