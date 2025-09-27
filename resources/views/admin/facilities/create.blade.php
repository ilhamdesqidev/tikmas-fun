@extends('layouts.app')

@section('title', 'Tambah Fasilitas')
@section('page-title', 'Tambah Fasilitas')
@section('page-description', 'Tambah fasilitas baru ke sistem')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.facilities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kolom Kiri -->
            <div class="space-y-6">
                <!-- Nama Wahana -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Wahana *</label>
                    <input type="text" name="name" id="name" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama wahana">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Durasi -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700">Durasi *</label>
                    <input type="text" name="duration" id="duration" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                        value="{{ old('duration', '15-30 menit') }}"
                        placeholder="Contoh: 15-30 menit">
                    @error('duration')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rentang Usia -->
                <div>
                    <label for="age_range" class="block text-sm font-medium text-gray-700">Rentang Usia *</label>
                    <select name="age_range" id="age_range" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        <option value="">Pilih rentang usia</option>
                        <option value="Untuk semua usia" {{ old('age_range') == 'Untuk semua usia' ? 'selected' : '' }}>Untuk semua usia</option>
                        <option value="Anak-anak (3-12 tahun)" {{ old('age_range') == 'Anak-anak (3-12 tahun)' ? 'selected' : '' }}>Anak-anak (3-12 tahun)</option>
                        <option value="Remaja (13-17 tahun)" {{ old('age_range') == 'Remaja (13-17 tahun)' ? 'selected' : '' }}>Remaja (13-17 tahun)</option>
                        <option value="Dewasa (18+ tahun)" {{ old('age_range') == 'Dewasa (18+ tahun)' ? 'selected' : '' }}>Dewasa (18+ tahun)</option>
                        <option value="Anak-anak dan keluarga" {{ old('age_range') == 'Anak-anak dan keluarga' ? 'selected' : '' }}>Anak-anak dan keluarga</option>
                    </select>
                    @error('age_range')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Kategori *</label>
                    <select name="category" id="category" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        <option value="wahana" {{ old('category') == 'wahana' ? 'selected' : '' }}>Wahana</option>
                        <option value="fasilitas" {{ old('category') == 'fasilitas' ? 'selected' : '' }}>Fasilitas</option>
                        <option value="restoran" {{ old('category') == 'restoran' ? 'selected' : '' }}>Restoran</option>
                        <option value="toko" {{ old('category') == 'toko' ? 'selected' : '' }}>Toko</option>
                    </select>
                    @error('category')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Wahana *</label>
                    <textarea name="description" id="description" rows="4" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                        placeholder="Masukkan deskripsi lengkap wahana">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar Utama -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Gambar Utama *</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-opacity-80 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                    <span>Upload gambar utama</span>
                                    <input id="image" name="image" type="file" class="sr-only" required accept="image/*">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gallery Images -->
                <div>
                    <label for="gallery_images" class="block text-sm font-medium text-gray-700">Gambar Gallery (Opsional)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="gallery_images" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-opacity-80 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                    <span>Upload multiple gambar</span>
                                    <input id="gallery_images" name="gallery_images[]" type="file" class="sr-only" multiple accept="image/*">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB per gambar</p>
                        </div>
                    </div>
                    @error('gallery_images')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div id="gallery-preview" class="mt-2 grid grid-cols-3 gap-2 hidden"></div>
                </div>
            </div>
        </div>

        <!-- Preview Gambar Utama -->
        <div id="image-preview" class="mt-4 hidden">
            <label class="block text-sm font-medium text-gray-700">Preview Gambar Utama</label>
            <img id="preview" class="mt-2 w-full h-64 object-cover rounded-lg border border-gray-200">
        </div>

        <div class="mt-6 flex items-center justify-end space-x-4">
            <a href="{{ route('admin.facilities.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition-all duration-200">
                Batal
            </a>
            <button type="submit" class="bg-primary hover:bg-opacity-90 text-black font-medium py-2 px-4 rounded-lg transition-all duration-200">
                Simpan Fasilitas
            </button>
        </div>
    </form>
</div>
@endsection

@section('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preview image utama
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const preview = document.getElementById('preview');
        
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Preview gallery images
        const galleryInput = document.getElementById('gallery_images');
        const galleryPreview = document.getElementById('gallery-preview');
        
        galleryInput.addEventListener('change', function() {
            galleryPreview.innerHTML = '';
            const files = this.files;
            
            if (files.length > 0) {
                galleryPreview.classList.remove('hidden');
                
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                            <span class="absolute top-1 right-1 bg-black bg-opacity-50 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center">${i + 1}</span>
                        `;
                        galleryPreview.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                }
            } else {
                galleryPreview.classList.add('hidden');
            }
        });
    });
</script>
@endsection