<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher Scanner Dashboard - MestaKara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #CFD916 0%, #9DB91C 100%);
        }
        .scanner-container {
            position: relative;
            background: #000;
            border-radius: 12px;
            overflow: hidden;
        }
        .scanner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .scanner-line {
            width: 200px;
            height: 3px;
            background: #CFD916;
            animation: scan 2s infinite;
        }
        @keyframes scan {
            0% { transform: translateY(-100px); }
            50% { transform: translateY(100px); }
            100% { transform: translateY(-100px); }
        }
        .shake {
            animation: shake 0.5s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="gradient-bg text-gray-800 p-3 sm:p-4 shadow-lg">
        <div class="max-w-7xl mx-auto flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center space-x-2 sm:space-x-4">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-base sm:text-xl font-bold">Voucher Scanner</h1>
                    <p class="text-xs sm:text-sm opacity-90">{{ $staffName }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 sm:space-x-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm opacity-90">Status: Online</p>
                    <p class="text-xs opacity-75" id="current-time"></p>
                </div>
                <a href="{{ route('scanner.dashboard') }}" 
                   class="bg-blue-600 hover:bg-blue-700 px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors text-white">
                    Dashboard Tiket
                </a>
                <a href="{{ route('scanner.logout') }}" 
                   class="bg-red-500 hover:bg-red-600 px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors text-white">
                    Logout
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto p-3 sm:p-4">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6 mb-6 sm:mb-8">
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Voucher Digunakan Hari Ini</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900" id="today-scanned">{{ $todayScanned }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Total Claim Hari Ini</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $todayTotal }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Tingkat Penggunaan</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $todayTotal > 0 ? round(($todayScanned / $todayTotal) * 100) : 0 }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8">
            <!-- Scanner Section -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">Voucher Scanner</h2>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 sm:w-3 sm:h-3 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-xs sm:text-sm text-green-600 font-medium">Ready</span>
                    </div>
                </div>

                <!-- Manual Input -->
                <div class="mb-4 sm:mb-6">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                        Input Kode Voucher
                    </label>
                    <div class="flex space-x-2">
                        <input 
                            type="text" 
                            id="manual-barcode"
                            class="flex-1 px-3 py-2 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm uppercase"
                            placeholder="Scan/ketik kode voucher"
                            autocomplete="off"
                        >
                        <button 
                            onclick="scanManualBarcode()"
                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg font-medium transition-colors flex-shrink-0"
                        >
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Camera Scanner -->
                <div class="mb-4 sm:mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">
                            Scanner Kamera
                        </label>
                        <button 
                            id="toggle-camera"
                            onclick="toggleCamera()"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 sm:px-3 sm:py-1 rounded text-xs sm:text-sm font-medium transition-colors"
                        >
                            Start Camera
                        </button>
                    </div>
                    
                    <div class="scanner-container aspect-video" id="scanner-container" style="display: none;">
                        <video id="camera" autoplay playsinline class="w-full h-full object-cover"></video>
                        <div id="quagga-overlay"></div>
                        
                        <div class="scanner-overlay">
                            <div class="scanner-line" id="barcode-overlay"></div>
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-2">
                        Arahkan kamera ke barcode voucher untuk scan otomatis
                    </p>
                </div>

                <!-- Scan Status -->
                <div id="scan-status" class="hidden p-2 sm:p-3 rounded-lg mb-4 text-sm"></div>
            </div>

            <!-- Voucher Detail Section -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Detail Voucher</h2>
                
                <div id="voucher-detail" class="hidden">
                    <div class="border rounded-lg p-3 sm:p-4 bg-gradient-to-br from-yellow-50 to-yellow-100">
                        <div class="grid grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm">
                            <div class="col-span-2">
                                <p class="text-gray-600">Kode Voucher</p>
                                <p class="font-mono font-bold text-lg break-all" id="detail-code">-</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-600">Nama Voucher</p>
                                <p class="font-bold text-base break-words" id="detail-voucher-name">-</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-600">Deskripsi</p>
                                <p class="text-sm break-words" id="detail-description">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Nama Pelanggan</p>
                                <p class="font-medium break-words" id="detail-customer-name">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">No. Telepon</p>
                                <p class="font-medium" id="detail-phone">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Tanggal Claim</p>
                                <p class="font-medium" id="detail-claimed-date">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Berlaku Hingga</p>
                                <p class="font-medium" id="detail-expiry-date">-</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-600">Status</p>
                                <p class="font-medium" id="detail-status">-</p>
                            </div>
                        </div>

                        <div class="mt-4 sm:mt-6 pt-3 sm:pt-4 border-t border-yellow-300">
                            <button 
                                id="use-voucher-btn"
                                onclick="useVoucher()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 sm:py-3 px-4 rounded-lg transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed text-sm sm:text-base"
                                disabled
                            >
                                ✅ Gunakan Voucher
                            </button>
                        </div>
                    </div>
                </div>

                <div id="no-voucher" class="text-center py-8 sm:py-12">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">Scan barcode untuk melihat detail voucher</p>
                </div>
            </div>
        </div>

        <!-- Recent Scans -->
        <div class="mt-6 sm:mt-8 bg-white rounded-xl shadow-lg p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Scan Terakhir Hari Ini</h2>
            
            @if(count($recentScans) > 0)
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="text-left p-2 sm:p-3 text-xs sm:text-sm font-medium text-gray-700">Kode</th>
                            <th class="text-left p-2 sm:p-3 text-xs sm:text-sm font-medium text-gray-700">Nama</th>
                            <th class="text-left p-2 sm:p-3 text-xs sm:text-sm font-medium text-gray-700 hidden sm:table-cell">Voucher</th>
                            <th class="text-left p-2 sm:p-3 text-xs sm:text-sm font-medium text-gray-700">Waktu</th>
                        </tr>
                    </thead>
                    <tbody id="recent-scans-body">
                        @foreach($recentScans as $scan)
                        <tr class="border-b">
                            <td class="p-2 sm:p-3 text-xs sm:text-sm font-mono">{{ $scan->unique_code }}</td>
                            <td class="p-2 sm:p-3 text-xs sm:text-sm">{{ $scan->user_name }}</td>
                            <td class="p-2 sm:p-3 text-xs sm:text-sm hidden sm:table-cell">{{ $scan->voucher ? $scan->voucher->name : 'Unknown' }}</td>
                            <td class="p-2 sm:p-3 text-xs sm:text-sm">
                                {{ \Carbon\Carbon::parse($scan->scanned_at)->timezone('Asia/Jakarta')->translatedFormat('d M, H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-6 sm:py-8">
                <p class="text-gray-500 text-sm">Belum ada voucher yang di-scan hari ini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl p-4 sm:p-6 max-w-md w-full">
            <div class="text-center">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Voucher Berhasil Digunakan!</h3>
                <div id="success-message" class="text-sm sm:text-base">Selamat menikmati promo!</div>
                <button onclick="closeSuccessModal()" class="mt-4 bg-green-600 text-white px-4 sm:px-6 py-2 rounded-lg font-medium text-sm sm:text-base">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        let camera = null;
        let scanning = false;
        let currentCode = null;
        let quaggaInitialized = false;

        // Update current time
        function updateCurrentTime() {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleTimeString('id-ID');
        }
        setInterval(updateCurrentTime, 1000);
        updateCurrentTime();

        // Manual barcode scan
        function scanManualBarcode() {
            const barcode = document.getElementById('manual-barcode').value.trim().toUpperCase();
            if (!barcode) {
                showScanStatus('Mohon masukkan kode voucher!', 'error');
                return;
            }
            processBarcode(barcode);
        }

        // Enter key untuk manual input
        document.getElementById('manual-barcode').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                scanManualBarcode();
            }
        });

        // Auto uppercase input
        document.getElementById('manual-barcode').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });

        // Toggle camera
        async function toggleCamera() {
            const button = document.getElementById('toggle-camera');
            const container = document.getElementById('scanner-container');
            
            if (camera) {
                stopAllScanners();
                button.textContent = 'Start Camera';
                button.className = 'bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 sm:px-3 sm:py-1 rounded text-xs sm:text-sm font-medium transition-colors';
                container.style.display = 'none';
            } else {
                try {
                    const constraints = {
                        video: { 
                            facingMode: { ideal: 'environment' },
                            width: { ideal: 1280 },
                            height: { ideal: 720 }
                        }
                    };
                    
                    camera = await navigator.mediaDevices.getUserMedia(constraints);
                    
                    const video = document.getElementById('camera');
                    video.srcObject = camera;
                    
                    button.textContent = 'Stop Camera';
                    button.className = 'bg-red-600 hover:bg-red-700 text-white px-2 py-1 sm:px-3 sm:py-1 rounded text-xs sm:text-sm font-medium transition-colors';
                    container.style.display = 'block';
                    
                    scanning = true;
                    startScanner();
                } catch (error) {
                    console.error('Camera access error:', error);
                    showScanStatus('Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.', 'error');
                }
            }
        }

        // Stop semua scanner
        function stopAllScanners() {
            scanning = false;
            
            if (camera) {
                camera.getTracks().forEach(track => track.stop());
                camera = null;
            }
            
            if (quaggaInitialized) {
                try {
                    Quagga.stop();
                } catch (e) {
                    console.log('Quagga already stopped');
                }
                quaggaInitialized = false;
            }
        }

        // Start scanner
        function startScanner() {
            if (!scanning || !camera) return;
            
            if (!quaggaInitialized) {
                Quagga.init({
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: document.querySelector('#camera'),
                        constraints: {
                            width: 640,
                            height: 480,
                            facingMode: "environment"
                        }
                    },
                    decoder: {
                        readers: [
                            "code_128_reader",
                            "ean_reader",
                            "ean_8_reader",
                            "code_39_reader",
                            "upc_reader",
                            "upc_e_reader"
                        ]
                    },
                    locate: true,
                    debug: false
                }, function(err) {
                    if (err) {
                        console.error('Quagga init error:', err);
                        showScanStatus('Gagal inisialisasi scanner barcode.', 'error');
                        return;
                    }
                    quaggaInitialized = true;
                    Quagga.start();
                    
                    Quagga.onDetected(function(result) {
                        if (result && result.codeResult && result.codeResult.code) {
                            const barcode = result.codeResult.code;
                            processBarcode(barcode);
                        }
                    });
                });
            }
        }

        // Process barcode
        async function processBarcode(barcode) {
            barcode = barcode.trim().toUpperCase();
            
            if (barcode.length < 3) {
                return;
            }
            
            document.getElementById('manual-barcode').value = barcode;
            showScanStatus('Memproses kode voucher...', 'loading');
            
            try {
                const response = await fetch('/voucher-scanner/scan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ barcode: barcode })
                });
                
                const result = await response.json();
                
                if (result.success && result.claim) {
                    showVoucherDetail(result.claim);
                    showScanStatus('✅ ' + result.message, 'success');
                    currentCode = result.claim.unique_code;
                } else {
                    showScanStatus('❌ ' + result.message, 'error');
                    hideVoucherDetail();
                    currentCode = null;
                }
            } catch (error) {
                console.error('Scan error:', error);
                showScanStatus('❌ Terjadi kesalahan saat memproses voucher', 'error');
                hideVoucherDetail();
                currentCode = null;
            }
        }

        // Show scan status
        function showScanStatus(message, type) {
            const statusDiv = document.getElementById('scan-status');
            statusDiv.className = 'p-2 sm:p-3 rounded-lg mb-4 text-sm';
            
            if (type === 'success') {
                statusDiv.className += ' bg-green-50 text-green-800 border border-green-200';
            } else if (type === 'error') {
                statusDiv.className += ' bg-red-50 text-red-800 border border-red-200';
                statusDiv.classList.add('shake');
                setTimeout(() => statusDiv.classList.remove('shake'), 500);
            } else if (type === 'loading') {
                statusDiv.className += ' bg-blue-50 text-blue-800 border border-blue-200';
            }
            
            statusDiv.innerHTML = message;
            statusDiv.classList.remove('hidden');
        }

        // Show voucher detail
        function showVoucherDetail(claim) {
            document.getElementById('voucher-detail').classList.remove('hidden');
            document.getElementById('no-voucher').classList.add('hidden');
            
            document.getElementById('detail-code').textContent = claim.unique_code;
            document.getElementById('detail-voucher-name').textContent = claim.voucher_name;
            document.getElementById('detail-description').textContent = claim.voucher_description || '-';
            document.getElementById('detail-customer-name').textContent = claim.user_name;
            document.getElementById('detail-phone').textContent = claim.user_phone;
            document.getElementById('detail-claimed-date').textContent = claim.claimed_at;
            document.getElementById('detail-expiry-date').textContent = claim.expiry_date;
            
            const statusElement = document.getElementById('detail-status');
            const useButton = document.getElementById('use-voucher-btn');
            
            if (claim.status === 'valid') {
                statusElement.innerHTML = '<span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">✓ Valid</span>';
                useButton.disabled = false;
                useButton.className = 'w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 sm:py-3 px-4 rounded-lg transition-colors text-sm sm:text-base';
            } else if (claim.status === 'used') {
                statusElement.innerHTML = '<span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">✗ Sudah Digunakan</span>';
                useButton.disabled = true;
                useButton.className = 'w-full bg-gray-400 cursor-not-allowed text-white font-bold py-2.5 sm:py-3 px-4 rounded-lg text-sm sm:text-base';
                useButton.textContent = '❌ Voucher Sudah Digunakan';
            } else {
                statusElement.innerHTML = '<span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">✗ ' + claim.status + '</span>';
                useButton.disabled = true;
                useButton.className = 'w-full bg-gray-400 cursor-not-allowed text-white font-bold py-2.5 sm:py-3 px-4 rounded-lg text-sm sm:text-base';
                useButton.textContent = '❌ Voucher Tidak Valid';
            }
        }

        // Hide voucher detail
        function hideVoucherDetail() {
            document.getElementById('voucher-detail').classList.add('hidden');
            document.getElementById('no-voucher').classList.remove('hidden');
        }

        // Use voucher
        async function useVoucher() {
            if (!currentCode) return;
            
            const button = document.getElementById('use-voucher-btn');
            const originalText = button.textContent;
            
            button.disabled = true;
            button.textContent = 'Memproses...';
            
            try {
                const response = await fetch('/voucher-scanner/use', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ unique_code: currentCode })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    document.getElementById('detail-status').innerHTML = '<span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">✗ Sudah Digunakan</span>';
                    button.textContent = '❌ Voucher Sudah Digunakan';
                    button.className = 'w-full bg-gray-400 cursor-not-allowed text-white font-bold py-2.5 sm:py-3 px-4 rounded-lg text-sm sm:text-base';
                    
                    showSuccessModal(result.message);
                    
                    // Update stats
                    const todayScannedElement = document.getElementById('today-scanned');
                    todayScannedElement.textContent = parseInt(todayScannedElement.textContent) + 1;
                    
                    // Add to recent scans table
                    addToRecentScans(result.claim);
                    
                    // Clear manual input
                    document.getElementById('manual-barcode').value = '';
                    
                } else {
                    showScanStatus(result.message, 'error');
                    button.disabled = false;
                    button.textContent = originalText;
                }
            } catch (error) {
                console.error('Use voucher error:', error);
                showScanStatus('Terjadi kesalahan saat memproses voucher', 'error');
                button.disabled = false;
                button.textContent = originalText;
            }
        }

        // Show success modal
        function showSuccessModal(message) {
            const modal = document.getElementById('success-modal');
            const messageElement = document.getElementById('success-message');
            
            messageElement.textContent = message;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Close success modal
        function closeSuccessModal() {
            document.getElementById('success-modal').classList.add('hidden');
            document.getElementById('success-modal').classList.remove('flex');
            
            hideVoucherDetail();
            currentCode = null;
            showScanStatus('Siap untuk scan berikutnya', 'success');
        }

        // Add to recent scans table
        function addToRecentScans(claim) {
            const tbody = document.getElementById('recent-scans-body');
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            const row = document.createElement('tr');
            row.className = 'border-b bg-green-50';
            row.innerHTML = `
                <td class="p-2 sm:p-3 text-xs sm:text-sm font-mono">${claim.unique_code}</td>
                <td class="p-2 sm:p-3 text-xs sm:text-sm">${claim.user_name}</td>
                <td class="p-2 sm:p-3 text-xs sm:text-sm hidden sm:table-cell">${claim.voucher_name}</td>
                <td class="p-2 sm:p-3 text-xs sm:text-sm">${timeStr}</td>
            `;
            
            tbody.insertBefore(row, tbody.firstChild);
            
            setTimeout(() => {
                row.classList.remove('bg-green-50');
            }, 3000);
        }

        // Auto-focus manual input
        document.getElementById('manual-barcode').focus();

        // Cleanup saat page unload
        window.addEventListener('beforeunload', function() {
            stopAllScanners();
        });

        // Handle page visibility change
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAllScanners();
            }
        });
    </script>
</body>
</html>