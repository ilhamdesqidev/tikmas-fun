<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\Voucher; // Import Voucher model
use App\Models\VoucherClaim;
use App\Models\Facility;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        // Get promos for dashboard
        $promos = Promo::forDashboard()
                      ->withCount(['successfulOrders'])
                      ->limit(12)
                      ->get();
        
        // Get vouchers untuk dashboard
        $vouchers = Voucher::where('status', 'aktif')
                          ->where(function($query) {
                              $query->whereNull('expiry_date')
                                    ->orWhere('expiry_date', '>=', now());
                          })
                          ->withCount('claims')
                          ->latest()
                          ->limit(12)
                          ->get();
            
        // Get facilities untuk carousel
        $facilities = Facility::latest()
            ->take(10)
            ->get();

        // Get settings
        $settings = [];
        $settingRecords = Setting::all();
        
        foreach ($settingRecords as $setting) {
            $settings[$setting->key] = $setting->value;
        }

        return view('dashboard', compact('promos', 'vouchers', 'facilities', 'settings'));
    }
}