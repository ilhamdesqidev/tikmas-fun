<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Promo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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
        
        return view('admin.tickets.index', compact('orders', 'status', 'search', 'statusCounts', 'totalOrders'));
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
     * Export data tiket berdasarkan status (CSV)
     */
    public function export(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $filename = 'tickets_' . $status . '_' . date('Y-m-d_His') . '.csv';
        
        $query = Order::with('promo')->orderBy('created_at', 'desc');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $orders = $query->get();
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, [
                'No',
                'Order Number',
                'Invoice Number',
                'Paket Promo',
                'Category',
                'Customer Name',
                'WhatsApp',
                'Visit Date',
                'Quantity',
                'Total Price',
                'Status',
                'Order Date'
            ]);
            
            $no = 1;
            foreach ($orders as $order) {
                fputcsv($file, [
                    $no++,
                    $order->order_number,
                    $order->invoice_number ?? '-',
                    $order->promo ? $order->promo->name : '-',
                    $order->promo ? ucfirst($order->promo->category) : '-',
                    $order->customer_name,
                    $order->whatsapp_number,
                    \Carbon\Carbon::parse($order->visit_date)->format('d M Y'),
                    $order->ticket_quantity,
                    $order->total_price,
                    ucfirst($order->status),
                    $order->created_at->format('d M Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export semua data dengan multiple sheets (Excel)
     */
    public function exportAll()
    {
        $spreadsheet = new Spreadsheet();
        
        $statuses = [
            'success' => 'Success',
            'pending' => 'Pending',
            'challenge' => 'Challenge',
            'denied' => 'Denied',
            'expired' => 'Expired',
            'canceled' => 'Canceled'
        ];
        
        $sheetIndex = 0;
        
        foreach ($statuses as $statusKey => $statusLabel) {
            $orders = Order::with('promo')
                ->where('status', $statusKey)
                ->orderBy('created_at', 'desc')
                ->get();
            
            if ($sheetIndex == 0) {
                $sheet = $spreadsheet->getActiveSheet();
            } else {
                $sheet = $spreadsheet->createSheet();
            }
            
            $sheet->setTitle($statusLabel);
            
            // Header styling
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ];
            
            // Set headers
            $headers = ['No', 'Order Number', 'Invoice Number', 'Paket Promo', 'Category', 
                       'Customer Name', 'WhatsApp', 'Visit Date', 'Quantity', 'Total Price', 
                       'Status', 'Order Date'];
            
            $sheet->fromArray($headers, null, 'A1');
            $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);
            
            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(5);
            $sheet->getColumnDimension('B')->setWidth(18);
            $sheet->getColumnDimension('C')->setWidth(18);
            $sheet->getColumnDimension('D')->setWidth(25);
            $sheet->getColumnDimension('E')->setWidth(12);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(15);
            $sheet->getColumnDimension('H')->setWidth(12);
            $sheet->getColumnDimension('I')->setWidth(10);
            $sheet->getColumnDimension('J')->setWidth(15);
            $sheet->getColumnDimension('K')->setWidth(12);
            $sheet->getColumnDimension('L')->setWidth(18);
            
            // Add data
            $row = 2;
            $no = 1;
            foreach ($orders as $order) {
                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $order->order_number);
                $sheet->setCellValue('C' . $row, $order->invoice_number ?? '-');
                $sheet->setCellValue('D' . $row, $order->promo ? $order->promo->name : '-');
                $sheet->setCellValue('E' . $row, $order->promo ? ucfirst($order->promo->category) : '-');
                $sheet->setCellValue('F' . $row, $order->customer_name);
                $sheet->setCellValue('G' . $row, $order->whatsapp_number);
                $sheet->setCellValue('H' . $row, \Carbon\Carbon::parse($order->visit_date)->format('d M Y'));
                $sheet->setCellValue('I' . $row, $order->ticket_quantity);
                $sheet->setCellValue('J' . $row, $order->total_price);
                $sheet->setCellValue('K' . $row, ucfirst($order->status));
                $sheet->setCellValue('L' . $row, $order->created_at->format('d M Y H:i'));
                $row++;
            }
            
            $sheetIndex++;
        }
        
        $spreadsheet->setActiveSheetIndex(0);
        
        $filename = 'tickets_all_sheets_' . date('Y-m-d_His') . '.xlsx';
        
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
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