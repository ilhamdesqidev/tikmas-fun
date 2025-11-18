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
use PhpOffice\PhpSpreadsheet\Style\Color;

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
            'syarat_ketentuan' => 'required|string', // Tambahan validasi
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
                'syarat_ketentuan' => $request->syarat_ketentuan, // Tambahan
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
            'syarat_ketentuan' => 'required|string', // Tambahan validasi
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
            $voucher->syarat_ketentuan = $request->syarat_ketentuan; // Tambahan
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

    public function export(Request $request)
    {
        try {
            ini_set('memory_limit', '512M');
            set_time_limit(300);
            
            $status = $request->get('status', 'all');
            
            $claims = VoucherClaim::with('voucher')->latest()->get();
            $filteredClaims = $this->filterClaimsByStatus($claims, $status);
            
            $spreadsheet = $this->generateExcelSpreadsheet($filteredClaims, $status);
            
            $writer = new Xlsx($spreadsheet);
            $writer->setPreCalculateFormulas(false);
            
            $filename = $this->generateExcelFilename($status);
            
            if (ob_get_length()) {
                ob_end_clean();
            }
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
            
            $writer->save('php://output');
            
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            
            exit;
            
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
    
    private function generateExcelSpreadsheet($claims, $status)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Klaim Voucher');
        
        $statusLabels = [
            'all' => 'SEMUA DATA',
            'active' => 'BELUM TERPAKAI',
            'used' => 'SUDAH TERPAKAI',
            'expired' => 'KADALUARSA'
        ];
        
        $sheet->setCellValue('A1', '📊 LAPORAN DATA KLAIM VOUCHER');
        $sheet->mergeCells('A1:K1');
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
        
        $sheet->setCellValue('A3', 'Status Filter:');
        $sheet->setCellValue('B3', $statusLabels[$status] ?? 'SEMUA');
        $sheet->setCellValue('A4', 'Tanggal Export:');
        $sheet->setCellValue('B4', Carbon::now()->format('d F Y H:i:s'));
        $sheet->setCellValue('A5', 'Total Data:');
        $sheet->setCellValue('B5', number_format($claims->count()) . ' klaim');
        
        $sheet->getStyle('A3:A5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7E6E6']]
        ]);
        
        $sheet->getStyle('B3:B5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '2E75B6']],
        ]);
        
        $headers = [
            'No', 'Nama User', 'Domisili', 'No. WhatsApp', 'Nama Voucher', 
            'Kode Unik', 'Tanggal Klaim', 'Expired Date', 'Status Voucher', 
            'Status Pemakaian', 'Tanggal Terpakai'
        ];
        
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '7', $header);
            $col++;
        }
        
        $sheet->getStyle('A7:K7')->applyFromArray([
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
        $sheet->getRowDimension(7)->setRowHeight(30);
        
        $row = 8;
        $counter = 1;
        
        foreach ($claims as $claim) {
            $isUsed = $claim->is_used || $claim->scanned_at;
            $voucherExpired = $claim->voucher && 
                            Carbon::now()->startOfDay()->greaterThan(Carbon::parse($claim->voucher->expiry_date));
            
            if ($isUsed) {
                $statusPemakaian = '✅ Terpakai';
                $statusColor = 'D3D3D3';
                $statusIcon = '✓';
            } elseif ($voucherExpired) {
                $statusPemakaian = '⚠️ Kadaluarsa';
                $statusColor = 'FFB3BA';
                $statusIcon = '⚠';
            } else {
                $statusPemakaian = '🟢 Belum Terpakai';
                $statusColor = 'BAFFC9';
                $statusIcon = '○';
            }
            
            $sheet->setCellValue('A' . $row, $counter++);
            $sheet->setCellValue('B' . $row, $this->cleanText($claim->user_name));
            $sheet->setCellValue('C' . $row, $this->cleanText($claim->user_domisili ?? '-'));
            $sheet->setCellValue('D' . $row, $this->formatPhoneNumber($claim->user_phone));
            $sheet->setCellValue('E' . $row, $this->cleanText($claim->voucher->name ?? '-'));
            $sheet->setCellValue('F' . $row, $claim->unique_code);
            $sheet->setCellValue('G' . $row, $claim->created_at->format('d/m/Y H:i'));
            $sheet->setCellValue('H' . $row, $claim->voucher ? Carbon::parse($claim->voucher->expiry_date)->format('d/m/Y') : '-');
            $sheet->setCellValue('I' . $row, $voucherExpired ? '❌ Expired' : '✅ Aktif');
            $sheet->setCellValue('J' . $row, $statusPemakaian);
            $sheet->setCellValue('K' . $row, $claim->scanned_at ? $claim->scanned_at->format('d/m/Y H:i') : '-');
            
            $bgColor = ($counter % 2 == 0) ? 'F2F2F2' : 'FFFFFF';
            
            $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
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
            
            $sheet->getStyle('J' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID, 
                    'startColor' => ['rgb' => $statusColor]
                ],
                'font' => ['bold' => true, 'size' => 10]
            ]);
            
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I' . $row . ':J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $row++;
        }
        
        $summaryRow = $row + 2;
        $sheet->setCellValue('A' . $summaryRow, '📈 RINGKASAN STATISTIK');
        $sheet->mergeCells('A' . $summaryRow . ':K' . $summaryRow);
        $sheet->getStyle('A' . $summaryRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheet->getRowDimension($summaryRow)->setRowHeight(30);
        
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
            ['🟢 Belum Terpakai (Aktif):', number_format($activeCount) . ' klaim', 'BAFFC9'],
            ['✅ Sudah Terpakai:', number_format($usedCount) . ' klaim', 'D3D3D3'],
            ['⚠️ Kadaluarsa:', number_format($expiredCount) . ' klaim', 'FFB3BA'],
            ['📊 TOTAL KESELURUHAN:', number_format($claims->count()) . ' klaim', 'FFD966']
        ];
        
        $summaryRow++;
        foreach ($summaryData as $data) {
            $sheet->setCellValue('A' . $summaryRow, $data[0]);
            $sheet->setCellValue('B' . $summaryRow, $data[1]);
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
        
        $footerRow = $summaryRow + 2;
        $sheet->setCellValue('A' . $footerRow, '💡 Tips: Gunakan Filter & Sort untuk analisis data lebih detail');
        $sheet->mergeCells('A' . $footerRow . ':K' . $footerRow);
        $sheet->getStyle('A' . $footerRow)->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['rgb' => '666666']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        
        $sheet->getColumnDimension('A')->setWidth(22);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(35);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(18);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(18);
        
        $sheet->getStyle('E8:E' . ($row-1))->getAlignment()->setWrapText(true);
        $sheet->getStyle('B8:B' . ($row-1))->getAlignment()->setWrapText(true);
        
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
    
    private function cleanText($text)
    {
        if (empty($text)) return '-';
        
        $cleaned = str_replace(["\t", "\r", "\n"], ' ', $text);
        
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        
        return trim($cleaned);
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