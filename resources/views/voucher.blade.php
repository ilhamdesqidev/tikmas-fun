<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher & Promo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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
        <div class="container mx-auto px-4 py-5">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center bg-[#CFD916] hover:bg-[#B5C91A] text-gray-800 px-4 py-2 rounded-lg transition-all duration-200 font-medium group shadow-sm">
                    <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="text-sm">Kembali ke Dashboard</span>
                </a>
                
                <div class="text-center flex-1 px-4">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Voucher & Promo</h1>
                    <p class="text-xs text-gray-600">Dapatkan penawaran terbaik untuk Anda</p>
                </div>
                
                <div class="flex items-center space-x-2 text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="text-sm font-medium" id="voucherCount">0 Voucher</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-12">
        <!-- Voucher Grid -->
        <div id="voucherGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Vouchers will be loaded here -->
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden flex flex-col items-center justify-center py-20 animate-fade-in">
            <div class="bg-white rounded-3xl shadow-xl p-12 text-center max-w-md">
                <div class="w-32 h-32 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Voucher</h3>
                <p class="text-gray-500 mb-6">Saat ini belum ada voucher yang tersedia. Silakan cek kembali nanti!</p>
                <a href="/" class="btn-primary inline-block text-gray-800 px-6 py-3 rounded-xl font-bold">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
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
                        <span id="modalExpiry">Berlaku hingga: -</span>
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
                    <button onclick="openClaimModal()" class="bg-white hover:bg-gray-100 text-gray-800 px-8 py-4 rounded-xl font-bold text-lg shadow-md transition-all duration-300 hover:shadow-xl hover:scale-105">
                        🎉 Claim Voucher Sekarang
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

    <!-- Modal Claim Voucher -->
    <div id="claimModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto h-full w-full z-[60]">
        <div class="relative top-10 mx-auto p-6 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white my-10 animate-fade-in">
            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-4 border-b-2 border-gray-100">
                <h3 class="text-2xl font-bold text-gray-900">Claim Voucher</h3>
                <button type="button" onclick="closeClaimModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-2 hover:bg-gray-100 rounded-full">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="claimForm" class="mt-6">
                <div class="mb-4">
                    <label for="userName" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="userName" 
                           required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916] focus:border-transparent" 
                           placeholder="Masukkan nama lengkap Anda">
                </div>

                <div class="mb-6">
                    <label for="userPhone" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nomor Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           id="userPhone" 
                           required
                           pattern="[0-9]{10,13}"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916] focus:border-transparent" 
                           placeholder="08xxxxxxxxxx">
                    <p class="mt-1 text-xs text-gray-500">Format: 08xxxxxxxxxx (10-13 digit)</p>
                </div>

                <button type="submit" 
                        class="w-full btn-primary text-gray-800 px-6 py-4 rounded-xl font-bold text-lg shadow-md">
                    Claim & Download Voucher
                </button>
            </form>
        </div>
    </div>

    <!-- Hidden Voucher Template for Download -->
    <div id="voucherTemplate" style="position: absolute; left: -9999px; width: 800px; background: white;">
        <div style="padding: 40px; font-family: Arial, sans-serif;">
            <div style="background: linear-gradient(135deg, #CFD916 0%, #9DB91C 100%); padding: 30px; border-radius: 20px; margin-bottom: 20px;">
                <h1 style="color: #1f2937; font-size: 32px; font-weight: bold; margin: 0 0 10px 0;" id="templateTitle"></h1>
                <p style="color: #374151; font-size: 16px; margin: 0;">🎉 Voucher Berhasil Di-claim!</p>
            </div>
            
            <div style="background: #f9fafb; padding: 25px; border-radius: 15px; margin-bottom: 20px;">
                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;"><strong>Nama:</strong> <span id="templateName"></span></p>
                <p style="margin: 0 0 8px 0; color: #6b7280; font-size: 14px;"><strong>No. Telepon:</strong> <span id="templatePhone"></span></p>
                <p style="margin: 0; color: #6b7280; font-size: 14px;"><strong>Berlaku hingga:</strong> <span id="templateExpiry"></span></p>
            </div>

            <div style="text-align: center; background: white; padding: 30px; border: 3px dashed #CFD916; border-radius: 15px;">
                <p style="color: #1f2937; font-weight: bold; margin: 0 0 15px 0; font-size: 18px;">Kode Voucher:</p>
                <svg id="templateBarcode"></svg>
                <p style="margin: 15px 0 0 0; color: #6b7280; font-size: 12px;">Tunjukkan barcode ini saat melakukan pembayaran</p>
            </div>

            <div style="margin-top: 25px; padding: 20px; background: #fef3c7; border-radius: 10px; border-left: 4px solid #CFD916;">
                <p style="margin: 0; color: #92400e; font-size: 13px; line-height: 1.6;" id="templateDesc"></p>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Sample voucher data - replace with actual data from backend
        const vouchers = [
            {
                id: 1,
                name: "Diskon 50% Hari Kemerdekaan",
                deskripsi: "Dapatkan diskon 50% untuk semua layanan. Berlaku untuk pembelian minimal Rp 100.000. Tidak dapat digabung dengan promo lain.",
                image_url: "https://via.placeholder.com/400x300/CFD916/1f2937?text=Diskon+50%25",
                expiry_date: "2025-12-31",
                created_at: "2024-01-15"
            },
            {
                id: 2,
                name: "Gratis Ongkir Se-Indonesia",
                deskripsi: "Nikmati gratis ongkir untuk seluruh Indonesia tanpa minimum pembelian. Berlaku untuk semua metode pengiriman reguler.",
                image_url: "https://via.placeholder.com/400x300/9DB91C/1f2937?text=Gratis+Ongkir",
                expiry_date: "2025-11-30",
                created_at: "2024-02-01"
            },
            {
                id: 3,
                name: "Cashback 100rb",
                deskripsi: "Dapatkan cashback Rp 100.000 untuk pembelian minimal Rp 500.000. Cashback akan dikembalikan dalam 3 hari kerja.",
                image_url: "https://via.placeholder.com/400x300/B5C91A/1f2937?text=Cashback+100K",
                expiry_date: "2025-10-31",
                created_at: "2024-01-20"
            }
        ];

        let currentVoucher = null;

        // Load vouchers on page load
        window.addEventListener('DOMContentLoaded', function() {
            loadVouchers();
        });

        function loadVouchers() {
            const grid = document.getElementById('voucherGrid');
            const emptyState = document.getElementById('emptyState');
            const voucherCount = document.getElementById('voucherCount');

            if (vouchers.length === 0) {
                grid.classList.add('hidden');
                emptyState.classList.remove('hidden');
                voucherCount.textContent = '0 Voucher';
                return;
            }

            voucherCount.textContent = `${vouchers.length} Voucher`;
            grid.innerHTML = '';

            vouchers.forEach((voucher, index) => {
                const card = createVoucherCard(voucher, index);
                grid.innerHTML += card;
            });
        }

        function createVoucherCard(voucher, index) {
            const formattedDate = new Date(voucher.created_at).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });

            return `
                <div class="voucher-card bg-white rounded-2xl shadow-lg overflow-hidden animate-fade-in" style="animation-delay: ${index * 0.1}s">
                    <div class="relative h-56 gradient-card">
                        <img src="${voucher.image_url}" 
                             alt="${voucher.name}" 
                             class="w-full h-full object-cover">
                        <div class="ribbon text-xs font-bold uppercase tracking-wider">✓ Aktif</div>
                        <div class="absolute bottom-0 left-0 w-0 h-0 border-l-[40px] border-l-white border-t-[40px] border-t-transparent"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-3 line-clamp-2 min-h-[4rem]">${voucher.name}</h3>
                        <p class="text-gray-600 text-sm mb-6 line-clamp-3 min-h-[4.5rem]">
                            ${voucher.deskripsi.substring(0, 120)}...
                        </p>
                        <div class="flex items-center justify-between pt-4 border-t-2 border-gray-100">
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">${formattedDate}</span>
                            </div>
                            <button onclick="openDetailModal(${voucher.id})" 
                                    class="btn-primary text-gray-800 px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wide shadow-md">
                                Lihat Detail →
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function openDetailModal(id) {
            const voucher = vouchers.find(v => v.id === id);
            if (!voucher) return;

            currentVoucher = voucher;
            
            const expiryDate = new Date(voucher.expiry_date).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            document.getElementById('modalTitle').textContent = voucher.name;
            document.getElementById('modalExpiry').textContent = `Berlaku hingga: ${expiryDate}`;
            document.getElementById('modalDescription').textContent = voucher.deskripsi;
            document.getElementById('modalImage').src = voucher.image_url;
            document.getElementById('modalImage').alt = voucher.name;
            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openClaimModal() {
            document.getElementById('claimModal').classList.remove('hidden');
        }

        function closeClaimModal() {
            document.getElementById('claimModal').classList.add('hidden');
            document.getElementById('claimForm').reset();
        }

        function generateUniqueCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let code = '';
            for (let i = 0; i < 12; i++) {
                code += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return code;
        }

        document.getElementById('claimForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const userName = document.getElementById('userName').value;
            const userPhone = document.getElementById('userPhone').value;
            const uniqueCode = generateUniqueCode();

            const expiryDate = new Date(currentVoucher.expiry_date).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            // Fill template
            document.getElementById('templateTitle').textContent = currentVoucher.name;
            document.getElementById('templateName').textContent = userName;
            document.getElementById('templatePhone').textContent = userPhone;
            document.getElementById('templateExpiry').textContent = expiryDate;
            document.getElementById('templateDesc').textContent = currentVoucher.deskripsi;

            // Generate barcode
            JsBarcode("#templateBarcode", uniqueCode, {
                format: "CODE128",
                width: 2,
                height: 80,
                displayValue: true,
                fontSize: 16,
                margin: 10
            });

            // Generate and download image
            const template = document.getElementById('voucherTemplate');
            
            try {
                const canvas = await html2canvas(template, {
                    scale: 2,
                    backgroundColor: '#ffffff',
                    logging: false
                });

                canvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `Voucher-${uniqueCode}.png`;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);

                    // Close modals
                    closeClaimModal();
                    closeDetailModal();

                    // Show success message
                    alert('✅ Voucher berhasil di-download! Silakan cek folder Download Anda.');
                });
            } catch (error) {
                console.error('Error generating voucher:', error);
                alert('Terjadi kesalahan saat membuat voucher. Silakan coba lagi.');
            }
        });

        // Close modal saat klik di luar
        document.getElementById('detailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDetailModal();
            }
        });

        document.getElementById('claimModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeClaimModal();
            }
        });

        // Close modal dengan tombol ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDetailModal();
                closeClaimModal();
            }
        });
    </script>
</body>
</html>