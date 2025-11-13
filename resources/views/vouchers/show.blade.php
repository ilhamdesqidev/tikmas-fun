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
        
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .btn-primary {
            background: #CFD916;
            color: #1f2937;
        }
        
        .btn-primary:hover {
            background: #b8c214;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="text-2xl font-bold">
                MestaKara<span class="text-[#CFD916]">.</span>
            </a>
            <a href="/dashboard" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
                <i data-feather="arrow-left" class="w-5 h-5"></i>
                Kembali
            </a>
        </div>
    </header>

    <!-- Voucher Detail Section -->
    <section class="max-w-6xl mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="md:flex">
                <!-- Voucher Image -->
                <div class="md:w-1/2">
                    <img src="{{ $voucher->image_url }}" 
                         alt="{{ $voucher->name }}" 
                         class="w-full h-full object-cover">
                </div>
                
                <!-- Voucher Info -->
                <div class="md:w-1/2 p-8">
                    <div class="flex items-center gap-2 mb-4">
                        @if($voucher->is_available)
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                                ✓ Tersedia
                            </span>
                        @elseif($voucher->is_sold_out)
                            <span class="px-3 py-1 bg-red-100 text-red-700 text-sm font-semibold rounded-full">
                                ✕ Habis
                            </span>
                        @elseif($voucher->is_expired)
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                                ✕ Kadaluarsa
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $voucher->name }}</h1>
                    
                    <p class="text-gray-600 mb-6 leading-relaxed">{{ $voucher->deskripsi }}</p>

                    <!-- Info Grid -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center gap-3">
                            <i data-feather="calendar" class="w-5 h-5 text-gray-400"></i>
                            <div>
                                <p class="text-sm text-gray-500">Berlaku Hingga</p>
                                <p class="font-semibold">
                                    @if($voucher->expiry_date)
                                        {{ \Carbon\Carbon::parse($voucher->expiry_date)->format('d M Y') }}
                                    @else
                                        Tidak Terbatas
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if(!$voucher->is_unlimited)
                        <div class="flex items-center gap-3">
                            <i data-feather="users" class="w-5 h-5 text-gray-400"></i>
                            <div>
                                <p class="text-sm text-gray-500">Kuota Tersisa</p>
                                <p class="font-semibold">{{ $voucher->remaining_quota }} / {{ $voucher->quota }}</p>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            @php
                                $claimedCount = $voucher->claims()->count();
                                $percentage = $voucher->quota > 0 ? (($voucher->quota - $voucher->remaining_quota) / $voucher->quota) * 100 : 0;
                                $barColor = $percentage < 50 ? 'bg-green-500' : ($percentage < 80 ? 'bg-yellow-500' : 'bg-red-500');
                            @endphp
                            <div class="{{ $barColor }} h-full rounded-full transition-all" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                        @else
                        <div class="flex items-center gap-3">
                            <i data-feather="infinity" class="w-5 h-5 text-blue-500"></i>
                            <div>
                                <p class="font-semibold text-blue-600">Kuota Unlimited</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center gap-3">
                            <i data-feather="check-circle" class="w-5 h-5 text-gray-400"></i>
                            <div>
                                <p class="text-sm text-gray-500">Total Diklaim</p>
                                <p class="font-semibold" id="total-claimed">{{ $voucher->claims()->count() }} Orang</p>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    @if($voucher->is_available)
                        <button onclick="showClaimForm()" 
                                class="w-full btn-primary py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all">
                            🎁 Klaim Voucher Sekarang
                        </button>
                    @else
                        <button disabled 
                                class="w-full bg-gray-300 text-gray-500 py-4 rounded-xl font-bold text-lg cursor-not-allowed">
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
        <div class="bg-white rounded-2xl shadow-lg p-8 mt-8">
            <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                <i data-feather="info" class="w-6 h-6 text-blue-500"></i>
                Syarat & Ketentuan
            </h2>
            <ul class="space-y-2 text-gray-600">
                <li class="flex items-start gap-2">
                    <i data-feather="check" class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0"></i>
                    <span>Voucher hanya dapat diklaim satu kali per nomor WhatsApp</span>
                </li>
                <li class="flex items-start gap-2">
                    <i data-feather="check" class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0"></i>
                    <span>Tunjukkan barcode voucher saat melakukan pembayaran</span>
                </li>
                <li class="flex items-start gap-2">
                    <i data-feather="check" class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0"></i>
                    <span>Voucher berlaku hingga tanggal yang tertera</span>
                </li>
                <li class="flex items-start gap-2">
                    <i data-feather="check" class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0"></i>
                    <span>Voucher tidak dapat digabungkan dengan promo lain</span>
                </li>
                <li class="flex items-start gap-2">
                    <i data-feather="check" class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0"></i>
                    <span>Voucher tidak dapat diuangkan</span>
                </li>
            </ul>
        </div>
    </section>

    <!-- Claim Form Modal -->
    <div id="claimModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="gradient-bg p-6 text-center" style="background: linear-gradient(135deg, #CFD916 0%, #9DB91C 100%);">
                <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <i data-feather="gift" class="w-10 h-10 text-gray-800"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Klaim Voucher</h2>
                <p class="text-sm text-gray-700">{{ $voucher->name }}</p>
            </div>

            <!-- Form -->
            <form id="claimForm" class="p-6">
                <input type="hidden" id="voucherId" value="{{ $voucher->id }}">
                
                <!-- Nama Lengkap -->
                <div class="mb-5">
                    <label for="userName" class="block text-sm font-bold text-gray-700 mb-2">
                        📝 Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="userName" 
                           required
                           class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916] focus:border-transparent transition-all" 
                           placeholder="Contoh: John Doe">
                </div>

                <!-- Domisili -->
                <div class="mb-5">
                    <label for="userDomisili" class="block text-sm font-bold text-gray-700 mb-2">
                        🏠 Domisili <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="userDomisili" 
                           required
                           class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916] focus:border-transparent transition-all" 
                           placeholder="Contoh: Jakarta Selatan">
                    <p class="mt-1.5 text-xs text-gray-500">💡 Masukkan kota/kabupaten tempat tinggal Anda</p>
                </div>

                <!-- Nomor WhatsApp -->
                <div class="mb-6">
                    <label for="userPhone" class="block text-sm font-bold text-gray-700 mb-2">
                        📱 Nomor WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           id="userPhone" 
                           required
                           pattern="[0-9]{10,13}"
                           class="w-full px-4 py-3 text-base border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916] focus:border-transparent transition-all" 
                           placeholder="08xxxxxxxxxx">
                    <p class="mt-1.5 text-xs text-gray-500">💡 Format: 08xxxxxxxxxx (10-13 digit)</p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="button" 
                            onclick="hideClaimForm()"
                            class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 font-bold">
                        Batal
                    </button>
                    <button type="submit" 
                            id="submitBtn"
                            class="flex-1 px-6 py-3 rounded-xl font-bold shadow-lg transition-all duration-300 btn-primary">
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

        // Simpan current claimed count
        let currentClaimedCount = {{ $voucher->claims()->count() }};

        function showClaimForm() {
            document.getElementById('claimModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
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
                        
                        // Reload halaman setelah 2 detik
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
            notification.className = `fixed top-4 left-1/2 -translate-x-1/2 ${bgColor} text-white px-6 py-4 rounded-xl shadow-2xl z-[100] max-w-md`;
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