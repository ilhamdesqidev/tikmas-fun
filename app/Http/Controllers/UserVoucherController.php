<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
            'user_name' => 'required|string|max:255',
            'user_phone' => 'required|string|max:15',
        ]);

        try {
            $voucher = Voucher::findOrFail($request->voucher_id);

            // VALIDASI 1: Cek apakah nomor telepon sudah pernah claim voucher ini
            // Ini harus paling atas sebelum validasi lainnya
            $existingClaim = VoucherClaim::where('voucher_id', $request->voucher_id)
                                        ->where('user_phone', $request->user_phone)
                                        ->first();

            if ($existingClaim) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor telepon Anda sudah pernah mengklaim voucher ini sebelumnya. Satu nomor hanya dapat mengklaim voucher yang sama sekali saja.'
                ], 400);
            }

            // VALIDASI 2: Cek apakah voucher masih bisa diklaim
            if (!$voucher->canBeClaimed()) {
                if ($voucher->is_expired) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Voucher sudah kadaluarsa.'
                    ], 400);
                }
                
                if ($voucher->is_sold_out) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maaf, kuota voucher sudah habis.'
                    ], 400);
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher tidak dapat diklaim saat ini.'
                ], 400);
            }

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

            // Update status voucher jika kuota habis setelah claim ini
            if (!$voucher->is_unlimited) {
                $claimedCount = $voucher->claims()->count();
                if ($claimedCount >= $voucher->quota) {
                    $voucher->update(['status' => 'habis']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Voucher berhasil di-claim!',
                'data' => [
                    'unique_code' => $uniqueCode,
                    'voucher' => $voucher,
                    'claim' => $claim,
                ]
            ]);

        } catch (QueryException $e) {
            // Tangkap error database constraint violation
            if ($e->getCode() === '23000') {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor telepon Anda sudah pernah mengklaim voucher ini sebelumnya.'
                ], 400);
            }
            
            Log::error('Database error claiming voucher: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada database. Silakan coba lagi.'
            ], 500);

        } catch (\Exception $e) {
            Log::error('Error claiming voucher: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi nanti.'
            ], 500);
        }
    }
}