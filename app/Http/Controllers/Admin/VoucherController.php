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
     * Export data voucher claims ke CSV dengan format tabel yang rapi
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
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }

    /**
     * Export sebagai CSV dengan format tabel yang rapi dan terstruktur
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
            
            // Add BOM untuk UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // ========== HEADER SECTION ==========
            fputcsv($file, ['LAPORAN DATA KLAIM VOUCHER']);
            fputcsv($file, ['==================================================']);
            fputcsv($file, []);
            
            // Info Section
            fputcsv($file, ['STATUS FILTER', $statusLabels[$status] ?? 'SEMUA DATA']);
            fputcsv($file, ['TANGGAL EXPORT', date('d F Y H:i:s')]);
            fputcsv($file, ['TOTAL DATA', number_format($claims->count()) . ' Klaim']);
            fputcsv($file, []);
            fputcsv($file, ['==================================================']);
            fputcsv($file, []);
            
            // ========== TABLE HEADER ==========
            fputcsv($file, [
                'NO',
                'NAMA USER', 
                'DOMISILI', 
                'NO WHATSAPP', 
                'NAMA VOUCHER',
                'KODE UNIK', 
                'TANGGAL KLAIM', 
                'TANGGAL EXPIRED',
                'STATUS VOUCHER',
                'STATUS PEMAKAIAN',
                'TANGGAL TERPAKAI'
            ]);
            
            fputcsv($file, ['--------------------------------------------------']);
            
            // ========== DATA ROWS ==========
            $counter = 1;
            foreach ($claims as $claim) {
                $isUsed = $claim->is_used || $claim->scanned_at;
                $voucherExpired = $claim->voucher && 
                                Carbon::now()->startOfDay()->greaterThan(Carbon::parse($claim->voucher->expiry_date));
                
                // Format status
                if ($isUsed) {
                    $statusPemakaian = 'TERPAKAI';
                    $statusVoucher = 'AKTIF';
                } elseif ($voucherExpired) {
                    $statusPemakaian = 'KADALUARSA';
                    $statusVoucher = 'EXPIRED';
                } else {
                    $statusPemakaian = 'BELUM TERPAKAI';
                    $statusVoucher = 'AKTIF';
                }
                
                fputcsv($file, [
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
            
            // ========== SUMMARY SECTION ==========
            fputcsv($file, []);
            fputcsv($file, ['==================================================']);
            fputcsv($file, ['RINGKASAN STATISTIK']);
            fputcsv($file, ['==================================================']);
            fputcsv($file, []);
            
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
            
            fputcsv($file, ['Belum Terpakai (Aktif)', number_format($activeCount) . ' klaim']);
            fputcsv($file, ['Sudah Terpakai', number_format($usedCount) . ' klaim']);
            fputcsv($file, ['Kadaluarsa', number_format($expiredCount) . ' klaim']);
            fputcsv($file, ['']);
            fputcsv($file, ['TOTAL KESELURUHAN', number_format($claims->count()) . ' klaim']);
            fputcsv($file, []);
            fputcsv($file, ['==================================================']);
            
            // ========== FOOTER ==========
            fputcsv($file, []);
            fputcsv($file, ['PETUNJUK MEMBUKA FILE:']);
            fputcsv($file, ['1. Buka file CSV ini dengan Microsoft Excel atau Google Sheets']);
            fputcsv($file, ['2. Pastikan encoding UTF-8 dipilih saat membuka file']);
            fputcsv($file, ['3. Data akan tampil dalam format tabel yang rapi']);
            fputcsv($file, ['4. Gunakan fitur Filter atau Sort untuk analisis data']);
            fputcsv($file, ['5. Simpan sebagai Excel (.xlsx) jika ingin mempertahankan formatting']);
            fputcsv($file, []);
            fputcsv($file, ['==================================================']);
            fputcsv($file, ['Generated by Voucher Management System - ' . date('Y')]);
            fputcsv($file, ['==================================================']);
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Format nomor telepon
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
        
        return $clean;
    }
    
    /**
     * Bersihkan text dari karakter problematik
     */
    private function cleanText($text)
    {
        if (empty($text)) return '-';
        
        $cleaned = str_replace(["\t", "\r", "\n"], ' ', $text);
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        
        return trim($cleaned);
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
                case 'active': return !$isUsed && !$voucherExpired;
                case 'used': return $isUsed;
                case 'expired': return !$isUsed && $voucherExpired;
                default: return true;
            }
        });
    }
}