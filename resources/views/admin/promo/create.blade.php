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
        
        <form action="{{ route('admin.promo.store') }}" method="POST" enctype="multipart/form-data" id="promoForm" class="space-y-6">
            @csrf
            
            <!-- Field Upload Gambar Promo -->
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
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Field Upload Desain Gelang -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Desain Gelang (Opsional)</label>
                <div class="mt-1 flex items-center">
                    <div class="relative rounded-lg overflow-hidden w-40 h-40 bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300">
                        <img id="braceletPreview" src="" alt="Preview Desain Gelang" class="hidden w-full h-full object-cover">
                        <span id="braceletPlaceholder" class="text-gray-400 text-sm">No image</span>
                    </div>
                    <div class="ml-4">
                        <input type="file" name="bracelet_design" id="braceletInput" accept="image/*" 
                               class="hidden" onchange="previewBracelet(this)">
                        <label for="braceletInput" class="cursor-pointer px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 text-sm">
                            Pilih Desain Gelang
                        </label>
                        <p class="text-xs text-gray-500 mt-2">Format: JPEG, PNG, JPG, GIF, SVG<br>Maksimal: 10MB</p>
                    </div>
                </div>
                @error('bracelet_design')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Promo *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="bulanan" {{ old('category') == 'bulanan' ? 'selected' : '' }}>Promo Bulanan</option>
                        <option value="holiday" {{ old('category') == 'holiday' ? 'selected' : '' }}>Promo Holiday</option>
                        <option value="birthday" {{ old('category') == 'birthday' ? 'selected' : '' }}>Promo Birthday</option>
                        <option value="nasional" {{ old('category') == 'nasional' ? 'selected' : '' }}>Promo Hari Nasional</option>
                        <option value="student" {{ old('category') == 'student' ? 'selected' : '' }}>Promo Student Discount</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
                <textarea name="description" rows="3" required 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Syarat dan Ketentuan *</label>
                <textarea name="terms_conditions" rows="4" required 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('terms_conditions') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Gunakan bullet points atau penomoran untuk setiap poin</p>
                @error('terms_conditions')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Normal *</label>
                    <input type="number" name="original_price" value="{{ old('original_price') }}" min="1" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('original_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Promo *</label>
                    <input type="number" name="promo_price" value="{{ old('promo_price') }}" min="1" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('promo_price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Diskon (%)</label>
                    <input type="number" id="discount_percent" readonly 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('start_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('end_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kuota (opsional)</label>
                    <input type="number" name="quota" value="{{ old('quota') }}" min="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    @error('quota')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Awal *</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="coming_soon" {{ old('status') == 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Status akan disesuaikan otomatis berdasarkan tanggal</p>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} 
                       class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
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
document.addEventListener('DOMContentLoaded', function() {
    // Simple image preview functions
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
        } else {
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            preview.src = '';
        }
    }

    function previewBracelet(input) {
        const preview = document.getElementById('braceletPreview');
        const placeholder = document.getElementById('braceletPlaceholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
            preview.src = '';
        }
    }

    // Auto calculate discount
    function calculateDiscount() {
        const originalPrice = parseFloat(document.querySelector('input[name="original_price"]').value) || 0;
        const promoPrice = parseFloat(document.querySelector('input[name="promo_price"]').value) || 0;
        const discountPercent = document.getElementById('discount_percent');
        
        if (originalPrice > 0 && promoPrice > 0 && promoPrice < originalPrice) {
            const discount = Math.round(((originalPrice - promoPrice) / originalPrice) * 100);
            discountPercent.value = discount;
        } else {
            discountPercent.value = 0;
        }
    }

    // Auto update status based on start date
    function updateStatusBasedOnDate() {
        const startDateInput = document.querySelector('input[name="start_date"]');
        const statusSelect = document.querySelector('select[name="status"]');
        
        if (startDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const now = new Date();
            
            if (startDate > now && statusSelect.value === 'active') {
                // Jika start date di masa depan dan status active, sarankan coming_soon
                if (confirm('Tanggal mulai di masa depan. Ubah status menjadi "Coming Soon"?')) {
                    statusSelect.value = 'coming_soon';
                }
            }
        }
    }

    // Attach event listeners
    document.querySelector('input[name="original_price"]').addEventListener('input', calculateDiscount);
    document.querySelector('input[name="promo_price"]').addEventListener('input', calculateDiscount);
    document.querySelector('input[name="start_date"]').addEventListener('change', updateStatusBasedOnDate);
    
    // Initial calculation
    calculateDiscount();
});
</script>
@endsection