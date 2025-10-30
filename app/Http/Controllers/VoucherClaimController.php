<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VoucherClaimController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::where('status', 'aktif')->latest()->get();
        return view('vouchers.index', compact('vouchers'));
    }

    public function claim(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
            'user_name' => 'required|string|max:255',
            'user_phone' => 'required|string|max:15',
        ]);

        try {
            // Generate unique code
            $uniqueCode = strtoupper(Str::random(12));
            
            // Pastikan kode unik
            while (VoucherClaim::where('unique_code', $uniqueCode)->exists()) {
                $uniqueCode = strtoupper(Str::random(12));
            }

            // Simpan claim
            $claim = VoucherClaim::create([
                'voucher_id' => $request->voucher_id,
                'user_name' => $request->user_name,
                'user_phone' => $request->user_phone,
                'unique_code' => $uniqueCode,
            ]);

            // Get voucher data
            $voucher = Voucher::find($request->voucher_id);

            return response()->json([
                'success' => true,
                'message' => 'Voucher berhasil di-claim!',
                'data' => [
                    'unique_code' => $uniqueCode,
                    'voucher' => $voucher,
                    'claim' => $claim,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error claiming voucher: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal claim voucher: ' . $e->getMessage()
            ], 500);
        }
    }
}
