<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Belum Selesai - MestaKara</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
        <!-- Fixed SVG Warning Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
            </div>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 mb-3">
            <!-- Status akan berubah sesuai kondisi -->
            <span id="status-title">Pembayaran Belum Selesai</span>
        </h1>
        
        <p class="text-gray-600 mb-6 leading-relaxed">
            <span id="status-description">Pembayaran Anda belum selesai diproses. Silakan selesaikan pembayaran atau coba lagi.</span>
        </p>
        
        <div class="bg-gray-50 p-4 rounded-lg text-left mb-6 border border-gray-200">
            <div class="space-y-2">
                <p class="text-sm text-gray-700">
                    <span class="font-medium">No. Order:</span> 
                    <span class="font-mono text-gray-900">MSK-2024-001234</span>
                </p>
                <p class="text-sm text-gray-700">
                    <span class="font-medium">Status:</span> 
                    <span id="order-status" class="font-semibold text-red-600 capitalize">Canceled</span>
                </p>
                <p class="text-sm text-gray-700">
                    <span class="font-medium">Total:</span> 
                    <span class="font-semibold text-gray-900">Rp 150.000</span>
                </p>
                <p class="text-sm text-gray-700">
                    <span class="font-medium">Tanggal:</span> 
                    <span class="text-gray-900">24 Sep 2025, 14:30</span>
                </p>
            </div>
        </div>
        
        <div class="flex flex-col gap-3">
            <button onclick="window.location.href='/'" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                Batalkan Pembayaran
            </button>
        </div>

       
    </div>

    <script>
        function changeStatus(status) {
            const statusTitle = document.getElementById('status-title');
            const statusDescription = document.getElementById('status-description');
            const orderStatus = document.getElementById('order-status');
            const icon = document.querySelector('.bg-yellow-100 svg');
            const iconContainer = document.querySelector('.bg-yellow-100');
            
            // Reset classes
            orderStatus.className = 'font-semibold capitalize';
            iconContainer.className = 'w-16 h-16 rounded-full flex items-center justify-center';
            
            switch(status) {
                case 'canceled':
                    statusTitle.textContent = 'Pembayaran Dibatalkan';
                    statusDescription.textContent = 'Anda telah membatalkan pembayaran.';
                    orderStatus.textContent = 'canceled';
                    orderStatus.classList.add('text-red-600');
                    iconContainer.classList.add('bg-red-100');
                    icon.classList.remove('text-yellow-600');
                    icon.classList.add('text-red-600');
                    break;
                    
                case 'expired':
                    statusTitle.textContent = 'Pembayaran Kadaluarsa';
                    statusDescription.textContent = 'Waktu pembayaran telah habis.';
                    orderStatus.textContent = 'expired';
                    orderStatus.classList.add('text-orange-600');
                    iconContainer.classList.add('bg-orange-100');
                    icon.classList.remove('text-yellow-600');
                    icon.classList.add('text-orange-600');
                    break;
                    
                default:
                    statusTitle.textContent = 'Pembayaran Belum Selesai';
                    statusDescription.textContent = 'Pembayaran Anda belum selesai diproses. Silakan selesaikan pembayaran atau coba lagi.';
                    orderStatus.textContent = 'pending';
                    orderStatus.classList.add('text-yellow-600');
                    iconContainer.classList.add('bg-yellow-100');
                    icon.classList.remove('text-red-600', 'text-orange-600');
                    icon.classList.add('text-yellow-600');
            }
        }
    </script>
</body>
</html>