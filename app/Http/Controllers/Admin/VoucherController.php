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
            
            $spreadsheet = $this->generateExcelSpreadsheet($filteredClaims, $status);
            
            $filename = $this->generateExcelFilename($status);
            $writer = new Xlsx($spreadsheet);
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;
            
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate Excel Spreadsheet dengan styling profesional dan tampilan yang lebih baik
     */
    private function generateExcelSpreadsheet($claims, $status)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Status labels
        $statusLabels = [
            'all' => 'SEMUA DATA',
            'active' => 'BELUM TERPAKAI',
            'used' => 'SUDAH TERPAKAI',
            'expired' => 'KADALUARSA'
        ];
        
        // ========== HEADER SECTION ==========
        $sheet->setCellValue('A1', 'LAPORAN DATA KLAIM VOUCHER');
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true, 
                'size' => 18, 
                'color' => ['rgb' => 'FFFFFF'],
                'name' => 'Arial'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID, 
                'startColor' => ['rgb' => '2E5090']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, 
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);
        
        // Info Section dengan styling lebih baik
        $sheet->setCellValue('A3', 'Status Filter:');
        $sheet->setCellValue('B3', $statusLabels[$status] ?? 'SEMUA');
        $sheet->setCellValue('A4', 'Tanggal Export:');
        $sheet->setCellValue('B4', Carbon::now()->format('d F Y, H:i:s'));
        $sheet->setCellValue('A5', 'Total Data:');
        $sheet->setCellValue('B5', number_format($claims->count()) . ' klaim');
        
        $sheet->getStyle('A3:A5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ]);
        
        $sheet->getStyle('B3:B5')->applyFromArray([
            'font' => ['size' => 11],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ]);
        
        // Border untuk info section
        $sheet->getStyle('A3:B5')->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);
        
        // ========== TABLE HEADER ==========
        $headers = [
            'No', 
            'Nama User', 
            'Domisili', 
            'No. WhatsApp', 
            'Nama Voucher', 
            'Kode Unik', 
            'Tanggal Klaim', 
            'Tanggal Expired', 
            'Status Voucher', 
            'Status Pemakaian', 
            'Tanggal Terpakai'
        ];
        
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '7', $header);
            $col++;
        }
        
        $sheet->getStyle('A7:K7')->applyFromArray([
            'font' => [
                'bold' => true, 
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID, 
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, 
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '2E5090']
                ]
            ]
        ]);
        $sheet->getRowDimension(7)->setRowHeight(30);
        
        // ========== DATA ROWS ==========
        $row = 8;
        foreach ($claims as $index => $claim) {
            $isUsed = $claim->is_used || $claim->scanned_at;
            $voucherExpired = $claim->voucher && 
                            Carbon::now()->startOfDay()->greaterThan(Carbon::parse($claim->voucher->expiry_date));
            
            // Tentukan status dan warna
            if ($isUsed) {
                $statusPemakaian = '✓ Terpakai';
                $statusColor = 'C5C5C5';
                $fontColor = '333333';
            } elseif ($voucherExpired) {
                $statusPemakaian = '✗ Kadaluarsa';
                $statusColor = 'FFC7CE';
                $fontColor = '9C0006';
            } else {
                $statusPemakaian = '● Belum Terpakai';
                $statusColor = 'C6EFCE';
                $fontColor = '006100';
            }
            
            // Status voucher
            $statusVoucher = $voucherExpired ? '⚠ Expired' : '✓ Aktif';
            $voucherStatusColor = $voucherExpired ? 'FFE699' : 'D9E1F2';
            
            // Isi data
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $this->cleanText($claim->user_name));
            $sheet->setCellValue('C' . $row, $this->cleanText($claim->user_domisili ?? '-'));
            $sheet->setCellValue('D' . $row, $this->formatPhoneNumber($claim->user_phone));
            $sheet->setCellValue('E' . $row, $this->cleanText($claim->voucher->name ?? '-'));
            $sheet->setCellValue('F' . $row, $claim->unique_code);
            $sheet->setCellValue('G' . $row, $claim->created_at->format('d/m/Y H:i'));
            $sheet->setCellValue('H' . $row, $claim->voucher ? Carbon::parse($claim->voucher->expiry_date)->format('d/m/Y') : '-');
            $sheet->setCellValue('I' . $row, $statusVoucher);
            $sheet->setCellValue('J' . $row, $statusPemakaian);
            $sheet->setCellValue('K' . $row, $claim->scanned_at ? $claim->scanned_at->format('d/m/Y H:i') : '-');
            
            // Styling untuk row
            $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN, 
                        'color' => ['rgb' => 'DDDDDD']
                    ]
                ],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
            ]);
            
            // Zebra striping (baris bergantian)
            if ($index % 2 == 0) {
                $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID, 
                        'startColor' => ['rgb' => 'F8F9FA']
                    ]
                ]);
            }
            
            // Status Voucher column color
            $sheet->getStyle('I' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID, 
                    'startColor' => ['rgb' => $voucherStatusColor]
                ],
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
            
            // Status Pemakaian column color
            $sheet->getStyle('J' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID, 
                    'startColor' => ['rgb' => $statusColor]
                ],
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => $fontColor]
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
            
            // Center align untuk kolom tertentu
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $sheet->getRowDimension($row)->setRowHeight(25);
            $row++;
        }
        
        // ========== SUMMARY SECTION ==========
        $summaryRow = $row + 2;
        $sheet->setCellValue('A' . $summaryRow, 'RINGKASAN STATISTIK');
        $sheet->mergeCells('A' . $summaryRow . ':C' . $summaryRow);
        $sheet->getStyle('A' . $summaryRow)->applyFromArray([
            'font' => [
                'bold' => true, 
                'size' => 14,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID, 
                'startColor' => ['rgb' => '70AD47']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getRowDimension($summaryRow)->setRowHeight(30);
        
        // Hitung statistik
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
            ['● Belum Terpakai (Aktif)', $activeCount, 'C6EFCE', '006100'],
            ['✓ Sudah Terpakai', $usedCount, 'C5C5C5', '333333'],
            ['✗ Kadaluarsa', $expiredCount, 'FFC7CE', '9C0006'],
            ['TOTAL KESELURUHAN', $claims->count(), '4472C4', 'FFFFFF']
        ];
        
        $summaryRow++;
        foreach ($summaryData as $data) {
            $sheet->setCellValue('A' . $summaryRow, $data[0]);
            $sheet->setCellValue('B' . $summaryRow, number_format($data[1]));
            $sheet->setCellValue('C' . $summaryRow, 'klaim');
            
            $sheet->getStyle('A' . $summaryRow . ':C' . $summaryRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['rgb' => $data[3]]
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
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
            ]);
            
            $sheet->getStyle('B' . $summaryRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getRowDimension($summaryRow)->setRowHeight(25);
            $summaryRow++;
        }
        
        // ========== COLUMN WIDTHS - Optimal untuk tampilan ==========
        $sheet->getColumnDimension('A')->setWidth(6);   // No
        $sheet->getColumnDimension('B')->setWidth(25);  // Nama User
        $sheet->getColumnDimension('C')->setWidth(20);  // Domisili
        $sheet->getColumnDimension('D')->setWidth(18);  // No WhatsApp
        $sheet->getColumnDimension('E')->setWidth(35);  // Nama Voucher
        $sheet->getColumnDimension('F')->setWidth(18);  // Kode Unik
        $sheet->getColumnDimension('G')->setWidth(18);  // Tanggal Klaim
        $sheet->getColumnDimension('H')->setWidth(16);  // Expired Date
        $sheet->getColumnDimension('I')->setWidth(16);  // Status Voucher
        $sheet->getColumnDimension('J')->setWidth(20);  // Status Pemakaian
        $sheet->getColumnDimension('K')->setWidth(18);  // Tanggal Terpakai
        
        // Auto-wrap text untuk kolom panjang
        $lastRow = $row - 1;
        $sheet->getStyle('E8:E' . $lastRow)->getAlignment()->setWrapText(true);
        $sheet->getStyle('B8:B' . $lastRow)->getAlignment()->setWrapText(true);
        
        // Freeze panes (baris header tetap terlihat saat scroll)
        $sheet->freezePane('A8');
        
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

    /**
     * Bersihkan text dari karakter problematik
     */
    private function cleanText($text)
    {
        if (empty($text)) return '-';
        
        // Hilangkan karakter tab, newline, carriage return
        $cleaned = str_replace(["\t", "\r", "\n"], ' ', $text);
        
        // Hilangkan multiple spaces
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        
        return trim($cleaned);
    }
    
    /**
     * Format nomor telepon yang lebih robust
     */
    private function formatPhoneNumber($phone)
    {
        if (empty($phone)) return '-';
        
        // Hilangkan karakter non-digit
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        if (empty($clean)) return $phone;
        
        // Format ke +62
        if (substr($clean, 0, 1) === '0') {
            return '+62 ' . substr($clean, 1);
        } elseif (substr($clean, 0, 2) === '62') {
            return '+62 ' . substr($clean, 2);
        }
        
        return '+62 ' . $clean;
    }
    
    /**
     * Generate nama file Excel
     */
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