<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Wahana - Mestakara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#CFD916',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="w-full py-5 px-7 flex items-center justify-between bg-white border-b border-gray-400 fixed top-0 left-0 right-0 z-50" style="border-bottom: 1px solid #597336;">
        <a href="#" class="text-3xl font-bold text-black italic">
            Mesta<span class="text-primary">Kara</span>.
        </a>
        
        <div class="flex space-x-4">
            <a href="{{ route('wahana.index') }}" class="bg-gray-200 text-black px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors duration-300 font-medium">
                ← Kembali ke Wahana
            </a>
            <a href="{{ route('home') }}" class="bg-primary text-black px-4 py-2 rounded-lg hover:bg-yellow-500 transition-colors duration-300 font-medium">
                Back to Dashboard
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 px-7 pb-12">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li><a href="{{ route('home') }}" class="hover:text-primary">Dashboard</a></li>
                    <li>→</li>
                    <li><a href="{{ route('wahana.index') }}" class="hover:text-primary">Wahana</a></li>
                    <li>→</li>
                    <li class="text-gray-900 font-medium">{{ $facility->name }}</li>
                </ol>
            </nav>

            <!-- Gambar Utama -->
            <div class="mb-8">
                <img src="{{ asset('storage/' . $facility->image) }}" 
                     alt="{{ $facility->name }}" 
                     class="w-full h-96 object-cover rounded-lg shadow-md">
            </div>

             <!-- Gallery -->
            @if($facility->gallery_images && count($facility->gallery_images) > 0)
            <div class="mt-8 bg-white rounded-lg shadow-sm p-8">
                <h3 class="text-2xl font-semibold text-gray-800 mb-6">Gallery {{ $facility->name }}</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($facility->gallery_images as $index => $galleryImage)
                    <div class="aspect-w-16 aspect-h-9">
                        <img src="{{ asset('storage/' . $galleryImage) }}" 
                             alt="{{ $facility->name }} - View {{ $index + 1 }}" 
                             class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity"
                             onclick="openModal('{{ asset('storage/' . $galleryImage) }}')">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Konten Detail -->
            <div class="bg-white rounded-lg shadow-sm p-8">
                <div class="mb-6">
                    <span class="inline-block bg-primary bg-opacity-20 text-primary text-sm font-medium px-3 py-1 rounded-full mb-4">
                        {{ ucfirst($facility->category) }} Unggulan
                    </span>
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $facility->name }}</h1>
                </div>

                <!-- Deskripsi Lengkap -->
                <div class="prose max-w-none">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Deskripsi {{ ucfirst($facility->category) }}</h2>
                    <div class="text-gray-700 leading-relaxed text-lg">
                        {!! nl2br(e($facility->description)) !!}
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-primary mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">Durasi: {{ $facility->duration }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-primary mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">{{ $facility->age_range }}</span>
                        </div>
                    </div>
                </div>
            </div>

           
        </div>
    </main>

    <!-- Modal untuk gambar gallery -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="max-w-4xl max-h-full">
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-white text-2xl bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center">
                ×
            </button>
        </div>
    </div>

    <script>
        function openModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Tutup modal ketika klik di luar gambar
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target.id === 'imageModal') {
                closeModal();
            }
        });
    </script>
</body>
</html>