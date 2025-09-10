<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
   // Di dalam method index(), perbaiki perhitungan statistik
    public function index()
    {
        $promos = Promo::latest()->get();
        
        // Hitung statistik dengan pengecekan division by zero
        $totalPromos = $promos->count();
        $activePromos = $promos->where('status', 'active')->count();
        
        // Hitung persentase promo aktif
        $activePercentage = $totalPromos > 0 ? round(($activePromos / $totalPromos) * 100) : 0;
        
        // Hitung total penjualan
        $totalSales = 0;
        foreach ($promos as $promo) {
            $totalSales += $promo->sold_count * $promo->promo_price;
        }
        
        // Hitung rata-rata diskon
        $totalDiscount = 0;
        $countWithDiscount = 0;
        foreach ($promos as $promo) {
            if ($promo->discount_percent > 0) {
                $totalDiscount += $promo->discount_percent;
                $countWithDiscount++;
            }
        }
        $averageDiscount = $countWithDiscount > 0 ? round($totalDiscount / $countWithDiscount) : 0;
        
        return view('admin.promo.index', compact('promos', 'activePercentage', 'totalSales', 'averageDiscount'));
    }

    public function create()
    {
        return view('admin.promo.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'original_price' => 'required|numeric|min:0',
            'promo_price' => 'required|numeric|min:0|lt:original_price',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'quota' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Upload gambar
            $imagePath = $request->file('image')->store('promos', 'public');
            
            // Hitung diskon
            $discountPercent = (($request->original_price - $request->promo_price) / $request->original_price) * 100;

            // Simpan promo
            $promo = Promo::create([
                'name' => $request->name,
                'image' => $imagePath,
                'description' => $request->description,
                'original_price' => $request->original_price,
                'promo_price' => $request->promo_price,
                'discount_percent' => round($discountPercent),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'quota' => $request->quota,
                'status' => $request->status,
                'featured' => $request->has('featured'),
            ]);

            return redirect()->route('admin.promo.index')
                ->with('success', 'Promo berhasil dibuat!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $promo = Promo::findOrFail($id);
        return view('admin.promo.show', compact('promo'));
    }

    public function edit($id)
    {
        $promo = Promo::findOrFail($id);
        return view('admin.promo.edit', compact('promo'));
    }

    public function update(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'original_price' => 'required|numeric|min:0',
            'promo_price' => 'required|numeric|min:0|lt:original_price',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'quota' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Jika ada gambar baru, upload dan hapus yang lama
            if ($request->hasFile('image')) {
                // Hapus gambar lama
                if ($promo->image) {
                    Storage::disk('public')->delete($promo->image);
                }
                
                // Upload gambar baru
                $imagePath = $request->file('image')->store('promos', 'public');
                $promo->image = $imagePath;
            }
            
            // Hitung diskon
            $discountPercent = (($request->original_price - $request->promo_price) / $request->original_price) * 100;

            // Update promo
            $promo->update([
                'name' => $request->name,
                'description' => $request->description,
                'original_price' => $request->original_price,
                'promo_price' => $request->promo_price,
                'discount_percent' => round($discountPercent),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'quota' => $request->quota,
                'status' => $request->status,
                'featured' => $request->has('featured'),
            ]);

            return redirect()->route('admin.promo.index')
                ->with('success', 'Promo berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $promo = Promo::findOrFail($id);
            
            // Hapus gambar
            if ($promo->image) {
                Storage::disk('public')->delete($promo->image);
            }
            
            $promo->delete();

            return redirect()->route('admin.promo.index')
                ->with('success', 'Promo berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $promo = Promo::findOrFail($id);
            $promo->status = $promo->status === 'active' ? 'inactive' : 'active';
            $promo->save();

            return response()->json([
                'success' => true,
                'message' => 'Status promo berhasil diubah!',
                'new_status' => $promo->status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}