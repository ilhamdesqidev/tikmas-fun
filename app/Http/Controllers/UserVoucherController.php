<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;

class UserVoucherController extends Controller
{
    public function index()
    {
        // Ambil hanya voucher yang aktif
        $vouchers = Voucher::where('status', 'aktif')
                          ->latest()
                          ->get();
        
        return view('voucher', compact('vouchers'));
    }
}