<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher & Promo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .voucher-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .voucher-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(207, 217, 22, 0.3);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #CFD916 0%, #9DB91C 100%);
        }
        .gradient-card {
            background: linear-gradient(135deg, #CFD916 0%, #B5C91A 50%, #9DB91C 100%);
        }
        .btn-primary {
            background: #CFD916;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #B5C91A;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(207, 217, 22, 0.4);
        }
        .badge-active {
            background: #CFD916;
            color: #1f2937;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .ribbon {
            position: absolute;
            top: 15px;
            right: -5px;
            background: #CFD916;
            color: #1f2937;
            padding: 5px 15px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .ribbon:before {
            content: '';
            position: absolute;
            right: 0;
            bottom: -10px;
            border-left: 10px solid transparent;
            border-right: 10px solid #9DB91C;
            border-top: 10px solid #9DB91C;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header with Back Button -->
    <header class="bg-white border-b border-black">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center bg-[#CFD916] hover:bg-[#B5C91A] text-gray-800 px-5 py-2.5 rounded-lg transition-all duration-200 font-medium group shadow-sm">
                    <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span>Kembali ke Dashboard</span>
                </a>
                <div class="flex items-center space-x-2 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="font-medium">{{ $vouchers->count() }} Voucher</span>
                </div>
            </div>
            <div class="text-center mt-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Voucher & Promo</h1>
                <p class="text-gray-600">Dapatkan penawaran terbaik untuk Anda</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">
        @if($vouchers->count() > 0)
            <!-- Voucher Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($vouchers as $index => $voucher)
                <div class="voucher-card bg-white rounded-2xl shadow-lg overflow-hidden animate-fade-in" style="animation-delay: {{ $index * 0.1 }}s">
                    <!-- Voucher Image -->
                    <div class="relative h-56 gradient-card">
                        <img src="{{ $voucher->image_url }}" 
                             alt="{{ $voucher->name }}" 
                             class="w-full h-full object-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <!-- Fallback gradient -->
                        <div class="absolute inset-0 flex items-center justify-center text-gray-800 text-2xl font-bold px-4 text-center" style="display: none;">
                            {{ $voucher->name }}
                        </div>
                        
                        <!-- Status Ribbon -->
                        <div class="ribbon text-xs font-bold uppercase tracking-wider">
                            ✓ Aktif
                        </div>

                        <!-- Decorative Corner -->
                        <div class="absolute bottom-0 left-0 w-0 h-0 border-l-[40px] border-l-white border-t-[40px] border-t-transparent"></div>
                    </div>

                    <!-- Voucher Content -->
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-3 line-clamp-2 min-h-[4rem]">{{ $voucher->name }}</h3>
                        
                        <!-- Deskripsi -->
                        <p class="text-gray-600 text-sm mb-6 line-clamp-3 min-h-[4.5rem]">
                            {{ Str::limit($voucher->deskripsi, 120) }}
                        </p>

                        <!-- Footer -->
                        <div class="flex items-center justify-between pt-4 border-t-2 border-gray-100">
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">{{ $voucher->created_at->format('d M Y') }}</span>
                            </div>
                            <button onclick="openDetailModal({{ $voucher->id }}, '{{ addslashes($voucher->name) }}', '{{ addslashes($voucher->deskripsi) }}', '{{ $voucher->image_url }}')" 
                                    class="btn-primary text-gray-800 px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wide shadow-md">
                                Lihat Detail →
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-20 animate-fade-in">
                <div class="bg-white rounded-3xl shadow-xl p-12 text-center max-w-md">
                    <div class="w-32 h-32 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Voucher</h3>
                    <p class="text-gray-500 mb-6">Saat ini belum ada voucher yang tersedia. Silakan cek kembali nanti!</p>
                    <a href="home" class="btn-primary inline-block text-gray-800 px-6 py-3 rounded-xl font-bold">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        @endif
    </main>

    <!-- Modal Detail Voucher -->
    <div id="detailModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-10 mx-auto p-6 border-0 w-full max-w-3xl shadow-2xl rounded-2xl bg-white my-10 animate-fade-in">
            <!-- Modal Header -->
            <div class="flex justify-between items-start pb-4 border-b-2 border-gray-100">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-1" id="modalTitle"></h3>
                    <div class="flex items-center text-sm text-gray-500">
                        <span class="badge-active px-3 py-1 rounded-full text-xs font-bold uppercase mr-2">Aktif</span>
                        <span>Berlaku hingga waktu yang ditentukan</span>
                    </div>
                </div>
                <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-full">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mt-6">
                <!-- Gambar Voucher -->
                <div class="mb-6 rounded-xl overflow-hidden shadow-lg">
                    <img id="modalImage" src="" alt="" class="w-full h-80 object-cover">
                </div>

                <!-- Deskripsi -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-6 mb-6 border-l-4 border-[#CFD916]">
                    <div class="flex items-start mb-3">
                        <svg class="w-6 h-6 text-[#CFD916] mr-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h4 class="font-bold text-gray-800 text-lg">Deskripsi & Syarat Ketentuan</h4>
                    </div>
                    <p id="modalDescription" class="text-gray-700 whitespace-pre-wrap leading-relaxed pl-8"></p>
                </div>

                <!-- Call to Action -->
                <div class="gradient-bg rounded-xl p-6 text-center shadow-lg">
                    <p class="text-gray-800 font-semibold mb-4 text-lg">✨ Tertarik dengan voucher ini?</p>
                    <button class="bg-white hover:bg-gray-100 text-gray-800 px-8 py-4 rounded-xl font-bold text-lg shadow-md transition-all duration-300 hover:shadow-xl hover:scale-105">
                        🎉 Gunakan Voucher Sekarang
                    </button>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end pt-6 border-t-2 border-gray-100 mt-6">
                <button type="button" 
                        onclick="closeDetailModal()" 
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 font-semibold">
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
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
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