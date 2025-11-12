<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - MestaKara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
        .countdown {
            font-size: 1.2rem;
            font-weight: bold;
            color: #059669;
        }
    </style>
</head>
<body class="font-poppins bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-8 px-4">
        <div class="max-w-md w-full bg-white rounded-xl shadow-md overflow-hidden p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mt-4">Pembayaran Berhasil!</h1>
            <p class="text-gray-600 mt-2">Terima kasih telah melakukan pembayaran. Tiket Anda telah diproses.</p>
            
            <!-- Countdown Timer -->
            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                <p class="text-gray-700">Mengarahkan ke invoice dalam:</p>
                <div class="countdown" id="countdown">5</div>
                <p class="text-sm text-gray-600 mt-1">Silakan tunggu atau klik tombol di bawah</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg mt-4 text-left">
                <h2 class="font-semibold text-lg mb-2">Detail Pesanan</h2>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">No. Pesanan:</span>
                        <span class="font-medium">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Pemesan:</span>
                        <span class="font-medium">{{ $order->customer_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Domisili:</span>
                        <span class="font-medium">{{ $order->domicile }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Kunjungan:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jumlah Tiket:</span>
                        <span class="font-medium">{{ $order->ticket_quantity }} orang</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Pembayaran:</span>
                        <span class="font-medium text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 space-y-3">
                <a href="{{ route('payment.invoice', ['order_id' => $order->order_number]) }}" 
                   class="block w-full bg-primary text-black font-semibold py-3 px-6 rounded-lg hover:bg-yellow-500 transition-colors text-center">
                    Lihat Invoice Sekarang
                </a>
                <a href="{{ route('home') }}" 
                   class="block w-full bg-gray-200 text-gray-800 font-semibold py-3 px-6 rounded-lg hover:bg-gray-300 transition-colors text-center">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        // Countdown timer untuk redirect otomatis
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        const countdownInterval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                window.location.href = "{{ route('payment.invoice', ['order_id' => $order->order_number]) }}";
            }
        }, 1000);
    </script>
</body>
</html>