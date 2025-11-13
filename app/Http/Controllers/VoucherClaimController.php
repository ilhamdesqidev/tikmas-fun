<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class VoucherClaimController extends Controller
{
    /**
     * Display vouchers page
     */
    public function index()
    {
        $vouchers = Voucher::where('status', 'aktif')
                          ->latest()
                          ->get();
        
        return view('vouchers.index', compact('vouchers'));
    }

    
public function show($id)
{
    // Load voucher dengan relasi claims untuk counting
    $voucher = Voucher::withCount('claims')->findOrFail($id);
    
    // Cek apakah voucher bisa diklaim
    if (!$voucher->is_available) {
        return redirect()->route('vouchers.index')
            ->with('error', 'Voucher tidak tersedia untuk diklaim.');
    }
    
    return view('vouchers.show', compact('voucher'));
}

    /**
     * Claim voucher dengan validasi ketat
     */
    public function claim(Request $request)
    {
        // ==================== VALIDASI INPUT ====================
        try {
            $validated = $request->validate([
                'voucher_id' => 'required|exists:vouchers,id',
                'user_name'  => 'required|string|min:3|max:255',
                'user_phone' => [
                    'required',
                    'string',
                    'regex:/^(08|62)[0-9]{8,12}$/', // Format Indonesia
                ],
                'user_domisili' => 'required|string|min:3|max:255', // TAMBAHAN BARU
            ], [
                'user_name.required' => 'Nama lengkap wajib diisi',
                'user_name.min' => 'Nama minimal 3 karakter',
                'user_phone.required' => 'Nomor WhatsApp wajib diisi',
                'user_phone.regex' => 'Format nomor tidak valid. Gunakan format 08xxx atau 62xxx (10-14 digit)',
                'user_domisili.required' => 'Domisili wajib diisi',
                'user_domisili.min' => 'Domisili minimal 3 karakter',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
                'errors' => $e->validator->errors()
            ], 422);
        }

        // Normalisasi nomor telepon
        $userPhone = $this->normalizePhoneNumber($validated['user_phone']);

        try {
            // ==================== CEK VOUCHER ====================
            $voucher = Voucher::findOrFail($request->voucher_id);

            // Cek status voucher
            if (!$voucher->canBeClaimed()) {
                $message = $this->getVoucherUnavailableMessage($voucher);
                
                Log::warning('Voucher tidak dapat diklaim', [
                    'voucher_id' => $voucher->id,
                    'reason' => $message,
                    'phone' => $userPhone
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'technical' => 'Voucher status: ' . $voucher->status
                ], 400);
            }

            // ==================== CEK DUPLICATE CLAIM ====================
            $existingClaim = VoucherClaim::where('voucher_id', $request->voucher_id)
                                        ->where('user_phone', $userPhone)
                                        ->first();

            if ($existingClaim) {
                Log::warning('Duplicate claim attempt blocked', [
                    'voucher_id' => $request->voucher_id,
                    'phone' => $userPhone,
                    'existing_claim_id' => $existingClaim->id,
                    'claim_date' => $existingClaim->created_at
                ]);

                return response()->json([
                    'success' => false,
                    'message' => '❌ Nomor telepon ini sudah pernah mengklaim voucher ini sebelumnya.',
                    'technical' => 'Duplicate phone number detected',
                    'data' => [
                        'claimed_at' => $existingClaim->created_at->format('d M Y H:i'),
                        'unique_code' => $existingClaim->unique_code
                    ]
                ], 409);
            }

            // ==================== CEK KUOTA ====================
            if (!$voucher->is_unlimited) {
                $remainingQuota = $voucher->remaining_quota;
                
                if ($remainingQuota <= 0) {
                    Log::warning('Voucher quota exhausted during claim', [
                        'voucher_id' => $voucher->id,
                        'phone' => $userPhone
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => '😔 Maaf, kuota voucher sudah habis.',
                        'technical' => 'Quota exhausted'
                    ], 400);
                }
            }

            // ==================== PROSES CLAIM ====================
            DB::beginTransaction();
            
            try {
                // Generate unique code yang unik
                $uniqueCode = $this->generateUniqueCode();

                // Buat claim baru
                $claim = VoucherClaim::create([
                    'voucher_id'    => $request->voucher_id,
                    'user_name'     => trim($validated['user_name']),
                    'user_phone'    => $userPhone,
                    'user_domisili' => trim($validated['user_domisili']), // TAMBAHAN BARU
                    'unique_code'   => $uniqueCode,
                    'is_used'       => false,
                ]);

                // Update status voucher jika kuota habis
                if (!$voucher->is_unlimited) {
                    $currentClaimedCount = $voucher->claims()->count();
                    
                    if ($currentClaimedCount >= $voucher->quota) {
                        $voucher->update(['status' => 'habis']);
                        
                        Log::info('Voucher quota reached, status changed to habis', [
                            'voucher_id' => $voucher->id,
                            'quota' => $voucher->quota,
                            'claimed' => $currentClaimedCount
                        ]);
                    }
                }

                DB::commit();

                // Log success
                Log::info('Voucher claimed successfully', [
                    'claim_id' => $claim->id,
                    'voucher_id' => $voucher->id,
                    'voucher_name' => $voucher->name,
                    'user_name' => $claim->user_name,
                    'user_phone' => $userPhone,
                    'user_domisili' => $claim->user_domisili,
                    'unique_code' => $uniqueCode
                ]);

                // Response success
                return response()->json([
                    'success' => true,
                    'message' => '🎉 Voucher berhasil di-claim!',
                    'data' => [
                        'claim_id'     => $claim->id,
                        'unique_code'  => $uniqueCode,
                        'voucher_name' => $voucher->name,
                        'user_name'    => $claim->user_name,
                        'user_domisili' => $claim->user_domisili,
                        'expiry_date'  => $voucher->expiry_date,
                        'claimed_at'   => $claim->created_at->format('d M Y H:i')
                    ]
                ], 200);

            } catch (QueryException $e) {
                DB::rollBack();
                
                $errorCode = $e->errorInfo[1] ?? null;

                if ($errorCode == 1062) {
                    Log::error('Database duplicate entry constraint violation', [
                        'error_message' => $e->getMessage(),
                        'voucher_id' => $request->voucher_id,
                        'phone' => $userPhone
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => '❌ Nomor telepon ini sudah pernah mengklaim voucher ini.',
                        'technical' => 'Database unique constraint violation (1062)'
                    ], 409);
                }

                Log::error('Database error during voucher claim', [
                    'error' => $e->getMessage(),
                    'code' => $errorCode,
                    'voucher_id' => $request->voucher_id,
                    'phone' => $userPhone
                ]);

                return response()->json([
                    'success' => false,
                    'message' => '⚠️ Terjadi kesalahan database. Silakan coba lagi.',
                    'technical' => 'Database query exception: ' . $errorCode
                ], 500);
            }

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            Log::error('Unexpected error during voucher claim', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'voucher_id' => $request->voucher_id ?? null,
                'phone' => $userPhone ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => '🔧 Terjadi kesalahan sistem. Silakan coba lagi atau hubungi admin.',
                'technical' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Cek status claim untuk nomor tertentu
     */
    public function checkClaim(Request $request)
    {
        $request->validate([
            'user_phone' => 'required|string',
            'voucher_id' => 'required|exists:vouchers,id'
        ]);

        $userPhone = $this->normalizePhoneNumber($request->user_phone);

        $claim = VoucherClaim::where('user_phone', $userPhone)
                            ->where('voucher_id', $request->voucher_id)
                            ->with('voucher')
                            ->first();

        if ($claim) {
            return response()->json([
                'success' => true,
                'data' => [
                    'has_claimed' => true,
                    'claim_date' => $claim->created_at->format('d M Y H:i'),
                    'unique_code' => $claim->unique_code,
                    'is_used' => $claim->is_used,
                    'status' => $claim->status_label,
                    'voucher_name' => $claim->voucher->name
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => ['has_claimed' => false]
        ]);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Generate unique code yang belum ada
     */
    private function generateUniqueCode()
    {
        $maxAttempts = 10;
        $attempt = 0;

        do {
            $uniqueCode = 'VOUC-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
            $exists = VoucherClaim::where('unique_code', $uniqueCode)->exists();
            $attempt++;
        } while ($exists && $attempt < $maxAttempts);

        if ($exists) {
            $uniqueCode = 'VOUC-' . strtoupper(Str::random(6)) . '-' . time();
        }

        return $uniqueCode;
    }

    /**
     * Normalisasi format nomor telepon
     */
    private function normalizePhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (substr($phone, 0, 2) === '62') {
            $phone = '0' . substr($phone, 2);
        }
        
        return $phone;
    }

    /**
     * Get pesan error berdasarkan status voucher
     */
    private function getVoucherUnavailableMessage($voucher)
    {
        if ($voucher->is_expired) {
            return '⏰ Voucher ini sudah kadaluarsa.';
        }
        
        if ($voucher->is_sold_out) {
            return '😔 Maaf, kuota voucher sudah habis.';
        }
        
        if ($voucher->status !== 'aktif') {
            return '❌ Voucher tidak tersedia saat ini.';
        }
        
        return '⚠️ Voucher tidak dapat diklaim saat ini.';
    }
}