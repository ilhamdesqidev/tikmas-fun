<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - MestaKara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .font-poppins {
            font-family: 'Poppins', sans-serif;
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
            
            <div class="bg-gray-50 p-4 rounded-lg mt-6 text-left">
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
                        <span class="text-gray-600">Total Pembayaran:</span>
                        <span class="font-medium text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium text-green-600">Berhasil</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <p class="text-gray-600">Tiket elektronik telah dikirim ke WhatsApp Anda.</p>
                <p class="text-gray-500 text-sm mt-2">Silakan simpan bukti pembayaran ini untuk ditunjukkan saat check-in.</p>
            </div>
            
            <div class="mt-8">
                <a href="{{ route('home') }}" class="inline-block bg-primary text-black font-semibold py-2 px-6 rounded-lg hover:bg-yellow-500 transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>