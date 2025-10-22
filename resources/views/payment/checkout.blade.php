<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - MestaKara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .glass-dark {
            background: rgba(102, 126, 234, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-green {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-gold {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .glow-box {
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
        }
        
        .qris-container {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4);
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(102, 126, 234, 0.4); }
            50% { box-shadow: 0 0 40px rgba(102, 126, 234, 0.8); }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        .detail-card {
            transition: all 0.3s ease;
        }
        
        .detail-card:hover {
            transform: translateX(5px);
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        }
        
        .icon-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .total-badge {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 10px 30px rgba(245, 87, 108, 0.4);
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 107, 0.4);
        }
        
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        
        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }
        
        .step-indicator {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
    </style>
</head>
<body class="font-poppins min-h-screen">
    <!-- Decorative Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-20 left-1/2 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- Navbar -->
    <nav class="relative z-50 w-full py-4 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="glass-effect rounded-2xl px-6 py-4 flex items-center justify-between glow-box">
                <a href="/" class="text-2xl sm:text-3xl font-bold text-transparent bg-clip-text gradient-green flex items-center">
                    Mesta<span class="text-yellow-400">Kara</span><span class="text-purple-600">.</span>
                </a>
                <div class="hidden sm:flex items-center space-x-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-2 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="font-semibold text-sm">Pembayaran Aman</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="relative z-10 pt-6 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header with Steps -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center space-x-2 sm:space-x-4 mb-6 bg-white/20 backdrop-blur-lg rounded-full px-6 py-3">
                    <div class="step-indicator text-white px-4 py-2 rounded-full text-sm font-bold">1</div>
                    <div class="hidden sm:block w-12 h-1 bg-white/40 rounded"></div>
                    <div class="step-indicator text-white px-4 py-2 rounded-full text-sm font-bold">2</div>
                    <div class="hidden sm:block w-12 h-1 bg-white/40 rounded"></div>
                    <div class="bg-white/30 text-white px-4 py-2 rounded-full text-sm font-bold">3</div>
                </div>
                
                <div class="float-animation inline-block mb-4">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-br from-purple-400 to-pink-400 rounded-3xl flex items-center justify-center transform rotate-12 glow-box">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-white transform -rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>
                
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-3 tracking-tight">
                    Selesaikan Pembayaran
                </h1>
                <p class="text-base sm:text-lg text-purple-100 max-w-2xl mx-auto">
                    Scan kode QRIS untuk menyelesaikan transaksi Anda dengan aman dan cepat
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Detail Pesanan -->
                <div class="lg:col-span-2">
                    <div class="glass-effect rounded-3xl overflow-hidden glow-box">
                        <div class="gradient-green p-6 sm:p-8 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
                            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
                            
                            <div class="relative">
                                <div class="flex items-center justify-between mb-2">
                                    <h2 class="text-2xl sm:text-3xl font-bold text-white">Detail Pesanan</h2>
                                    <div class="bg-white/20 px-4 py-2 rounded-full">
                                        <span class="text-white font-semibold text-sm">Order #{{ $order->order_number }}</span>
                                    </div>
                                </div>
                                <p class="text-purple-100">Verifikasi informasi pemesanan Anda</p>
                            </div>
                        </div>
                        
                        <div class="p-6 sm:p-8 space-y-4">
                            <div class="detail-card flex items-center space-x-4 p-4 rounded-2xl">
                                <div class="icon-wrapper w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 font-medium">Nama Pemesan</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $order->customer_name }}</p>
                                </div>
                            </div>

                            <div class="detail-card flex items-center space-x-4 p-4 rounded-2xl">
                                <div class="icon-wrapper w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 font-medium">No WhatsApp</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $order->whatsapp_number }}</p>
                                </div>
                            </div>

                            <div class="detail-card flex items-center space-x-4 p-4 rounded-2xl">
                                <div class="icon-wrapper w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 font-medium">Promo Aktif</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $promo->name }}</p>
                                </div>
                            </div>

                            <div class="detail-card flex items-center space-x-4 p-4 rounded-2xl">
                                <div class="icon-wrapper w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 font-medium">Jumlah Tiket</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $order->ticket_quantity }} Tiket</p>
                                </div>
                            </div>

                            <div class="detail-card flex items-center space-x-4 p-4 rounded-2xl">
                                <div class="icon-wrapper w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-500 font-medium">Tanggal Kunjungan</p>
                                    <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="total-badge rounded-3xl p-6 mt-6 transform hover:scale-105 transition-transform">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-white/90 text-sm font-medium mb-1">Total Pembayaran</p>
                                        <p class="text-3xl sm:text-4xl font-extrabold text-white">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="sticky top-6 space-y-4">
                        <div class="qris-container rounded-3xl overflow-hidden pulse-glow">
                            <div class="gradient-gold p-6 text-center relative overflow-hidden">
                                <div class="absolute inset-0 bg-white/10 transform -skew-y-6"></div>
                                <div class="relative">
                                    <div class="w-16 h-16 bg-white rounded-2xl mx-auto mb-3 flex items-center justify-center transform rotate-6">
                                        <svg class="w-8 h-8 text-pink-600 transform -rotate-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                        </svg>
                                    </div>
                                    <h2 class="text-2xl font-bold text-white mb-1">Scan QRIS</h2>
                                    <p class="text-pink-100 text-sm">Bayar dengan mudah & cepat</p>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div id="snap-container" class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-4 border-2 border-dashed border-purple-300 min-h-[280px] flex items-center justify-center">
                                    @if($snapToken)
                                        <div class="text-center">
                                            <div class="shimmer w-16 h-16 bg-purple-200 rounded-full mx-auto mb-3"></div>
                                            <p class="text-sm text-purple-600 font-medium">Memuat kode QRIS...</p>
                                        </div>
                                    @else
                                        <div class="text-center py-8">
                                            <svg class="w-16 h-16 text-red-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-red-600 font-bold">Gagal Memuat QRIS</p>
                                            <p class="text-gray-500 text-sm mt-1">Refresh halaman ini</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="mt-6 space-y-3">
                                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl p-4 border border-purple-200">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="font-bold text-purple-900 mb-2">Cara Bayar:</p>
                                                <ol class="space-y-1 text-sm text-purple-800">
                                                    <li class="flex items-start">
                                                        <span class="font-bold mr-2">1.</span>
                                                        <span>Buka aplikasi e-wallet/m-banking</span>
                                                    </li>
                                                    <li class="flex items-start">
                                                        <span class="font-bold mr-2">2.</span>
                                                        <span>Pilih menu Scan QR Code</span>
                                                    </li>
                                                    <li class="flex items-start">
                                                        <span class="font-bold mr-2">3.</span>
                                                        <span>Scan kode QRIS di atas</span>
                                                    </li>
                                                    <li class="flex items-start">
                                                        <span class="font-bold mr-2">4.</span>
                                                        <span>Konfirmasi & selesai!</span>
                                                    </li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-3 border border-green-200 text-center">
                                        <div class="flex items-center justify-center space-x-2 text-green-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            <span class="text-sm font-bold">Verifikasi Otomatis</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tombol Batalkan -->
                        <a href="{{ route('payment.unfinish') }}?order_id={{ $order->order_number }}" 
                           class="btn-cancel w-full flex items-center justify-center px-6 py-4 rounded-2xl text-white font-bold text-lg shadow-2xl">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batalkan Pesanan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Trust Badges -->
            <div class="mt-12 text-center">
                <div class="inline-flex flex-wrap items-center justify-center gap-4 sm:gap-6 glass-effect rounded-2xl px-6 py-4">
                    <div class="flex items-center space-x-2">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">SSL Encrypted</span>
                    </div>
                    <div class="hidden sm:block w-px h-6 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">100% Aman</span>
                    </div>
                    <div class="hidden sm:block w-px h-6 bg-gray-300"></div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">Proses Instan</span>
                    </div>
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