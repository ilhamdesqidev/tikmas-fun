<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil promo yang aktif dan urutkan berdasarkan yang diunggulkan
        $promos = Promo::where('status', 'active')
                      ->orderBy('featured', 'desc')
                      ->orderBy('created_at', 'desc')
                      ->get();
        
        return view('dashboard', compact('promos'));
    }
}