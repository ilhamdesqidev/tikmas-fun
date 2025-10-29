<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VoucherController extends Controller
{
    public function index()
    {
        try {
            Log::info('Voucher index called');
            $vouchers = Voucher::latest()->get();
            Log::info('Vouchers loaded: ' . $vouchers->count());
            
            return view('admin.voucher.index', compact('vouchers'));
        } catch (\Exception $e) {
            Log::error('Error loading vouchers: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        Log::info('Store voucher called', $request->all());
        
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:aktif,tidak_aktif,kadaluarsa',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240', // max 10MB
        ], [
            'name.required' => 'Nama voucher wajib diisi',
            'name.max' => 'Nama voucher maksimal 255 karakter',
            'status.required' => 'Status voucher wajib dipilih',
            'status.in' => 'Status tidak valid',
            'image.required' => 'Gambar voucher wajib diupload',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 10MB',
        ]);

        try {
            // Upload image ke folder storage_laravel/app/public/vouchers
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            Log::info('Uploading image: ' . $imageName);
            
            // Simpan ke storage_laravel/app/public/vouchers
            $imagePath = $image->storeAs('vouchers', $imageName, 'public');
            
            Log::info('Image uploaded to: ' . $imagePath);

            // Simpan data voucher
            $voucher = Voucher::create([
                'name' => $request->name,
                'status' => $request->status,
                'image' => $imagePath,
            ]);

            Log::info('Voucher created: ' . $voucher->id);

            return redirect()->route('admin.voucher.index')
                           ->with('success', 'Voucher berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error creating voucher: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Hapus image jika gagal menyimpan data
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
        
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:aktif,tidak_aktif,kadaluarsa',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // optional saat update
        ], [
            'name.required' => 'Nama voucher wajib diisi',
            'name.max' => 'Nama voucher maksimal 255 karakter',
            'status.required' => 'Status voucher wajib dipilih',
            'status.in' => 'Status tidak valid',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 10MB',
        ]);

        try {
            $voucher = Voucher::findOrFail($id);
            $oldImage = $voucher->image;

            // Update data
            $voucher->name = $request->name;
            $voucher->status = $request->status;

            // Jika ada image baru
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                Log::info('Uploading new image: ' . $imageName);
                
                // Upload image baru
                $imagePath = $image->storeAs('vouchers', $imageName, 'public');
                $voucher->image = $imagePath;

                // Hapus image lama
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                    Log::info('Deleted old image: ' . $oldImage);
                }
            }

            $voucher->save();
            Log::info('Voucher updated: ' . $voucher->id);

            return redirect()->route('admin.voucher.index')
                           ->with('success', 'Voucher berhasil diupdate!');
        } catch (\Exception $e) {
            Log::error('Error updating voucher: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Hapus image baru jika gagal update
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return redirect()->route('admin.voucher.index')
                           ->with('error', 'Gagal mengupdate voucher: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Delete voucher called: ' . $id);
            
            $voucher = Voucher::findOrFail($id);
            
            // Hapus image
            if ($voucher->image) {
                Storage::disk('public')->delete($voucher->image);
                Log::info('Deleted image: ' . $voucher->image);
            }

            // Hapus data
            $voucher->delete();
            Log::info('Voucher deleted: ' . $id);

            return redirect()->route('admin.voucher.index')
                           ->with('success', 'Voucher berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting voucher: ' . $e->getMessage());
            return redirect()->route('admin.voucher.index')
                           ->with('error', 'Gagal menghapus voucher: ' . $e->getMessage());
        }
    }
}