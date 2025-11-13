<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Promo;
use Carbon\Carbon;

class TicketController extends Controller
{
    /**
     * Menampilkan halaman tiket untuk admin
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');
        
        $query = Order::with('promo')
                    ->orderBy('created_at', 'desc');
        
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%")
                  ->orWhere('whatsapp_number', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->paginate(20);
        
        $statusCounts = [
            'pending' => Order::where('status', 'pending')->count(),
            'success' => Order::where('status', 'success')->count(),
            'challenge' => Order::where('status', 'challenge')->count(),
            'denied' => Order::where('status', 'denied')->count(),
            'expired' => Order::where('status', 'expired')->count(),
            'canceled' => Order::where('status', 'canceled')->count(),
        ];
        
        $totalOrders = Order::count();
        
        // Get all promos with order counts
        $promos = Promo::withCount('orders')->orderBy('name')->get();
        
        return view('admin.tickets.index', compact('orders', 'status', 'search', 'statusCounts', 'totalOrders', 'promos'));
    }
    
    /**
     * Menampilkan detail tiket
     */
    public function show($order_id)
    {
        $order = Order::with('promo')->where('order_number', $order_id)->firstOrFail();
        
        return view('admin.tickets.show', compact('order'));
    }
    
    /**
     * Update status tiket
     */
    public function updateStatus(Request $request, $order_id)
    {
        $request->validate([
            'status' => 'required|in:pending,success,challenge,denied,expired,canceled'
        ]);
        
        $order = Order::where('order_number', $order_id)->firstOrFail();
        $order->status = $request->status;
        $order->save();
        
        return redirect()->back()->with('success', 'Status tiket berhasil diupdate.');
    }
    
    /**
     * Export data tiket berdasarkan status dan promo (CSV)
     */
    public function export(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            $promoId = $request->get('promo_id', 'all');

            $filename = 'tickets_' . $status;
            if ($promoId !== 'all') {
                $promo = Promo::find($promoId);
                $filename .= '_' . ($promo ? str_replace(' ', '_', $promo->name) : 'promo');
            }
            $filename .= '_' . date('Y-m-d_His') . '.csv';

            $query = Order::with('promo')->orderBy('created_at', 'desc');

            if ($status !== 'all') {
                $query->where('status', $status);
            }

            if ($promoId !== 'all') {
                $query->where('promo_id', $promoId);
            }

            $orders = $query->get();

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($orders, $status, $promoId) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fwrite($file, "\xEF\xBB\xBF");
                
                // Header Information
                fputcsv($file, ['LAPORAN DATA TIKET']);
                fputcsv($file, ['Status: ' . ($status === 'all' ? 'SEMUA STATUS' : strtoupper($status))]);
                fputcsv($file, ['Tanggal Export: ' . Carbon::now()->format('d M Y H:i:s')]);
                fputcsv($file, ['Total Data: ' . $orders->count() . ' tiket']);
                
                if ($promoId !== 'all') {
                    $promo = Promo::find($promoId);
                    fputcsv($file, ['Promo: ' . ($promo ? $promo->name : '-')]);
                }
                
                fputcsv($file, []); // Empty line
                
                // Column Headers
                fputcsv($file, [
                    'NO',
                    'ORDER NUMBER',
                    'INVOICE NUMBER', 
                    'PAKET PROMO',
                    'CATEGORY',
                    'CUSTOMER NAME',
                    'WHATSAPP',
                    'VISIT DATE',
                    'QUANTITY',
                    'TOTAL PRICE',
                    'STATUS',
                    'ORDER DATE'
                ]);
                
                fputcsv($file, []); // Empty line
                
                // Data Rows
                $counter = 1;
                foreach ($orders as $order) {
                    fputcsv($file, [
                        $counter++,
                        $order->order_number,
                        $order->invoice_number ?? '-',
                        $order->promo ? $order->promo->name : '-',
                        $order->promo ? ucfirst($order->promo->category) : '-',
                        $order->customer_name,
                        $order->whatsapp_number,
                        Carbon::parse($order->visit_date)->format('d M Y'),
                        $order->ticket_quantity,
                        number_format($order->total_price, 0, ',', '.'),
                        strtoupper($order->status),
                        $order->created_at->format('d M Y H:i')
                    ]);
                }
                
                // Summary
                fputcsv($file, []);
                fputcsv($file, ['RINGKASAN:']);
                
                $statusCounts = [
                    'success' => $orders->where('status', 'success')->count(),
                    'pending' => $orders->where('status', 'pending')->count(),
                    'challenge' => $orders->where('status', 'challenge')->count(),
                    'denied' => $orders->where('status', 'denied')->count(),
                    'expired' => $orders->where('status', 'expired')->count(),
                    'canceled' => $orders->where('status', 'canceled')->count(),
                ];
                
                foreach ($statusCounts as $statusKey => $count) {
                    if ($count > 0) {
                        fputcsv($file, [strtoupper($statusKey) . ':', $count]);
                    }
                }
                
                fputcsv($file, ['TOTAL:', $orders->count()]);
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            \Log::error('Export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
    
    /**
     * Export semua data dengan multiple status (CSV)
     */
    public function exportAll(Request $request)
    {
        try {
            $promoId = $request->get('promo_id', 'all');

            $filename = 'tickets_all_status_' . date('Y-m-d_His') . '.csv';

            $statuses = [
                'success' => 'SUCCESS',
                'pending' => 'PENDING', 
                'challenge' => 'CHALLENGE',
                'denied' => 'DENIED',
                'expired' => 'EXPIRED',
                'canceled' => 'CANCELED'
            ];

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($statuses, $promoId) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fwrite($file, "\xEF\xBB\xBF");
                
                // Header Information
                fputcsv($file, ['LAPORAN DATA TIKET - SEMUA STATUS']);
                fputcsv($file, ['Tanggal Export: ' . Carbon::now()->format('d M Y H:i:s')]);
                
                if ($promoId !== 'all') {
                    $promo = Promo::find($promoId);
                    fputcsv($file, ['Promo: ' . ($promo ? $promo->name : '-')]);
                }
                
                fputcsv($file, []); // Empty line
                
                $totalAllOrders = 0;
                
                foreach ($statuses as $statusKey => $statusLabel) {
                    $query = Order::with('promo')
                        ->where('status', $statusKey)
                        ->orderBy('created_at', 'desc');
                    
                    if ($promoId !== 'all') {
                        $query->where('promo_id', $promoId);
                    }
                    
                    $orders = $query->get();
                    $totalAllOrders += $orders->count();
                    
                    if ($orders->isEmpty()) {
                        continue;
                    }
                    
                    // Section Header
                    fputcsv($file, ['=== ' . $statusLabel . ' ===']);
                    fputcsv($file, ['Total: ' . $orders->count() . ' tiket']);
                    fputcsv($file, []); // Empty line
                    
                    // Column Headers
                    fputcsv($file, [
                        'NO',
                        'ORDER NUMBER',
                        'INVOICE NUMBER', 
                        'PAKET PROMO',
                        'CATEGORY',
                        'CUSTOMER NAME',
                        'WHATSAPP',
                        'VISIT DATE',
                        'QUANTITY',
                        'TOTAL PRICE',
                        'STATUS',
                        'ORDER DATE'
                    ]);
                    
                    // Data Rows
                    $counter = 1;
                    foreach ($orders as $order) {
                        fputcsv($file, [
                            $counter++,
                            $order->order_number,
                            $order->invoice_number ?? '-',
                            $order->promo ? $order->promo->name : '-',
                            $order->promo ? ucfirst($order->promo->category) : '-',
                            $order->customer_name,
                            $order->whatsapp_number,
                            Carbon::parse($order->visit_date)->format('d M Y'),
                            $order->ticket_quantity,
                            number_format($order->total_price, 0, ',', '.'),
                            strtoupper($order->status),
                            $order->created_at->format('d M Y H:i')
                        ]);
                    }
                    
                    fputcsv($file, []); // Empty line between statuses
                    fputcsv($file, []); // Empty line
                }
                
                // Total Summary
                fputcsv($file, ['TOTAL SEMUA TIKET:', $totalAllOrders]);
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            \Log::error('Export all error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
    
    /**
     * Get stats for API
     */
    public function getStats()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $successOrders = Order::where('status', 'success')->count();
        $totalRevenue = Order::where('status', 'success')->sum('total_price');
        
        return response()->json([
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'success_orders' => $successOrders,
            'total_revenue' => $totalRevenue
        ]);
    }
}