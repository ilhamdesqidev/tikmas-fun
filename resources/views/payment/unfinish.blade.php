<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Belum Selesai - MestaKara</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
        <div class="text-yellow-500 mx-auto mb-4">
            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            @if($order->status === 'canceled')
                Pembayaran Dibatalkan
            @elseif($order->status === 'expired')
                Pembayaran Kadaluarsa
            @else
                Pembayaran Belum Selesai
            @endif
        </h1>
        
        <p class="text-gray-600 mb-4">
            @if($order->status === 'canceled')
                Anda telah membatalkan pembayaran.
            @elseif($order->status === 'expired')
                Waktu pembayaran telah habis.
            @else
                Pembayaran Anda belum selesai diproses. Silakan selesaikan pembayaran atau coba lagi.
            @endif
        </p>
        
        <div class="bg-gray-50 p-4 rounded-lg text-left mb-4">
            <p class="text-sm"><strong>No. Order:</strong> {{ $order->order_number }}</p>
            <p class="text-sm"><strong>Status:</strong> 
                <span class="font-bold 
                    @if($order->status === 'canceled') text-red-600
                    @elseif($order->status === 'expired') text-orange-600
                    @else text-yellow-600 @endif">
                    {{ $order->status }}
                </span>
            </p>
            <p class="text-sm"><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        </div>
        
        <div class="flex flex-col gap-3">
            <a href="{{ route('payment.checkout', ['order_id' => $order->order_number]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-center">
                Coba Lagi
            </a>
            <a href="{{ url('/') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-center">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>