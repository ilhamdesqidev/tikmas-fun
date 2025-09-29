<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Promo;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ScannerController extends Controller
{
    // Method untuk menampilkan form verifikasi petugas
    public function showVerificationForm()
    {
        return view('scanner.verification');
    }

    // Method untuk verifikasi petugas
    public function verifyStaff(Request $request)
    {
        $request->validate([
            'staff_code' => 'required|string'
        ]);

        // Simple staff verification - bisa disesuaikan dengan kebutuhan
        $validCodes = ['STAFF001', 'STAFF002', 'PETUGAS01', 'SCAN123', 'ADMINSCAN', 'MESTAKARA', 'ILHAM']; // Contoh kode valid
        
        if (in_array(strtoupper($request->staff_code), $validCodes)) {
            Session::put('scanner_verified', true);
            Session::put('staff_code', strtoupper($request->staff_code));
            
            return redirect()->route('scanner.dashboard')
                ->with('success', 'Verifikasi berhasil! Selamat datang, Petugas.');
        }

        return redirect()->back()
            ->with('error', 'Kode petugas tidak valid!')
            ->withInput();
    }

    // Method untuk menampilkan dashboard scanner
    public function dashboard()
    {
        // Check if staff is verified
        if (!Session::has('scanner_verified')) {
            return redirect()->route('scanner.verification');
        }

        $today = Carbon::today();
        
        // Get today's statistics
        $todayUsed = Order::whereDate('used_at', $today)
            ->where('status', 'used')
            ->sum('ticket_quantity');
            
        $todayTotal = Order::whereDate('visit_date', $today)
            ->where('status', 'success')
            ->orWhere('status', 'used')
            ->sum('ticket_quantity');

        // Get recent scans
        $recentScans = Order::with('promo')
            ->whereDate('used_at', $today)
            ->where('status', 'used')
            ->orderBy('used_at', 'desc')
            ->limit(10)
            ->get();

        return view('scanner.dashboard', compact('todayUsed', 'todayTotal', 'recentScans'));
    }

    // Method untuk scan barcode
    public function scanBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $barcode = trim($request->barcode);
        
        try {
            // Find order by order_number (barcode)
            $order = Order::with('promo')
                ->where('order_number', $barcode)
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barcode tidak ditemukan dalam sistem!'
                ]);
            }

            // Check if order is paid/success
            if ($order->status !== 'success' && $order->status !== 'used') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket belum dibayar atau tidak valid!'
                ]);
            }

            // Check if already used
            if ($order->status === 'used') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket sudah pernah digunakan sebelumnya!',
                    'order' => [
                        'order_number' => $order->order_number,
                        'customer_name' => $order->customer_name,
                        'status' => $order->status,
                        'used_at' => $order->used_at
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tiket valid dan siap digunakan!',
                'order' => [
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'whatsapp_number' => $order->whatsapp_number,
                    'visit_date' => Carbon::parse($order->visit_date)->format('d/m/Y'),
                    'ticket_quantity' => $order->ticket_quantity,
                    'total_price' => $order->total_price,
                    'promo_name' => $order->promo ? $order->promo->name : 'Unknown',
                    'status' => $order->status
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Scanner error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memproses barcode!'
            ]);
        }
    }

    // Method untuk menggunakan tiket dan mencetak bracelet
    public function useTicket(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string'
        ]);

        try {
            $order = Order::with('promo')
                ->where('order_number', $request->order_number)
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order tidak ditemukan!'
                ]);
            }

            if ($order->status === 'used') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket sudah pernah digunakan!'
                ]);
            }

            if ($order->status !== 'success') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket tidak valid untuk digunakan!'
                ]);
            }

            // Update order status to used
            $order->status = 'used';
            $order->used_at = now();
            $order->save();

            // Update promo sold_count
            if ($order->promo) {
                $order->promo->increment('sold_count', $order->ticket_quantity);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tiket berhasil digunakan! Selamat menikmati MestaKara!',
                'order' => [
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'ticket_quantity' => $order->ticket_quantity,
                    'used_at' => $order->used_at->format('d/m/Y H:i:s')
                ],
                'print_url' => route('scanner.print.bracelet', ['order_id' => $order->order_number])
            ]);

        } catch (\Exception $e) {
            \Log::error('Use ticket error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses tiket!'
            ]);
        }
    }

    // Method untuk mencetak bracelet design
    public function printBracelet($order_id)
    {
        try {
            $order = Order::with('promo')
                ->where('order_number', $order_id)
                ->where('status', 'used')
                ->firstOrFail();

            if (!$order->promo || !$order->promo->bracelet_design) {
                return response()->json([
                    'success' => false,
                    'message' => 'Desain gelang tidak tersedia untuk tiket ini!'
                ], 404);
            }

            // Generate PDF dengan multiple tickets
            $pdf = $this->generateBraceletPDF($order);

            $filename = "Bracelet_Tickets_{$order->order_number}_" . date('YmdHi') . ".pdf";
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            \Log::error('Print bracelet error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencetak tiket gelang: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk generate PDF bracelet
    private function generateBraceletPDF($order)
    {
        $promo = $order->promo;
        $quantity = $order->ticket_quantity;
        
        // Prepare data for PDF
        $tickets = [];
        for ($i = 1; $i <= $quantity; $i++) {
            $tickets[] = [
                'ticket_number' => $order->order_number . '-T' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'promo_name' => $promo->name,
                'visit_date' => Carbon::parse($order->visit_date)->format('d/m/Y'),
                'ticket_index' => $i,
                'total_tickets' => $quantity,
                'issued_at' => Carbon::now()->format('d/m/Y H:i')
            ];
        }

        // Get bracelet design image path
        $braceletDesignPath = null;
        if ($promo->bracelet_design) {
            $fullPath = storage_path('app/public/' . $promo->bracelet_design);
            if (file_exists($fullPath)) {
                $braceletDesignPath = $fullPath;
            }
        }

        $pdf = Pdf::loadView('scanner.bracelet-tickets-pdf', [
            'tickets' => $tickets,
            'order' => $order,
            'promo' => $promo,
            'bracelet_design_path' => $braceletDesignPath
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('dpi', 150);
        $pdf->setOption('defaultFont', 'sans-serif');
        $pdf->setOption('isHtml5ParserEnabled', true);

        return $pdf;
    }

    // Method untuk auto print setelah use ticket (AJAX endpoint)
    public function autoPrintBracelet(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string'
        ]);

        try {
            $order = Order::with('promo')
                ->where('order_number', $request->order_number)
                ->where('status', 'used')
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order tidak ditemukan atau belum digunakan!'
                ]);
            }

            if (!$order->promo || !$order->promo->bracelet_design) {
                return response()->json([
                    'success' => false,
                    'message' => 'Desain gelang tidak tersedia untuk tiket ini!'
                ]);
            }

            $printUrl = route('scanner.print.bracelet', ['order_id' => $order->order_number]);

            return response()->json([
                'success' => true,
                'message' => 'Siap untuk mencetak tiket gelang!',
                'print_url' => $printUrl,
                'ticket_quantity' => $order->ticket_quantity
            ]);

        } catch (\Exception $e) {
            \Log::error('Auto print error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mempersiapkan pencetakan!'
            ]);
        }
    }

    // Method untuk check ticket (API)
    public function checkTicket(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string'
        ]);

        $order = Order::with('promo')
            ->where('order_number', $request->order_number)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'order' => $order,
            'promo' => $order->promo
        ]);
    }

    // Method untuk logout petugas
    public function logout()
    {
        Session::forget('scanner_verified');
        Session::forget('staff_code');
        
        return redirect()->route('scanner.verification')
            ->with('success', 'Anda telah logout dari sistem scanner.');
    }
}