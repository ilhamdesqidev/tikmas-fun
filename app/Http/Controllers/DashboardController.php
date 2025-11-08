<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\Voucher;
use App\Models\VoucherClaim;
use App\Models\Facility;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class DashboardController extends Controller
{
    public function index()
    {
        // Update voucher yang kuotanya habis menjadi status "habis"
        Voucher::where('status', 'aktif')
               ->where('is_unlimited', false)
               ->whereRaw('quota <= (SELECT COUNT(*) FROM voucher_claims WHERE voucher_claims.voucher_id = vouchers.id)')
               ->update(['status' => 'habis']);
        
        // Update status voucher yang expired
        Voucher::whereIn('status', ['aktif', 'tidak_aktif'])
               ->where('expiry_date', '<', Carbon::now()->startOfDay())
               ->update(['status' => 'kadaluarsa']);

        // Get promos for dashboard
        $promos = Promo::forDashboard()
                      ->withCount(['successfulOrders'])
                      ->limit(12)
                      ->get();
        
        // Get vouchers untuk dashboard - Tampilkan aktif, habis, dan kadaluarsa (BUKAN tidak_aktif)
        $vouchers = Voucher::whereIn('status', ['aktif', 'habis', 'kadaluarsa'])
                          ->with('claims')
                          ->withCount('claims')
                          ->latest()
                          ->get()
                          ->sortBy(function($voucher) {
                              // Sort priority: available > sold_out > expired
                              if ($voucher->is_available) {
                                  return 1;
                              } elseif ($voucher->is_sold_out) {
                                  return 2;
                              } else {
                                  return 3;
                              }
                          })
                          ->values()
                          ->take(12);
            
        // Debug log
        \Log::info('Dashboard Vouchers count: ' . $vouchers->count());
        \Log::info('Dashboard Vouchers data: ', $vouchers->map(function($v) {
            return [
                'id' => $v->id,
                'name' => $v->name,
                'status' => $v->status,
                'effective_status' => $v->effective_status,
                'is_available' => $v->is_available,
                'is_sold_out' => $v->is_sold_out,
                'remaining_quota' => $v->remaining_quota,
            ];
        })->toArray());
            
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