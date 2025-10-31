<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VoucherClaim;
use App\Models\Voucher;
use App\Models\StaffCode;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class VoucherScannerController extends Controller
{
    // Middleware untuk check access
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Session::has('scanner_verified')) {
                return redirect()->route('scanner.verification');
            }

            // Check if staff has voucher scan access
            $staffId = Session::get('staff_id');
            $staff = StaffCode::find($staffId);
            
            if (!$staff || !$staff->canScanVouchers()) {
                return redirect()->route('scanner.dashboard')
                    ->with('error', 'Anda tidak memiliki akses untuk scan voucher!');
            }

            return $next($request);
        });
    }

    // Dashboard voucher scanner
    public function dashboard()
    {
        $today = Carbon::today();
        
        // Get today's statistics
        $todayScanned = VoucherClaim::whereDate('scanned_at', $today)
            ->whereNotNull('scanned_at')
            ->count();
            
        $todayTotal = VoucherClaim::whereDate('created_at', $today)
            ->count();

        // Get recent scans
        $recentScans = VoucherClaim::with('voucher')
            ->whereDate('scanned_at', $today)
            ->whereNotNull('scanned_at')
            ->orderBy('scanned_at', 'desc')
            ->limit(10)
            ->get();

        // Get staff info
        $staffName = Session::get('staff_name', 'Petugas');
        $staffRole = Session::get('staff_role', 'scanner');

        return view('scanner.voucher-dashboard', compact('todayScanned', 'todayTotal', 'recentScans', 'staffName', 'staffRole'));
    }

    // Scan voucher barcode
    public function scanVoucher(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $barcode = trim($request->barcode);
        
        try {
            // Find voucher claim by unique_code (barcode)
            $claim = VoucherClaim::with('voucher')
                ->where('unique_code', $barcode)
                ->first();

            if (!$claim) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode voucher tidak ditemukan dalam sistem!'
                ]);
            }

            // Check if voucher is expired
            if ($claim->voucher && Carbon::parse($claim->voucher->expiry_date)->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher sudah kadaluarsa!',
                    'claim' => [
                        'unique_code' => $claim->unique_code,
                        'user_name' => $claim->user_name,
                        'voucher_name' => $claim->voucher->name ?? 'Unknown',
                        'status' => 'expired'
                    ]
                ]);
            }

            // Check if already scanned
            if ($claim->scanned_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher sudah pernah digunakan!',
                    'claim' => [
                        'unique_code' => $claim->unique_code,
                        'user_name' => $claim->user_name,
                        'user_phone' => $claim->user_phone,
                        'voucher_name' => $claim->voucher->name ?? 'Unknown',
                        'claimed_at' => Carbon::parse($claim->created_at)->format('d/m/Y H:i'),
                        'scanned_at' => Carbon::parse($claim->scanned_at)->format('d/m/Y H:i'),
                        'status' => 'used'
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Voucher valid dan siap digunakan!',
                'claim' => [
                    'unique_code' => $claim->unique_code,
                    'user_name' => $claim->user_name,
                    'user_phone' => $claim->user_phone,
                    'voucher_name' => $claim->voucher->name ?? 'Unknown',
                    'voucher_description' => $claim->voucher->deskripsi ?? '',
                    'claimed_at' => Carbon::parse($claim->created_at)->format('d/m/Y H:i'),
                    'expiry_date' => $claim->voucher ? Carbon::parse($claim->voucher->expiry_date)->format('d/m/Y') : '-',
                    'status' => 'valid'
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Voucher scanner error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses voucher!'
            ]);
        }
    }

    // Use/redeem voucher
    public function useVoucher(Request $request)
    {
        $request->validate([
            'unique_code' => 'required|string'
        ]);

        try {
            $claim = VoucherClaim::with('voucher')
                ->where('unique_code', $request->unique_code)
                ->first();

            if (!$claim) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher tidak ditemukan!'
                ]);
            }

            if ($claim->scanned_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher sudah pernah digunakan!'
                ]);
            }

            // Check if expired
            if ($claim->voucher && Carbon::parse($claim->voucher->expiry_date)->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Voucher sudah kadaluarsa!'
                ]);
            }

            // Mark voucher as scanned/used
            $claim->scanned_at = now();
            $claim->scanned_by = Session::get('staff_id');
            $claim->save();

            return response()->json([
                'success' => true,
                'message' => 'Voucher berhasil digunakan!',
                'claim' => [
                    'unique_code' => $claim->unique_code,
                    'user_name' => $claim->user_name,
                    'voucher_name' => $claim->voucher->name ?? 'Unknown',
                    'scanned_at' => $claim->scanned_at->format('d/m/Y H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Use voucher error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses voucher!'
            ]);
        }
    }
}