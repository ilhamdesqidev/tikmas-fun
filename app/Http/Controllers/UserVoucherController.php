<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Carbon\Carbon;

class UserVoucherController extends Controller
{
    public function index()
    {
        // Ambil semua voucher (termasuk yang habis dan expired untuk ditampilkan sebagai disabled)
        // Urutkan: Tersedia dulu, lalu habis, lalu expired
        $vouchers = Voucher::with('claims')
                          ->latest()
                          ->get()
                          ->sortBy(function($voucher) {
                              // Sort priority: 1 = available, 2 = sold out, 3 = expired
                              if ($voucher->is_available) {
                                  return 1;
                              } elseif ($voucher->is_sold_out) {
                                  return 2;
                              } else {
                                  return 3;
                              }
                          });
        
        return view('voucher', compact('vouchers'));
    }
    
    // Alternative: Jika hanya ingin menampilkan voucher yang tersedia
    public function indexAvailableOnly()
    {
        // Ambil hanya voucher yang bisa diklaim (aktif, belum expired, masih ada kuota)
        $vouchers = Voucher::where('status', 'aktif')
                          ->whereDate('expiry_date', '>=', Carbon::now()->startOfDay())
                          ->where(function($query) {
                              $query->where('is_unlimited', true)
                                    ->orWhereRaw('quota > (SELECT COUNT(*) FROM voucher_claims WHERE voucher_claims.voucher_id = vouchers.id)');
                          })
                          ->latest()
                          ->get();
        
        return view('voucher', compact('vouchers'));
    }
}