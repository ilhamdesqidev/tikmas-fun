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
/**
 * Export data tiket berdasarkan status dan promo (Excel)
 */
public function export(Request $request)
{
    $status = $request->get('status', 'all');
    $promoId = $request->get('promo_id', 'all');

    // Buat nama file yang valid
    $filename = 'tickets_export_' . date('Y-m-d_His') . '.xlsx';

    $query = Order::with('promo')->orderBy('created_at', 'desc');

    if ($status !== 'all') {
        $query->where('status', $status);
    }

    if ($promoId !== 'all') {
        $query->where('promo_id', $promoId);
    }

    $orders = $query->get();

    // Pastikan PhpSpreadsheet di-load dengan benar
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Set judul sheet berdasarkan filter
    $sheetTitle = 'Tickets';
    if ($status !== 'all') {
        $sheetTitle = ucfirst($status);
    }
    if ($promoId !== 'all') {
        $promo = Promo::find($promoId);
        $sheetTitle .= $promo ? ' - ' . $promo->name : '';
    }
    $sheet->setTitle(substr($sheetTitle, 0, 31)); // Excel sheet title max 31 chars

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
    $headers = [
        'No', 'Order Number', 'Invoice Number', 'Paket Promo', 'Category', 
        'Customer Name', 'WhatsApp', 'Visit Date', 'Quantity', 'Total Price', 
        'Status', 'Order Date'
    ];

    $sheet->fromArray($headers, null, 'A1');
    $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);

    // Set column widths
    $columnWidths = [
        'A' => 5,  'B' => 18, 'C' => 25, 'D' => 25, 'E' => 12,
        'F' => 20, 'G' => 15, 'H' => 12, 'I' => 10, 'J' => 15,
        'K' => 12, 'L' => 18
    ];

    foreach ($columnWidths as $column => $width) {
        $sheet->getColumnDimension($column)->setWidth($width);
    }

    // Add data
    $row = 2;
    foreach ($orders as $index => $order) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $order->order_number);
        $sheet->setCellValue('C' . $row, $order->invoice_number ?? '-');
        $sheet->setCellValue('D' . $row, $order->promo ? $order->promo->name : '-');
        $sheet->setCellValue('E' . $row, $order->promo ? ucfirst($order->promo->category) : '-');
        $sheet->setCellValue('F' . $row, $order->customer_name);
        $sheet->setCellValue('G' . $row, $order->whatsapp_number);
        $sheet->setCellValue('H' . $row, $order->visit_date ? \Carbon\Carbon::parse($order->visit_date)->format('d M Y') : '-');
        $sheet->setCellValue('I' . $row, $order->ticket_quantity);
        $sheet->setCellValue('J' . $row, $order->total_price);
        $sheet->setCellValue('K' . $row, ucfirst($order->status));
        $sheet->setCellValue('L' . $row, $order->created_at->format('d M Y H:i'));
        $row++;
    }

    // Auto-size columns for better fit
    foreach (range('A', 'L') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(false);
    }

    // Create writer and output
    $writer = new Xlsx($spreadsheet);

    // Clear any previous output
    if (ob_get_length()) ob_clean();

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Pragma: public');

    $writer->save('php://output');
    exit;
}
    
    /**
     * Export semua data dengan multiple sheets (Excel) - dengan filter promo
     */
   /**
 * Export semua data dengan multiple sheets (Excel) - dengan filter promo
 */
public function exportAll(Request $request)
{
    $promoId = $request->get('promo_id', 'all');
    
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
    $hasData = false;
    
    foreach ($statuses as $statusKey => $statusLabel) {
        $query = Order::with('promo')
            ->where('status', $statusKey)
            ->orderBy('created_at', 'desc');
        
        if ($promoId !== 'all') {
            $query->where('promo_id', $promoId);
        }
        
        $orders = $query->get();
        
        // Skip jika tidak ada data
        if ($orders->isEmpty()) {
            continue;
        }
        
        $hasData = true;
        
        if ($sheetIndex == 0) {
            $sheet = $spreadsheet->getActiveSheet();
        } else {
            $sheet = $spreadsheet->createSheet();
        }
        
        $sheet->setTitle(substr($statusLabel, 0, 31));
        
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
        $headers = [
            'No', 'Order Number', 'Invoice Number', 'Paket Promo', 'Category',
            'Customer Name', 'WhatsApp', 'Visit Date', 'Quantity', 'Total Price',
            'Status', 'Order Date'
        ];
        
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);
        
        // Set column widths
        $columnWidths = [
            'A' => 5,  'B' => 18, 'C' => 25, 'D' => 25, 'E' => 12,
            'F' => 20, 'G' => 15, 'H' => 12, 'I' => 10, 'J' => 15,
            'K' => 12, 'L' => 18
        ];
        
        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }
        
        // Add data
        $row = 2;
        foreach ($orders as $index => $order) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $order->order_number);
            $sheet->setCellValue('C' . $row, $order->invoice_number ?? '-');
            $sheet->setCellValue('D' . $row, $order->promo ? $order->promo->name : '-');
            $sheet->setCellValue('E' . $row, $order->promo ? ucfirst($order->promo->category) : '-');
            $sheet->setCellValue('F' . $row, $order->customer_name);
            $sheet->setCellValue('G' . $row, $order->whatsapp_number);
            $sheet->setCellValue('H' . $row, $order->visit_date ? \Carbon\Carbon::parse($order->visit_date)->format('d M Y') : '-');
            $sheet->setCellValue('I' . $row, $order->ticket_quantity);
            $sheet->setCellValue('J' . $row, $order->total_price);
            $sheet->setCellValue('K' . $row, ucfirst($order->status));
            $sheet->setCellValue('L' . $row, $order->created_at->format('d M Y H:i'));
            $row++;
        }
        
        $sheetIndex++;
    }
    
    // Jika tidak ada data sama sekali, buat sheet kosong dengan pesan
    if (!$hasData) {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('No Data');
        $sheet->setCellValue('A1', 'No data available for the selected filters');
    }
    
    $spreadsheet->setActiveSheetIndex(0);
    
    $filename = 'tickets_export_all_' . date('Y-m-d_His') . '.xlsx';
    
    $writer = new Xlsx($spreadsheet);
    
    // Clear output buffer
    if (ob_get_length()) ob_clean();
    
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Pragma: public');
    
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