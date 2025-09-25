<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner Dashboard - MestaKara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
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
        }
        .scanner-box {
            width: 200px;
            height: 200px;
            border: 3px solid #00ff00;
            border-radius: 12px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { border-color: #00ff00; }
            50% { border-color: #00aa00; }
            100% { border-color: #00ff00; }
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
    <header class="gradient-bg text-white p-4 shadow-lg">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold">MestaKara Scanner</h1>
                    <p class="text-sm opacity-90">Dashboard Petugas</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right">
                    <p class="text-sm opacity-90">Status: Online</p>
                    <p class="text-xs opacity-75" id="current-time"></p>
                </div>
                <a href="{{ route('scanner.logout') }}" 
                   class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Logout
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto p-4">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tiket Digunakan Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900" id="today-used">{{ $todayUsed }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Tiket Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $todayTotal }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tingkat Penggunaan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $todayTotal > 0 ? round(($todayUsed / $todayTotal) * 100) : 0 }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Scanner Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Scanner Barcode</h2>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-sm text-green-600 font-medium">Ready</span>
                    </div>
                </div>

                <!-- Manual Input -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Input Manual Barcode
                    </label>
                    <div class="flex space-x-2">
                        <input 
                            type="text" 
                            id="manual-barcode"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ketik atau scan barcode di sini"
                            autocomplete="off"
                        >
                        <button 
                            onclick="scanManualBarcode()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Camera Scanner -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-gray-700">
                            Scanner Kamera
                        </label>
                        <button 
                            id="toggle-camera"
                            onclick="toggleCamera()"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors"
                        >
                            Start Camera
                        </button>
                    </div>
                    
                    <div class="scanner-container aspect-video" id="scanner-container" style="display: none;">
                        <video id="camera" autoplay playsinline class="w-full h-full object-cover"></video>
                        <canvas id="canvas" style="display: none;"></canvas>
                        <div class="scanner-overlay">
                            <div class="scanner-box"></div>
                        </div>
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-2">
                        Arahkan kamera ke barcode untuk scan otomatis
                    </p>
                </div>

                <!-- Scan Status -->
                <div id="scan-status" class="hidden p-3 rounded-lg mb-4"></div>
            </div>

            <!-- Ticket Detail Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Detail Tiket</h2>
                
                <div id="ticket-detail" class="hidden">
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">No. Order</p>
                                <p class="font-medium" id="detail-order-number">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Status</p>
                                <p class="font-medium" id="detail-status">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Nama Pelanggan</p>
                                <p class="font-medium" id="detail-customer-name">-</p>
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
                                <p class="font-medium" id="detail-promo-name">-</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-600">Total Harga</p>
                                <p class="font-bold text-lg text-green-600" id="detail-total-price">-</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t">
                            <button 
                                id="use-ticket-btn"
                                onclick="useTicket()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                                disabled
                            >
                                ✅ Gunakan Tiket
                            </button>
                        </div>
                    </div>
                </div>

                <div id="no-ticket" class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500">Scan barcode untuk melihat detail tiket</p>
                </div>
            </div>
        </div>

        <!-- Recent Scans -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Scan Terakhir Hari Ini</h2>
            
            @if(count($recentScans) > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="text-left p-3 text-sm font-medium text-gray-700">Order</th>
                            <th class="text-left p-3 text-sm font-medium text-gray-700">Nama</th>
                            <th class="text-left p-3 text-sm font-medium text-gray-700">Promo</th>
                            <th class="text-left p-3 text-sm font-medium text-gray-700">Qty</th>
                            <th class="text-left p-3 text-sm font-medium text-gray-700">Waktu Digunakan</th>
                        </tr>
                    </thead>
                    <tbody id="recent-scans-body">
                        @foreach($recentScans as $scan)
                        <tr class="border-b">
                            <td class="p-3 text-sm">{{ $scan->order_number }}</td>
                            <td class="p-3 text-sm">{{ $scan->customer_name }}</td>
                            <td class="p-3 text-sm">{{ $scan->promo->name }}</td>
                            <td class="p-3 text-sm">{{ $scan->ticket_quantity }}</td>
                            <td class="p-3 text-sm">{{ \Carbon\Carbon::parse($scan->used_at)->format('H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <p class="text-gray-500">Belum ada tiket yang di-scan hari ini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 mx-4 max-w-md w-full">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Tiket Berhasil Digunakan!</h3>
                <p class="text-gray-600 mb-4" id="success-message">Selamat datang!</p>
                <button onclick="closeSuccessModal()" class="bg-green-600 text-white px-6 py-2 rounded-lg font-medium">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        let camera = null;
        let scanning = false;
        let currentOrderNumber = null;

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

        // Toggle camera
        async function toggleCamera() {
            const button = document.getElementById('toggle-camera');
            const container = document.getElementById('scanner-container');
            
            if (camera) {
                // Stop camera
                camera.getTracks().forEach(track => track.stop());
                camera = null;
                scanning = false;
                button.textContent = 'Start Camera';
                button.className = 'bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors';
                container.style.display = 'none';
            } else {
                // Start camera
                try {
                    camera = await navigator.mediaDevices.getUserMedia({
                        video: { 
                            facingMode: 'environment',
                            width: { ideal: 640 },
                            height: { ideal: 480 }
                        }
                    });
                    
                    const video = document.getElementById('camera');
                    video.srcObject = camera;
                    
                    button.textContent = 'Stop Camera';
                    button.className = 'bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm font-medium transition-colors';
                    container.style.display = 'block';
                    
                    scanning = true;
                    scanFromCamera();
                } catch (error) {
                    console.error('Camera access error:', error);
                    showScanStatus('Tidak dapat mengakses kamera. Gunakan input manual.', 'error');
                }
            }
        }

        // Scan from camera
        function scanFromCamera() {
            if (!scanning || !camera) return;
            
            const video = document.getElementById('camera');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');
            
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);
                
                if (code) {
                    processBarcode(code.data);
                    return; // Stop scanning after successful read
                }
            }
            
            setTimeout(scanFromCamera, 100);
        }

        // Process barcode
        async function processBarcode(barcode) {
            document.getElementById('manual-barcode').value = barcode;
            showScanStatus('Memproses barcode...', 'loading');
            
            try {
                const response = await fetch('{{ route("scanner.scan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ barcode: barcode })
                });
                
                const result = await response.json();
                
                if (result.success && result.order) {
                    showTicketDetail(result.order);
                    showScanStatus(result.message, 'success');
                    currentOrderNumber = result.order.order_number;
                } else {
                    showScanStatus(result.message, 'error');
                    hideTicketDetail();
                    currentOrderNumber = null;
                }
            } catch (error) {
                console.error('Scan error:', error);
                showScanStatus('Terjadi kesalahan saat memproses barcode', 'error');
                hideTicketDetail();
                currentOrderNumber = null;
            }
        }

        // Show scan status
        function showScanStatus(message, type) {
            const statusDiv = document.getElementById('scan-status');
            statusDiv.className = 'p-3 rounded-lg mb-4';
            
            if (type === 'success') {
                statusDiv.className += ' bg-green-50 text-green-800 border border-green-200';
            } else if (type === 'error') {
                statusDiv.className += ' bg-red-50 text-red-800 border border-red-200';
            } else if (type === 'loading') {
                statusDiv.className += ' bg-blue-50 text-blue-800 border border-blue-200';
            }
            
            statusDiv.textContent = message;
            statusDiv.classList.remove('hidden');
            
            // Auto hide after 5 seconds for success/error
            if (type !== 'loading') {
                setTimeout(() => {
                    statusDiv.classList.add('hidden');
                }, 5000);
            }
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
            document.getElementById('detail-total-price').textContent = 'Rp ' + order.total_price.toLocaleString('id-ID');
            
            const statusElement = document.getElementById('detail-status');
            const useButton = document.getElementById('use-ticket-btn');
            
            if (order.status === 'success') {
                statusElement.textContent = 'Valid';
                statusElement.className = 'font-medium text-green-600';
                useButton.disabled = false;
                useButton.className = 'w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-colors';
            } else if (order.status === 'used') {
                statusElement.textContent = 'Sudah Digunakan';
                statusElement.className = 'font-medium text-red-600';
                useButton.disabled = true;
                useButton.className = 'w-full bg-gray-400 cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg';
                useButton.textContent = '❌ Tiket Sudah Digunakan';
            } else {
                statusElement.textContent = order.status;
                statusElement.className = 'font-medium text-red-600';
                useButton.disabled = true;
                useButton.className = 'w-full bg-gray-400 cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg';
            }
        }

        // Hide ticket detail
        function hideTicketDetail() {
            document.getElementById('ticket-detail').classList.add('hidden');
            document.getElementById('no-ticket').classList.remove('hidden');
        }

        // Use ticket
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
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ order_number: currentOrderNumber })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Update UI
                    document.getElementById('detail-status').textContent = 'Sudah Digunakan';
                    document.getElementById('detail-status').className = 'font-medium text-red-600';
                    button.textContent = '❌ Tiket Sudah Digunakan';
                    button.className = 'w-full bg-gray-400 cursor-not-allowed text-white font-bold py-3 px-4 rounded-lg';
                    
                    // Show success modal
                    document.getElementById('success-message').textContent = result.message;
                    document.getElementById('success-modal').classList.remove('hidden');
                    document.getElementById('success-modal').classList.add('flex');
                    
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

        // Add to recent scans table
        function addToRecentScans(order) {
            const tbody = document.getElementById('recent-scans-body');
            const now = new Date();
            const timeStr = now.toTimeString().substring(0, 5);
            
            const row = document.createElement('tr');
            row.className = 'border-b bg-green-50';
            row.innerHTML = `
                <td class="p-3 text-sm">${order.order_number}</td>
                <td class="p-3 text-sm">${order.customer_name}</td>
                <td class="p-3 text-sm">-</td>
                <td class="p-3 text-sm">${order.ticket_quantity}</td>
                <td class="p-3 text-sm">${timeStr}</td>
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
    </script>
</body>
</html>