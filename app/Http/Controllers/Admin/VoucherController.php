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
            
            // Update semua voucher yang sudah expired atau habis
            Voucher::where(function($query) {
                $query->where('expiry_date', '<', Carbon::now()->startOfDay())
                      ->orWhere(function($q) {
                          $q->where('is_unlimited', false)
                            ->whereRaw('quota <= (SELECT COUNT(*) FROM voucher_claims WHERE voucher_claims.voucher_id = vouchers.id)');
                      });
            })->where('status', 'aktif')->update(['status' => 'kadaluarsa']);
            
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
            'quota_type' => 'required|in:unlimited,limited',
            'quota' => 'required_if:quota_type,limited|integer|min:1|nullable',
        ], [
            'name.required' => 'Nama voucher wajib diisi',
            'deskripsi.required' => 'Deskripsi voucher wajib diisi',
            'status.required' => 'Status voucher wajib dipilih',
            'image.required' => 'Gambar voucher wajib diupload',
            'expiry_date.required' => 'Tanggal kadaluarsa wajib diisi',
            'quota_type.required' => 'Tipe kuota wajib dipilih',
            'quota.required_if' => 'Kuota wajib diisi untuk tipe terbatas',
            'quota.min' => 'Kuota minimal 1',
        ]);

        try {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('vouchers', $imageName, 'public');

            // Cek apakah tanggal expiry sudah lewat
            $status = $request->status;
            $expiryDate = Carbon::parse($request->expiry_date);
            if (Carbon::now()->startOfDay()->greaterThan($expiryDate)) {
                $status = 'kadaluarsa';
            }

            // Tentukan kuota
            $isUnlimited = $request->quota_type === 'unlimited';
            $quota = $isUnlimited ? null : $request->quota;

            Voucher::create([
                'name' => $request->name,
                'deskripsi' => $request->deskripsi,
                'status' => $status,
                'image' => $imagePath,
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
            'quota_type' => 'required|in:unlimited,limited',
            'quota' => 'required_if:quota_type,limited|integer|min:1|nullable',
        ]);

        try {
            $voucher = Voucher::findOrFail($id);
            $oldImage = $voucher->image;

            $voucher->name = $request->name;
            $voucher->deskripsi = $request->deskripsi;
            $voucher->expiry_date = $request->expiry_date;

            // Cek apakah tanggal expiry sudah lewat
            $expiryDate = Carbon::parse($request->expiry_date);
            if (Carbon::now()->startOfDay()->greaterThan($expiryDate)) {
                $voucher->status = 'kadaluarsa';
            } else {
                $voucher->status = $request->status;
            }

            // Update kuota
            $isUnlimited = $request->quota_type === 'unlimited';
            $voucher->is_unlimited = $isUnlimited;
            $voucher->quota = $isUnlimited ? null : $request->quota;

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
}