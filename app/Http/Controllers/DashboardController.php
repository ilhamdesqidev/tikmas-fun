<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\Voucher;
use App\Models\VoucherClaim;
use App\Models\Facility;
use App\Models\Setting;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Update status voucher yang expired
        Voucher::where('expiry_date', '<', Carbon::now()->startOfDay())
               ->where('status', '!=', 'kadaluarsa')
               ->update(['status' => 'kadaluarsa']);

        // Get promos for dashboard
        $promos = Promo::forDashboard()
                      ->withCount(['successfulOrders'])
                      ->limit(12)
                      ->get();
        
        // Get vouchers untuk dashboard - HANYA yang aktif dan belum expired
        $vouchers = Voucher::where('status', 'aktif')
                          ->where(function($query) {
                              // Voucher tanpa expiry date atau yang expiry_date >= hari ini
                              $query->whereNull('expiry_date')
                                    ->orWhere('expiry_date', '>=', Carbon::now()->startOfDay());
                          })
                          ->withCount('claims')
                          ->latest()
                          ->limit(12)
                          ->get();
            
        // Debug: Cek apakah ada voucher
        \Log::info('Vouchers count: ' . $vouchers->count());
        \Log::info('Vouchers data: ', $vouchers->toArray());
            
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