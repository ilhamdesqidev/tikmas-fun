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
            
            // Update voucher yang kuotanya habis menjadi status "habis"
            Voucher::where('status', 'aktif')
                   ->where('is_unlimited', false)
                   ->whereRaw('quota <= (SELECT COUNT(*) FROM voucher_claims WHERE voucher_claims.voucher_id = vouchers.id)')
                   ->update(['status' => 'habis']);
            
            // Update voucher yang sudah expired menjadi status "kadaluarsa"
            Voucher::whereIn('status', ['aktif', 'tidak_aktif'])
                   ->where('expiry_date', '<', Carbon::now()->startOfDay())
                   ->update(['status' => 'kadaluarsa']);
            
            // Load vouchers dengan count claims
            $vouchers = Voucher::withCount('claims')->latest()->get();
            
            // Load semua claims dengan voucher terkait
            $claims = VoucherClaim::with('voucher')->latest()->get();
            
            // Hitung statistik claims
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
            
            Log::info('Vouchers loaded: ' . $vouchers->count());
            Log::info('Claims loaded: ' . $claims->count());
            Log::info('Claims stats: ', $claimsStats);
            
            return view('admin.voucher.index', compact('vouchers', 'claims', 'claimsStats'));
        } catch (\Exception $e) {
            Log::error('Error loading vouchers: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        Log::info('Store voucher called', $request->all());
        
        $request->validate([
            'name' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'status' => 'required|in:aktif,tidak_aktif,kadaluarsa,habis',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'download_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'expiry_date' => 'required|date',
            'quota_type' => 'required|in:unlimited,limited',
            'quota' => 'required_if:quota_type,limited|integer|min:1|nullable',
        ], [
            'name.required' => 'Nama voucher wajib diisi',
            'deskripsi.required' => 'Deskripsi voucher wajib diisi',
            'status.required' => 'Status voucher wajib dipilih',
            'image.required' => 'Gambar voucher wajib diupload',
            'download_image.image' => 'File gambar download harus berupa gambar',
            'expiry_date.required' => 'Tanggal kadaluarsa wajib diisi',
            'quota_type.required' => 'Tipe kuota wajib dipilih',
            'quota.required_if' => 'Kuota wajib diisi untuk tipe terbatas',
            'quota.min' => 'Kuota minimal 1',
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

            // Auto-set status berdasarkan kondisi
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

            return redirect()->route('admin.voucher.index')
                           ->with('success', 'Voucher berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error creating voucher: ' . $e->getMessage());
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            if (isset($downloadImagePath)) {
                Storage::disk('public')->delete($downloadImagePath);
            }
            return redirect()->route('admin.voucher.index')
                           ->with('error', 'Gagal menambahkan voucher: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('Update voucher called: ' . $id, $request->all());
        
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

            // Auto-set status berdasarkan kondisi
            if (Carbon::now()->startOfDay()->greaterThan($expiryDate)) {
                $voucher->status = 'kadaluarsa';
            } elseif (!$isUnlimited && $quota <= $voucher->claims()->count()) {
                $voucher->status = 'habis';
            } else {
                $voucher->status = $request->status;
            }

            // Update gambar display jika ada
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('vouchers', $imageName, 'public');
                $voucher->image = $imagePath;

                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            // Update gambar download jika ada
            if ($request->hasFile('download_image')) {
                $downloadImage = $request->file('download_image');
                $downloadImageName = time() . '_download_' . uniqid() . '.' . $downloadImage->getClientOriginalExtension();
                $downloadImagePath = $downloadImage->storeAs('vouchers/downloads', $downloadImageName, 'public');
                $voucher->download_image = $downloadImagePath;

                if ($oldDownloadImage) {
                    Storage::disk('public')->delete($oldDownloadImage);
                }
            }

            $voucher->save();

            return redirect()->route('admin.voucher.index')
                           ->with('success', 'Voucher berhasil diupdate!');
        } catch (\Exception $e) {
            Log::error('Error updating voucher: ' . $e->getMessage());
            return redirect()->route('admin.voucher.index')
                           ->with('error', 'Gagal mengupdate voucher: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $voucher = Voucher::findOrFail($id);
            
            if ($voucher->image) {
                Storage::disk('public')->delete($voucher->image);
            }

            if ($voucher->download_image) {
                Storage::disk('public')->delete($voucher->download_image);
            }

            $voucher->delete();

            return redirect()->route('admin.voucher.index')
                           ->with('success', 'Voucher berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting voucher: ' . $e->getMessage());
            return redirect()->route('admin.voucher.index')
                           ->with('error', 'Gagal menghapus voucher: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $status = $request->get('status', 'all');
            
            // Ambil semua claims dengan voucher
            $claims = VoucherClaim::with('voucher')->latest()->get();
            
            // Filter berdasarkan status
            $filteredClaims = $this->filterClaimsByStatus($claims, $status);
            
            // Generate Excel
            $filename = $this->generateExcelFilename($status);
            $excelData = $this->generateExcelData($filteredClaims, $status);
            
            // Return download response
            return response()->streamDownload(function() use ($excelData) {
                echo $excelData;
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
            
        } catch (\Exception $e) {
            Log::error('Export voucher claims error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export data: ' . $e->getMessage());
        }
    }
    
    /**
     * Filter claims berdasarkan status
     */
    private function filterClaimsByStatus($claims, $status)
    {
        if ($status === 'all') {
            return $claims;
        }
        
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
    
    /**
     * Generate Excel data dalam format CSV yang kompatibel dengan Excel
     */
    private function generateExcelData($claims, $status)
    {
        // Gunakan format CSV dengan UTF-8 BOM untuk Excel compatibility
        $output = "\xEF\xBB\xBF"; // UTF-8 BOM
        
        // Status label untuk header
        $statusLabels = [
            'all' => 'SEMUA DATA',
            'active' => 'BELUM TERPAKAI',
            'used' => 'SUDAH TERPAKAI',
            'expired' => 'KADALUARSA'
        ];
        
        // Header Info
        $output .= "DATA KLAIM VOUCHER - " . ($statusLabels[$status] ?? 'SEMUA') . "\n";
        $output .= "Tanggal Export: " . Carbon::now()->format('d M Y H:i:s') . "\n";
        $output .= "Total Data: " . $claims->count() . " klaim\n";
        $output .= "\n";
        
        // Header Kolom
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
        
        $output .= implode("\t", $headers) . "\n";
        
        // Data Rows
        foreach ($claims as $index => $claim) {
            $isUsed = $claim->is_used || $claim->scanned_at;
            $voucherExpired = $claim->voucher && 
                            Carbon::now()->startOfDay()->greaterThan(Carbon::parse($claim->voucher->expiry_date));
            
            // Tentukan status
            if ($isUsed) {
                $statusPemakaian = 'Terpakai';
            } elseif ($voucherExpired) {
                $statusPemakaian = 'Kadaluarsa';
            } else {
                $statusPemakaian = 'Belum Terpakai';
            }
            
            $row = [
                $index + 1,
                $claim->user_name,
                $claim->user_domisili ?? '-',
                $claim->user_phone,
                $claim->voucher->name ?? '-',
                $claim->unique_code,
                $claim->created_at->format('d M Y H:i'),
                $claim->voucher ? Carbon::parse($claim->voucher->expiry_date)->format('d M Y') : '-',
                $voucherExpired ? 'Expired' : 'Aktif',
                $statusPemakaian,
                $claim->scanned_at ? $claim->scanned_at->format('d M Y H:i') : '-'
            ];
            
            // Escape dan format data
            $row = array_map(function($value) {
                // Handle special characters
                $value = str_replace(["\r\n", "\n", "\r"], ' ', $value);
                return $value;
            }, $row);
            
            $output .= implode("\t", $row) . "\n";
        }
        
        // Summary
        $output .= "\n";
        $output .= "RINGKASAN:\n";
        
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
        
        $output .= "Total Belum Terpakai: " . $activeCount . "\n";
        $output .= "Total Sudah Terpakai: " . $usedCount . "\n";
        $output .= "Total Kadaluarsa: " . $expiredCount . "\n";
        $output .= "TOTAL: " . $claims->count() . "\n";
        
        return $output;
    }

}