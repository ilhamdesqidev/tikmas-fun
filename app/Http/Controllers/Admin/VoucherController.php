<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\VoucherClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

class VoucherController extends Controller
{
    public function index()
    {
        try {
            Log::info('Voucher index called');
            
            Voucher::where('status', 'aktif')
                   ->where('is_unlimited', false)
                   ->whereRaw('quota <= (SELECT COUNT(*) FROM voucher_claims WHERE voucher_claims.voucher_id = vouchers.id)')
                   ->update(['status' => 'habis']);
            
            Voucher::whereIn('status', ['aktif', 'tidak_aktif'])
                   ->where('expiry_date', '<', Carbon::now()->startOfDay())
                   ->update(['status' => 'kadaluarsa']);
            
            $vouchers = Voucher::withCount('claims')->latest()->get();
            $claims = VoucherClaim::with('voucher')->latest()->get();
            
            $claimsStats = [
                'total' => $claims->count(),
                'used' => $claims->filter(function($claim) {
                    return $claim->is_used || $claim->scanned_at;
                })->count(),
                'expired' => $claims->filter(function($claim) {
                    return !($claim->is_used || $claim->scanned_at) && 
                           $claim->voucher && 
                           Carbon::now()->startOfDay()->greaterThan(Carbon::parse($claim->voucher->expiry_date));
                })->count(),
                'active' => $claims->filter(function($claim) {
                    return !($claim->is_used || $claim->scanned_at) && 
                           (!$claim->voucher || 
                            Carbon::now()->startOfDay()->lessThanOrEqualTo(Carbon::parse($claim->voucher->expiry_date)));
                })->count(),
            ];
            
            return view('admin.voucher.index', compact('vouchers', 'claims', 'claimsStats'));
        } catch (\Exception $e) {
            Log::error('Error loading vouchers: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|in:aktif,tidak_aktif,kadaluarsa,habis',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'download_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'expiry_date' => 'required|date',
            'quota_type' => 'required|in:unlimited,limited',
            'quota' => 'required_if:quota_type,limited|integer|min:1|nullable',
        ]);

        try {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('vouchers', $imageName, 'public');

            $downloadImagePath = null;
            if ($request->hasFile('download_image')) {
                $downloadImage = $request->file('download_image');
                $downloadImageName = time() . '_download_' . uniqid() . '.' . $downloadImage->getClientOriginalExtension();
                $downloadImagePath = $downloadImage->storeAs('vouchers/downloads', $downloadImageName, 'public');
            }

            $status = $request->status;
            $expiryDate = Carbon::parse($request->expiry_date);
            $isUnlimited = $request->quota_type === 'unlimited';
            $quota = $isUnlimited ? null : $request->quota;

            if (Carbon::now()->startOfDay()->greaterThan($expiryDate)) {
                $status = 'kadaluarsa';
            } elseif (!$isUnlimited && $quota <= 0) {
                $status = 'habis';
            }

            Voucher::create([
                'name' => $request->name,
                'deskripsi' => $request->deskripsi,
                'status' => $status,
                'image' => $imagePath,
                'download_image' => $downloadImagePath,
                'expiry_date' => $request->expiry_date,
                'quota' => $quota,
                'is_unlimited' => $isUnlimited,
            ]);

            return redirect()->route('admin.voucher.index')->with('success', 'Voucher berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error creating voucher: ' . $e->getMessage());
            if (isset($imagePath)) Storage::disk('public')->delete($imagePath);
            if (isset($downloadImagePath)) Storage::disk('public')->delete($downloadImagePath);
            return redirect()->route('admin.voucher.index')->with('error', 'Gagal menambahkan voucher: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|in:aktif,tidak_aktif,kadaluarsa,habis',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'download_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'expiry_date' => 'required|date',
            'quota_type' => 'required|in:unlimited,limited',
            'quota' => 'required_if:quota_type,limited|integer|min:1|nullable',
        ]);

        try {
            $voucher = Voucher::findOrFail($id);
            $oldImage = $voucher->image;
            $oldDownloadImage = $voucher->download_image;

            $voucher->name = $request->name;
            $voucher->deskripsi = $request->deskripsi;
            $voucher->expiry_date = $request->expiry_date;

            $expiryDate = Carbon::parse($request->expiry_date);
            $isUnlimited = $request->quota_type === 'unlimited';
            $quota = $isUnlimited ? null : $request->quota;
            
            $voucher->is_unlimited = $isUnlimited;
            $voucher->quota = $quota;

            if (Carbon::now()->startOfDay()->greaterThan($expiryDate)) {
                $voucher->status = 'kadaluarsa';
            } elseif (!$isUnlimited && $quota <= $voucher->claims()->count()) {
                $voucher->status = 'habis';
            } else {
                $voucher->status = $request->status;
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('vouchers', $imageName, 'public');
                $voucher->image = $imagePath;
                if ($oldImage) Storage::disk('public')->delete($oldImage);
            }

            if ($request->hasFile('download_image')) {
                $downloadImage = $request->file('download_image');
                $downloadImageName = time() . '_download_' . uniqid() . '.' . $downloadImage->getClientOriginalExtension();
                $downloadImagePath = $downloadImage->storeAs('vouchers/downloads', $downloadImageName, 'public');
                $voucher->download_image = $downloadImagePath;
                if ($oldDownloadImage) Storage::disk('public')->delete($oldDownloadImage);
            }

            $voucher->save();

            return redirect()->route('admin.voucher.index')->with('success', 'Voucher berhasil diupdate!');
        } catch (\Exception $e) {
            Log::error('Error updating voucher: ' . $e->getMessage());
            return redirect()->route('admin.voucher.index')->with('error', 'Gagal mengupdate voucher: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $voucher = Voucher::findOrFail($id);
            
            if ($voucher->image) Storage::disk('public')->delete($voucher->image);
            if ($voucher->download_image) Storage::disk('public')->delete($voucher->download_image);

            $voucher->delete();

            return redirect()->route('admin.voucher.index')->with('success', 'Voucher berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting voucher: ' . $e->getMessage());
            return redirect()->route('admin.voucher.index')->with('error', 'Gagal menghapus voucher: ' . $e->getMessage());
        }
    }

    /**
     * Export data voucher claims ke Excel dengan styling profesional
     */
    public function export(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            
            $claims = VoucherClaim::with('voucher')->latest()->get();
            $filteredClaims = $this->filterClaimsByStatus($claims, $status);
            
            // Generate Excel dengan styling
            $spreadsheet = $this->generateProfessionalExcel($filteredClaims, $status);
            
            // Generate filename
            $filename = $this->generateExcelFilename($status);
            
            // Create temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'voucher_export_');
            
            // Create writer and save to temp file
            $writer = new Xlsx($spreadsheet);
            $writer->save($tempFile);
            
            // Return file download response
            return response()->download($tempFile, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }

    /**
     * Generate Excel dengan styling profesional
     */
    private function generateProfessionalExcel($claims, $status)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set page setup
        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        
        $statusLabels = [
            'all' => 'SEMUA DATA',
            'active' => 'BELUM TERPAKAI',
            'used' => 'SUDAH TERPAKAI',
            'expired' => 'KADALUARSA'
        ];
        
        $currentRow = 1;
        
        // ========== HEADER SECTION ==========
        $sheet->setCellValue('A' . $currentRow, 'LAPORAN DATA KLAIM VOUCHER');
        $sheet->mergeCells('A' . $currentRow . ':K' . $currentRow);
        $sheet->getStyle('A' . $currentRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getRowDimension($currentRow)->setRowHeight(35);
        $currentRow++;
        
        // Info Section
        $infoData = [
            ['Status Filter:', $statusLabels[$status] ?? 'SEMUA'],
            ['Tanggal Export:', Carbon::now()->format('d F Y, H:i:s')],
            ['Total Data:', number_format($claims->count()) . ' klaim'],
        ];
        
        foreach ($infoData as $info) {
            $sheet->setCellValue('A' . $currentRow, $info[0]);
            $sheet->setCellValue('B' . $currentRow, $info[1]);
            $sheet->mergeCells('B' . $currentRow . ':K' . $currentRow);
            
            $sheet->getStyle('A' . $currentRow)->applyFromArray([
                'font' => ['bold' => true, 'size' => 11],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7E6E6']]
            ]);
            $sheet->getStyle('B' . $currentRow)->applyFromArray([
                'font' => ['size' => 11],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
            ]);
            
            $currentRow++;
        }
        
        $currentRow++; // Empty row
        
        // ========== TABLE HEADER ==========
        $headers = [
            'NO', 'NAMA USER', 'DOMISILI', 'NO WHATSAPP', 'NAMA VOUCHER',
            'KODE UNIK', 'TANGGAL KLAIM', 'EXPIRED DATE', 'STATUS VOUCHER',
            'STATUS PEMAKAIAN', 'TANGGAL TERPAKAI'
        ];
        
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $currentRow, $header);
            $col++;
        }
        
        $sheet->getStyle('A' . $currentRow . ':K' . $currentRow)->applyFromArray([
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
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);
        $sheet->getRowDimension($currentRow)->setRowHeight(25);
        
        $headerRow = $currentRow;
        $currentRow++;
        
        // ========== DATA ROWS ==========
        $dataStartRow = $currentRow;
        
        foreach ($claims as $index => $claim) {
            $isUsed = $claim->is_used || $claim->scanned_at;
            $voucherExpired = $claim->voucher && 
                            Carbon::now()->startOfDay()->greaterThan(Carbon::parse($claim->voucher->expiry_date));
            
            // Determine status and color
            if ($isUsed) {
                $statusPemakaian = 'TERPAKAI';
                $statusColor = 'D3D3D3'; // Gray
            } elseif ($voucherExpired) {
                $statusPemakaian = 'KADALUARSA';
                $statusColor = 'FFB3BA'; // Light Red
            } else {
                $statusPemakaian = 'BELUM TERPAKAI';
                $statusColor = 'BAFFC9'; // Light Green
            }
            
            $statusVoucher = $voucherExpired ? 'EXPIRED' : 'AKTIF';
            
            // Fill data - gunakan setCellValueExplicit untuk memastikan text
            $sheet->setCellValueExplicit('A' . $currentRow, $index + 1, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $sheet->setCellValueExplicit('B' . $currentRow, $this->cleanText($claim->user_name), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('C' . $currentRow, $this->cleanText($claim->user_domisili ?? '-'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('D' . $currentRow, $this->formatPhoneNumber($claim->user_phone), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('E' . $currentRow, $this->cleanText($claim->voucher->name ?? '-'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('F' . $currentRow, $claim->unique_code, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('G' . $currentRow, $claim->created_at->format('d/m/Y H:i'), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('H' . $currentRow, $claim->voucher ? Carbon::parse($claim->voucher->expiry_date)->format('d/m/Y') : '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('I' . $currentRow, $statusVoucher, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('J' . $currentRow, $statusPemakaian, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('K' . $currentRow, $claim->scanned_at ? $claim->scanned_at->format('d/m/Y H:i') : '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            
            // Row styling
            $sheet->getStyle('A' . $currentRow . ':K' . $currentRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ]);
            
            // Center align for specific columns
            $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I' . $currentRow . ':J' . $currentRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Status column color
            $sheet->getStyle('J' . $currentRow)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $statusColor]
                ],
                'font' => ['bold' => true]
            ]);
            
            // Alternating row colors for better readability
            if ($index % 2 == 0) {
                $sheet->getStyle('A' . $currentRow . ':K' . $currentRow)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F9F9F9']
                    ]
                ]);
            }
            
            $sheet->getRowDimension($currentRow)->setRowHeight(20);
            $currentRow++;
        }
        
        $dataEndRow = $currentRow - 1;
        
        // ========== SUMMARY SECTION ==========
        $currentRow += 2;
        
        $sheet->setCellValueExplicit('A' . $currentRow, 'RINGKASAN STATISTIK', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->mergeCells('A' . $currentRow . ':B' . $currentRow);
        $sheet->getStyle('A' . $currentRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFC000']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getRowDimension($currentRow)->setRowHeight(25);
        
        $currentRow++;
        
        // Calculate statistics
        $activeCount = $claims->filter(function($c) {
            $isUsed = $c->is_used || $c->scanned_at;
            $expired = $c->voucher && Carbon::now()->startOfDay()->greaterThan(Carbon::parse($c->voucher->expiry_date));
            return !$isUsed && !$expired;
        })->count();
        
        $usedCount = $claims->filter(function($c) {
            return $c->is_used || $c->scanned_at;
        })->count();
        
        $expiredCount = $claims->filter(function($c) {
            $isUsed = $c->is_used || $c->scanned_at;
            $expired = $c->voucher && Carbon::now()->startOfDay()->greaterThan(Carbon::parse($c->voucher->expiry_date));
            return !$isUsed && $expired;
        })->count();
        
        $summaryData = [
            ['Belum Terpakai (Aktif):', number_format($activeCount), 'BAFFC9'],
            ['Sudah Terpakai:', number_format($usedCount), 'D3D3D3'],
            ['Kadaluarsa:', number_format($expiredCount), 'FFB3BA'],
            ['TOTAL KESELURUHAN:', number_format($claims->count()), 'FFD966']
        ];
        
        foreach ($summaryData as $data) {
            $sheet->setCellValueExplicit('A' . $currentRow, $data[0], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B' . $currentRow, $data[1], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            
            $sheet->getStyle('A' . $currentRow . ':B' . $currentRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 11
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $data[2]]
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);
            
            $sheet->getRowDimension($currentRow)->setRowHeight(20);
            $currentRow++;
        }
        
        // ========== COLUMN WIDTHS ==========
        $columnWidths = [
            'A' => 8,   // NO
            'B' => 25,  // NAMA USER
            'C' => 20,  // DOMISILI
            'D' => 18,  // NO WHATSAPP
            'E' => 35,  // NAMA VOUCHER
            'F' => 18,  // KODE UNIK
            'G' => 18,  // TANGGAL KLAIM
            'H' => 16,  // EXPIRED DATE
            'I' => 16,  // STATUS VOUCHER
            'J' => 18,  // STATUS PEMAKAIAN
            'K' => 18   // TANGGAL TERPAKAI
        ];
        
        foreach ($columnWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
        
        // Freeze panes (freeze header row)
        $sheet->freezePane('A' . ($headerRow + 1));
        
        // Auto filter
        if ($dataEndRow >= $headerRow + 1) {
            $sheet->setAutoFilter('A' . $headerRow . ':K' . $dataEndRow);
        }
        
        return $spreadsheet;
    }
    
    private function filterClaimsByStatus($claims, $status)
    {
        if ($status === 'all') return $claims;
        
        return $claims->filter(function($claim) use ($status) {
            $isUsed = $claim->is_used || $claim->scanned_at;
            $voucherExpired = $claim->voucher && 
                            Carbon::now()->startOfDay()->greaterThan(Carbon::parse($claim->voucher->expiry_date));
            
            switch ($status) {
                case 'active': return !$isUsed && !$voucherExpired;
                case 'used': return $isUsed;
                case 'expired': return !$isUsed && $voucherExpired;
                default: return true;
            }
        });
    }
    
    private function cleanText($text)
    {
        if (empty($text)) return '-';
        
        $cleaned = str_replace(["\t", "\r", "\n"], ' ', $text);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        
        return trim($cleaned);
    }
    
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
        
        return $clean;
    }
    
    private function generateExcelFilename($status)
    {
        $statusLabel = [
            'all' => 'Semua_Data',
            'active' => 'Belum_Terpakai',
            'used' => 'Sudah_Terpakai',
            'expired' => 'Kadaluarsa'
        ];
        
        $label = $statusLabel[$status] ?? 'Data';
        $date = Carbon::now()->format('Y-m-d_His');
        
        return "Voucher_Claims_{$label}_{$date}.xlsx";
    }
}