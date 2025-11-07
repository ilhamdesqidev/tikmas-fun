@extends('layouts.app')

@section('title', 'Tambah Paket Promo')
@section('page-title', 'Tambah Paket Promo')
@section('page-description', 'Buat paket promo baru')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header -->
            <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Tambah Paket Promo</h2>
                        <p class="text-sm text-gray-500 mt-1">Buat paket promo baru untuk pelanggan</p>
                    </div>
                    <a href="{{ route('admin.promo.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </a>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mx-8 mt-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i data-feather="alert-circle" class="w-5 h-5 text-red-600 mt-0.5 mr-3"></i>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-red-800 mb-2">Terdapat kesalahan:</h3>
                            <ul class="text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            <form action="{{ route('admin.promo.store') }}" method="POST" enctype="multipart/form-data" id="promoForm" class="px-8 py-8">
                @csrf
                
                <!-- Images Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Gambar Promo -->
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-900">Gambar Promo *</label>
                        <div class="relative">
                            <div class="aspect-video rounded-xl overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-dashed border-gray-300 hover:border-primary transition-all duration-200">
                                <img id="imagePreview" src="" alt="Preview" class="hidden w-full h-full object-cover">
                                <div id="placeholderImage" class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <i data-feather="image" class="w-12 h-12 text-gray-300 mx-auto mb-2"></i>
                                        <p class="text-sm text-gray-400">Belum ada gambar</p>
                                    </div>
                                </div>
                            </div>
                            <input type="file" name="image" id="imageInput" accept="image/*" required class="hidden">
                            <button type="button" onclick="document.getElementById('imageInput').click()" 
                                    class="mt-3 w-full px-4 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-all duration-200 text-sm font-medium flex items-center justify-center">
                                <i data-feather="upload" class="w-4 h-4 mr-2"></i>
                                Pilih Gambar
                            </button>
                            <p class="text-xs text-gray-500 mt-2 text-center">JPEG, PNG, JPG, GIF • Max 10MB</p>
                        </div>
                        @error('image')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Desain Gelang -->
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-900">Desain Gelang <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <div class="relative">
                            <div class="aspect-video rounded-xl overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-dashed border-gray-300 hover:border-primary transition-all duration-200">
                                <img id="braceletPreview" src="" alt="Preview Desain Gelang" class="hidden w-full h-full object-cover">
                                <div id="braceletPlaceholder" class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <i data-feather="image" class="w-12 h-12 text-gray-300 mx-auto mb-2"></i>
                                        <p class="text-sm text-gray-400">Belum ada desain</p>
                                    </div>
                                </div>
                            </div>
                            <input type="file" name="bracelet_design" id="braceletInput" accept="image/*" class="hidden">
                            <button type="button" onclick="document.getElementById('braceletInput').click()" 
                                    class="mt-3 w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 text-sm font-medium flex items-center justify-center">
                                <i data-feather="upload" class="w-4 h-4 mr-2"></i>
                                Pilih Desain Gelang
                            </button>
                            <p class="text-xs text-gray-500 mt-2 text-center">JPEG, PNG, JPG, GIF, SVG • Max 10MB</p>
                        </div>
                        @error('bracelet_design')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="space-y-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Nama Promo *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   placeholder="Contoh: Promo Imlek 2025">
                            @error('name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Kategori</label>
                            <select name="category" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                <option value="bulanan" {{ old('category') == 'bulanan' ? 'selected' : '' }}>Promo Bulanan</option>
                                <option value="holiday" {{ old('category') == 'holiday' ? 'selected' : '' }}>Promo Holiday</option>
                                <option value="birthday" {{ old('category') == 'birthday' ? 'selected' : '' }}>Promo Birthday</option>
                                <option value="nasional" {{ old('category') == 'nasional' ? 'selected' : '' }}>Promo Hari Nasional</option>
                                <option value="student" {{ old('category') == 'student' ? 'selected' : '' }}>Promo Student Discount</option>
                            </select>
                            @error('category')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Deskripsi *</label>
                        <textarea name="description" rows="3" required 
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                  placeholder="Jelaskan detail promo ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-900 mb-2">Syarat dan Ketentuan *</label>
                        <textarea name="terms_conditions" rows="4" required 
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                  placeholder="- Poin 1&#10;- Poin 2&#10;- Poin 3">{{ old('terms_conditions') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1.5">Gunakan bullet points untuk setiap poin</p>
                        @error('terms_conditions')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl p-6 mb-8 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Harga & Diskon</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Harga Normal *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="number" name="original_price" value="{{ old('original_price') }}" min="1" required 
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                       placeholder="0">
                            </div>
                            @error('original_price')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Harga Promo *</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="number" name="promo_price" value="{{ old('promo_price') }}" min="1" required 
                                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                       placeholder="0">
                            </div>
                            @error('promo_price')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Diskon</label>
                            <div class="relative">
                                <input type="number" id="discount_percent" readonly 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 font-semibold"
                                       placeholder="0">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule & Quota -->
                <div class="space-y-6 mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Tanggal Mulai *</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" required 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            @error('start_date')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Tanggal Berakhir <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            @error('end_date')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Kuota <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input type="number" name="quota" value="{{ old('quota') }}" min="1" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                   placeholder="Kosongkan untuk unlimited">
                            @error('quota')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Status Awal *</label>
                            <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="coming_soon" {{ old('status') == 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1.5">Status akan disesuaikan otomatis berdasarkan tanggal</p>
                            @error('status')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Featured Toggle -->
                <div class="bg-blue-50 rounded-xl p-6 mb-8 border border-blue-100">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        <span class="ms-3 text-sm font-semibold text-gray-900">Tampilkan sebagai Promo Unggulan</span>
                    </label>
                    <p class="text-xs text-gray-600 mt-2 ml-14">Promo unggulan akan ditampilkan di halaman utama</p>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('admin.promo.index') }}" 
                       class="px-6 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-all duration-200 font-semibold shadow-sm hover:shadow-md">
                        Simpan Promo
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview gambar promo
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const placeholderImage = document.getElementById('placeholderImage');
    
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                placeholderImage.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
            placeholderImage.classList.remove('hidden');
            imagePreview.src = '';
        }
    });

    // Preview desain gelang
    const braceletInput = document.getElementById('braceletInput');
    const braceletPreview = document.getElementById('braceletPreview');
    const braceletPlaceholder = document.getElementById('braceletPlaceholder');
    
    braceletInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                braceletPreview.src = e.target.result;
                braceletPreview.classList.remove('hidden');
                braceletPlaceholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            braceletPreview.classList.add('hidden');
            braceletPlaceholder.classList.remove('hidden');
            braceletPreview.src = '';
        }
    });

    // Auto calculate discount
    const originalPrice = document.querySelector('input[name="original_price"]');
    const promoPrice = document.querySelector('input[name="promo_price"]');
    const discountPercent = document.getElementById('discount_percent');

    function calculateDiscount() {
        const original = parseFloat(originalPrice.value) || 0;
        const promo = parseFloat(promoPrice.value) || 0;
        
        if (original > 0 && promo > 0 && promo < original) {
            const discount = Math.round(((original - promo) / original) * 100);
            discountPercent.value = discount;
        } else {
            discountPercent.value = 0;
        }
    }

    originalPrice.addEventListener('input', calculateDiscount);
    promoPrice.addEventListener('input', calculateDiscount);

    // Auto update status based on date
    const startDateInput = document.querySelector('input[name="start_date"]');
    const statusSelect = document.querySelector('select[name="status"]');
    
    startDateInput.addEventListener('change', function() {
        const startDate = new Date(this.value);
        const now = new Date();
        now.setHours(0, 0, 0, 0);
        
        if (startDate > now && statusSelect.value === 'active') {
            if (confirm('Tanggal mulai di masa depan. Ubah status menjadi "Coming Soon"?')) {
                statusSelect.value = 'coming_soon';
            }
        }
    });

    // Form validation
    document.getElementById('promoForm').addEventListener('submit', function(e) {
        const original = parseFloat(originalPrice.value) || 0;
        const promo = parseFloat(promoPrice.value) || 0;
        
        if (promo >= original) {
            e.preventDefault();
            alert('Harga promo harus lebih kecil dari harga normal');
            return false;
        }
    });

    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection