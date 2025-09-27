@extends('layouts.app')

@section('title', 'Edit Fasilitas')
@section('page-title', 'Edit Fasilitas')
@section('page-description', 'Edit data fasilitas')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <form action="{{ route('admin.facilities.update', $facility->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kolom Kiri -->
            <div class="space-y-6">
                <!-- Nama Wahana -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Wahana *</label>
                    <input type="text" name="name" id="name" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                        value="{{ old('name', $facility->name) }}"
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
                        value="{{ old('duration', $facility->duration) }}"
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
                        <option value="Untuk semua usia" {{ old('age_range', $facility->age_range) == 'Untuk semua usia' ? 'selected' : '' }}>Untuk semua usia</option>
                        <option value="Anak-anak (3-12 tahun)" {{ old('age_range', $facility->age_range) == 'Anak-anak (3-12 tahun)' ? 'selected' : '' }}>Anak-anak (3-12 tahun)</option>
                        <option value="Remaja (13-17 tahun)" {{ old('age_range', $facility->age_range) == 'Remaja (13-17 tahun)' ? 'selected' : '' }}>Remaja (13-17 tahun)</option>
                        <option value="Dewasa (18+ tahun)" {{ old('age_range', $facility->age_range) == 'Dewasa (18+ tahun)' ? 'selected' : '' }}>Dewasa (18+ tahun)</option>
                        <option value="Anak-anak dan keluarga" {{ old('age_range', $facility->age_range) == 'Anak-anak dan keluarga' ? 'selected' : '' }}>Anak-anak dan keluarga</option>
                    </select>
                    @error('age_range')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori (Hidden - Default Wisata) -->
                <input type="hidden" name="category" value="wisata">
                
                <!-- Display Kategori (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <div class="mt-1 p-3 bg-gray-100 rounded-md border border-gray-300">
                        <span class="text-gray-700 font-medium">Wisata</span>
                        <p class="text-xs text-gray-500 mt-1">Kategori default untuk semua fasilitas</p>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-6">
                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Wahana *</label>
                    <textarea name="description" id="description" rows="4" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                        placeholder="Masukkan deskripsi lengkap wahana">{{ old('description', $facility->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gambar Utama -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Gambar Utama</label>
                    <div class="mt-1">
                        <img src="{{ asset('storage/' . $facility->image) }}" alt="Current Image" class="w-32 h-32 object-cover rounded-lg mb-2">
                    </div>
                    <input type="file" name="image" id="image" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-primary file:text-black hover:file:bg-opacity-90">
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gallery Images -->
                <div>
                    <label for="gallery_images" class="block text-sm font-medium text-gray-700">Gambar Gallery (Opsional)</label>
                    @if($facility->gallery_images)
                    <div class="mt-2 grid grid-cols-3 gap-2 mb-2">
                        @foreach($facility->gallery_images as $galleryImage)
                        <img src="{{ asset('storage/' . $galleryImage) }}" alt="Gallery Image" class="w-full h-24 object-cover rounded-lg">
                        @endforeach
                    </div>
                    @endif
                    <input type="file" name="gallery_images[]" id="gallery_images" multiple accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300">
                    @error('gallery_images')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-4">
            <a href="{{ route('admin.facilities.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition-all duration-200">
                Batal
            </a>
            <button type="submit" class="bg-primary hover:bg-opacity-90 text-black font-medium py-2 px-4 rounded-lg transition-all duration-200">
                Update Fasilitas
            </button>
        </div>
    </form>
</div>
@endsection