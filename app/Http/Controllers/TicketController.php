<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Promo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
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
     * Export data tiket berdasarkan status dan promo (Excel)
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
            $filename .= '_' . date('Y-m-d_His') . '.xlsx';

            $query = Order::with('promo')->orderBy('created_at', 'desc');

            if ($status !== 'all') {
                $query->where('status', $status);
            }

            if ($promoId !== 'all') {
                $query->where('promo_id', $promoId);
            }

            $orders = $query->get();

            // Buat spreadsheet baru
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle(ucfirst($status));

            // ========== HEADER SECTION ==========
            $sheet->setCellValue('A1', 'LAPORAN DATA TIKET');
            $sheet->mergeCells('A1:L1');
            $sheet->getStyle('A1')->applyFromArray([
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
            ]);
            $sheet->getRowDimension(1)->setRowHeight(30);

            // Info Section
            $sheet->setCellValue('A2', 'Status Filter:');
            $sheet->setCellValue('B2', $status === 'all' ? 'SEMUA STATUS' : strtoupper($status));
            $sheet->setCellValue('A3', 'Tanggal Export:');
            $sheet->setCellValue('B3', Carbon::now()->format('d M Y H:i:s'));
            $sheet->setCellValue('A4', 'Total Data:');
            $sheet->setCellValue('B4', $orders->count() . ' tiket');

            if ($promoId !== 'all') {
                $promo = Promo::find($promoId);
                $sheet->setCellValue('A5', 'Promo Filter:');
                $sheet->setCellValue('B5', $promo ? $promo->name : '-');
            }

            $sheet->getStyle('A2:A5')->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7E6E6']]
            ]);

            // ========== TABLE HEADER ==========
            $headers = ['No', 'Order Number', 'Invoice Number', 'Paket Promo', 'Category', 
                       'Customer Name', 'WhatsApp', 'Visit Date', 'Quantity', 'Total Price', 
                       'Status', 'Order Date'];

            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '7', $header);
                $col++;
            }

            $sheet->getStyle('A7:L7')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '70AD47']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);
            $sheet->getRowDimension(7)->setRowHeight(25);

            // ========== DATA ROWS ==========
            $row = 8;
            foreach ($orders as $index => $order) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $order->order_number);
                $sheet->setCellValue('C' . $row, $order->invoice_number ?? '-');
                $sheet->setCellValue('D' . $row, $order->promo ? $order->promo->name : '-');
                $sheet->setCellValue('E' . $row, $order->promo ? ucfirst($order->promo->category) : '-');
                $sheet->setCellValue('F' . $row, $order->customer_name);
                $sheet->setCellValue('G' . $row, $order->whatsapp_number);
                $sheet->setCellValue('H' . $row, Carbon::parse($order->visit_date)->format('d M Y'));
                $sheet->setCellValue('I' . $row, $order->ticket_quantity);
                $sheet->setCellValue('J' . $row, $order->total_price);
                $sheet->setCellValue('K' . $row, ucfirst($order->status));
                $sheet->setCellValue('L' . $row, $order->created_at->format('d M Y H:i'));

                // Row styling
                $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
                ]);

                // Status color coding
                $statusColor = $this->getStatusColor($order->status);
                $sheet->getStyle('K' . $row)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $statusColor]],
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);

                $row++;
            }

            // ========== COLUMN WIDTHS ==========
            $sheet->getColumnDimension('A')->setWidth(8);   // No
            $sheet->getColumnDimension('B')->setWidth(20);  // Order Number
            $sheet->getColumnDimension('C')->setWidth(25);  // Invoice Number
            $sheet->getColumnDimension('D')->setWidth(30);  // Paket Promo
            $sheet->getColumnDimension('E')->setWidth(15);  // Category
            $sheet->getColumnDimension('F')->setWidth(25);  // Customer Name
            $sheet->getColumnDimension('G')->setWidth(20);  // WhatsApp
            $sheet->getColumnDimension('H')->setWidth(15);  // Visit Date
            $sheet->getColumnDimension('I')->setWidth(12);  // Quantity
            $sheet->getColumnDimension('J')->setWidth(15);  // Total Price
            $sheet->getColumnDimension('K')->setWidth(15);  // Status
            $sheet->getColumnDimension('L')->setWidth(20);  // Order Date

            // Format currency untuk kolom harga
            $sheet->getStyle('J8:J' . ($row-1))->getNumberFormat()->setFormatCode('#,##0');

            // Center align untuk kolom tertentu
            $sheet->getStyle('A8:A' . ($row-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I8:I' . ($row-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K8:K' . ($row-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $writer = new Xlsx($spreadsheet);

            return response()->streamDownload(function() use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);

        } catch (\Exception $e) {
            \Log::error('Export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }

    /**
     * Helper method untuk warna status
     */
    private function getStatusColor($status)
    {
        $colors = [
            'success' => 'BAFFC9', // Green
            'pending' => 'FFFFBA', // Yellow
            'challenge' => 'FFD8BA', // Orange
            'denied' => 'FFB3BA', // Red
            'expired' => 'D3D3D3', // Gray
            'canceled' => 'FFB3BA', // Red
            'used' => 'BAE1FF' // Blue
        ];

        return $colors[$status] ?? 'E7E6E6'; // Default gray
    }
    
    /**
     * Export semua data dengan multiple sheets (Excel) - dengan filter promo
     */
    public function exportAll(Request $request)
    {
        try {
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
            
            foreach ($statuses as $statusKey => $statusLabel) {
                $query = Order::with('promo')
                    ->where('status', $statusKey)
                    ->orderBy('created_at', 'desc');
                
                if ($promoId !== 'all') {
                    $query->where('promo_id', $promoId);
                }
                
                $orders = $query->get();
                
                // Skip empty sheets
                if ($orders->isEmpty()) {
                    continue;
                }
                
                if ($sheetIndex == 0) {
                    $sheet = $spreadsheet->getActiveSheet();
                } else {
                    $sheet = $spreadsheet->createSheet();
                }
                
                $sheet->setTitle($statusLabel);
                
                // Apply same styling as single export method
                $this->applySheetStyling($sheet, $orders, $statusLabel, $promoId);
                
                $sheetIndex++;
            }
            
            // Jika tidak ada data sama sekali
            if ($sheetIndex === 0) {
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('No Data');
                $sheet->setCellValue('A1', 'Tidak ada data yang ditemukan');
            }
            
            $spreadsheet->setActiveSheetIndex(0);
            
            $filename = 'tickets_all_sheets';
            if ($promoId !== 'all') {
                $promo = Promo::find($promoId);
                $filename .= '_' . ($promo ? str_replace(' ', '_', $promo->name) : 'promo');
            }
            $filename .= '_' . date('Y-m-d_His') . '.xlsx';
            
            $writer = new Xlsx($spreadsheet);
            
            return response()->streamDownload(function() use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);

        } catch (\Exception $e) {
            \Log::error('Export all error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }

    /**
     * Helper method untuk apply styling ke sheet
     */
    private function applySheetStyling($sheet, $orders, $statusLabel, $promoId)
    {
        // ========== HEADER SECTION ==========
        $sheet->setCellValue('A1', 'LAPORAN DATA TIKET - ' . strtoupper($statusLabel));
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Info Section
        $sheet->setCellValue('A2', 'Status:');
        $sheet->setCellValue('B2', strtoupper($statusLabel));
        $sheet->setCellValue('A3', 'Tanggal Export:');
        $sheet->setCellValue('B3', Carbon::now()->format('d M Y H:i:s'));
        $sheet->setCellValue('A4', 'Total Data:');
        $sheet->setCellValue('B4', $orders->count() . ' tiket');

        if ($promoId !== 'all') {
            $promo = Promo::find($promoId);
            $sheet->setCellValue('A5', 'Promo Filter:');
            $sheet->setCellValue('B5', $promo ? $promo->name : '-');
        }

        $sheet->getStyle('A2:A5')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7E6E6']]
        ]);

        // ========== TABLE HEADER ==========
        $headers = ['No', 'Order Number', 'Invoice Number', 'Paket Promo', 'Category', 
                   'Customer Name', 'WhatsApp', 'Visit Date', 'Quantity', 'Total Price', 
                   'Status', 'Order Date'];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '7', $header);
            $col++;
        }

        $sheet->getStyle('A7:L7')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '70AD47']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getRowDimension(7)->setRowHeight(25);

        // ========== DATA ROWS ==========
        $row = 8;
        foreach ($orders as $index => $order) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $order->order_number);
            $sheet->setCellValue('C' . $row, $order->invoice_number ?? '-');
            $sheet->setCellValue('D' . $row, $order->promo ? $order->promo->name : '-');
            $sheet->setCellValue('E' . $row, $order->promo ? ucfirst($order->promo->category) : '-');
            $sheet->setCellValue('F' . $row, $order->customer_name);
            $sheet->setCellValue('G' . $row, $order->whatsapp_number);
            $sheet->setCellValue('H' . $row, Carbon::parse($order->visit_date)->format('d M Y'));
            $sheet->setCellValue('I' . $row, $order->ticket_quantity);
            $sheet->setCellValue('J' . $row, $order->total_price);
            $sheet->setCellValue('K' . $row, ucfirst($order->status));
            $sheet->setCellValue('L' . $row, $order->created_at->format('d M Y H:i'));

            // Row styling
            $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
            ]);

            // Status color coding
            $statusColor = $this->getStatusColor($order->status);
            $sheet->getStyle('K' . $row)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $statusColor]],
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);

            $row++;
        }

        // ========== COLUMN WIDTHS ==========
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(12);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(20);

        // Format currency
        if ($row > 8) {
            $sheet->getStyle('J8:J' . ($row-1))->getNumberFormat()->setFormatCode('#,##0');
        }

        // Center align
        $sheet->getStyle('A8:A' . ($row-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I8:I' . ($row-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K8:K' . ($row-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
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