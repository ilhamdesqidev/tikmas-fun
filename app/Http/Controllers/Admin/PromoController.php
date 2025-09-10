<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // In a real application, you would fetch promos from database
        $promos = collect([
            [
                'id' => 1,
                'name' => 'Paket Family Weekend',
                'category' => 'weekend',
                'description' => 'Nikmati liburan keluarga dengan diskon spesial untuk tiket masuk dan wahana pilihan',
                'original_price' => 500000,
                'promo_price' => 375000,
                'discount_percent' => 25,
                'start_date' => '2024-12-01',
                'end_date' => '2024-12-31',
                'status' => 'active',
                'featured' => true,
                'sold_count' => 125,
                'quota' => null,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(2),
            ],
            [
                'id' => 2,
                'name' => 'Promo Pelajar',
                'category' => 'student',
                'description' => 'Khusus pelajar dengan menunjukkan kartu pelajar valid',
                'original_price' => 75000,
                'promo_price' => 50000,
                'discount_percent' => 33,
                'start_date' => '2024-01-01',
                'end_date' => null,
                'status' => 'active',
                'featured' => false,
                'sold_count' => 89,
                'quota' => null,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(5),
            ],
            [
                'id' => 3,
                'name' => 'All-Inclusive VIP',
                'category' => 'premium',
                'description' => 'Semua wahana + makan siang + foto profesional + guide pribadi',
                'original_price' => 1200000,
                'promo_price' => 950000,
                'discount_percent' => 21,
                'start_date' => '2024-12-15',
                'end_date' => '2025-01-15',
                'status' => 'active',
                'featured' => true,
                'sold_count' => 23,
                'quota' => 50,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDay(),
            ],
            [
                'id' => 4,
                'name' => 'Promo Tahun Baru',
                'category' => 'early_bird',
                'description' => 'Dapatkan diskon super untuk pembelian tiket di awal tahun',
                'original_price' => 300000,
                'promo_price' => 200000,
                'discount_percent' => 33,
                'start_date' => '2024-01-01',
                'end_date' => '2024-01-31',
                'status' => 'expired',
                'featured' => false,
                'sold_count' => 456,
                'quota' => 500,
                'created_at' => now()->subDays(365),
                'updated_at' => now()->subDays(300),
            ],
        ]);

        return view('admin.promo.index', compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.promo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:weekend,student,premium,early_bird,group',
            'description' => 'required|string|max:500',
            'original_price' => 'required|numeric|min:0',
            'promo_price' => 'required|numeric|min:0|lt:original_price',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'quota' => 'nullable|integer|min:1',
            'status' => 'required|string|in:active,inactive',
            'featured' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Calculate discount percentage
            $discountPercent = (($request->original_price - $request->promo_price) / $request->original_price) * 100;

            // In a real application, you would save to database
            // $promo = Promo::create([
            //     'name' => $request->name,
            //     'category' => $request->category,
            //     'description' => $request->description,
            //     'original_price' => $request->original_price,
            //     'promo_price' => $request->promo_price,
            //     'discount_percent' => round($discountPercent, 2),
            //     'start_date' => $request->start_date,
            //     'end_date' => $request->end_date,
            //     'quota' => $request->quota,
            //     'status' => $request->status,
            //     'featured' => $request->has('featured'),
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Promo berhasil dibuat!',
                'data' => [
                    'id' => rand(100, 999),
                    'name' => $request->name,
                    'discount_percent' => round($discountPercent, 2)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // In a real application, you would fetch from database
        // $promo = Promo::findOrFail($id);
        
        $promo = [
            'id' => $id,
            'name' => 'Paket Family Weekend',
            'category' => 'weekend',
            'description' => 'Nikmati liburan keluarga dengan diskon spesial untuk tiket masuk dan wahana pilihan',
            'original_price' => 500000,
            'promo_price' => 375000,
            'discount_percent' => 25,
            'start_date' => '2024-12-01',
            'end_date' => '2024-12-31',
            'status' => 'active',
            'featured' => true,
            'sold_count' => 125,
            'quota' => null,
        ];

        return view('admin.promo.show', compact('promo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // In a real application, you would fetch from database
        // $promo = Promo::findOrFail($id);
        
        $promo = [
            'id' => $id,
            'name' => 'Paket Family Weekend',
            'category' => 'weekend',
            'description' => 'Nikmati liburan keluarga dengan diskon spesial untuk tiket masuk dan wahana pilihan',
            'original_price' => 500000,
            'promo_price' => 375000,
            'discount_percent' => 25,
            'start_date' => '2024-12-01',
            'end_date' => '2024-12-31',
            'status' => 'active',
            'featured' => true,
            'sold_count' => 125,
            'quota' => null,
        ];

        return view('admin.promo.edit', compact('promo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:weekend,student,premium,early_bird,group',
            'description' => 'required|string|max:500',
            'original_price' => 'required|numeric|min:0',
            'promo_price' => 'required|numeric|min:0|lt:original_price',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'quota' => 'nullable|integer|min:1',
            'status' => 'required|string|in:active,inactive',
            'featured' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Calculate discount percentage
            $discountPercent = (($request->original_price - $request->promo_price) / $request->original_price) * 100;

            // In a real application, you would update in database
            // $promo = Promo::findOrFail($id);
            // $promo->update([
            //     'name' => $request->name,
            //     'category' => $request->category,
            //     'description' => $request->description,
            //     'original_price' => $request->original_price,
            //     'promo_price' => $request->promo_price,
            //     'discount_percent' => round($discountPercent, 2),
            //     'start_date' => $request->start_date,
            //     'end_date' => $request->end_date,
            //     'quota' => $request->quota,
            //     'status' => $request->status,
            //     'featured' => $request->has('featured'),
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Promo berhasil diperbarui!',
                'data' => [
                    'id' => $id,
                    'name' => $request->name,
                    'discount_percent' => round($discountPercent, 2)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // In a real application, you would delete from database
            // $promo = Promo::findOrFail($id);
            // $promo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Promo berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data'
            ], 500);
        }
    }

    /**
     * Toggle promo status.
     */
    public function toggleStatus(string $id)
    {
        try {
            // In a real application, you would update status in database
            // $promo = Promo::findOrFail($id);
            // $promo->status = $promo->status === 'active' ? 'inactive' : 'active';
            // $promo->save();

            return response()->json([
                'success' => true,
                'message' => 'Status promo berhasil diubah!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status'
            ], 500);
        }
    }

    /**
     * Export promos data.
     */
    public function export(Request $request)
    {
        try {
            // In a real application, you would generate export file
            // This could be Excel, PDF, or CSV format

            return response()->json([
                'success' => true,
                'message' => 'Data promo berhasil diekspor!',
                'download_url' => '/admin/promo/download/promos_' . date('Y-m-d') . '.xlsx'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengekspor data'
            ], 500);
        }
    }

    /**
     * Get promo statistics.
     */
    public function statistics()
    {
        // In a real application, you would calculate from database
        $stats = [
            'total_promos' => 12,
            'active_promos' => 8,
            'inactive_promos' => 3,
            'expired_promos' => 1,
            'total_sales' => 45200000,
            'total_sold' => 693,
            'average_discount' => 25,
            'most_popular_category' => 'weekend',
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}