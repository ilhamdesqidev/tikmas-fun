<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $voucher->name }} - Detail Voucher</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .btn-primary {
            background: #CFD916;
            color: #1f2937;
        }
        
        .btn-primary:hover {
            background: #b8c214;
            transform: translateY(-2px);
        }

        .voucher-card {
            transition: all 0.3s ease;
        }

        .info-item {
            transition: all 0.2s ease;
        }

        .info-item:hover {
            transform: translateX(4px);
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-down {
            animation: slideDown 0.4s ease-out;
        }

        .modal-content {
            animation: slideDown 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-40 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <a href="/" class="text-2xl font-bold text-gray-900 hover:opacity-80 transition-opacity">
                MestaKara<span class="text-[#CFD916]">.</span>
            </a>
            <a href="/dashboard" class="text-gray-600 hover:text-gray-900 flex items-center gap-2 transition-colors px-4 py-2 rounded-lg hover:bg-gray-50">
                <i data-feather="arrow-left" class="w-5 h-5"></i>
                <span class="font-medium">Kembali</span>
            </a>
        </div>
    </header>

    <!-- Voucher Detail Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <div class="voucher-card bg-white rounded-3xl shadow-lg overflow-hidden border border-gray-100 animate-slide-down">
            <div class="lg:flex">
                <!-- Voucher Image -->
                <div class="lg:w-5/12">
                    <div class="relative h-64 lg:h-full">
                        <img src="{{ $voucher->image_url }}" 
                             alt="{{ $voucher->name }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            @if($voucher->is_available)
                                <span class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-full shadow-lg flex items-center gap-2">
                                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                    Tersedia
                                </span>
                            @elseif($voucher->is_sold_out)
                                <span class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-full shadow-lg">
                                    Habis
                                </span>
                            @elseif($voucher->is_expired)
                                <span class="px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-full shadow-lg">
                                    Kadaluarsa
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Voucher Info -->
                <div class="lg:w-7/12 p-6 sm:p-8 lg:p-10">
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 leading-tight">{{ $voucher->name }}</h1>
                    
                    <p class="text-gray-600 mb-8 leading-relaxed text-lg">{{ $voucher->deskripsi }}</p>

                    <!-- Info Grid -->
                    <div class="space-y-5 mb-8">
                        <div class="info-item flex items-start gap-4 p-4 rounded-xl hover:bg-gray-50">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <i data-feather="calendar" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Berlaku Hingga</p>
                                <p class="font-semibold text-gray-900 text-lg">
                                    @if($voucher->expiry_date)
                                        {{ \Carbon\Carbon::parse($voucher->expiry_date)->format('d M Y') }}
                                    @else
                                        Tidak Terbatas
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if(!$voucher->is_unlimited)
                        <div class="info-item p-4 rounded-xl hover:bg-gray-50">
                            <div class="flex items-start gap-4 mb-3">
                                <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center flex-shrink-0">
                                    <i data-feather="package" class="w-5 h-5 text-purple-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Kuota Tersisa</p>
                                    <p class="font-semibold text-gray-900 text-lg">{{ $voucher->remaining_quota }} / {{ $voucher->quota }}</p>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                @php
                                    $percentage = ($voucher->remaining_quota / $voucher->quota) * 100;
                                    $barColor = $percentage > 50 ? 'bg-green-500' : ($percentage > 20 ? 'bg-yellow-500' : 'bg-red-500');
                                @endphp
                                <div class="{{ $barColor }} h-full rounded-full transition-all duration-500" 
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        @else
                        <div class="info-item flex items-start gap-4 p-4 rounded-xl hover:bg-gray-50">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <i data-feather="zap" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Kuota</p>
                                <p class="font-semibold text-blue-600 text-lg">Unlimited</p>
                            </div>
                        </div>
                        @endif

                        <div class="info-item flex items-start gap-4 p-4 rounded-xl hover:bg-gray-50">
                            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
                                <i data-feather="check-circle" class="w-5 h-5 text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-1">Total Diklaim</p>
                                <p class="font-semibold text-gray-900 text-lg" id="total-claimed">{{ $voucher->claims()->count() }} Orang</p>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    @if($voucher->is_available)
                        <button onclick="showClaimForm()" 
                                class="w-full btn-primary py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2">
                            <span>🎁</span>
                            <span>Klaim Voucher Sekarang</span>
                        </button>
                    @else
                        <button disabled 
                                class="w-full bg-gray-200 text-gray-500 py-4 rounded-xl font-bold text-lg cursor-not-allowed">
                            @if($voucher->is_sold_out)
                                🚫 Kuota Habis
                            @else
                                ⏰ Voucher Kadaluarsa
                            @endif
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Syarat & Ketentuan -->
        <div class="bg-white rounded-3xl shadow-lg p-6 sm:p-8 lg:p-10 mt-8 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center">
                    <i data-feather="info" class="w-6 h-6 text-blue-600"></i>
                </div>
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900">Syarat & Ketentuan</h2>
            </div>
            <ul class="space-y-3">
                @php
                    $syaratArray = $voucher->syarat_ketentuan_array;
                @endphp
                @forelse($syaratArray as $syarat)
                <li class="flex items-start gap-3 text-gray-700 hover:bg-gray-50 p-3 rounded-lg transition-colors">
                    <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i data-feather="check" class="w-3.5 h-3.5 text-green-600"></i>
                    </div>
                    <span class="leading-relaxed">{{ $syarat }}</span>
                </li>
                @empty
                <li class="flex items-start gap-3 text-gray-700 hover:bg-gray-50 p-3 rounded-lg transition-colors">
                    <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i data-feather="check" class="w-3.5 h-3.5 text-green-600"></i>
                    </div>
                    <span class="leading-relaxed">Voucher hanya dapat diklaim satu kali per nomor WhatsApp</span>
                </li>
                <li class="flex items-start gap-3 text-gray-700 hover:bg-gray-50 p-3 rounded-lg transition-colors">
                    <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i data-feather="check" class="w-3.5 h-3.5 text-green-600"></i>
                    </div>
                    <span class="leading-relaxed">Tunjukkan barcode voucher saat melakukan pembayaran</span>
                </li>
                <li class="flex items-start gap-3 text-gray-700 hover:bg-gray-50 p-3 rounded-lg transition-colors">
                    <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i data-feather="check" class="w-3.5 h-3.5 text-green-600"></i>
                    </div>
                    <span class="leading-relaxed">Voucher berlaku hingga tanggal yang tertera</span>
                </li>
                <li class="flex items-start gap-3 text-gray-700 hover:bg-gray-50 p-3 rounded-lg transition-colors">
                    <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i data-feather="check" class="w-3.5 h-3.5 text-green-600"></i>
                    </div>
                    <span class="leading-relaxed">Voucher tidak dapat digabungkan dengan promo lain</span>
                </li>
                <li class="flex items-start gap-3 text-gray-700 hover:bg-gray-50 p-3 rounded-lg transition-colors">
                    <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i data-feather="check" class="w-3.5 h-3.5 text-green-600"></i>
                    </div>
                    <span class="leading-relaxed">Voucher tidak dapat diuangkan</span>
                </li>
                @endforelse
            </ul>
        </div>
    </section>

    <!-- Claim Form Modal -->
    <div id="claimModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="modal-content bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="p-8 text-center border-b border-gray-100" style="background: linear-gradient(135deg, #CFD916 0%, #b8c214 100%);">
                <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <i data-feather="gift" class="w-10 h-10 text-gray-800"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Klaim Voucher</h2>
                <p class="text-sm text-gray-700 font-medium">{{ $voucher->name }}</p>
            </div>

            <!-- Form -->
            <form id="claimForm" class="p-8">
                <input type="hidden" id="voucherId" value="{{ $voucher->id }}">
                
                <!-- Nama Lengkap -->
                <div class="mb-6">
                    <label for="userName" class="block text-sm font-semibold text-gray-700 mb-2">
                        📝 Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="userName" 
                           required
                           class="w-full px-4 py-3.5 text-base border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916] focus:border-transparent transition-all" 
                           placeholder="Contoh: John Doe">
                </div>

                <!-- Domisili -->
                <div class="mb-6">
                    <label for="userDomisili" class="block text-sm font-semibold text-gray-700 mb-2">
                        🏠 Domisili <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="userDomisili" 
                           required
                           class="w-full px-4 py-3.5 text-base border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916] focus:border-transparent transition-all" 
                           placeholder="Contoh: Jakarta Selatan">
                    <p class="mt-2 text-xs text-gray-500 flex items-center gap-1.5">
                        <span>💡</span>
                        <span>Masukkan kota/kabupaten tempat tinggal Anda</span>
                    </p>
                </div>

                <!-- Nomor WhatsApp -->
                <div class="mb-8">
                    <label for="userPhone" class="block text-sm font-semibold text-gray-700 mb-2">
                        📱 Nomor WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           id="userPhone" 
                           required
                           pattern="[0-9]{10,13}"
                           class="w-full px-4 py-3.5 text-base border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916] focus:border-transparent transition-all" 
                           placeholder="08xxxxxxxxxx">
                    <p class="mt-2 text-xs text-gray-500 flex items-center gap-1.5">
                        <span>💡</span>
                        <span>Format: 08xxxxxxxxxx (10-13 digit)</span>
                    </p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="button" 
                            onclick="hideClaimForm()"
                            class="flex-1 px-6 py-3.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300 font-semibold">
                        Batal
                    </button>
                    <button type="submit" 
                            id="submitBtn"
                            class="flex-1 px-6 py-3.5 rounded-xl font-semibold shadow-lg transition-all duration-300 btn-primary">
                        Klaim & Download 🎁
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden Template untuk Download -->
    <div id="voucherTemplate" style="position: absolute; left: -9999px; width: 800px; height: 600px;">
        <div style="position: relative; width: 100%; height: 100%; font-family: Arial, sans-serif;">
            <img id="templateBgImage" src="{{ $voucher->download_image_url ?? $voucher->image_url }}" 
                 style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">

            <div style="position: absolute; bottom: 60px; left: 50%; transform: translateX(-50%); background: rgba(255, 255, 255, 0.95); padding: 8px 15px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25); backdrop-filter: blur(10px);">
                <p style="text-align: center; color: #1f2937; font-weight: bold; margin: 0 0 5px 0; font-size: 11px;">KODE VOUCHER</p>
                <svg id="templateBarcode"></svg>
            </div>

            <div style="position: absolute; bottom: 15px; left: 0; right: 0; text-align: center;">
                <p style="margin: 0; color: white; font-size: 11px; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
                    Tunjukkan barcode ini saat melakukan pembayaran
                </p>
            </div>
        </div>
    </div>

    <script>
        feather.replace();

        let currentClaimedCount = {{ $voucher->claims()->count() }};

        function showClaimForm() {
            document.getElementById('claimModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            // Re-initialize feather icons in modal
            setTimeout(() => feather.replace(), 100);
        }

        function hideClaimForm() {
            document.getElementById('claimModal').classList.add('hidden');
            document.getElementById('claimForm').reset();
            document.body.style.overflow = 'auto';
        }

        // Close modal on overlay click
        document.getElementById('claimModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideClaimForm();
            }
        });

        // Handle form submission
        document.getElementById('claimForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '⏳ Memproses...';

            const userName = document.getElementById('userName').value;
            const userDomisili = document.getElementById('userDomisili').value;
            const userPhone = document.getElementById('userPhone').value;
            const voucherId = document.getElementById('voucherId').value;

            try {
                const res = await fetch('/vouchers/claim', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ 
                        voucher_id: voucherId, 
                        user_name: userName,
                        user_domisili: userDomisili,
                        user_phone: userPhone 
                    })
                });

                let payload = null;
                try { payload = await res.json(); } catch (err) { payload = null; }

                if (!res.ok) {
                    const userMsg = (payload && payload.message) ? payload.message : 'Gagal mengklaim voucher.';
                    showNotification(userMsg, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                    return;
                }

                const result = payload;
                const uniqueCode = result.data.unique_code;

                // Update total claimed count
                currentClaimedCount++;
                document.getElementById('total-claimed').textContent = currentClaimedCount + ' Orang';

                // Generate barcode
                JsBarcode("#templateBarcode", uniqueCode, {
                    format: "CODE128",
                    width: 1.5,
                    height: 40,
                    displayValue: true,
                    fontSize: 12,
                    margin: 3,
                    background: "transparent"
                });

                const bgImage = document.getElementById('templateBgImage');
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
                        showNotification('✅ Voucher berhasil di-download!', 'success');
                        
                        setTimeout(() => location.reload(), 2000);
                    });
                };

                if (bgImage.complete) {
                    bgImage.onload();
                }

            } catch (error) {
                console.error('Error:', error);
                showNotification('❌ Terjadi kesalahan saat claim voucher', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        function showNotification(message, type) {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const notification = document.createElement('div');
            notification.className = `fixed top-4 left-1/2 -translate-x-1/2 ${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl z-[100] max-w-md animate-slide-down`;
            notification.innerHTML = `
                <div class="flex items-center space-x-3">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'}"></path>
                    </svg>
                    <p class="text-sm font-medium">${message}</p>
                </div>
            `;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 5000);
        }
    </script>
</body>
</html>