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
        .voucher-card:hover { transform: translateY(-8px) scale(1.02); box-shadow: 0 20px 40px rgba(207, 217, 22, 0.3); }
        .gradient-bg { background: linear-gradient(135deg, #CFD916 0%, #9DB91C 100%); }
        .gradient-card { background: linear-gradient(135deg, #CFD916 0%, #B5C91A 50%, #9DB91C 100%); }
        .btn-primary { background: #CFD916; transition: all 0.3s ease; }
        .btn-primary:hover { background: #B5C91A; transform: translateY(-2px); box-shadow: 0 8px 16px rgba(207, 217, 22, 0.4); }
        .badge-active { background: #CFD916; color: #1f2937; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(100px) scale(0.9); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .animate-fade-in { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-slide-up { animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        .ribbon { position: absolute; top: 15px; right: -5px; background: #CFD916; color: #1f2937; padding: 5px 15px; font-weight: bold; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .ribbon:before { content: ''; position: absolute; right: 0; bottom: -10px; border-left: 10px solid transparent; border-right: 10px solid #9DB91C; border-top: 10px solid #9DB91C; }
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-white border-b border-black">
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
    </header>

    <main class="container mx-auto px-4 py-12">
        @if($vouchers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($vouchers as $index => $voucher)
                <div class="voucher-card bg-white rounded-2xl shadow-lg overflow-hidden animate-fade-in" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="relative h-56 gradient-card">
                        <img src="{{ $voucher->image_url }}" 
                             alt="{{ $voucher->name }}" 
                             class="w-full h-full object-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="absolute inset-0 flex items-center justify-center text-gray-800 text-2xl font-bold px-4 text-center" style="display: none;">
                            {{ $voucher->name }}
                        </div>
                        
                        <div class="ribbon text-xs font-bold uppercase tracking-wider">✓ {{ $voucher->status_text }}</div>
                        <div class="absolute bottom-0 left-0 w-0 h-0 border-l-[40px] border-l-white border-t-[40px] border-t-transparent"></div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-3 line-clamp-2 min-h-[4rem]">{{ $voucher->name }}</h3>
                        
                        <p class="text-gray-600 text-sm mb-6 line-clamp-3 min-h-[4.5rem]">
                            {{ Str::limit($voucher->deskripsi, 120) }}
                        </p>

                        <div class="flex items-center justify-between pt-4 border-t-2 border-gray-100">
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">{{ $voucher->created_at->format('d M Y') }}</span>
                            </div>
                            <button onclick='showClaimForm(@json($voucher))' 
                                    class="btn-primary text-gray-800 px-5 py-2.5 rounded-xl text-sm font-bold uppercase tracking-wide shadow-md">
                                🎉 Claim Sekarang
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20 animate-fade-in">
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
        @endif
    </main>

    <!-- Claim Form Pop-up Card -->
    <div id="claimOverlay" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div id="claimCard" class="bg-white rounded-3xl shadow-2xl max-w-md w-full animate-slide-up overflow-hidden">
            <!-- Card Header -->
            <div class="gradient-bg p-6 text-center">
                <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Claim Voucher</h2>
                <p class="text-gray-700 text-sm" id="claimVoucherName"></p>
                <p class="text-gray-600 text-xs mt-1">Berlaku hingga: <span id="claimExpiryDate"></span></p>
            </div>

            <!-- Form -->
            <form id="claimForm" class="p-6">
                <input type="hidden" id="voucherId">
                
                <div class="mb-5">
                    <label for="userName" class="block text-sm font-bold text-gray-700 mb-2">
                        📝 Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="userName" 
                           required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916] focus:border-transparent transition-all" 
                           placeholder="Masukkan nama lengkap Anda">
                </div>

                <div class="mb-6">
                    <label for="userPhone" class="block text-sm font-bold text-gray-700 mb-2">
                        📱 Nomor Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           id="userPhone" 
                           required
                           pattern="[0-9]{10,13}"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916] focus:border-transparent transition-all" 
                           placeholder="08xxxxxxxxxx">
                    <p class="mt-1.5 text-xs text-gray-500">💡 Format: 08xxxxxxxxxx (10-13 digit)</p>
                </div>

                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="hideClaimForm()"
                            class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 font-bold">
                        Batal
                    </button>
                    <button type="submit" 
                            id="submitBtn"
                            class="flex-1 btn-primary text-gray-800 px-6 py-3 rounded-xl font-bold shadow-lg">
                        Claim & Download 🎁
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden Template -->
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

    <script>
        let currentVoucher = null;

        function showClaimForm(voucher) {
            currentVoucher = voucher;
            
            const expiryDate = new Date(voucher.expiry_date).toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            document.getElementById('voucherId').value = voucher.id;
            document.getElementById('claimVoucherName').textContent = voucher.name;
            document.getElementById('claimExpiryDate').textContent = expiryDate;
            document.getElementById('claimOverlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function hideClaimForm() {
            document.getElementById('claimOverlay').classList.add('hidden');
            document.getElementById('claimForm').reset();
            document.body.style.overflow = 'auto';
        }

        document.getElementById('claimOverlay').addEventListener('click', function(e) {
            if (e.target === this) {
                hideClaimForm();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideClaimForm();
            }
        });

        document.getElementById('claimForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
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

                if (!result.success) {
                    throw new Error(result.message);
                }

                const uniqueCode = result.data.unique_code;
                const expiryDate = new Date(currentVoucher.expiry_date).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });

                document.getElementById('templateTitle').textContent = currentVoucher.name;
                document.getElementById('templateName').textContent = userName;
                document.getElementById('templatePhone').textContent = userPhone;
                document.getElementById('templateExpiry').textContent = expiryDate;
                document.getElementById('templateDesc').textContent = currentVoucher.deskripsi;

                JsBarcode("#templateBarcode", uniqueCode, {
                    format: "CODE128",
                    width: 2,
                    height: 80,
                    displayValue: true,
                    fontSize: 16,
                    margin: 10
                });

                const template = document.getElementById('voucherTemplate');
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

                    hideClaimForm();
                    
                    // Success notification
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl z-[100] animate-slide-up';
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
                });
            } catch (error) {
                console.error('Error claiming voucher:', error);
                alert('❌ Terjadi kesalahan: ' + error.message);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    </script>
</body>
</html>