<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher & Promo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .voucher-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .voucher-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold text-gray-800">Voucher & Promo</h1>
            <p class="text-gray-600 mt-2">Dapatkan penawaran terbaik untuk Anda!</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @if($vouchers->count() > 0)
            <!-- Voucher Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($vouchers as $voucher)
                <div class="voucher-card bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Voucher Image -->
                    <div class="relative h-48 bg-gradient-to-br from-blue-500 to-purple-600">
                        <img src="{{ $voucher->image_url }}" 
                             alt="{{ $voucher->name }}" 
                             class="w-full h-full object-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <!-- Fallback gradient jika gambar error -->
                        <div class="absolute inset-0 flex items-center justify-center text-white text-xl font-bold" style="display: none;">
                            {{ $voucher->name }}
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full shadow-lg">
                                Aktif
                            </span>
                        </div>
                    </div>

                    <!-- Voucher Content -->
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $voucher->name }}</h3>
                        
                        <!-- Deskripsi (dipotong) -->
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ Str::limit($voucher->deskripsi, 120) }}
                        </p>

                        <!-- Footer -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <span class="text-xs text-gray-500">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $voucher->created_at->format('d M Y') }}
                            </span>
                            <button onclick="openDetailModal({{ $voucher->id }}, '{{ addslashes($voucher->name) }}', '{{ addslashes($voucher->deskripsi) }}', '{{ $voucher->image_url }}')" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                Lihat Detail
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-16">
                <svg class="w-24 h-24 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Voucher</h3>
                <p class="text-gray-500">Saat ini belum ada voucher yang tersedia. Silakan cek kembali nanti!</p>
            </div>
        @endif
    </main>

    <!-- Modal Detail Voucher -->
    <div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-2xl font-bold text-gray-900" id="modalTitle"></h3>
                <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-4">
                <!-- Gambar Voucher -->
                <div class="mb-4">
                    <img id="modalImage" src="" alt="" class="w-full h-64 object-cover rounded-lg">
                </div>

                <!-- Deskripsi -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-700 mb-2">Deskripsi & Syarat Ketentuan:</h4>
                    <p id="modalDescription" class="text-gray-700 whitespace-pre-wrap"></p>
                </div>

                <!-- Call to Action -->
                <div class="mt-6 bg-blue-50 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-600 mb-3">Tertarik dengan voucher ini?</p>
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                        Gunakan Voucher
                    </button>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end pt-4 border-t mt-4">
                <button type="button" 
                        onclick="closeDetailModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function openDetailModal(id, name, description, imageUrl) {
            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = name;
            document.getElementById('modalDescription').textContent = description;
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('modalImage').alt = name;
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // Close modal saat klik di luar
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });

        // Close modal dengan tombol ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDetailModal();
            }
        });
    </script>
</body>
</html>