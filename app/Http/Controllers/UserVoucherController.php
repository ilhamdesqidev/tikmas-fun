<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class UserVoucherController extends Controller
{
    public function index()
    {
        // Ambil voucher yang statusnya BUKAN 'tidak_aktif'
        // Termasuk: aktif, habis, kadaluarsa (tetap muncul tapi disabled)
        $vouchers = Voucher::whereIn('status', ['aktif', 'habis', 'kadaluarsa'])
                          ->with('claims')
                          ->latest()
                          ->get()
                          ->sortBy(function($voucher) {
                              // Sort priority: 
                              // 1 = available (aktif & belum habis & belum expired)
                              // 2 = sold out (habis)
                              // 3 = expired (kadaluarsa)
                              if ($voucher->is_available) {
                                  return 1;
                              } elseif ($voucher->is_sold_out) {
                                  return 2;
                              } else {
                                  return 3;
                              }
                          })
                          ->values(); // Reset array keys setelah sort
        
        return view('voucher', compact('vouchers'));
    }

    public function claim(Request $request)
{
    $request->validate([
        'voucher_id' => 'required|exists:vouchers,id',
        'user_name'  => 'required|string|max:255',
        'user_phone' => 'required|string|max:32',
    ]);

    try {
        $voucher = Voucher::findOrFail($request->voucher_id);

        if (!$voucher->canBeClaimed()) {
            $msg = $voucher->is_expired ? 'Voucher sudah kadaluarsa.' :
                   ($voucher->is_sold_out ? 'Kuota voucher sudah habis.' : 'Voucher tidak dapat diklaim saat ini.');
            return response()->json(['success' => false, 'message' => $msg], 400);
        }

        // generate unique code
        $uniqueCode = strtoupper(\Illuminate\Support\Str::random(12));
        while (VoucherClaim::where('unique_code', $uniqueCode)->exists()) {
            $uniqueCode = strtoupper(\Illuminate\Support\Str::random(12));
        }

        // inser dengan transaction; jika duplicate terjadi, tangani QueryException
        $claim = null;
        DB::beginTransaction();
        try {
            $claim = VoucherClaim::create([
                'voucher_id'  => $request->voucher_id,
                'user_name'   => $request->user_name,
                'user_phone'  => $request->user_phone,
                'unique_code' => $uniqueCode,
            ]);

            if (!$voucher->is_unlimited) {
                $claimedCount = $voucher->claims()->count();
                if ($claimedCount >= $voucher->quota) {
                    $voucher->update(['status' => 'habis']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Voucher berhasil di-claim!',
                'data'    => ['unique_code' => $uniqueCode, 'claim' => $claim]
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            $errNo = $e->errorInfo[1] ?? null;

            // Duplicate entry -> kembalikan 409 dengan pesan aman
            if ($errNo == 1062) {
                \Log::warning('Duplicate claim prevented: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor telepon ini sudah pernah mengklaim voucher ini.'
                ], 409);
            }

            \Log::error('DB error when claiming voucher: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan klaim voucher.'
            ], 500);
        }

    } catch (\Illuminate\Database\QueryException $e) {
        \Log::error('QueryException claiming voucher: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan database. Silakan coba lagi.'
        ], 500);
    } catch (\Exception $e) {
        \Log::error('Exception claiming voucher: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan sistem. Silakan coba lagi nanti.'
        ], 500);
    }
}
}