<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Promo;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

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
     * Export data tiket berdasarkan status dan promo (EXCEL dengan styling profesional)
     */
    public function export(Request $request)
    {
        try {
            // Increase memory limit untuk file besar
            ini_set('memory_limit', '512M');
            set_time_limit(300);
            
            $status = $request->get('status', 'all');
            $promoId = $request->get('promo_id', 'all');

            $query = Order::with('promo')->orderBy('created_at', 'desc');

            if ($status !== 'all') {
                $query->where('status', $status);
            }

            if ($promoId !== 'all') {
                $query->where('promo_id', $promoId);
            }

            $orders = $query->get();
            
            // Generate Excel spreadsheet
            $spreadsheet = $this->generateTicketExcel($orders, $status, $promoId);
            
            // Create writer dengan optimization
            $writer = new Xlsx($spreadsheet);
            $writer->setPreCalculateFormulas(false);
            
            // Generate filename
            $filename = $this->generateExcelFilename($status, $promoId);
            
            // Clear output buffer untuk prevent corruption
            if (ob_get_length()) {
                ob_end_clean();
            }
            
            // Set headers untuk download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
            
            // Output directly ke browser
            $writer->save('php://output');
            
            // Cleanup memory
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            
            exit;

        } catch (\Exception $e) {
            \Log::error('Export error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate Excel Spreadsheet untuk single status
     */
    private function generateTicketExcel($orders, $status, $promoId)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Tiket');
        
        // Status labels dengan emoji
        $statusLabels = [
            'all' => '📊 SEMUA STATUS',
            'success' => '✅ SUCCESS',
            'pending' => '⏳ PENDING',
            'challenge' => '⚠️ CHALLENGE',
            'denied' => '❌ DENIED',
            'expired' => '⏰ EXPIRED',
            'canceled' => '🚫 CANCELED'
        ];
        
        $statusLabel = $statusLabels[$status] ?? 'SEMUA STATUS';
        
        // ========== HEADER SECTION ==========
        $sheet->setCellValue('A1', '🎫 LAPORAN DATA TIKET');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true, 
                'size' => 18, 
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID, 
                'startColor' => ['rgb' => '2E75B6']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, 
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);
        
        // Info Section
        $sheet->setCellValue('A3', 'Status Filter:');
        $sheet->setCellValue('B3', $statusLabel);
        $sheet->setCellValue('A4', 'Tanggal Export:');
        $sheet->setCellValue('B4', Carbon::now()->format('d F Y H:i:s'));
        $sheet->setCellValue('A5', 'Total Data:');
        $sheet->setCellValue('B5', number_format($orders->count()) . ' tiket');
        
        // Promo info jika ada filter
        $currentRow = 6;
        if ($promoId !== 'all') {
            $promo = Promo::find($promoId);
            $sheet->setCellValue('A6', 'Promo:');
            $sheet->setCellValue('B6', $promo ? $promo->name : '-');
            $sheet->getStyle('B6')->applyFromArray([
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'E67E22']]
            ]);
            $currentRow = 7;
        }
        
        $sheet->getStyle('A3:A' . ($currentRow-1))->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7E6E6']]
        ]);
        
        $sheet->getStyle('B3:B5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '2E75B6']],
        ]);
        
        // ========== TABLE HEADER ==========
        $headerRow = $currentRow + 1;
        $headers = [
            'No', 'Order Number', 'Invoice Number', 'Paket Promo', 'Category',
            'Customer Name', 'WhatsApp', 'Visit Date', 'Quantity', 
            'Total Price', 'Status', 'Order Date'
        ];
        
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $headerRow, $header);
            $col++;
        }
        
        $sheet->getStyle('A' . $headerRow . ':L' . $headerRow)->applyFromArray([
            'font' => [
                'bold' => true, 
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID, 
                'startColor' => ['rgb' => '70AD47']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, 
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(30);
        
        // ========== DATA ROWS ==========
        $row = $headerRow + 1;
        $counter = 1;
        
        foreach ($orders as $order) {
            // Determine status color dan icon
            $statusColor = $this->getStatusColor($order->status);
            $statusIcon = $this->getStatusIcon($order->status);
            
            // Fill data
            $sheet->setCellValue('A' . $row, $counter++);
            $sheet->setCellValue('B' . $row, $order->order_number);
            $sheet->setCellValue('C' . $row, $order->invoice_number ?? '-');
            $sheet->setCellValue('D' . $row, $order->promo ? $order->promo->name : '-');
            $sheet->setCellValue('E' . $row, $order->promo ? ucfirst($order->promo->category) : '-');
            $sheet->setCellValue('F' . $row, $this->cleanText($order->customer_name));
            $sheet->setCellValue('G' . $row, $this->formatPhoneNumber($order->whatsapp_number));
            $sheet->setCellValue('H' . $row, Carbon::parse($order->visit_date)->format('d/m/Y'));
            $sheet->setCellValue('I' . $row, $order->ticket_quantity);
            
            // Format currency
            $sheet->setCellValue('J' . $row, $order->total_price);
            $sheet->getStyle('J' . $row)->getNumberFormat()
                ->setFormatCode('Rp #,##0');
            
            $sheet->setCellValue('K' . $row, $statusIcon . ' ' . strtoupper($order->status));
            $sheet->setCellValue('L' . $row, $order->created_at->format('d/m/Y H:i'));
            
            // Row styling dengan alternating colors
            $bgColor = ($counter % 2 == 0) ? 'F2F2F2' : 'FFFFFF';
            
            $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN, 
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID, 
                    'startColor' => ['rgb' => $bgColor]
                ]
            ]);
            
            // Status column dengan highlight color
            $sheet->getStyle('K' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID, 
                    'startColor' => ['rgb' => $statusColor]
                ],
                'font' => ['bold' => true, 'size' => 10]
            ]);
            
            // Center align untuk kolom tertentu
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I' . $row . ':K' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $row++;
        }
        
        // ========== SUMMARY SECTION ==========
        $summaryRow = $row + 2;
        $sheet->setCellValue('A' . $summaryRow, '📈 RINGKASAN STATISTIK');
        $sheet->mergeCells('A' . $summaryRow . ':L' . $summaryRow);
        $sheet->getStyle('A' . $summaryRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheet->getRowDimension($summaryRow)->setRowHeight(30);
        
        // Calculate statistics
        $statusCounts = [
            ['✅ Success:', $orders->where('status', 'success')->count(), 'C3E6CB'],
            ['⏳ Pending:', $orders->where('status', 'pending')->count(), 'FFF3CD'],
            ['⚠️ Challenge:', $orders->where('status', 'challenge')->count(), 'FFE5B4'],
            ['❌ Denied:', $orders->where('status', 'denied')->count(), 'F8D7DA'],
            ['⏰ Expired:', $orders->where('status', 'expired')->count(), 'D6D8DB'],
            ['🚫 Canceled:', $orders->where('status', 'canceled')->count(), 'E2E3E5'],
        ];
        
        // Filter only non-zero counts
        $statusCounts = array_filter($statusCounts, fn($item) => $item[1] > 0);
        
        $summaryRow++;
        foreach ($statusCounts as $data) {
            $sheet->setCellValue('A' . $summaryRow, $data[0]);
            $sheet->setCellValue('B' . $summaryRow, number_format($data[1]) . ' tiket');
            $sheet->mergeCells('B' . $summaryRow . ':C' . $summaryRow);
            
            $sheet->getStyle('A' . $summaryRow . ':C' . $summaryRow)->applyFromArray([
                'font' => ['bold' => true, 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $data[2]]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
            ]);
            
            $sheet->getRowDimension($summaryRow)->setRowHeight(25);
            $summaryRow++;
        }
        
        // Total revenue for success orders
        $successRevenue = $orders->where('status', 'success')->sum('total_price');
        if ($successRevenue > 0) {
            $sheet->setCellValue('A' . $summaryRow, '💰 Total Revenue (Success):');
            $sheet->setCellValue('B' . $summaryRow, $successRevenue);
            $sheet->getStyle('B' . $summaryRow)->getNumberFormat()->setFormatCode('Rp #,##0');
            $sheet->mergeCells('B' . $summaryRow . ':C' . $summaryRow);
            
            $sheet->getStyle('A' . $summaryRow . ':C' . $summaryRow)->applyFromArray([
                'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '28A745']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
            ]);
            $sheet->getRowDimension($summaryRow)->setRowHeight(25);
            $summaryRow++;
        }
        
        // Total keseluruhan
        $sheet->setCellValue('A' . $summaryRow, '📊 TOTAL KESELURUHAN:');
        $sheet->setCellValue('B' . $summaryRow, number_format($orders->count()) . ' tiket');
        $sheet->mergeCells('B' . $summaryRow . ':C' . $summaryRow);
        
        $sheet->getStyle('A' . $summaryRow . ':C' . $summaryRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFD966']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheet->getRowDimension($summaryRow)->setRowHeight(28);
        
        // ========== FOOTER INFO ==========
        $footerRow = $summaryRow + 2;
        $sheet->setCellValue('A' . $footerRow, '💡 Tips: Gunakan Filter & Sort untuk analisis data lebih detail | Export by ' . config('app.name'));
        $sheet->mergeCells('A' . $footerRow . ':L' . $footerRow);
        $sheet->getStyle('A' . $footerRow)->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '666666']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        
        // ========== COLUMN WIDTHS ==========
        $sheet->getColumnDimension('A')->setWidth(16);   // No
        $sheet->getColumnDimension('B')->setWidth(20);  // Order Number
        $sheet->getColumnDimension('C')->setWidth(20);  // Invoice
        $sheet->getColumnDimension('D')->setWidth(30);  // Promo
        $sheet->getColumnDimension('E')->setWidth(15);  // Category
        $sheet->getColumnDimension('F')->setWidth(25);  // Customer
        $sheet->getColumnDimension('G')->setWidth(18);  // WhatsApp
        $sheet->getColumnDimension('H')->setWidth(15);  // Visit Date
        $sheet->getColumnDimension('I')->setWidth(10);  // Qty
        $sheet->getColumnDimension('J')->setWidth(18);  // Price
        $sheet->getColumnDimension('K')->setWidth(18);  // Status
        $sheet->getColumnDimension('L')->setWidth(18);  // Order Date
        
        // Auto-wrap text untuk kolom yang panjang
        $dataStartRow = $headerRow + 1;
        $dataEndRow = $row - 1;
        $sheet->getStyle('D' . $dataStartRow . ':D' . $dataEndRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('F' . $dataStartRow . ':F' . $dataEndRow)->getAlignment()->setWrapText(true);
        
        // Freeze pane pada header
        $sheet->freezePane('A' . ($headerRow + 1));
        
        return $spreadsheet;
    }
    
    /**
     * Export semua data dengan multiple status (Excel dengan multiple sheets)
     */
    public function exportAll(Request $request)
    {
        try {
            // Increase memory limit
            ini_set('memory_limit', '512M');
            set_time_limit(300);
            
            $promoId = $request->get('promo_id', 'all');
            
            $spreadsheet = new Spreadsheet();
            
            $statuses = [
                'success' => '✅ SUCCESS',
                'pending' => '⏳ PENDING',
                'challenge' => '⚠️ CHALLENGE',
                'denied' => '❌ DENIED',
                'expired' => '⏰ EXPIRED',
                'canceled' => '🚫 CANCELED'
            ];
            
            $sheetIndex = 0;
            $totalAllOrders = 0;
            
            foreach ($statuses as $statusKey => $statusLabel) {
                $query = Order::with('promo')
                    ->where('status', $statusKey)
                    ->orderBy('created_at', 'desc');
                
                if ($promoId !== 'all') {
                    $query->where('promo_id', $promoId);
                }
                
                $orders = $query->get();
                
                if ($orders->isEmpty()) {
                    continue;
                }
                
                $totalAllOrders += $orders->count();
                
                // Create new sheet untuk setiap status
                if ($sheetIndex > 0) {
                    $spreadsheet->createSheet();
                }
                
                $sheet = $spreadsheet->setActiveSheetIndex($sheetIndex);
                $sheet->setTitle(substr($statusKey, 0, 31)); // Excel limit 31 chars
                
                $this->generateTicketSheetForStatus($sheet, $orders, $statusKey, $statusLabel, $promoId);
                
                $sheetIndex++;
            }
            
            // Set active sheet ke sheet pertama
            $spreadsheet->setActiveSheetIndex(0);
            
            // Create writer
            $writer = new Xlsx($spreadsheet);
            $writer->setPreCalculateFormulas(false);
            
            // Generate filename
            $filename = 'tickets_all_status_' . date('Y-m-d_His') . '.xlsx';
            
            // Clear output buffer
            if (ob_get_length()) {
                ob_end_clean();
            }
            
            // Set headers
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            // Output
            $writer->save('php://output');
            
            // Cleanup
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            
            exit;

        } catch (\Exception $e) {
            \Log::error('Export all error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate sheet untuk status tertentu
     */
    private function generateTicketSheetForStatus($sheet, $orders, $statusKey, $statusLabel, $promoId)
    {
        // Header
        $sheet->setCellValue('A1', '🎫 ' . $statusLabel);
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $this->getStatusColor($statusKey)]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);
        
        // Info
        $sheet->setCellValue('A2', 'Total: ' . number_format($orders->count()) . ' tiket');
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        
        // Table header
        $headers = ['No', 'Order Number', 'Invoice', 'Promo', 'Category', 'Customer', 'WhatsApp', 'Visit Date', 'Qty', 'Total Price', 'Status', 'Order Date'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $col++;
        }
        
        $sheet->getStyle('A4:L4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '70AD47']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM]]
        ]);
        
        // Data rows
        $row = 5;
        $counter = 1;
        foreach ($orders as $order) {
            $sheet->setCellValue('A' . $row, $counter++);
            $sheet->setCellValue('B' . $row, $order->order_number);
            $sheet->setCellValue('C' . $row, $order->invoice_number ?? '-');
            $sheet->setCellValue('D' . $row, $order->promo ? $order->promo->name : '-');
            $sheet->setCellValue('E' . $row, $order->promo ? ucfirst($order->promo->category) : '-');
            $sheet->setCellValue('F' . $row, $order->customer_name);
            $sheet->setCellValue('G' . $row, $this->formatPhoneNumber($order->whatsapp_number));
            $sheet->setCellValue('H' . $row, Carbon::parse($order->visit_date)->format('d/m/Y'));
            $sheet->setCellValue('I' . $row, $order->ticket_quantity);
            $sheet->setCellValue('J' . $row, $order->total_price);
            $sheet->getStyle('J' . $row)->getNumberFormat()->setFormatCode('Rp #,##0');
            $sheet->setCellValue('K' . $row, strtoupper($order->status));
            $sheet->setCellValue('L' . $row, $order->created_at->format('d/m/Y H:i'));
            
            $bgColor = ($counter % 2 == 0) ? 'F2F2F2' : 'FFFFFF';
            $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]]
            ]);
            
            $row++;
        }
        
        // Column widths
        $widths = ['A' => 16, 'B' => 20, 'C' => 20, 'D' => 30, 'E' => 15, 'F' => 25, 'G' => 18, 'H' => 15, 'I' => 10, 'J' => 18, 'K' => 15, 'L' => 18];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
        
        $sheet->freezePane('A5');
    }
    
    /**
     * Get status color for styling
     */
    private function getStatusColor($status)
    {
        $colors = [
            'success' => 'C3E6CB',   // Light green
            'pending' => 'FFF3CD',   // Light yellow
            'challenge' => 'FFE5B4', // Light orange
            'denied' => 'F8D7DA',    // Light red
            'expired' => 'D6D8DB',   // Light gray
            'canceled' => 'E2E3E5'   // Gray
        ];
        
        return $colors[$status] ?? 'FFFFFF';
    }
    
    /**
     * Get status icon
     */
    private function getStatusIcon($status)
    {
        $icons = [
            'success' => '✅',
            'pending' => '⏳',
            'challenge' => '⚠️',
            'denied' => '❌',
            'expired' => '⏰',
            'canceled' => '🚫'
        ];
        
        return $icons[$status] ?? '•';
    }
    
    /**
     * Format phone number
     */
    private function formatPhoneNumber($phone)
    {
        if (empty($phone)) return '-';
        
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        if (empty($clean)) return $phone;
        
        if (substr($clean, 0, 1) === '0') {
            return '+62 ' . substr($clean, 1);
        } elseif (substr($clean, 0, 2) === '62') {
            return '+62 ' . substr($clean, 2);
        }
        
        return '+62 ' . $clean;
    }
    
    /**
     * Clean text from problematic characters
     */
    private function cleanText($text)
    {
        if (empty($text)) return '-';
        
        $cleaned = str_replace(["\t", "\r", "\n"], ' ', $text);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        
        return trim($cleaned);
    }
    
    /**
     * Generate Excel filename
     */
    private function generateExcelFilename($status, $promoId)
    {
        $filename = 'Tickets_';
        
        $statusLabels = [
            'all' => 'All',
            'success' => 'Success',
            'pending' => 'Pending',
            'challenge' => 'Challenge',
            'denied' => 'Denied',
            'expired' => 'Expired',
            'canceled' => 'Canceled'
        ];
        
        $filename .= $statusLabels[$status] ?? 'Data';
        
        if ($promoId !== 'all') {
            $promo = Promo::find($promoId);
            if ($promo) {
                $promoName = str_replace(' ', '_', $promo->name);
                $promoName = preg_replace('/[^A-Za-z0-9_-]/', '', $promoName);
                $filename .= '_' . substr($promoName, 0, 30);
            }
        }
        
        $filename .= '_' . date('Y-m-d_His') . '.xlsx';
        
        return $filename;
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