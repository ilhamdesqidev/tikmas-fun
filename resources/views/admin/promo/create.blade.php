@extends('layouts.app')

@section('title', 'Tambah Paket Promo')
@section('page-title', 'Tambah Paket Promo')
@section('page-description', 'Buat paket promo baru')

@section('content')
    <div class="card rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900">Tambah Paket Promo Baru</h2>
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
        
        <form action="{{ route('admin.promo.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Field Upload Gambar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Promo *</label>
                <div class="mt-1 flex items-center">
                    <div class="relative rounded-lg overflow-hidden w-40 h-40 bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300">
                        <img id="imagePreview" src="" alt="Preview" class="hidden w-full h-full object-cover">
                        <span id="placeholderText" class="text-gray-400 text-sm">No image</span>
                    </div>
                    <div class="ml-4">
                        <input type="file" name="image" id="imageInput" accept="image/*" required 
                               class="hidden" onchange="previewImage(this)">
                        <label for="imageInput" class="cursor-pointer px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm">
                            Pilih Gambar
                        </label>
                        <p class="text-xs text-gray-500 mt-2">Format: JPEG, PNG, JPG, GIF<br>Maksimal: 10MB</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Promo *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="bulanan" {{ old('category', $promo->category ?? '') == 'bulanan' ? 'selected' : '' }}>promo bulanan</option>
                    <option value="holiday" {{ old('category', $promo->category ?? '') == 'holiday' ? 'selected' : '' }}>promo holiday</option>
                    <option value="birthday" {{ old('category', $promo->category ?? '') == 'birthday' ? 'selected' : '' }}>promo Birthday</option>
                    <option value="nasional" {{ old('category', $promo->category ?? '') == 'nasional' ? 'selected' : '' }}>promo hari nasional</option>
                    <option value="student" {{ old('category', $promo->category ?? '') == 'student' ? 'selected' : '' }}>promo student discount</option>
                </select>
            </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
                <textarea name="description" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Syarat dan Ketentuan *</label>
                <textarea name="terms_conditions" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('terms_conditions') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Gunakan bullet points atau penomoran untuk setiap poin</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Normal *</label>
                    <input type="number" name="original_price" value="{{ old('original_price') }}" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Promo *</label>
                    <input type="number" name="promo_price" value="{{ old('promo_price') }}" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Diskon (%)</label>
                    <input type="number" id="discount_percent" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kuota (opsional)</label>
                    <input type="number" name="quota" value="{{ old('quota') }}" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                <label for="featured" class="ml-2 text-sm font-medium text-gray-700">Tampilkan sebagai promo unggulan</label>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('admin.promo.index') }}" class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 font-medium">
                    Simpan Promo
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

        // Perbaiki fungsi calculateDiscount untuk menghindari division by zero
            function calculateDiscount() {
                if (originalPrice.value && promoPrice.value) {
                    const original = parseFloat(originalPrice.value);
                    const promo = parseFloat(promoPrice.value);
                    
                    // Cegah division by zero
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
        }

        originalPrice?.addEventListener('input', calculateDiscount);
        promoPrice?.addEventListener('input', calculateDiscount);
        
        // Hitung diskon awal jika ada nilai dari old input
        calculateDiscount();
    });

    // Validasi form
    document.querySelector('form').addEventListener('submit', function(e) {
        const imageInput = document.getElementById('imageInput');
        const originalPrice = parseFloat(document.querySelector('input[name="original_price"]').value);
        const promoPrice = parseFloat(document.querySelector('input[name="promo_price"]').value);
        
        // Validasi harga promo harus lebih kecil dari harga normal
        if (promoPrice >= originalPrice) {
            e.preventDefault();
            alert('Harga promo harus lebih kecil dari harga normal');
            return false;
        }
        
        // Validasi file gambar
        if (!imageInput.files.length) {
            e.preventDefault();
            alert('Silakan pilih gambar promo');
            return false;
        }
    });
</script>
