<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Konfirmasi - MestaKara</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 mx-auto mb-4"></div>
        
        <!-- Success Icon (hidden by default) -->
        <div id="successIcon" class="hidden text-green-500 mx-auto mb-4">
            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <!-- Canceled Icon (hidden by default) -->
        <div id="canceledIcon" class="hidden text-red-500 mx-auto mb-4">
            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-2" id="title">Menunggu Konfirmasi</h1>
        <p class="text-gray-600 mb-4" id="message">Pembayaran Anda sedang diproses. Silakan tunggu...</p>
        
        <div class="bg-gray-50 p-4 rounded-lg text-left mb-4">
            <p class="text-sm"><strong>No. Order:</strong> {{ $order->order_number }}</p>
            <p class="text-sm"><strong>Status:</strong> <span id="statusText">{{ $order->status }}</span></p>
            <p class="text-sm"><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        </div>

        <div id="actionButtons">
            <button onclick="checkStatus()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2">
                Cek Status Sekarang
            </button>
            <button onclick="cancelPayment()" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Batalkan
            </button>
        </div>

        <!-- Success Redirect Info (hidden by default) -->
        <div id="successRedirect" class="hidden mt-4 p-3 bg-green-50 rounded-lg">
            <p class="text-green-700">Pembayaran berhasil! Mengarahkan ke halaman konfirmasi...</p>
        </div>

        <!-- Canceled Redirect Info (hidden by default) -->
        <div id="canceledRedirect" class="hidden mt-4 p-3 bg-yellow-50 rounded-lg">
            <p class="text-yellow-700">Pembayaran dibatalkan. Mengarahkan ke halaman unfinish...</p>
        </div>
    </div>

    <script>
        let checkCount = 0;
        const maxChecks = 30; // Maksimal 30x check (5 menit)

        function checkStatus() {
            if (checkCount >= maxChecks) {
                document.getElementById('message').textContent = 'Timeout: Silakan hubungi customer service';
                return;
            }

            checkCount++;
            
            fetch(`/payment/check-status?order_id={{ $order->order_number }}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Status check response:', data);
                    document.getElementById('statusText').textContent = data.status;
                    
                    if (data.status === 'success') {
                        // Pembayaran sukses
                        showSuccessState();
                        
                    } else if (data.status === 'canceled' || data.status === 'expired') {
                        // Pembayaran dibatalkan atau expired
                        showCanceledState(data.status);
                        
                    } else if (data.status === 'denied') {
                        // Pembayaran ditolak
                        showErrorState();
                        
                    } else {
                        // Masih pending, check lagi dalam 10 detik
                        setTimeout(checkStatus, 10000);
                    }
                })
                .catch(error => {
                    console.error('Error checking status:', error);
                    // Retry setelah 10 detik jika error
                    setTimeout(checkStatus, 10000);
                });
        }

        function showSuccessState() {
            document.getElementById('loadingSpinner').classList.add('hidden');
            document.getElementById('successIcon').classList.remove('hidden');
            document.getElementById('title').textContent = 'Pembayaran Berhasil!';
            document.getElementById('message').textContent = 'Pembayaran Anda telah berhasil dikonfirmasi.';
            document.getElementById('actionButtons').classList.add('hidden');
            document.getElementById('successRedirect').classList.remove('hidden');
            
            // Redirect ke halaman finish setelah 3 detik
            setTimeout(() => {
                window.location.href = "{{ route('payment.finish', ['order_id' => $order->order_number]) }}";
            }, 3000);
        }

        function showCanceledState(status) {
            document.getElementById('loadingSpinner').classList.add('hidden');
            document.getElementById('canceledIcon').classList.remove('hidden');
            document.getElementById('title').textContent = 'Pembayaran ' + (status === 'canceled' ? 'Dibatalkan' : 'Kadaluarsa');
            document.getElementById('message').textContent = 'Status: ' + status;
            document.getElementById('actionButtons').classList.add('hidden');
            document.getElementById('canceledRedirect').classList.remove('hidden');
            
            // Redirect ke halaman unfinish setelah 3 detik
            setTimeout(() => {
                window.location.href = "{{ route('payment.unfinish', ['order_id' => $order->order_number]) }}";
            }, 3000);
        }

        function showErrorState() {
            document.getElementById('loadingSpinner').classList.add('hidden');
            document.getElementById('canceledIcon').classList.remove('hidden');
            document.getElementById('title').textContent = 'Pembayaran Gagal';
            document.getElementById('message').textContent = 'Pembayaran Anda ditolak.';
            document.getElementById('actionButtons').innerHTML = `
                <a href="{{ route('payment.checkout', ['order_id' => $order->order_number]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2">
                    Coba Lagi
                </a>
                <a href="{{ url('/') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Kembali ke Beranda
                </a>
            `;
        }

        function cancelPayment() {
            if (confirm('Apakah Anda yakin ingin membatalkan pembayaran?')) {
                // Redirect ke halaman unfinish untuk update status
                window.location.href = "{{ route('payment.unfinish', ['order_id' => $order->order_number]) }}";
            }
        }

        // Auto check status pertama kali setelah 3 detik
        setTimeout(checkStatus, 3000);

        // Juga check status setiap 30 detik sebagai backup
        setInterval(checkStatus, 30000);
    </script>
</body>
</html>