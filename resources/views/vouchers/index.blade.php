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
        .voucher-card:hover:not(.disabled) { transform: translateY(-8px) scale(1.02); box-shadow: 0 20px 40px rgba(207, 217, 22, 0.3); }
        .voucher-card.disabled { opacity: 0.6; filter: grayscale(50%); cursor: not-allowed; }
        .voucher-card.disabled img { opacity: 0.5; }
        .gradient-bg { background: linear-gradient(135deg, #CFD916 0%, #9DB91C 100%); }
        .gradient-card { background: linear-gradient(135deg, #CFD916 0%, #B5C91A 50%, #9DB91C 100%); }
        .btn-primary { background: #CFD916; transition: all 0.3s ease; }
        .btn-primary:hover { background: #B5C91A; transform: translateY(-2px); box-shadow: 0 8px 16px rgba(207, 217, 22, 0.4); }
        .btn-disabled { background: #d1d5db; color: #6b7280; cursor: not-allowed; }
        .btn-disabled:hover { background: #d1d5db; transform: none; box-shadow: none; }
        .badge-active { background: #CFD916; color: #1f2937; }
        .badge-sold-out { background: #ef4444; color: white; }
        .badge-expired { background: #6b7280; color: white; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(100px) scale(0.9); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .animate-fade-in { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-slide-up { animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        .ribbon { 
            position: absolute; 
            top: 10px; 
            right: -5px; 
            padding: 4px 12px; 
            font-weight: bold; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 10px;
            z-index: 10;
        }
        .ribbon.active { background: #CFD916; color: #1f2937; }
        .ribbon.sold-out { background: #ef4444; color: white; }
        .ribbon.expired { background: #6b7280; color: white; }
        .ribbon:before { 
            content: ''; 
            position: absolute; 
            right: 0; 
            bottom: -8px; 
        }
        .ribbon.active:before {
            border-left: 8px solid transparent; 
            border-right: 8px solid #9DB91C; 
            border-top: 8px solid #9DB91C;
        }
        .ribbon.sold-out:before {
            border-left: 8px solid transparent; 
            border-right: 8px solid #dc2626; 
            border-top: 8px solid #dc2626;
        }
        .ribbon.expired:before {
            border-left: 8px solid transparent; 
            border-right: 8px solid #4b5563; 
            border-top: 8px solid #4b5563;
        }
        
        /* Mobile optimizations */
        @media (max-width: 640px) {
            .voucher-card:active:not(.disabled) { transform: scale(0.98); }
            .ribbon { 
                padding: 3px 10px; 
                font-size: 9px;
                top: 8px;
            }
            .ribbon:before { 
                bottom: -6px;
            }
            .ribbon.active:before {
                border-left: 6px solid transparent; 
                border-right: 6px solid #9DB91C; 
                border-top: 6px solid #9DB91C;
            }
            .ribbon.sold-out:before {
                border-left: 6px solid transparent; 
                border-right: 6px solid #dc2626; 
                border-top: 6px solid #dc2626;
            }
            .ribbon.expired:before {
                border-left: 6px solid transparent; 
                border-right: 6px solid #4b5563; 
                border-top: 6px solid #4b5563;
            }
        }
        
        body { overflow-x: hidden; }
        button, a { -webkit-tap-highlight-color: transparent; }
        html { scroll-behavior: smooth; }
        img { object-position: center; }
        
        @media (max-width: 640px) {
            #claimCard { 
                margin: auto;
                max-height: 90vh;
            }
        }
        
        @keyframes slideInFromTop {
            from { opacity: 0; transform: translate(-50%, -100%); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }
        
        .notification-enter {
            animation: slideInFromTop 0.4s ease-out forwards;
        }
        
        /* Progress Bar Animation */
        @keyframes progressFill {
            from { width: 0%; }
        }
        
        .progress-bar-animate {
            animation: progressFill 1s ease-out forwards;
        }
        
        /* Pulse animation for low quota */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .pulse-warn {
            animation: pulse 2s ease-in-out infinite;
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

    <!-- Main Content - Responsive -->
    <main class="container mx-auto px-3 sm:px-4 py-6 sm:py-12">
        @if($vouchers->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                @foreach($vouchers as $index => $voucher)
                @php
                    // Cek status voucher
                    $isAvailable = $voucher->is_available;
                    $isSoldOut = $voucher->is_sold_out;
                    $isExpired = $voucher->is_expired;
                    $effectiveStatus = $voucher->effective_status;
                    
                    // Tentukan class dan badge
                    $cardClass = $isAvailable ? '' : 'disabled';
                    $ribbonClass = $isAvailable ? 'active' : ($isSoldOut ? 'sold-out' : 'expired');
                    $ribbonText = $isAvailable ? '✓ TERSEDIA' : ($isSoldOut ? '✕ HABIS' : '✕ KADALUARSA');
                @endphp
                
                <div class="voucher-card {{ $cardClass }} bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden animate-fade-in" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="relative h-40 sm:h-48 md:h-56 gradient-card">
                        <img src="{{ $voucher->image_url }}" 
                             alt="{{ $voucher->name }}" 
                             class="w-full h-full object-cover"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="absolute inset-0 flex items-center justify-center text-gray-800 text-lg sm:text-xl md:text-2xl font-bold px-4 text-center" style="display: none;">
                            {{ $voucher->name }}
                        </div>
                        
                        <div class="ribbon {{ $ribbonClass }} uppercase tracking-wider">{{ $ribbonText }}</div>
                        
                        @if(!$isAvailable)
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                            <div class="bg-white/90 px-4 py-2 rounded-lg">
                                <p class="text-sm sm:text-base font-bold text-gray-800">
                                    @if($isSoldOut)
                                        🚫 Kuota Habis
                                    @else
                                        ⏰ Sudah Kadaluarsa
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="absolute bottom-0 left-0 w-0 h-0 border-l-[30px] sm:border-l-[40px] border-l-white border-t-[30px] sm:border-t-[40px] border-t-transparent"></div>
                    </div>

                    <div class="p-4 sm:p-5 md:p-6">
                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-800 mb-2 sm:mb-3 line-clamp-2 min-h-[3.5rem] sm:min-h-[4rem]">{{ $voucher->name }}</h3>
                        
                        <p class="text-gray-600 text-xs sm:text-sm mb-4 sm:mb-6 line-clamp-3 min-h-[3.5rem] sm:min-h-[4.5rem]">
                            {{ Str::limit($voucher->deskripsi, 120) }}
                        </p>

                        <!-- Quota Progress Bar (Jika Limited) -->
                        @if(!$voucher->is_unlimited)
                        @php
                            $claimed = $voucher->claims->count();
                            $remaining = $voucher->remaining_quota;
                            $percentage = $voucher->quota > 0 ? ($remaining / $voucher->quota) * 100 : 0;
                            
                            // Tentukan warna berdasarkan persentase
                            if ($percentage > 50) {
                                $barColor = 'bg-green-500';
                                $barBgColor = 'bg-green-100';
                                $textColor = 'text-green-700';
                            } elseif ($percentage > 20) {
                                $barColor = 'bg-yellow-500';
                                $barBgColor = 'bg-yellow-100';
                                $textColor = 'text-yellow-700';
                            } else {
                                $barColor = 'bg-red-500';
                                $barBgColor = 'bg-red-100';
                                $textColor = 'text-red-700';
                            }
                        @endphp
                        <div class="mb-3 sm:mb-4">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-semibold {{ $textColor }}">
                                    @if($isSoldOut)
                                        🚫 Kuota Habis
                                    @elseif($percentage <= 20)
                                        ⚠️ Kuota Terbatas
                                    @else
                                        📊 Kuota Tersedia
                                    @endif
                                </span>
                                <span class="font-bold {{ $isSoldOut ? 'text-red-600' : $textColor }}">
                                    {{ $remaining }}/{{ $voucher->quota }}
                                </span>
                            </div>
                            <div class="w-full {{ $barBgColor }} rounded-full h-2.5 overflow-hidden shadow-inner">
                                <div class="{{ $barColor }} h-full rounded-full transition-all duration-500 ease-out progress-bar-animate {{ $percentage <= 20 && $percentage > 0 ? 'pulse-warn' : '' }}" 
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                            @if($percentage <= 20 && $percentage > 0)
                            <p class="text-xs text-orange-600 mt-1.5 font-medium animate-pulse">
                                ⚡ Buruan! Hanya tersisa {{ $remaining }} voucher
                            </p>
                            @elseif($percentage > 50)
                            <p class="text-xs text-green-600 mt-1.5 font-medium">
                                ✨ Masih banyak tersedia
                            </p>
                            @endif
                        </div>
                        @else
                        <div class="mb-3 sm:mb-4">
                            <div class="flex items-center text-xs">
                                <span class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full font-semibold">
                                    ♾️ Kuota Unlimited
                                </span>
                            </div>
                        </div>
                        @endif

                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pt-3 sm:pt-4 border-t-2 border-gray-100">
                            <div class="flex items-center text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-1 sm:mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($voucher->expiry_date)->format('d M Y') }}</span>
                            </div>
                            
                            @if($isAvailable)
                            <button onclick='showClaimForm(@json($voucher))' 
                                    class="w-full sm:w-auto btn-primary text-gray-800 px-4 sm:px-5 py-2 sm:py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold uppercase tracking-wide shadow-md">
                                🎉 Claim Sekarang
                            </button>
                            @else
                            <button disabled
                                    class="w-full sm:w-auto btn-disabled px-4 sm:px-5 py-2 sm:py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold uppercase tracking-wide">
                                @if($isSoldOut)
                                    🚫 Habis
                                @else
                                    ⏰ Kadaluarsa
                                @endif
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 sm:py-20 animate-fade-in">
                <div class="bg-white rounded-3xl shadow-xl p-8 sm:p-12 text-center max-w-md mx-auto">
                    <div class="w-24 h-24 sm:w-32 sm:h-32 mx-auto mb-4 sm:mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2 sm:mb-3">Belum Ada Voucher</h3>
                    <p class="text-sm sm:text-base text-gray-500 mb-4 sm:mb-6">Saat ini belum ada voucher yang tersedia. Silakan cek kembali nanti!</p>
                    <a href="/" class="btn-primary inline-block text-gray-800 px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold text-sm sm:text-base">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        @endif
    </main>

    <!-- Claim Form Pop-up - Responsive -->
    <div id="claimOverlay" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-3 sm:p-4">
        <div id="claimCard" class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl max-w-md w-full animate-slide-up overflow-hidden max-h-[95vh] overflow-y-auto">
            <!-- Card Header -->
            <div class="gradient-bg p-5 sm:p-6 text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">Claim Voucher</h2>
                <p class="text-sm text-gray-700 px-2" id="claimVoucherName"></p>
                <p class="text-xs text-gray-600 mt-1">Berlaku hingga: <span id="claimExpiryDate"></span></p>
            </div>

            <!-- Form -->
            <form id="claimForm" class="p-5 sm:p-6">
                <input type="hidden" id="voucherId">
                
                <div class="mb-4 sm:mb-5">
                    <label for="userName" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                        📝 Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="userName" 
                           required
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916] focus:border-transparent transition-all" 
                           placeholder="Masukkan nama lengkap Anda">
                </div>

                <div class="mb-5 sm:mb-6">
                    <label for="userPhone" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                        📱 Nomor Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           id="userPhone" 
                           required
                           pattern="[0-9]{10,13}"
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#CFD916] focus:border-transparent transition-all" 
                           placeholder="08xxxxxxxxxx">
                    <p class="mt-1.5 text-xs text-gray-500">💡 Format: 08xxxxxxxxxx (10-13 digit)</p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="button" 
                            onclick="hideClaimForm()"
                            class="w-full sm:flex-1 px-5 sm:px-6 py-2.5 sm:py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 font-bold text-sm sm:text-base">
                        Batal
                    </button>
                    <button type="submit" 
                            id="submitBtn"
                            class="w-full sm:flex-1 btn-primary text-gray-800 px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold shadow-lg text-sm sm:text-base">
                        Claim & Download 🎁
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden Template -->
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
                    notification.className = 'fixed top-4 left-1/2 -translate-x-1/2 bg-green-500 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-xl shadow-2xl z-[100] notification-enter w-[calc(100%-2rem)] sm:w-auto max-w-md';
                    notification.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <p class="font-bold text-sm sm:text-base">Berhasil!</p>
                                <p class="text-xs sm:text-sm">Voucher telah di-download</p>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(notification);
                    setTimeout(() => notification.remove(), 5000);
                });
            } catch (error) {
                console.error('Error claiming voucher:', error);
                
                // Error notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 left-1/2 -translate-x-1/2 bg-red-500 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-xl shadow-2xl z-[100] notification-enter w-[calc(100%-2rem)] sm:w-auto max-w-md';
                notification.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <div>
                            <p class="font-bold text-sm sm:text-base">Gagal!</p>
                            <p class="text-xs sm:text-sm">${error.message}</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 5000);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    </script>
</body>
</html>