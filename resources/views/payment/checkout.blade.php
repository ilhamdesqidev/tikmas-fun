<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - MestaKara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #f6f8f3 0%, #e8f0dc 100%);
        }
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(89, 115, 54, 0.1), 0 2px 4px -1px rgba(89, 115, 54, 0.06);
        }
        .brand-border {
            border-color: #597336;
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .pulse-soft {
            animation: pulse-soft 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="font-poppins gradient-bg min-h-screen">
    <!-- Navbar -->
    <nav class="w-full py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between bg-white shadow-sm fixed top-0 left-0 right-0 z-50 brand-border" style="border-bottom: 2px solid #597336;">
        <a href="/" class="text-2xl sm:text-3xl font-bold text-gray-900 italic flex items-center">
            Mesta<span class="text-yellow-500">Kara</span><span class="text-gray-900">.</span>
        </a>
        <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <span class="font-medium">Pembayaran Aman</span>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-20 sm:pt-24 pb-8 sm:pb-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-6 sm:mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 bg-green-100 rounded-full mb-3 sm:mb-4">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Selesaikan Pembayaran</h1>
                <p class="text-sm sm:text-base text-gray-600">Scan kode QRIS untuk melanjutkan pesanan Anda</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                <!-- Detail Pesanan -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl card-shadow overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-green-700 p-4 sm:p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold text-white mb-1">Detail Pesanan</h2>
                                    <p class="text-green-100 text-xs sm:text-sm">Periksa kembali detail pesanan Anda</p>
                                </div>
                                <div class="hidden sm:block">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 text-green-300 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                            <div class="flex items-start justify-between py-3 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm text-gray-500">No. Pesanan</p>
                                        <p class="font-semibold text-sm sm:text-base text-gray-900">{{ $order->order_number }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start justify-between py-3 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm text-gray-500">Nama Pemesan</p>
                                        <p class="font-semibold text-sm sm:text-base text-gray-900">{{ $order->customer_name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start justify-between py-3 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm text-gray-500">No WhatsApp</p>
                                        <p class="font-semibold text-sm sm:text-base text-gray-900">{{ $order->whatsapp_number }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start justify-between py-3 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm text-gray-500">Promo</p>
                                        <p class="font-semibold text-sm sm:text-base text-gray-900">{{ $promo->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start justify-between py-3 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm text-gray-500">Jumlah Tiket</p>
                                        <p class="font-semibold text-sm sm:text-base text-gray-900">{{ $order->ticket_quantity }} Tiket</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start justify-between py-3 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm text-gray-500">Tanggal Kunjungan</p>
                                        <p class="font-semibold text-sm sm:text-base text-gray-900">{{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 sm:p-5 mt-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Total Pembayaran</p>
                                        <p class="text-2xl sm:text-3xl font-bold text-green-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-green-200 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- QRIS Payment -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl card-shadow overflow-hidden sticky top-24">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 sm:p-5 text-center">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white rounded-full mx-auto mb-3 flex items-center justify-center">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg sm:text-xl font-bold text-white">Scan QRIS</h2>
                            <p class="text-blue-100 text-xs sm:text-sm mt-1">Bayar dengan berbagai aplikasi</p>
                        </div>
                        
                        <div class="p-4 sm:p-6">
                            <div id="snap-container" class="bg-gray-50 rounded-xl p-3 sm:p-4 border-2 border-dashed border-gray-300">
                                @if($snapToken)
                                    <div class="flex justify-center items-center min-h-[200px]">
                                        <div class="text-center pulse-soft">
                                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                            </svg>
                                            <p class="text-xs sm:text-sm text-gray-500">Memuat kode QRIS...</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-6 sm:py-8">
                                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-red-600 font-semibold text-sm sm:text-base">Gagal memuat QRIS</p>
                                        <p class="text-gray-500 text-xs sm:text-sm mt-1">Silakan refresh halaman</p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-4 sm:mt-6 space-y-3">
                                <div class="bg-blue-50 rounded-lg p-3 sm:p-4">
                                    <div class="flex items-start space-x-2 sm:space-x-3">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-xs sm:text-sm text-blue-900">Cara Pembayaran:</p>
                                            <ul class="mt-1 sm:mt-2 space-y-1 text-xs text-blue-800">
                                                <li>1. Buka aplikasi e-wallet/mobile banking</li>
                                                <li>2. Pilih menu scan QR</li>
                                                <li>3. Scan kode QRIS di atas</li>
                                                <li>4. Konfirmasi pembayaran</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                                    <p class="text-xs text-yellow-800 text-center">
                                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        Pembayaran otomatis terverifikasi
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tombol Batalkan -->
                    <div class="mt-4">
                        <a href="{{ route('payment.unfinish') }}?order_id={{ $order->order_number }}" 
                           class="w-full flex items-center justify-center px-4 sm:px-6 py-3 border-2 border-gray-300 rounded-xl text-sm sm:text-base font-semibold text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 card-shadow">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batalkan Pesanan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info Footer -->
            <div class="mt-6 sm:mt-8 text-center">
                <div class="inline-flex items-center space-x-2 text-xs sm:text-sm text-gray-600 bg-white px-4 sm:px-6 py-2 sm:py-3 rounded-full card-shadow">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Transaksi Anda dilindungi dengan enkripsi SSL</span>
                </div>
            </div>
        </div>
    </main>

    <!-- Script Midtrans -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if($snapToken)
                window.snap.embed("{{ $snapToken }}", {
                    embedId: "snap-container",
                    onSuccess: function(result){
                        console.log("success", result);
                        window.location.href = "{{ route('payment.finish') }}?order_id={{ $order->order_number }}";
                    },
                    onPending: function(result){
                        console.log("pending", result);
                        window.location.href = "{{ route('payment.unfinish') }}?order_id={{ $order->order_number }}";
                    },
                    onError: function(result){
                        console.log("error", result);
                        window.location.href = "{{ route('payment.error') }}?order_id={{ $order->order_number }}";
                    },
                    onClose: function(){
                        console.log("customer closed the popup without finishing the payment");
                        window.location.href = "{{ route('payment.unfinish') }}?order_id={{ $order->order_number }}";
                    }
                });
            @endif
        });
    </script>
</body>
</html>