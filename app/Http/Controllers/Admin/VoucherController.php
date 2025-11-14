<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\VoucherClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VoucherController extends Controller
{
    public function index()
    {
        try {
            Log::info('Voucher index called');
            
            // Update status voucher yang habis
            Voucher::where('status', 'aktif')
                   ->where('is_unlimited', false)
                   ->whereRaw('quota <= (SELECT COUNT(*) FROM voucher_claims WHERE voucher_claims.voucher_id = vouchers.id)')
                   ->update(['status' => 'habis']);
            
            // Update status voucher yang kadaluarsa
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
     * Export data voucher claims ke CSV dengan format rapi
     */
    public function export(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            
            $claims = VoucherClaim::with('voucher')->latest()->get();
            $filteredClaims = $this->filterClaimsByStatus($claims, $status);
            
            return $this->exportAsCSV($filteredClaims, $status);
            
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }

    /**
     * Export sebagai CSV dengan format tabel rapi
     */
    private function exportAsCSV($claims, $status)
    {
        $statusLabels = [
            'all' => 'SEMUA_DATA',
            'active' => 'BELUM_TERPAKAI', 
            'used' => 'SUDAH_TERPAKAI',
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
            
            // Add BOM for UTF-8 (agar karakter Indonesia terbaca dengan baik)
            fwrite($file, "\xEF\xBB\xBF");
            
            // ========== HEADER LAPORAN ==========
            $this->writeCSVRow($file, ['╔══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╗']);
            $this->writeCSVRow($file, ['                                                       LAPORAN DATA KLAIM VOUCHER                                                                              ']);
            $this->writeCSVRow($file, ['╚══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════╝']);
            $this->writeCSVRow($file, []);
            
            // ========== INFORMASI EXPORT ==========
            $this->writeCSVRow($file, ['📊 INFORMASI EXPORT']);
            $this->writeCSVRow($file, ['══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════']);
            $this->writeCSVRow($file, ['Status Filter', ':', $statusLabels[$status] ?? 'SEMUA DATA']);
            $this->writeCSVRow($file, ['Tanggal Export', ':', Carbon::now()->format('d F Y, H:i:s')]);
            $this->writeCSVRow($file, ['Total Data', ':', number_format($claims->count()) . ' klaim']);
            $this->writeCSVRow($file, []);
            
            // ========== HEADER TABEL ==========
            $this->writeCSVRow($file, ['══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════']);
            $this->writeCSVRow($file, ['                                                                 DATA KLAIM VOUCHER                                                                           ']);
            $this->writeCSVRow($file, ['══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════']);
            $this->writeCSVRow($file, []);
            
            // Header kolom dengan width yang disesuaikan
            $headers = [
                'NO',
                'NAMA USER', 
                'DOMISILI', 
                'NO WHATSAPP', 
                'NAMA VOUCHER',
                'KODE UNIK', 
                'TANGGAL KLAIM', 
                'EXPIRED DATE',
                'STATUS VOUCHER',
                'STATUS PEMAKAIAN',
                'TANGGAL TERPAKAI'
            ];
            
            $this->writeCSVRow($file, $headers);
            
            // Garis pemisah header
            $this->writeCSVRow($file, [
                '───────', 
                '─────────────────────', 
                '──────────────────', 
                '─────────────────', 
                '──────────────────────────────',
                '─────────────────', 
                '─────────────────', 
                '────────────────', 
                '────────────────', 
                '──────────────────', 
                '─────────────────'
            ]);
            
            // ========== DATA ROWS ==========
            $counter = 1;
            foreach ($claims as $claim) {
                $isUsed = $claim->is_used || $claim->scanned_at;
                $voucherExpired = $claim->voucher && 
                                Carbon::now()->startOfDay()->greaterThan(Carbon::parse($claim->voucher->expiry_date));
                
                // Tentukan status dengan emoji
                if ($isUsed) {
                    $statusPemakaian = '✅ TERPAKAI';
                } elseif ($voucherExpired) {
                    $statusPemakaian = '⚠️ KADALUARSA';
                } else {
                    $statusPemakaian = '🟢 BELUM TERPAKAI';
                }
                
                $statusVoucher = $voucherExpired ? '❌ EXPIRED' : '✅ AKTIF';
                
                $this->writeCSVRow($file, [
                    $counter++,
                    $this->cleanText($claim->user_name),
                    $this->cleanText($claim->user_domisili ?? '-'),
                    $this->formatPhoneNumber($claim->user_phone),
                    $this->cleanText($claim->voucher->name ?? '-'),
                    $claim->unique_code,
                    $claim->created_at->format('d/m/Y H:i'),
                    $claim->voucher ? Carbon::parse($claim->voucher->expiry_date)->format('d/m/Y') : '-',
                    $statusVoucher,
                    $statusPemakaian,
                    $claim->scanned_at ? $claim->scanned_at->format('d/m/Y H:i') : '-'
                ]);
            }
            
            // Garis penutup tabel
            $this->writeCSVRow($file, [
                '───────', 
                '─────────────────────', 
                '──────────────────', 
                '─────────────────', 
                '──────────────────────────────',
                '─────────────────', 
                '─────────────────', 
                '────────────────', 
                '────────────────', 
                '──────────────────', 
                '─────────────────'
            ]);
            
            $this->writeCSVRow($file, []);
            $this->writeCSVRow($file, []);
            
            // ========== RINGKASAN STATISTIK ==========
            $this->writeCSVRow($file, ['══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════']);
            $this->writeCSVRow($file, ['                                                         RINGKASAN STATISTIK                                                                                 ']);
            $this->writeCSVRow($file, ['══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════']);
            $this->writeCSVRow($file, []);
            
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
            
            // Tampilkan ringkasan
            $this->writeCSVRow($file, ['📊 RINGKASAN BERDASARKAN STATUS:']);
            $this->writeCSVRow($file, []);
            $this->writeCSVRow($file, ['🟢 Belum Terpakai (Aktif)', ':', number_format($activeCount) . ' voucher']);
            $this->writeCSVRow($file, ['✅ Sudah Terpakai', ':', number_format($usedCount) . ' voucher']);
            $this->writeCSVRow($file, ['⚠️ Kadaluarsa', ':', number_format($expiredCount) . ' voucher']);
            $this->writeCSVRow($file, []);
            $this->writeCSVRow($file, ['──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────']);
            $this->writeCSVRow($file, ['📈 TOTAL KESELURUHAN', ':', number_format($claims->count()) . ' voucher']);
            $this->writeCSVRow($file, ['══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════']);
            $this->writeCSVRow($file, []);
            $this->writeCSVRow($file, []);
            
            // ========== FOOTER & PETUNJUK ==========
            $this->writeCSVRow($file, ['💡 PETUNJUK PENGGUNAAN:']);
            $this->writeCSVRow($file, []);
            $this->writeCSVRow($file, ['1. File ini dapat dibuka langsung di Microsoft Excel atau Google Sheets']);
            $this->writeCSVRow($file, ['2. Untuk format tabel yang lebih rapi, gunakan "Format as Table" di Excel']);
            $this->writeCSVRow($file, ['3. Gunakan fitur Filter untuk menyaring data berdasarkan kolom tertentu']);
            $this->writeCSVRow($file, ['4. Simpan sebagai Excel (.xlsx) untuk formatting yang lebih baik']);
            $this->writeCSVRow($file, ['5. Data akan otomatis tersortir berdasarkan tanggal klaim (terbaru ke terlama)']);
            $this->writeCSVRow($file, []);
            $this->writeCSVRow($file, ['══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════']);
            $this->writeCSVRow($file, ['Generated by: Voucher Management System']);
            $this->writeCSVRow($file, ['Copyright © ' . date('Y') . ' - All Rights Reserved']);
            $this->writeCSVRow($file, ['══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════']);
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper untuk menulis row CSV dengan format yang konsisten
     */
    private function writeCSVRow($file, $data)
    {
        // Jika data adalah array, proses setiap elemen
        if (is_array($data)) {
            fputcsv($file, $data);
        } else {
            fputcsv($file, [$data]);
        }
    }

    /**
     * Filter claims berdasarkan status
     */
    private function filterClaimsByStatus($claims, $status)
    {
        if ($status === 'all') return $claims;
        
        return $claims->filter(function($claim) use ($status) {
            $isUsed = $claim->is_used || $claim->scanned_at;
            $voucherExpired = $claim->voucher && 
                            Carbon::now()->startOfDay()->greaterThan(Carbon::parse($claim->voucher->expiry_date));
            
            switch ($status) {
                case 'active': 
                    return !$isUsed && !$voucherExpired;
                case 'used': 
                    return $isUsed;
                case 'expired': 
                    return !$isUsed && $voucherExpired;
                default: 
                    return true;
            }
        });
    }
    
    /**
     * Bersihkan teks dari karakter yang mengganggu
     */
    private function cleanText($text)
    {
        if (empty($text)) return '-';
        
        $cleaned = str_replace(["\t", "\r", "\n"], ' ', $text);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        $cleaned = trim($cleaned);
        
        return $cleaned ?: '-';
    }
    
    /**
     * Format nomor telepon Indonesia
     */
    private function formatPhoneNumber($phone)
    {
        if (empty($phone)) return '-';
        
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        if (empty($clean)) return $phone;
        
        // Format: +62 812-3456-7890
        if (substr($clean, 0, 1) === '0') {
            $clean = '62' . substr($clean, 1);
        }
        
        if (substr($clean, 0, 2) === '62') {
            $number = substr($clean, 2);
            if (strlen($number) === 11) {
                return '+62 ' . substr($number, 0, 3) . '-' . substr($number, 3, 4) . '-' . substr($number, 7);
            } elseif (strlen($number) === 10) {
                return '+62 ' . substr($number, 0, 3) . '-' . substr($number, 3, 3) . '-' . substr($number, 6);
            } elseif (strlen($number) === 9) {
                return '+62 ' . substr($number, 0, 3) . '-' . substr($number, 3, 3) . '-' . substr($number, 6);
            }
        }
        
        // Jika format tidak sesuai, kembalikan aslinya
        return $phone;
    }

    /**
     * Get voucher details for API
     */
    public function show($id)
    {
        try {
            $voucher = Voucher::withCount('claims')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $voucher
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching voucher: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update voucher status only
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,tidak_aktif,kadaluarsa,habis'
        ]);

        try {
            $voucher = Voucher::findOrFail($id);
            $voucher->status = $request->status;
            $voucher->save();

            return redirect()->route('admin.voucher.index')->with('success', 'Status voucher berhasil diupdate!');
        } catch (\Exception $e) {
            Log::error('Error updating voucher status: ' . $e->getMessage());
            return redirect()->route('admin.voucher.index')->with('error', 'Gagal mengupdate status voucher: ' . $e->getMessage());
        }
    }

    /**
     * Get voucher statistics for dashboard
     */
    public function statistics()
    {
        try {
            $totalVouchers = Voucher::count();
            $activeVouchers = Voucher::where('status', 'aktif')->count();
            $expiredVouchers = Voucher::where('status', 'kadaluarsa')->count();
            $claimedVouchers = VoucherClaim::count();
            $usedVouchers = VoucherClaim::where('is_used', true)->orWhereNotNull('scanned_at')->count();
            
            $recentClaims = VoucherClaim::with('voucher')
                ->latest()
                ->take(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_vouchers' => $totalVouchers,
                    'active_vouchers' => $activeVouchers,
                    'expired_vouchers' => $expiredVouchers,
                    'claimed_vouchers' => $claimedVouchers,
                    'used_vouchers' => $usedVouchers,
                    'recent_claims' => $recentClaims
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching voucher statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik voucher'
            ], 500);
        }
    }
}