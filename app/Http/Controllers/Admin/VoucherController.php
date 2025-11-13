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
        
        // Langsung gunakan CSV saja - lebih reliable
        return $this->exportAsCSV($filteredClaims, $status);
        
    } catch (\Exception $e) {
        Log::error('Export error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
    }
}
    /**
     * Export sebagai CSV (fallback method)
     */
  private function exportAsCSV($claims, $status)
{
    $statusLabels = [
        'all' => 'SEMUA DATA',
        'active' => 'BELUM TERPAKAI', 
        'used' => 'SUDAH TERPAKAI',
        'expired' => 'KADALUARSA'
    ];
    
    $filename = "Voucher_Claims_" . ($statusLabels[$status] ?? 'Data') . "_" . date('Y-m-d_His') . ".csv";
    
    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0'
    ];
    
$callback = function() use ($claims, $statusLabels, $status) {
    $file = fopen('php://output', 'w');
    
    // Add BOM for UTF-8
    fwrite($file, "\xEF\xBB\xBF");
        
        // ========== HEADER SECTION ==========
        fputcsv($file, ["LAPORAN DATA KLAIM VOUCHER"]); // Title
        fputcsv($file, ["Filter Status: " . ($statusLabels[$status] ?? 'SEMUA DATA')]);
        fputcsv($file, ["Tanggal Export: " . date('d M Y H:i:s')]);
        fputcsv($file, ["Total Data: " . $claims->count() . " klaim"]);
        fputcsv($file, [""]); // Empty line
        
        // ========== TABLE HEADER ==========
        fputcsv($file, [
            'NO',
            'NAMA USER', 
            'DOMISILI', 
            'NO. WHATSAPP', 
            'NAMA VOUCHER',
            'KODE UNIK', 
            'TANGGAL KLAIM', 
            'TANGGAL EXPIRED',
            'STATUS VOUCHER',
            'STATUS PEMAKAIAN',
            'TANGGAL TERPAKAI'
        ], ',', ' ');
        
        fputcsv($file, [""]); // Empty line
        
        // ========== DATA ROWS ==========
        $counter = 1;
        foreach ($claims as $claim) {
            $isUsed = $claim->is_used || $claim->scanned_at;
            $voucherExpired = $claim->voucher && 
                            \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($claim->voucher->expiry_date));
            
            // Tentukan status dan warna (untuk Excel nanti)
            if ($isUsed) {
                $statusPemakaian = 'TERPAKAI';
                $statusColor = 'GRAY';
            } elseif ($voucherExpired) {
                $statusPemakaian = 'KADALUARSA';
                $statusColor = 'RED';
            } else {
                $statusPemakaian = 'BELUM TERPAKAI';
                $statusColor = 'GREEN';
            }
            
            $statusVoucher = $voucherExpired ? 'EXPIRED' : 'AKTIF';
            
            fputcsv($file, [
                $counter++,
                $claim->user_name,
                $claim->user_domisili ?? '-',
                $this->formatPhoneNumber($claim->user_phone),
                $claim->voucher->name ?? '-',
                $claim->unique_code,
                $claim->created_at->format('d M Y H:i'),
                $claim->voucher ? \Carbon\Carbon::parse($claim->voucher->expiry_date)->format('d M Y') : '-',
                $statusVoucher,
                $statusPemakaian,
                $claim->scanned_at ? $claim->scanned_at->format('d M Y H:i') : '-'
            ], ',', ' ');
        }
        
        // ========== SUMMARY SECTION ==========
        fputcsv($file, [""]);
        fputcsv($file, ["RINGKASAN STATISTIK"]);
        fputcsv($file, ["==================="]);
        
        $activeCount = $claims->filter(function($c) {
            $isUsed = $c->is_used || $c->scanned_at;
            $expired = $c->voucher && \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($c->voucher->expiry_date));
            return !$isUsed && !$expired;
        })->count();
        
        $usedCount = $claims->filter(function($c) {
            return $c->is_used || $c->scanned_at;
        })->count();
        
        $expiredCount = $claims->filter(function($c) {
            $isUsed = $c->is_used || $c->scanned_at;
            $expired = $c->voucher && \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($c->voucher->expiry_date));
            return !$isUsed && $expired;
        })->count();
        
        fputcsv($file, ["Total Belum Terpakai (Aktif):", $activeCount]);
        fputcsv($file, ["Total Sudah Terpakai:", $usedCount]);
        fputcsv($file, ["Total Kadaluarsa:", $expiredCount]);
        fputcsv($file, ["TOTAL KESELURUHAN:", $claims->count()]);
        
        fputcsv($file, [""]);
        fputcsv($file, ["CATATAN:"]);
        fputcsv($file, ["- File ini di-generate otomatis dari sistem"]);
        fputcsv($file, ["- Data terupdate per: " . date('d M Y H:i:s')]);
        fputcsv($file, ["- Format tanggal: DD MMM YYYY HH:MM"]);
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}

private function generateExcelTemplateInstructions()
{
    return [
        "",
        "PETUNJUK FORMATTING DI EXCEL:",
        "1. Buka file CSV di Excel",
        "2. Pilih semua data (Ctrl+A)",
        "3. Format sebagai Table (Ctrl+T)",
        "4. Pilih style tabel yang diinginkan",
        "5. Untuk kolom status, gunakan conditional formatting:",
        "   - TERPAKAI: Fill color abu-abu",
        "   - BELUM TERPAKAI: Fill color hijau muda", 
        "   - KADALUARSA: Fill color merah muda",
        "6. Freeze pane pada baris 6 untuk header tabel",
        "7. Auto-fit semua kolom untuk tampilan optimal"
    ];
}

// Helper method untuk format nomor telepon
private function formatPhoneNumber($phone)
{
    // Hilangkan karakter non-digit
    $clean = preg_replace('/[^0-9]/', '', $phone);
    
    // Format ke +62
    if (substr($clean, 0, 1) === '0') {
        return '+62' . substr($clean, 1);
    } elseif (substr($clean, 0, 2) === '62') {
        return '+' . $clean;
    }
    
    return $phone;
}
    
    /**
     * Generate Excel Spreadsheet dengan styling profesional
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
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);
        
        // Info Section
        $sheet->setCellValue('A2', 'Status Filter:');
        $sheet->setCellValue('B2', $statusLabels[$status] ?? 'SEMUA');
        $sheet->setCellValue('A3', 'Tanggal Export:');
        $sheet->setCellValue('B3', Carbon::now()->format('d M Y H:i:s'));
        $sheet->setCellValue('A4', 'Total Data:');
        $sheet->setCellValue('B4', $claims->count() . ' klaim');
        
        $sheet->getStyle('A2:A4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E7E6E6']]
        ]);
        
        // ========== TABLE HEADER ==========
        $headers = ['No', 'Nama User', 'Domisili', 'No. WhatsApp', 'Nama Voucher', 
                    'Kode Unik', 'Tanggal Klaim', 'Tanggal Expired', 'Status Voucher', 
                    'Status Pemakaian', 'Tanggal Terpakai'];
        
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '6', $header);
            $col++;
        }
        
        $sheet->getStyle('A6:K6')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '70AD47']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);
        $sheet->getRowDimension(6)->setRowHeight(25);
        
        // ========== DATA ROWS ==========
        $row = 7;
        foreach ($claims as $index => $claim) {
            $isUsed = $claim->is_used || $claim->scanned_at;
            $voucherExpired = $claim->voucher && 
                            Carbon::now()->startOfDay()->greaterThan(Carbon::parse($claim->voucher->expiry_date));
            
            if ($isUsed) {
                $statusPemakaian = 'Terpakai';
                $statusColor = 'D3D3D3'; // Gray
            } elseif ($voucherExpired) {
                $statusPemakaian = 'Kadaluarsa';
                $statusColor = 'FFB3BA'; // Light Red
            } else {
                $statusPemakaian = 'Belum Terpakai';
                $statusColor = 'BAFFC9'; // Light Green
            }
            
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $claim->user_name);
            $sheet->setCellValue('C' . $row, $claim->user_domisili ?? '-');
            $sheet->setCellValue('D' . $row, $claim->user_phone);
            $sheet->setCellValue('E' . $row, $claim->voucher->name ?? '-');
            $sheet->setCellValue('F' . $row, $claim->unique_code);
            $sheet->setCellValue('G' . $row, $claim->created_at->format('d M Y H:i'));
            $sheet->setCellValue('H' . $row, $claim->voucher ? Carbon::parse($claim->voucher->expiry_date)->format('d M Y') : '-');
            $sheet->setCellValue('I' . $row, $voucherExpired ? 'Expired' : 'Aktif');
            $sheet->setCellValue('J' . $row, $statusPemakaian);
            $sheet->setCellValue('K' . $row, $claim->scanned_at ? $claim->scanned_at->format('d M Y H:i') : '-');
            
            // Row styling
            $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
            ]);
            
            // Status column color
            $sheet->getStyle('J' . $row)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $statusColor]],
                'font' => ['bold' => true]
            ]);
            
            $row++;
        }
        
        // ========== SUMMARY SECTION ==========
        $summaryRow = $row + 2;
        $sheet->setCellValue('A' . $summaryRow, 'RINGKASAN');
        $sheet->mergeCells('A' . $summaryRow . ':B' . $summaryRow);
        $sheet->getStyle('A' . $summaryRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC000']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        
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
            ['Total Belum Terpakai:', $activeCount, 'BAFFC9'],
            ['Total Sudah Terpakai:', $usedCount, 'D3D3D3'],
            ['Total Kadaluarsa:', $expiredCount, 'FFB3BA'],
            ['TOTAL KESELURUHAN:', $claims->count(), 'FFD966']
        ];
        
        $summaryRow++;
        foreach ($summaryData as $data) {
            $sheet->setCellValue('A' . $summaryRow, $data[0]);
            $sheet->setCellValue('B' . $summaryRow, $data[1]);
            $sheet->getStyle('A' . $summaryRow . ':B' . $summaryRow)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $data[2]]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);
            $summaryRow++;
        }
        
        // ========== COLUMN WIDTHS ==========
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(18);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(18);
        $sheet->getColumnDimension('K')->setWidth(18);
        
        // Center align untuk kolom tertentu
        $sheet->getStyle('A7:A' . ($row-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I7:J' . ($row-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
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