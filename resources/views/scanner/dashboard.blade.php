<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner Dashboard - MestaKara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Library untuk barcode linear -->
    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: #00ff00;
            animation: scan 2s infinite;
        }
        @keyframes scan {
            0% { transform: translateY(-100px); }
            50% { transform: translateY(100px); }
            100% { transform: translateY(-100px); }
        }
        .barcode-type-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
        .scanning-mode {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 10px;
            margin: 10px 0;
        }
        .shake {
            animation: shake 0.5s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        #quagga-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="gradient-bg text-white p-3 sm:p-4 shadow-lg">
        <div class="max-w-7xl mx-auto flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center space-x-2 sm:space-x-4">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-base sm:text-xl font-bold">MestaKara Scanner</h1>
                    <p class="text-xs sm:text-sm opacity-90">Dashboard Petugas</p>
                </div>
            </div>
            <div class="flex items-center space-x-2 sm:space-x-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm opacity-90">Status: Online</p>
                    <p class="text-xs opacity-75" id="current-time"></p>
                </div>
                
                @if(isset($hasVoucherAccess) && $hasVoucherAccess)
                <a href="{{ route('voucher.scanner.dashboard') }}" 
                class="bg-yellow-500 hover:bg-yellow-600 px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors text-gray-800">
                    Scanner Voucher
                </a>
                @endif
                
                <a href="{{ route('scanner.logout') }}" 
                class="bg-red-500 hover:bg-red-600 px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors">
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
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Tiket Digunakan Hari Ini</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900" id="today-used">{{ $todayUsed }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Total Tiket Hari Ini</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $todayTotal }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-600">Tingkat Penggunaan</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $todayTotal > 0 ? round(($todayUsed / $todayTotal) * 100) : 0 }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-8">
            <!-- Scanner Section -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">Barcode Scanner</h2>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 sm:w-3 sm:h-3 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-xs sm:text-sm text-green-600 font-medium">Ready</span>
                    </div>
                </div>

                <!-- Manual Input -->
                <div class="mb-4 sm:mb-6">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">
                        Input Manual Barcode
                    </label>
                    <div class="flex space-x-2">
                        <input 
                            type="text" 
                            id="manual-barcode"
                            class="flex-1 px-3 py-2 sm:px-4 sm:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Scan/ketik barcode"
                            autocomplete="off"
                        >
                        <button 
                            onclick="scanManualBarcode()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg font-medium transition-colors flex-shrink-0"
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
                        <div class="flex space-x-2">
                            <select id="camera-select" class="border rounded px-2 py-1 text-xs sm:text-sm hidden">
                                <option value="">Pilih Kamera</option>
                            </select>
                            <button 
                                id="toggle-camera"
                                onclick="toggleCamera()"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 sm:px-3 sm:py-1 rounded text-xs sm:text-sm font-medium transition-colors"
                            >
                                Start Camera
                            </button>
                        </div>
                    </div>
                    
                    <div class="scanner-container aspect-video" id="scanner-container" style="display: none;">
                        <video id="camera" autoplay playsinline class="w-full h-full object-cover"></video>
                        <div id="quagga-overlay"></div>
                        
                        <div class="scanner-overlay">
                            <div class="scanner-line" id="barcode-overlay"></div>
                        </div>
                        
                        <div class="barcode-type-indicator text-xs" id="barcode-type">
                            Mode: Barcode Linear
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-2">
                        <strong>Barcode:</strong> Arahkan kamera ke barcode linear (garis-garis)
                    </p>
                </div>

                <!-- Scan Status -->
                <div id="scan-status" class="hidden p-2 sm:p-3 rounded-lg mb-4 text-sm"></div>
            </div>

            <!-- Ticket Detail Section -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Detail Tiket</h2>
                
                <div id="ticket-detail" class="hidden">
                    <div class="border rounded-lg p-3 sm:p-4 bg-gray-50">
                        <div class="grid grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm">
                            <div>
                                <p class="text-gray-600">No. Order</p>
                                <p class="font-medium break-all" id="detail-order-number">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Status</p>
                                <p class="font-medium" id="detail-status">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Nama Pelanggan</p>
                                <p class="font-medium break-words" id="detail-customer-name">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">WhatsApp</p>
                                <p class="font-medium" id="detail-whatsapp">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Tanggal Kunjungan</p>
                                <p class="font-medium" id="detail-visit-date">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Jumlah Tiket</p>
                                <p class="font-medium" id="detail-quantity">-</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-600">Nama Promo</p>
                                <p class="font-medium break-words" id="detail-promo-name">-</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-600">Total Harga</p>
                                <p class="font-bold text-base sm:text-lg text-green-600" id="detail-total-price">-</p>
                            </div>
                        </div>

                        <div class="mt-4 sm:mt-6 pt-3 sm:pt-4 border-t">
                            <button 
                                id="use-ticket-btn"
                                onclick="useTicket()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 sm:py-3 px-4 rounded-lg transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed text-sm sm:text-base"
                                disabled
                            >
                                ✅ Gunakan Tiket
                            </button>
                        </div>
                    </div>
                </div>

                <div id="no-ticket" class="text-center py-8 sm:py-12">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm">Scan barcode untuk melihat detail tiket</p>
                </div>
            </div>
        </div>

        <!-- Recent Scans -->
        <div class="mt-6 sm:mt-8 bg-white rounded-xl shadow-lg p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Scan Terakhir Hari Ini</h2>
            
            @if(count($recentScans) > 0)
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="text-left p-2 sm:p-3 text-xs sm:text-sm font-medium text-gray-700">Order</th>
                                <th class="text-left p-2 sm:p-3 text-xs sm:text-sm font-medium text-gray-700">Nama</th>
                                <th class="text-left p-2 sm:p-3 text-xs sm:text-sm font-medium text-gray-700 hidden sm:table-cell">Promo</th>
                                <th class="text-left p-2 sm:p-3 text-xs sm:text-sm font-medium text-gray-700">Qty</th>
                                <th class="text-left p-2 sm:p-3 text-xs sm:text-sm font-medium text-gray-700">Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="recent-scans-body">
                            @foreach($recentScans as $scan)
                            <tr class="border-b">
                                <td class="p-2 sm:p-3 text-xs sm:text-sm">{{ $scan->order_number }}</td>
                                <td class="p-2 sm:p-3 text-xs sm:text-sm">{{ $scan->customer_name }}</td>
                                <td class="p-2 sm:p-3 text-xs sm:text-sm hidden sm:table-cell">{{ $scan->promo ? $scan->promo->name : 'Unknown' }}</td>
                                <td class="p-2 sm:p-3 text-xs sm:text-sm">{{ $scan->ticket_quantity }}</td>
                                <td class="p-2 sm:p-3 text-xs sm:text-sm">
                                    @if(isset($scan->used_at))
                                        {{ \Carbon\Carbon::parse($scan->used_at)->timezone('Asia/Jakarta')->translatedFormat('d M, H:i') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($scan->updated_at)->timezone('Asia/Jakarta')->translatedFormat('d M, H:i') }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="text-center py-6 sm:py-8">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <p class="text-gray-500 text-sm">Belum ada tiket yang di-scan hari ini</p>
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
                <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Tiket Berhasil Digunakan!</h3>
                <div id="success-message" class="text-sm sm:text-base">Selamat datang!</div>
                <button onclick="closeSuccessModal()" class="mt-4 bg-green-600 text-white px-4 sm:px-6 py-2 rounded-lg font-medium text-sm sm:text-base">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        let camera = null;
        let scanning = false;
        let currentOrderNumber = null;
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
            const barcode = document.getElementById('manual-barcode').value.trim();
            if (!barcode) {
                showScanStatus('Mohon masukkan barcode!', 'error');
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

        // Get available cameras
        async function getCameras() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');
                
                const select = document.getElementById('camera-select');
                select.innerHTML = '<option value="">Pilih Kamera</option>';
                
                videoDevices.forEach((device, index) => {
                    const option = document.createElement('option');
                    option.value = device.deviceId;
                    option.text = device.label || `Kamera ${index + 1}`;
                    select.appendChild(option);
                });
                
                select.classList.remove('hidden');
            } catch (error) {
                console.error('Error getting cameras:', error);
            }
        }

        // Toggle camera
        async function toggleCamera() {
            const button = document.getElementById('toggle-camera');
            const container = document.getElementById('scanner-container');
            
            if (camera) {
                // Stop camera
                stopAllScanners();
                button.textContent = 'Start Camera';
                button.className = 'bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 sm:px-3 sm:py-1 rounded text-xs sm:text-sm font-medium transition-colors';
                container.style.display = 'none';
            } else {
                // Start camera
                try {
                    await getCameras();
                    const select = document.getElementById('camera-select');
                    const cameraId = select.value || null;
                    
                    const constraints = {
                        video: { 
                            deviceId: cameraId ? { exact: cameraId } : undefined,
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
                    
                    // Fallback: coba tanpa constraints spesifik
                    try {
                        camera = await navigator.mediaDevices.getUserMedia({ video: true });
                        const video = document.getElementById('camera');
                        video.srcObject = camera;
                        
                        button.textContent = 'Stop Camera';
                        button.className = 'bg-red-600 hover:bg-red-700 text-white px-2 py-1 sm:px-3 sm:py-1 rounded text-xs sm:text-sm font-medium transition-colors';
                        container.style.display = 'block';
                        
                        scanning = true;
                        startScanner();
                    } catch (fallbackError) {
                        console.error('Fallback camera error:', fallbackError);
                        showScanStatus('Gagal mengakses kamera. Gunakan input manual.', 'error');
                    }
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
            
            scanBarcodeLinear();
        }

        // Scan Barcode Linear
        function scanBarcodeLinear() {
            if (!scanning) return;
            
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
            // Normalize barcode
            barcode = barcode.trim().replace(/\s+/g, '');
            
            // Skip jika barcode terlalu pendek
            if (barcode.length < 3) {
                return;
            }
            
            document.getElementById('manual-barcode').value = barcode;
            showScanStatus('Memproses barcode...', 'loading');
            
            try {
                const response = await fetch('{{ route("scanner.scan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ barcode: barcode })
                });
                
                const result = await response.json();
                
                if (result.success && result.order) {
                    showTicketDetail(result.order);
                    showScanStatus('✅ ' + result.message, 'success');
                    currentOrderNumber = result.order.order_number;
                    
                    // Berhenti scan sebentar setelah berhasil
                    setTimeout(() => {
                        if (scanning) {
                            startScanner(); // Lanjutkan scanning
                        }
                    }, 2000);
                } else {
                    showScanStatus('❌ ' + result.message, 'error');
                    hideTicketDetail();
                    currentOrderNumber = null;
                    
                    // Lanjutkan scanning setelah error
                    setTimeout(() => {
                        if (scanning) {
                            startScanner();
                        }
                    }, 1000);
                }
            } catch (error) {
                console.error('Scan error:', error);
                showScanStatus('❌ Terjadi kesalahan saat memproses barcode', 'error');
                hideTicketDetail();
                currentOrderNumber = null;
                
                setTimeout(() => {
                    if (scanning) {
                        startScanner();
                    }
                }, 1000);
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

        // Show ticket detail
        function showTicketDetail(order) {
            document.getElementById('ticket-detail').classList.remove('hidden');
            document.getElementById('no-ticket').classList.add('hidden');
            
            document.getElementById('detail-order-number').textContent = order.order_number;
            document.getElementById('detail-customer-name').textContent = order.customer_name;
            document.getElementById('detail-whatsapp').textContent = order.whatsapp_number;
            document.getElementById('detail-visit-date').textContent = order.visit_date;
            document.getElementById('detail-quantity').textContent = order.ticket_quantity;
            document.getElementById('detail-promo-name').textContent = order.promo_name;
            document.getElementById('detail-total-price').textContent = 'Rp ' + parseInt(order.total_price).toLocaleString('id-ID');
            
            const statusElement = document.getElementById('detail-status');
            const useButton = document.getElementById('use-ticket-btn');
            
            if (order.status === 'success') {
                statusElement.textContent = 'Valid';
                statusElement.className = 'font-medium text-green-600';
                useButton.disabled = false;
                useButton.className = 'w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 sm:py-3 px-4 rounded-lg transition-colors text-sm sm:text-base';
            } else if (order.status === 'used' || order.status === 'expired') {
                statusElement.textContent = 'Sudah Digunakan';
                statusElement.className = 'font-medium text-red-600';
                useButton.disabled = true;
                useButton.className = 'w-full bg-gray-400 cursor-not-allowed text-white font-bold py-2.5 sm:py-3 px-4 rounded-lg text-sm sm:text-base';
                useButton.textContent = '❌ Tiket Sudah Digunakan';
            } else {
                statusElement.textContent = order.status;
                statusElement.className = 'font-medium text-red-600';
                useButton.disabled = true;
                useButton.className = 'w-full bg-gray-400 cursor-not-allowed text-white font-bold py-2.5 sm:py-3 px-4 rounded-lg text-sm sm:text-base';
                useButton.textContent = '❌ Tiket Tidak Valid';
            }
        }

        // Hide ticket detail
        function hideTicketDetail() {
            document.getElementById('ticket-detail').classList.add('hidden');
            document.getElementById('no-ticket').classList.remove('hidden');
        }

        // Use ticket - UPDATED VERSION WITH PRINT SUPPORT
        async function useTicket() {
            if (!currentOrderNumber) return;
            
            const button = document.getElementById('use-ticket-btn');
            const originalText = button.textContent;
            
            button.disabled = true;
            button.textContent = 'Memproses...';
            
            try {
                const response = await fetch('{{ route("scanner.use") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ order_number: currentOrderNumber })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Update UI
                    document.getElementById('detail-status').textContent = 'Sudah Digunakan';
                    document.getElementById('detail-status').className = 'font-medium text-red-600';
                    button.textContent = '❌ Tiket Sudah Digunakan';
                    button.className = 'w-full bg-gray-400 cursor-not-allowed text-white font-bold py-2.5 sm:py-3 px-4 rounded-lg text-sm sm:text-base';
                    
                    // Show success modal with print option
                    showSuccessWithPrint(result);
                    
                    // Update stats
                    const todayUsedElement = document.getElementById('today-used');
                    todayUsedElement.textContent = parseInt(todayUsedElement.textContent) + 1;
                    
                    // Add to recent scans table
                    addToRecentScans(result.order);
                    
                    // Clear manual input
                    document.getElementById('manual-barcode').value = '';
                    
                } else {
                    showScanStatus(result.message, 'error');
                    button.disabled = false;
                    button.textContent = originalText;
                }
            } catch (error) {
                console.error('Use ticket error:', error);
                showScanStatus('Terjadi kesalahan saat memproses tiket', 'error');
                button.disabled = false;
                button.textContent = originalText;
            }
        }

        // Show success modal with print option
        function showSuccessWithPrint(result) {
            const modal = document.getElementById('success-modal');
            const messageElement = document.getElementById('success-message');
            
            messageElement.innerHTML = `
                <div class="text-center">
                    <p class="mb-4">${result.message}</p>
                    ${result.print_url ? `
                        <div class="bg-blue-50 p-3 rounded-lg mb-4">
                            <p class="text-xs sm:text-sm text-blue-800 mb-2">🎫 Siap mencetak tiket gelang!</p>
                            <div class="flex flex-col sm:flex-row gap-2 justify-center">
                                <button onclick="printBraceletTickets('${result.print_url}')" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium">
                                    📄 Cetak Tiket Gelang
                                </button>
                                <button onclick="openPrintInNewTab('${result.print_url}')" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium">
                                    🔗 Buka di Tab Baru
                                </button>
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Print bracelet tickets
        function printBraceletTickets(printUrl) {
            // Open print URL in new window/tab for printing
            const printWindow = window.open(printUrl, '_blank');
            if (printWindow) {
                printWindow.focus();
                // Auto-trigger print dialog after content loads
                printWindow.onload = function() {
                    setTimeout(() => {
                        printWindow.print();
                    }, 1000);
                };
            } else {
                // Fallback if popup is blocked
                showScanStatus('Pop-up diblokir! Silakan buka link cetak secara manual.', 'error');
            }
        }

        // Open print in new tab
        function openPrintInNewTab(printUrl) {
            window.open(printUrl, '_blank');
        }

        // Add to recent scans table
        function addToRecentScans(order) {
            const tbody = document.getElementById('recent-scans-body');
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            const row = document.createElement('tr');
            row.className = 'border-b bg-green-50';
            row.innerHTML = `
                <td class="p-2 sm:p-3 text-xs sm:text-sm">${order.order_number}</td>
                <td class="p-2 sm:p-3 text-xs sm:text-sm">${order.customer_name}</td>
                <td class="p-2 sm:p-3 text-xs sm:text-sm hidden sm:table-cell">-</td>
                <td class="p-2 sm:p-3 text-xs sm:text-sm">${order.ticket_quantity}</td>
                <td class="p-2 sm:p-3 text-xs sm:text-sm">${timeStr}</td>
            `;
            
            tbody.insertBefore(row, tbody.firstChild);
            
            // Remove green background after 3 seconds
            setTimeout(() => {
                row.classList.remove('bg-green-50');
            }, 3000);
        }

        // Close success modal
        function closeSuccessModal() {
            document.getElementById('success-modal').classList.add('hidden');
            document.getElementById('success-modal').classList.remove('flex');
            
            // Reset for next scan
            hideTicketDetail();
            currentOrderNumber = null;
            showScanStatus('Siap untuk scan berikutnya', 'success');
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