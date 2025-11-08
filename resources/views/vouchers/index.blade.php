<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Voucher & Promo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        .voucher-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .voucher-card:hover:not(.disabled) { transform: translateY(-8px) scale(1.02); }
        .voucher-card.disabled { opacity: 0.6; filter: grayscale(50%); cursor: not-allowed; }
        .gradient-bg { background: linear-gradient(135deg, #CFD916 0%, #9DB91C 100%); }
        .btn-primary { background: #CFD916; transition: all 0.3s ease; }
        .btn-primary:hover { background: #B5C91A; transform: translateY(-2px); }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(100px) scale(0.9); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .animate-fade-in { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-slide-up { animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        
        /* Barcode overlay styles */
        .barcode-overlay {
            position: absolute;
            bottom: 60px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.95);
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header - Responsive -->
    <header class="bg-white sticky top-0 z-40 shadow-sm">
        <!-- Mobile Layout (< 640px) -->
        <div class="sm:hidden">
            <div class="gradient-bg px-4 py-3">
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center bg-white/20 backdrop-blur-sm hover:bg-white/30 text-gray-800 px-3 py-1.5 rounded-lg transition-all duration-200 font-medium group">
                        <svg class="w-4 h-4 mr-1 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="text-xs font-semibold">Kembali</span>
                    </a>
                    
                    <div class="flex items-center space-x-1.5 bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-lg">
                        <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span class="text-xs font-bold text-gray-800">{{ $vouchers->count() }} Voucher</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white px-4 py-4 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-800 mb-1">Voucher & Promo</h1>
                <p class="text-xs text-gray-600">Dapatkan penawaran terbaik untuk Anda</p>
            </div>
        </div>
        
        <!-- Desktop Layout (>= 640px) -->
        <div class="hidden sm:block border-b border-gray-200">
            <div class="container mx-auto px-4 py-5">
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center bg-[#CFD916] hover:bg-[#B5C91A] text-gray-800 px-4 py-2 rounded-lg transition-all duration-200 font-medium group shadow-sm">
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
                        <span class="text-sm font-medium">{{ $vouchers->count() }} Voucher</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-3 sm:px-4 py-6 sm:py-12">
        @if($vouchers->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                @foreach($vouchers as $index => $voucher)
                <div class="voucher-card {{ $voucher->is_available ? '' : 'disabled' }} bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in">
                    <div class="relative h-48">
                        <img src="{{ $voucher->image_url }}" alt="{{ $voucher->name }}" class="w-full h-full object-cover">
                        
                        @if(!$voucher->is_available)
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                            <div class="bg-white/90 px-4 py-2 rounded-lg">
                                <p class="text-sm font-bold text-gray-800">
                                    @if($voucher->is_sold_out) 🚫 Kuota Habis
                                    @else ⏰ Sudah Kadaluarsa
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $voucher->name }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ Str::limit($voucher->deskripsi, 120) }}</p>

                        @if(!$voucher->is_unlimited)
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-semibold">Kuota Tersedia</span>
                                <span class="font-bold">{{ $voucher->remaining_quota }}/{{ $voucher->quota }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-500 h-full rounded-full" style="width: {{ ($voucher->remaining_quota / $voucher->quota) * 100 }}%"></div>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center justify-between pt-4 border-t">
                            <div class="text-xs text-gray-500">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($voucher->expiry_date)->format('d M Y') }}
                            </div>
                            
                            @if($voucher->is_available)
                            <button onclick='showClaimForm(@json($voucher))' 
                                    class="btn-primary text-gray-800 px-5 py-2 rounded-xl text-sm font-bold">
                                🎉 Claim Sekarang
                            </button>
                            @else
                            <button disabled class="bg-gray-300 text-gray-600 px-5 py-2 rounded-xl text-sm font-bold cursor-not-allowed">
                                @if($voucher->is_sold_out) 🚫 Habis @else ⏰ Kadaluarsa @endif
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20">
                <div class="bg-white rounded-3xl shadow-xl p-12 text-center max-w-md">
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Voucher</h3>
                    <p class="text-gray-500 mb-6">Silakan cek kembali nanti!</p>
                </div>
            </div>
        @endif
    </main>

    <!-- Claim Form Modal -->
    <div id="claimOverlay" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div id="claimCard" class="bg-white rounded-3xl shadow-2xl max-w-md w-full animate-slide-up overflow-hidden">
            <div class="gradient-bg p-6 text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Claim Voucher</h2>
                <p class="text-sm text-gray-700" id="claimVoucherName"></p>
            </div>

            <form id="claimForm" class="p-6">
                <input type="hidden" id="voucherId">
                
                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        📝 Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="userName" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916]" 
                           placeholder="Masukkan nama lengkap">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        📱 Nomor Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="userPhone" required pattern="[0-9]{10,13}"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916]" 
                           placeholder="08xxxxxxxxxx">
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="hideClaimForm()"
                            class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-bold">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                            class="flex-1 btn-primary text-gray-800 px-6 py-3 rounded-xl font-bold shadow-lg">
                        Claim & Download 🎁
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden Template untuk Download dengan Barcode Overlay -->
    <div id="voucherTemplate" style="position: absolute; left: -9999px; width: 800px; height: 600px;">
        <div style="position: relative; width: 100%; height: 100%; font-family: Arial, sans-serif;">
            <!-- Background Image -->
            <img id="templateBgImage" src="" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
            
            <!-- Overlay dengan Info -->
            <div style="position: absolute; top: 0; left: 0; right: 0; background: linear-gradient(180deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%); padding: 30px;">
                <h1 style="color: white; font-size: 32px; font-weight: bold; margin: 0 0 10px 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);" id="templateTitle"></h1>
                <p style="color: white; font-size: 16px; margin: 0; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">🎉 Voucher Berhasil Di-claim!</p>
            </div>
            
            <!-- Info User di Kiri Bawah -->
            <div style="position: absolute; bottom: 180px; left: 30px; background: rgba(255, 255, 255, 0.95); padding: 20px; border-radius: 15px; max-width: 300px; backdrop-filter: blur(10px);">
                <p style="margin: 0 0 8px 0; color: #1f2937; font-size: 14px;"><strong>Nama:</strong> <span id="templateName"></span></p>
                <p style="margin: 0 0 8px 0; color: #1f2937; font-size: 14px;"><strong>No. HP:</strong> <span id="templatePhone"></span></p>
                <p style="margin: 0; color: #1f2937; font-size: 14px;"><strong>Berlaku hingga:</strong> <span id="templateExpiry"></span></p>
            </div>

            <!-- Barcode Overlay di Tengah Bawah -->
            <div style="position: absolute; bottom: 60px; left: 50%; transform: translateX(-50%); background: rgba(255, 255, 255, 0.95); padding: 15px 25px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3); backdrop-filter: blur(10px);">
                <p style="text-align: center; color: #1f2937; font-weight: bold; margin: 0 0 10px 0; font-size: 14px;">KODE VOUCHER</p>
                <svg id="templateBarcode"></svg>
            </div>

            <!-- Footer -->
            <div style="position: absolute; bottom: 15px; left: 0; right: 0; text-align: center;">
                <p style="margin: 0; color: white; font-size: 11px; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
                    Tunjukkan barcode ini saat melakukan pembayaran
                </p>
            </div>
        </div>
    </div>

    <script>
        let currentVoucher = null;

        function showClaimForm(voucher) {
            currentVoucher = voucher;
            document.getElementById('voucherId').value = voucher.id;
            document.getElementById('claimVoucherName').textContent = voucher.name;
            document.getElementById('claimOverlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideClaimForm() {
            document.getElementById('claimOverlay').classList.add('hidden');
            document.getElementById('claimForm').reset();
            document.body.style.overflow = 'auto';
        }

        // ...existing code...

        document.getElementById('claimForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '⏳ Memproses...';

            const userName = document.getElementById('userName').value;
            const userPhone = document.getElementById('userPhone').value;
            const voucherId = document.getElementById('voucherId').value;

            try {
                const response = await fetch('/vouchers/claim', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        voucher_id: voucherId,
                        user_name: userName,
                        user_phone: userPhone
                    })
                });

                // Clone response so we can attempt json/text inspection reliably
                const responseClone = response.clone();
                let result = null;
                try {
                    result = await response.json();
                } catch (parseErr) {
                    // If JSON parse fails, try to read raw text for diagnostics
                    try {
                        const txt = await responseClone.text();
                        result = { rawText: txt };
                    } catch (_) {
                        result = null;
                    }
                }

                // Handle non-ok responses with friendly messages for known cases
                if (!response.ok) {
                    let message = 'Terjadi kesalahan saat mengklaim voucher';
                    if (result && result.message) {
                        message = result.message;
                    } else if (response.status === 409) {
                        message = 'Nomor telepon ini sudah pernah digunakan untuk klaim voucher ini.';
                    } else if (result && typeof result.rawText === 'string') {
                        const raw = result.rawText;
                        if (raw.includes('Duplicate entry') || raw.includes('unique_phone_per_voucher')) {
                            message = 'Nomor telepon ini sudah pernah digunakan untuk klaim voucher ini.';
                        } else if (response.status >= 500) {
                            message = 'Terjadi kesalahan server. Silakan coba lagi nanti.';
                        }
                    } else if (response.status >= 500) {
                        message = 'Terjadi kesalahan server. Silakan coba lagi nanti.';
                    }

                    // Tutup modal dulu sebelum tampilkan error
                    hideClaimForm();
                    throw new Error(message);
                }

                // Success path
                const uniqueCode = result.data.unique_code;
                const expiryDate = new Date(currentVoucher.expiry_date).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });

                // Set template values
                document.getElementById('templateTitle').textContent = currentVoucher.name;
                document.getElementById('templateName').textContent = userName;
                document.getElementById('templatePhone').textContent = userPhone;
                document.getElementById('templateExpiry').textContent = expiryDate;
                
                // Set background image (gunakan download_image jika ada, fallback ke image biasa)
                const bgImage = document.getElementById('templateBgImage');
                bgImage.src = currentVoucher.download_image_url || currentVoucher.image_url;

                // Generate barcode
                JsBarcode("#templateBarcode", uniqueCode, {
                    format: "CODE128",
                    width: 2,
                    height: 60,
                    displayValue: true,
                    fontSize: 14,
                    margin: 5,
                    background: "transparent"
                });

                // Wait for image to load before capturing
                bgImage.onload = async function() {
                    const template = document.getElementById('voucherTemplate');
                    const canvas = await html2canvas(template, {
                        scale: 2,
                        backgroundColor: '#ffffff',
                        logging: false,
                        useCORS: true,
                        allowTaint: true
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

                        hideClaimForm();
                        
                        // Success notification
                        const notification = document.createElement('div');
                        notification.className = 'fixed top-4 left-1/2 -translate-x-1/2 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl z-[100]';
                        notification.innerHTML = `
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <p class="font-bold">Berhasil!</p>
                                    <p class="text-sm">Voucher telah di-download</p>
                                </div>
                            </div>
                        `;
                        document.body.appendChild(notification);
                        setTimeout(() => notification.remove(), 5000);
                        
                        // Reload page untuk update kuota
                        setTimeout(() => location.reload(), 2000);
                    });
                };

                // If image already cached
                if (bgImage.complete) {
                    bgImage.onload();
                }

            } catch (error) {
                console.error('Error:', error);
                
                // Pastikan modal sudah tertutup
                hideClaimForm();
                
                // Tampilkan error notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 left-1/2 -translate-x-1/2 bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl z-[100] max-w-md';
                notification.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <div>
                            <p class="font-bold">Gagal!</p>
                            <p class="text-sm">${error.message}</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 5000);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Claim & Download 🎁';
            }
        });
    </script>
</body>
</html>