@extends('layouts.app')

@section('title', 'Edit Paket Promo')
@section('page-title', 'Edit Paket Promo')
@section('page-description', 'Edit paket promo yang sudah ada')

@section('content')
    <div class="card rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Edit Paket Promo</h2>
            <a href="{{ route('admin.promo.index') }}" class="text-gray-400 hover:text-gray-600">
                <i data-feather="x" class="w-6 h-6"></i>
            </a>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('admin.promo.update', $promo->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Field Upload Gambar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Promo</label>
                <div class="mt-1 flex items-center">
                    <div class="relative rounded-lg overflow-hidden w-40 h-40 bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300">
                        <img id="imagePreview" src="{{ $promo->image_url }}" alt="Preview" class="w-full h-full object-cover">
                        <span id="placeholderText" class="text-gray-400 text-sm hidden">No image</span>
                    </div>
                    <div class="ml-4">
                        <input type="file" name="image" id="imageInput" accept="image/*" 
                               class="hidden" onchange="previewImage(this)">
                        <label for="imageInput" class="cursor-pointer px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm">
                            Ganti Gambar
                        </label>
                        <p class="text-xs text-gray-500 mt-2">Format: JPEG, PNG, JPG, GIF<br>Maksimal: 2MB</p>
                        @if($promo->image)
                        <div class="mt-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="remove_image" value="1" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                                <span class="ml-2 text-sm text-gray-700">Hapus gambar saat ini</span>
                            </label>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Promo *</label>
                    <input type="text" name="name" value="{{ old('name', $promo->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="weekend" {{ old('category', $promo->category) == 'weekend' ? 'selected' : '' }}>Weekend Special</option>
                        <option value="student" {{ old('category', $promo->category) == 'student' ? 'selected' : '' }}>Student Discount</option>
                        <option value="premium" {{ old('category', $promo->category) == 'premium' ? 'selected' : '' }}>Premium Package</option>
                        <option value="early_bird" {{ old('category', $promo->category) == 'early_bird' ? 'selected' : '' }}>Early Bird</option>
                        <option value="group" {{ old('category', $promo->category) == 'group' ? 'selected' : '' }}>Group Package</option>
                        <option value="general" {{ old('category', $promo->category) == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
                <textarea name="description" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('description', $promo->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Syarat dan Ketentuan *</label>
                <textarea name="terms_conditions" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('terms_conditions', $promo->terms_conditions) }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Gunakan bullet points atau penomoran untuk setiap poin</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Normal *</label>
                    <input type="number" name="original_price" value="{{ old('original_price', $promo->original_price) }}" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Promo *</label>
                    <input type="number" name="promo_price" value="{{ old('promo_price', $promo->promo_price) }}" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Diskon (%)</label>
                    <input type="number" id="discount_percent" value="{{ $promo->discount_percent }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $promo->start_date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $promo->end_date ? $promo->end_date->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kuota (opsional)</label>
                    <input type="number" name="quota" value="{{ old('quota', $promo->quota) }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="active" {{ old('status', $promo->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $promo->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="expired" {{ old('status', $promo->status) == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Terjual</label>
                    <input type="number" value="{{ $promo->sold_count }}" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                </div>
                <div class="flex items-center justify-center">
                    <div class="flex items-center mt-6">
                        <input type="checkbox" id="featured" name="featured" value="1" {{ old('featured', $promo->featured) ? 'checked' : '' }} class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                        <label for="featured" class="ml-2 text-sm font-medium text-gray-700">Tampilkan sebagai promo unggulan</label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('admin.promo.index') }}" class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 font-medium">
                    Perbarui Promo
                </button>
            </div>
        </form>
    </div>
@endsection

@section('extra-js')
<script>
    // Preview gambar
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('placeholderText');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Auto calculate discount percentage
    document.addEventListener('DOMContentLoaded', function() {
        const originalPrice = document.querySelector('input[name="original_price"]');
        const promoPrice = document.querySelector('input[name="promo_price"]');
        const discountPercent = document.getElementById('discount_percent');

        function calculateDiscount() {
            if (originalPrice.value && promoPrice.value) {
                const original = parseFloat(originalPrice.value);
                const promo = parseFloat(promoPrice.value);
                
                if (original <= 0) {
                    discountPercent.value = '0';
                    return;
                }
                
                if (promo >= original) {
                    discountPercent.value = '0';
                    return;
                }
                
                const discount = ((original - promo) / original * 100).toFixed(0);
                discountPercent.value = discount;
            }
        }

        originalPrice?.addEventListener('input', calculateDiscount);
        promoPrice?.addEventListener('input', calculateDiscount);
        
        // Hitung diskon awal
        calculateDiscount();
    });

    // Validasi form
    document.querySelector('form').addEventListener('submit', function(e) {
        const originalPrice = parseFloat(document.querySelector('input[name="original_price"]').value);
        const promoPrice = parseFloat(document.querySelector('input[name="promo_price"]').value);
        
        // Validasi harga promo harus lebih kecil dari harga normal
        if (promoPrice >= originalPrice) {
            e.preventDefault();
            alert('Harga promo harus lebih kecil dari harga normal');
            return false;
        }
    });
</script>
@endsection