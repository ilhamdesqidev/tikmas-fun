<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    public function index()
    {
        // Update status promo berdasarkan tanggal terlebih dahulu
        $this->updatePromoStatuses();

        // Eager load relasi orders untuk optimasi
        $promos = Promo::withCount([
            'orders',
            'orders as sold_tickets' => function($query) {
                $query->whereIn('status', ['success', 'used'])->select(DB::raw('COALESCE(SUM(ticket_quantity), 0)'));
            }
        ])
        ->withSum([
            'orders as total_revenue' => function($query) {
                $query->whereIn('status', ['success', 'used']);
            }
        ], 'total_price')
        ->latest()
        ->get();
        
        // Hitung statistik dengan data real
        $totalPromos = $promos->count();
        $activePromos = $promos->where('status', 'active')->count();
        $draftPromos = $promos->where('status', 'draft')->count();
        $comingSoonPromos = $promos->where('status', 'coming_soon')->count();
        $expiredPromos = $promos->where('status', 'expired')->count();
        
        // Hitung persentase
        $activePercentage = $totalPromos > 0 ? round(($activePromos / $totalPromos) * 100) : 0;
        
        // Hitung total penjualan dari orders yang success
        $totalSales = Order::whereIn('status', ['success', 'used'])->sum('total_price');
        
        // Hitung rata-rata diskon dari promo yang memiliki diskon
        $promoWithDiscounts = $promos->filter(fn($promo) => $promo->discount_percent > 0);
        $averageDiscount = $promoWithDiscounts->count() > 0 
            ? round($promoWithDiscounts->avg('discount_percent')) 
            : 0;
        
        return view('admin.promo.index', compact(
            'promos', 
            'activePercentage', 
            'totalSales', 
            'averageDiscount',
            'draftPromos',
            'comingSoonPromos',
            'expiredPromos'
        ));
    }

    public function create()
    {
        return view('admin.promo.create');
    }

    public function store(Request $request)
    {
        \Log::info('Store method called');
        \Log::info('Request data:', $request->all());

        // Validasi
        $request->validate([
            'name' => 'required|string|max:255|unique:promos,name',
            'description' => 'required|string',
            'terms_conditions' => 'required|string',
            'original_price' => 'required|numeric|min:1',
            'promo_price' => 'required|numeric|min:1|lt:original_price',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'quota' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,coming_soon,active,inactive',
            'category' => 'required|in:bulanan,holiday,birthday,nasional,student',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:30728',
            'bracelet_design' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:30728',
        ]);

        try {
            // Upload gambar promo
            $imagePath = $request->file('image')->store('promos', 'public');
            
            // Upload desain gelang jika ada
            $braceletDesignPath = null;
            if ($request->hasFile('bracelet_design')) {
                $braceletDesignPath = $request->file('bracelet_design')->store('bracelet-designs', 'public');
            }

            // Hitung diskon
            $originalPrice = (float) $request->original_price;
            $promoPrice = (float) $request->promo_price;
            $discountPercent = round((($originalPrice - $promoPrice) / $originalPrice) * 100);

            // Tentukan status berdasarkan tanggal
            $status = $request->status;
            $startDate = Carbon::parse($request->start_date);
            
            // Auto-set status coming_soon jika start_date di masa depan
            if ($status === 'active' && $startDate->gt(Carbon::now())) {
                $status = 'coming_soon';
            }

            // Create promo
            $promo = Promo::create([
                'name' => $request->name,
                'image' => $imagePath,
                'bracelet_design' => $braceletDesignPath,
                'description' => $request->description,
                'terms_conditions' => $request->terms_conditions,
                'original_price' => $originalPrice,
                'promo_price' => $promoPrice,
                'discount_percent' => $discountPercent,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'quota' => $request->quota,
                'status' => $status,
                'category' => $request->category,
                'featured' => $request->has('featured'),
                'sold_count' => 0,
            ]);

            \Log::info('Promo created successfully: ' . $promo->id);
        
            return redirect()->route('admin.promo.index')
                ->with('success', 'Promo "' . $promo->name . '" berhasil dibuat!');
                
        } catch (\Exception $e) {
            \Log::error('Error creating promo: ' . $e->getMessage());
            
            // Hapus file yang sudah terupload jika ada error
            if (isset($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            if (isset($braceletDesignPath) && Storage::disk('public')->exists($braceletDesignPath)) {
                Storage::disk('public')->delete($braceletDesignPath);
            }
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())
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
            
            // Hapus desain gelang
            if ($promo->bracelet_design) {
                Storage::disk('public')->delete($promo->bracelet_design);
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
            
            // Status cycle: draft -> coming_soon -> active -> inactive
            $statusFlow = [
                'draft' => 'coming_soon',
                'coming_soon' => 'active',
                'active' => 'inactive',
                'inactive' => 'draft'
            ];
            
            // Jangan ubah status expired
            if ($promo->status === 'expired') {
                return response()->json([
                    'success' => false,
                    'message' => 'Promo yang sudah expired tidak dapat diubah statusnya!'
                ], 400);
            }
            
            $newStatus = $statusFlow[$promo->status] ?? 'draft';
            $promo->status = $newStatus;
            $promo->save();

            return response()->json([
                'success' => true,
                'message' => 'Status promo berhasil diubah!',
                'new_status' => $promo->status,
                'new_status_text' => $promo->status_text
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $promo = Promo::findOrFail($id);
            return view('admin.promo.edit', compact('promo'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $promo = Promo::findOrFail($id);

            // Validasi
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:promos,name,' . $promo->id,
                'description' => 'required|string',
                'terms_conditions' => 'required|string',
                'original_price' => 'required|numeric|min:1',
                'promo_price' => 'required|numeric|min:1|lt:original_price',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date|after:start_date',
                'quota' => 'nullable|integer|min:1',
                'status' => 'required|in:draft,coming_soon,active,inactive,expired',
                'category' => 'required|in:bulanan,holiday,birthday,nasional,student',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30728',
                'bracelet_design' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:30728',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image == '1') {
                if ($promo->image) {
                    Storage::disk('public')->delete($promo->image);
                    $promo->image = null;
                }
            }

            // Handle bracelet design removal
            if ($request->has('remove_bracelet_design') && $request->remove_bracelet_design == '1') {
                if ($promo->bracelet_design) {
                    Storage::disk('public')->delete($promo->bracelet_design);
                    $promo->bracelet_design = null;
                }
            }

            // Upload gambar baru jika ada
            if ($request->hasFile('image')) {
                // Hapus gambar lama
                if ($promo->image) {
                    Storage::disk('public')->delete($promo->image);
                }
                $imagePath = $request->file('image')->store('promos', 'public');
                $promo->image = $imagePath;
            }

            // Upload desain gelang baru jika ada
            if ($request->hasFile('bracelet_design')) {
                // Hapus desain gelang lama
                if ($promo->bracelet_design) {
                    Storage::disk('public')->delete($promo->bracelet_design);
                }
                $braceletDesignPath = $request->file('bracelet_design')->store('bracelet-designs', 'public');
                $promo->bracelet_design = $braceletDesignPath;
            }

            // Hitung diskon
            $originalPrice = (float) $request->original_price;
            $promoPrice = (float) $request->promo_price;
            
            if ($promoPrice >= $originalPrice) {
                return redirect()->back()
                    ->with('error', 'Harga promo harus lebih kecil dari harga normal')
                    ->withInput();
            }
            
            $discountPercent = round((($originalPrice - $promoPrice) / $originalPrice) * 100);

            // Update status berdasarkan tanggal jika diperlukan
            $status = $request->status;
            $startDate = Carbon::parse($request->start_date);
            
            // Auto-correct status based on dates
            if ($status !== 'expired') {
                if ($promo->is_expired) {
                    $status = 'expired';
                } elseif ($startDate->gt(Carbon::now()) && $status === 'active') {
                    $status = 'coming_soon';
                }
            }

            // Update data promo
            $promo->name = $request->name;
            $promo->description = $request->description;
            $promo->terms_conditions = $request->terms_conditions;
            $promo->original_price = $originalPrice;
            $promo->promo_price = $promoPrice;
            $promo->discount_percent = $discountPercent;
            $promo->start_date = $request->start_date;
            $promo->end_date = $request->end_date;
            $promo->quota = $request->quota;
            $promo->status = $status;
            $promo->category = $request->category;
            $promo->featured = $request->has('featured');
            $promo->save();

            return redirect()->route('admin.promo.index')
                ->with('success', 'Promo "' . $promo->name . '" berhasil diperbarui!');
                
        } catch (\Exception $e) {
            \Log::error('Error updating promo: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        try {
            $promo = Promo::findOrFail($id);
            return view('admin.promo.show', compact('promo'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method untuk automatic update status semua promo
    private function updatePromoStatuses()
    {
        try {
            $promos = Promo::where('status', '!=', 'expired')->get();
            $updatedCount = 0;

            foreach ($promos as $promo) {
                $originalStatus = $promo->status;
                $promo->updateStatusBasedOnDate();
                
                if ($promo->status !== $originalStatus) {
                    $updatedCount++;
                }
            }

            \Log::info("Auto-updated {$updatedCount} promo statuses");
            return $updatedCount;

        } catch (\Exception $e) {
            \Log::error('Error updating promo statuses: ' . $e->getMessage());
            return 0;
        }
    }

    // Method untuk mendapatkan statistik promo
    public function getPromoStats()
    {
        try {
            // Update status terlebih dahulu
            $this->updatePromoStatuses();

            $stats = [
                'total' => Promo::count(),
                'draft' => Promo::draft()->count(),
                'coming_soon' => Promo::comingSoon()->count(),
                'active' => Promo::active()->count(),
                'inactive' => Promo::inactive()->count(),
                'expired' => Promo::expired()->count(),
                'sold_out' => Promo::whereColumn('sold_count', '>=', 'quota')
                    ->whereNotNull('quota')
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk bulk action
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,delete,publish_draft',
            'promo_ids' => 'required|array',
            'promo_ids.*' => 'exists:promos,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid!',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $promos = Promo::whereIn('id', $request->promo_ids)->get();
            $successCount = 0;
            $errorMessages = [];

            foreach ($promos as $promo) {
                try {
                    switch ($request->action) {
                        case 'publish_draft':
                            if ($promo->status === 'draft') {
                                $promo->status = $promo->isComingSoon ? 'coming_soon' : 'active';
                                $promo->save();
                                $successCount++;
                            } else {
                                $errorMessages[] = "Promo '{$promo->name}' bukan draft";
                            }
                            break;
                            
                        case 'activate':
                            if ($promo->status !== 'expired') {
                                $promo->status = 'active';
                                $promo->save();
                                $successCount++;
                            } else {
                                $errorMessages[] = "Promo '{$promo->name}' sudah expired tidak dapat diaktifkan";
                            }
                            break;
                            
                        case 'deactivate':
                            if ($promo->status !== 'expired') {
                                $promo->status = 'inactive';
                                $promo->save();
                                $successCount++;
                            } else {
                                $errorMessages[] = "Promo '{$promo->name}' sudah expired";
                            }
                            break;
                            
                        case 'delete':
                            // Hapus file gambar
                            if ($promo->image) {
                                Storage::disk('public')->delete($promo->image);
                            }
                            if ($promo->bracelet_design) {
                                Storage::disk('public')->delete($promo->bracelet_design);
                            }
                            $promo->delete();
                            $successCount++;
                            break;
                    }
                } catch (\Exception $e) {
                    $errorMessages[] = "Promo '{$promo->name}': " . $e->getMessage();
                }
            }

            $message = "Berhasil memproses {$successCount} promo.";
            if (!empty($errorMessages)) {
                $message .= ' ' . implode(', ', array_slice($errorMessages, 0, 3));
                if (count($errorMessages) > 3) {
                    $message .= ' dan ' . (count($errorMessages) - 3) . ' error lainnya';
                }
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'processed_count' => $successCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk mengubah status spesifik
    public function changeStatus(Request $request, $id)
    {
        try {
            $promo = Promo::findOrFail($id);
            $newStatus = $request->status;

            $allowedStatuses = ['draft', 'coming_soon', 'active', 'inactive', 'expired'];
            
            if (!in_array($newStatus, $allowedStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak valid!'
                ], 422);
            }

            // Validasi perubahan status
            if ($newStatus === 'expired' && !$promo->is_expired) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya promo yang sudah melewati end_date yang bisa di-set sebagai expired!'
                ], 422);
            }

            $oldStatus = $promo->status;
            $promo->status = $newStatus;
            $promo->save();

            return response()->json([
                'success' => true,
                'message' => 'Status promo berhasil diubah dari ' . $oldStatus . ' menjadi ' . $newStatus,
                'new_status' => $promo->status,
                'new_status_text' => $promo->status_text
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}