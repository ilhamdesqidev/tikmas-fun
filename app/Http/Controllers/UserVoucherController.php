<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;

class UserVoucherController extends Controller
{
    public function index()
{
    $vouchers = Voucher::where('status', 'aktif')
                      ->whereDate('expiry_date', '>=', now()->startOfDay())
                      ->where(function($query) {
                          $query->where('is_unlimited', true)
                                ->orWhereRaw('quota > (SELECT COUNT(*) FROM voucher_claims WHERE voucher_claims.voucher_id = vouchers.id)');
                      })
                      ->latest()
                      ->get();
    
    return view('voucher', compact('vouchers'));
}
}