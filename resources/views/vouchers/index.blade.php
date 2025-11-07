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
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white sticky top-0 z-40 shadow-sm">
        <div class="sm:hidden">
            <div class="gradient-bg px-4 py-3">
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center bg-white/20 backdrop-blur-sm hover:bg-white/30 text-gray-800 px-3 py-1.5 rounded-lg transition-all duration-200 font-medium group">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="text-xs font-semibold">Kembali</span>
                    </a>
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

    <!-- Hidden Template - REVISED VERSION (BARCODE OVERLAY) -->
    <div id="voucherTemplate" style="position: absolute; left: -9999px; width: 800px; height: 1000px;">
        <div style="position: relative; width: 100%; height: 100%; background: #f3f4f6; font-family: Arial, sans-serif; overflow: hidden;">
            
            <!-- Top Section: Image with Overlay (60%) -->
            <div style="position: relative; width: 100%; height: 60%; overflow: hidden;">
                <img id="templateBgImage" src="" style="width: 100%; height: 100%; object-fit: cover;">
                <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0.7) 100%);"></div>
                
                <!-- Title on Image -->
                <div style="position: absolute; top: 30px; left: 30px; right: 30px;">
                    <h1 id="templateTitle" style="color: white; font-size: 36px; font-weight: bold; margin: 0 0 12px 0; text-shadow: 2px 2px 6px rgba(0,0,0,0.8); line-height: 1.2;"></h1>
                    <div style="display: inline-block; background: #CFD916; padding: 8px 20px; border-radius: 8px;">
                        <p style="margin: 0; font-size: 14px; font-weight: bold; color: #1f2937;">✓ Voucher Berhasil Di-claim</p>
                    </div>
                </div>

                <!-- Barcode Overlay on Image -->
                <div style="position: absolute; bottom: 40px; left: 0; right: 0; display: flex; justify-content: center;">
                    <div style="background: rgba(255, 255, 255, 0.95); padding: 20px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.3); max-width: 80%;">
                        <p style="margin: 0 0 12px 0; font-size: 16px; font-weight: bold; color: #1f2937; text-align: center; letter-spacing: 1px;">🎫 KODE VOUCHER</p>
                        <div style="display: flex; justify-content: center; margin: 10px 0;">
                            <svg id="templateBarcode"></svg>
                        </div>
                        <p style="margin: 10px 0 0 0; font-size: 12px; color: #6b7280; text-align: center; font-style: italic;">
                            Tunjukkan barcode ini saat melakukan pembayaran
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: White (40%) -->
            <div style="position: relative; width: 100%; height: 40%; background: white; padding: 40px;">
                
                <!-- User Info Box -->
                <div style="background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); padding: 20px 25px; border-radius: 16px; margin-bottom: 30px; border: 2px solid #CFD916; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <div style="margin-bottom: 10px;">
                        <span style="color: #6b7280; font-size: 13px; font-weight: 600;">👤 Nama:</span>
                        <span id="templateName" style="color: #111827; font-size: 14px; font-weight: bold; margin-left: 8px;"></span>
                    </div>
                    <div style="margin-bottom: 10px;">
                        <span style="color: #6b7280; font-size: 13px; font-weight: 600;">📱 No. HP:</span>
                        <span id="templatePhone" style="color: #111827; font-size: 14px; font-weight: bold; margin-left: 8px;"></span>
                    </div>
                    <div>
                        <span style="color: #6b7280; font-size: 13px; font-weight: 600;">📅 Berlaku:</span>
                        <span id="templateExpiry" style="color: #111827; font-size: 14px; font-weight: bold; margin-left: 8px;"></span>
                    </div>
                </div>

                <!-- Footer -->
                <div style="position: absolute; bottom: 15px; left: 0; right: 0; text-align: center;">
                    <p style="margin: 0; font-size: 10px; color: #9ca3af;">MestaKara © 2025 | Valid Voucher</p>
                </div>
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

                const result = await response.json();
                if (!result.success) throw new Error(result.message);

                const uniqueCode = result.data.unique_code;
                const expiryDate = new Date(currentVoucher.expiry_date).toLocaleDateString('id-ID', {
                    day: 'numeric', month: 'long', year: 'numeric'
                });

                // Set values
                document.getElementById('templateTitle').textContent = currentVoucher.name;
                document.getElementById('templateName').textContent = userName;
                document.getElementById('templatePhone').textContent = userPhone;
                document.getElementById('templateExpiry').textContent = expiryDate;
                
                const bgImage = document.getElementById('templateBgImage');
                bgImage.src = currentVoucher.download_image_url || currentVoucher.image_url;

                // Generate barcode
                JsBarcode("#templateBarcode", uniqueCode, {
                    format: "CODE128",
                    width: 2.5,
                    height: 70,
                    displayValue: true,
                    fontSize: 16,
                    margin: 10,
                    background: "transparent",
                    lineColor: "#111827"
                });

                // Wait for image load
                const waitForImage = new Promise((resolve) => {
                    if (bgImage.complete) {
                        resolve();
                    } else {
                        bgImage.onload = resolve;
                    }
                });

                await waitForImage;

                // Capture
                const template = document.getElementById('voucherTemplate');
                const canvas = await html2canvas(template, {
                    scale: 2,
                    backgroundColor: '#f3f4f6',
                    logging: false,
                    useCORS: true,
                    allowTaint: true,
                    windowWidth: 800,
                    windowHeight: 1000
                });

                canvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `Voucher-${currentVoucher.name.replace(/\s+/g, '-')}-${uniqueCode}.png`;
                    a.click();
                    URL.revokeObjectURL(url);

                    hideClaimForm();
                    
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-4 left-1/2 -translate-x-1/2 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl z-[100]';
                    notification.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div><p class="font-bold">Berhasil!</p><p class="text-sm">Voucher telah di-download</p></div>
                        </div>
                    `;
                    document.body.appendChild(notification);
                    setTimeout(() => { notification.remove(); location.reload(); }, 3000);
                }, 'image/png', 1.0);

            } catch (error) {
                console.error('Error:', error);
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 left-1/2 -translate-x-1/2 bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl z-[100]';
                notification.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <div><p class="font-bold">Gagal!</p><p class="text-sm">${error.message}</p></div>
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