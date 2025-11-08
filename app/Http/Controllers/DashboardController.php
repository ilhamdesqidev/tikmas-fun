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

    /**
     * Claim voucher endpoint (duplicate-safe, returns friendly errors)
     */
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

            // generate unique code (simple loop)
            $uniqueCode = strtoupper(Str::random(12));
            while (VoucherClaim::where('unique_code', $uniqueCode)->exists()) {
                $uniqueCode = strtoupper(Str::random(12));
            }

            DB::beginTransaction();
            try {
                // Attempt insert; rely on DB unique constraint to prevent duplicates in race conditions
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
                    'data'    => [
                        'unique_code' => $uniqueCode,
                        'voucher'     => $voucher,
                        'claim'       => $claim,
                    ]
                ], 200);

            } catch (QueryException $e) {
                DB::rollBack();
                $errorNo = $e->errorInfo[1] ?? null;

                // Duplicate entry (MySQL 1062)
                if ($errorNo == 1062) {
                    Log::warning('Duplicate claim prevented: ' . $e->getMessage());
                    return response()->json([
                        'success'   => false,
                        'message'   => 'Nomor telepon ini sudah pernah mengklaim voucher ini.',
                        // include technical only for debugging clients; keep message safe
                        'technical' => 'duplicate_entry'
                    ], 409);
                }

                // Other DB errors
                Log::error('DB error when claiming voucher: ' . $e->getMessage());
                return response()->json([
                    'success'   => false,
                    'message'   => 'Terjadi kesalahan pada sistem saat menyimpan klaim.',
                    'technical' => 'db_error'
                ], 500);
            }

        } catch (QueryException $e) {
            Log::error('QueryException claiming voucher: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan database. Silakan coba lagi.',
            ], 500);
        } catch (\Exception $e) {
            Log::error('Exception claiming voucher: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi nanti.',
            ], 500);
        }
    }
}
