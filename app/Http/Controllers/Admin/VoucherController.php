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
            
            // Update semua voucher yang sudah expired (gunakan end of day)
            Voucher::where('expiry_date', '<', Carbon::now()->startOfDay())
                ->where('status', '!=', 'kadaluarsa')
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
            'status' => 'required|in:aktif,tidak_aktif,kadaluarsa',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'expiry_date' => 'required|date',
        ], [
            'name.required' => 'Nama voucher wajib diisi',
            'deskripsi.required' => 'Deskripsi voucher wajib diisi',
            'status.required' => 'Status voucher wajib dipilih',
            'image.required' => 'Gambar voucher wajib diupload',
            'expiry_date.required' => 'Tanggal kadaluarsa wajib diisi',
        ]);

        try {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('vouchers', $imageName, 'public');

            // Cek apakah tanggal expiry sudah lewat (gunakan startOfDay untuk perbandingan tanggal saja)
            $status = $request->status;
            $expiryDate = Carbon::parse($request->expiry_date);
            if (Carbon::now()->startOfDay()->greaterThan($expiryDate)) {
                $status = 'kadaluarsa';
            }

            Voucher::create([
                'name' => $request->name,
                'deskripsi' => $request->deskripsi,
                'status' => $status,
                'image' => $imagePath,
                'expiry_date' => $request->expiry_date,
            ]);

            return redirect()->route('admin.voucher.index')
                           ->with('success', 'Voucher berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error creating voucher: ' . $e->getMessage());
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
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
            'status' => 'required|in:aktif,tidak_aktif,kadaluarsa',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'expiry_date' => 'required|date',
        ]);

        try {
            $voucher = Voucher::findOrFail($id);
            $oldImage = $voucher->image;

            $voucher->name = $request->name;
            $voucher->deskripsi = $request->deskripsi;
            $voucher->expiry_date = $request->expiry_date;

            // Cek apakah tanggal expiry sudah lewat (gunakan startOfDay)
            $expiryDate = Carbon::parse($request->expiry_date);
            if (Carbon::now()->startOfDay()->greaterThan($expiryDate)) {
                $voucher->status = 'kadaluarsa';
            } else {
                $voucher->status = $request->status;
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('vouchers', $imageName, 'public');
                $voucher->image = $imagePath;

                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
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

            $voucher->delete();

            return redirect()->route('admin.voucher.index')
                           ->with('success', 'Voucher berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting voucher: ' . $e->getMessage());
            return redirect()->route('admin.voucher.index')
                           ->with('error', 'Gagal menghapus voucher: ' . $e->getMessage());
        }
    }

    // Helper function untuk mengecek apakah voucher expired
    public static function isVoucherExpired($expiryDate)
    {
        return Carbon::now()->startOfDay()->greaterThan(Carbon::parse($expiryDate));
    }
}