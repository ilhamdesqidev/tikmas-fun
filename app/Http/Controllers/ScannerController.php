<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Promo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ScannerController extends Controller
{
    // Kode verifikasi petugas
    private $verificationCode = '250925';

    /**
     * Tampilkan halaman verifikasi petugas
     */
    public function showVerificationForm()
    {
        return view('scanner.verification');
    }

    /**
     * Verifikasi kode petugas
     */
    public function verifyStaff(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string'
        ]);

        $inputCode = $request->verification_code;
        $correctCode = env('STAFF_VERIFICATION_CODE', $this->verificationCode);

        if ($inputCode === $correctCode) {
            session(['staff_verified' => true, 'staff_verified_at' => now()]);
            return redirect()->route('scanner.dashboard')->with('success', 'Verifikasi berhasil! Selamat datang, Petugas.');
        }

        return back()->withErrors(['verification_code' => 'Kode verifikasi salah!']);
    }

    /**
     * Dashboard scanner untuk petugas
     */
    public function dashboard()
    {
        if (!session('staff_verified')) {
            return redirect()->route('scanner.verification')->with('error', 'Silakan masukkan kode verifikasi terlebih dahulu.');
        }

        $verifiedAt = session('staff_verified_at');
        if ($verifiedAt && Carbon::parse($verifiedAt)->diffInHours(now()) > 8) {
            session()->forget(['staff_verified', 'staff_verified_at']);
            return redirect()->route('scanner.verification')->with('error', 'Session expired. Silakan verifikasi ulang.');
        }

        // Gunakan status yang sudah ada atau cek dari kolom tambahan
        $todayUsed = $this->getTodayUsedCount();
        $todayTotal = Order::where('status', 'success')
                          ->whereDate('visit_date', today())
                          ->count();

        $recentScans = $this->getRecentScans();

        return view('scanner.dashboard', compact('todayUsed', 'todayTotal', 'recentScans'));
    }

    /**
     * Get today used count - dengan berbagai cara
     */
    private function getTodayUsedCount()
    {
        // Cek apakah ada kolom used_at
        if (\Schema::hasColumn('orders', 'used_at')) {
            return Order::whereNotNull('used_at')
                       ->whereDate('used_at', today())
                       ->count();
        }

        // Fallback: cek dari notes atau keterangan lain
        return Order::where('status', 'expired') // Gunakan status lain sebagai penanda 'used'
                   ->whereDate('updated_at', today())
                   ->where('updated_at', '>', Carbon::today()->addHours(6)) // Asumsi jam operasional
                   ->count();
    }

    /**
     * Get recent scans
     */
    private function getRecentScans()
    {
        if (\Schema::hasColumn('orders', 'used_at')) {
            return Order::whereNotNull('used_at')
                       ->whereDate('used_at', today())
                       ->with('promo')
                       ->orderBy('used_at', 'desc')
                       ->take(10)
                       ->get();
        }

        return Order::where('status', 'expired')
                   ->whereDate('updated_at', today())
                   ->with('promo')
                   ->orderBy('updated_at', 'desc')
                   ->take(10)
                   ->get();
    }

    /**
     * Scan barcode dan tampilkan detail
     */
    public function scanBarcode(Request $request)
    {
        if (!session('staff_verified')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'barcode' => 'required|string'
        ]);

        try {
            // Normalize barcode - hapus spasi dan karakter khusus
            $barcode = trim($request->barcode);
            $barcode = preg_replace('/\s+/', '', $barcode);
            
            // Cari order dengan berbagai kemungkinan field
            $order = Order::where(function($query) use ($barcode) {
                        $query->where('order_number', $barcode)
                              ->orWhere('order_number', 'like', '%' . $barcode . '%');
                    })
                    ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barcode tidak ditemukan!',
                    'order' => null
                ]);
            }

            $order->load('promo');
            $validation = $this->validateTicket($order);
            
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message'],
                    'order' => null
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Barcode berhasil di-scan!',
                'order' => [
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'whatsapp_number' => $order->whatsapp_number,
                    'branch' => $order->branch ?? 'Cabang Utama',
                    'visit_date' => Carbon::parse($order->visit_date)->format('d M Y'),
                    'ticket_quantity' => $order->ticket_quantity,
                    'total_price' => $order->total_price,
                    'status' => $order->status,
                    'promo_name' => $order->promo ? $order->promo->name : 'Unknown',
                    'created_at' => Carbon::parse($order->created_at)->format('d M Y H:i'),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Scanner error', ['error' => $e->getMessage(), 'barcode' => $request->barcode]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat scan barcode!',
                'order' => null
            ]);
        }
    }

    /**
     * Gunakan tiket - SOLUSI ALTERNATIF
     */
    public function useTicket(Request $request)
    {
        if (!session('staff_verified')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'order_number' => 'required|string'
        ]);

        try {
            $order = Order::where('order_number', $request->order_number)->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order tidak ditemukan!'
                ]);
            }

            $order->load('promo');
            $validation = $this->validateTicket($order);
            
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message']
                ]);
            }

            // SOLUSI 1: Cek apakah ada kolom used_at
            if (\Schema::hasColumn('orders', 'used_at')) {
                // Jika ada kolom used_at, tandai tiket sebagai used
                $order->used_at = now();
                if (\Schema::hasColumn('orders', 'used_by_staff')) {
                    $order->used_by_staff = 'Staff-' . substr(session()->getId(), 0, 10);
                }
                
                // Coba ubah status ke 'used' jika ENUM mendukung
                try {
                    $order->status = 'used';
                    $order->save();
                } catch (\Exception $e) {
                    // Jika ENUM tidak mendukung 'used', gunakan status lain
                    $order->status = 'expired'; // Gunakan 'expired' sebagai penanda 'used'
                    $order->save();
                    
                    Log::info('Used expired status as used marker', [
                        'order_number' => $order->order_number,
                        'reason' => 'ENUM does not support used status'
                    ]);
                }
            } else {
                // SOLUSI 2: Fallback tanpa kolom used_at
                // Gunakan kombinasi status + waktu update sebagai penanda
                $order->status = 'expired'; // Penanda bahwa tiket sudah digunakan
                $order->save();
                
                Log::info('Marked ticket as used using expired status', [
                    'order_number' => $order->order_number
                ]);
            }

            Log::info('Ticket used successfully', [
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'method' => \Schema::hasColumn('orders', 'used_at') ? 'used_at_column' : 'expired_status'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tiket berhasil digunakan! Selamat datang ' . $order->customer_name,
                'order' => [
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'ticket_quantity' => $order->ticket_quantity,
                    'used_at' => Carbon::now()->format('d M Y H:i'),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Use ticket error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'order_number' => $request->order_number ?? 'not provided',
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Validasi tiket - UPDATED
     */
    private function validateTicket($order)
    {
        // Cek apakah tiket sudah digunakan
        if ($this->isTicketUsed($order)) {
            return [
                'valid' => false,
                'message' => '❌ Tiket sudah digunakan sebelumnya!'
            ];
        }

        // Cek status pembayaran
        if ($order->status !== 'success') {
            return [
                'valid' => false,
                'message' => '❌ Tiket belum dibayar! Status: ' . ucfirst($order->status)
            ];
        }

        // Cek tanggal kunjungan
        $visitDate = Carbon::parse($order->visit_date);
        $today = Carbon::today();

        if ($visitDate->lt($today)) {
            return [
                'valid' => false,
                'message' => '❌ Tiket sudah expired! Tanggal kunjungan: ' . $visitDate->format('d M Y')
            ];
        }

        if ($visitDate->gt($today)) {
            return [
                'valid' => false,
                'message' => '❌ Tiket belum dapat digunakan! Tanggal kunjungan: ' . $visitDate->format('d M Y')
            ];
        }

        return [
            'valid' => true,
            'message' => '✅ Tiket valid dan dapat digunakan!'
        ];
    }

    /**
     * Cek apakah tiket sudah digunakan
     */
    private function isTicketUsed($order)
    {
        // Jika ada kolom used_at
        if (\Schema::hasColumn('orders', 'used_at')) {
            return !is_null($order->used_at);
        }

        // Fallback: gunakan kombinasi status dan waktu
        // Asumsi: jika status = expired DAN updated hari ini setelah jam operasional, berarti sudah digunakan
        if ($order->status === 'expired' && 
            $order->updated_at > Carbon::today()->addHours(6) && 
            Carbon::parse($order->updated_at)->isToday()) {
            return true;
        }

        return false;
    }

    /**
     * Logout petugas
     */
    public function logout()
    {
        session()->forget(['staff_verified', 'staff_verified_at']);
        return redirect()->route('scanner.verification')->with('success', 'Berhasil logout.');
    }
}