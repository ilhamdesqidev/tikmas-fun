<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\Setting;
use App\Models\WahanaImage;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all settings as array for easy access
        $settings = Setting::getForView();
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        // Get active wahana images
        $wahanaImages = WahanaImage::active()->get();
        
        // Get promos for dashboard
        $promos = Promo::forDashboard()
                      ->withCount(['successfulOrders'])
                      ->limit(12)
                      ->get();

        return view('dashboard', compact('settings', 'wahanaImages', 'promos'));
    }
}