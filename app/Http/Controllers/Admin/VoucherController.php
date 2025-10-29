<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->get();
        return view('admin.voucher.index', compact('vouchers'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:aktif,tidak_aktif,kadaluarsa',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10024', // max 10MB
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
            // Upload image
            $imagePath = $request->file('image')->store('vouchers', 'public');

            // Simpan data voucher
            Voucher::create([
                'name' => $request->name,
                'status' => $request->status,
                'image' => $imagePath,
            ]);

            return redirect()->route('admin.voucher.index')
                           ->with('success', 'Voucher berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Hapus image jika gagal menyimpan data
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return redirect()->route('admin.voucher.index')
                           ->with('error', 'Gagal menambahkan voucher. Silakan coba lagi.');
        }
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:aktif,tidak_aktif,kadaluarsa',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10024', // optional saat update
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
                // Upload image baru
                $imagePath = $request->file('image')->store('vouchers', 'public');
                $voucher->image = $imagePath;

                // Hapus image lama
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $voucher->save();

            return redirect()->route('admin.voucher.index')
                           ->with('success', 'Voucher berhasil diupdate!');
        } catch (\Exception $e) {
            // Hapus image baru jika gagal update
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            return redirect()->route('admin.voucher.index')
                           ->with('error', 'Gagal mengupdate voucher. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        try {
            $voucher = Voucher::findOrFail($id);
            
            // Hapus image
            if ($voucher->image) {
                Storage::disk('public')->delete($voucher->image);
            }

            // Hapus data
            $voucher->delete();

            return redirect()->route('admin.voucher.index')
                           ->with('success', 'Voucher berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.voucher.index')
                           ->with('error', 'Gagal menghapus voucher. Silakan coba lagi.');
        }
    }
}