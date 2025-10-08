<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\Facility; // Import Facility model
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        // Get active promos
        $promos = Promo::where('status', 'active')
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get facilities untuk carousel (ganti wahana images)
        $facilities = Facility::latest()
            ->take(10) // Ambil 10 facility terbaru
            ->get();

        // Get settings
        $settings = [];
        $settingRecords = Setting::all();
        
        foreach ($settingRecords as $setting) {
            $settings[$setting->key] = $setting->value;
        }

        return view('dashboard', compact('promos', 'facilities', 'settings'));
    }
}