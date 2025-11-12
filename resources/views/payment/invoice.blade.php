<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoiceNumber }} - MestaKara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
            .print-area {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
            .hidden-print {
                display: none !important;
            }
        }
        .barcode-container {
            background: white;
            padding: 20px;
            border: 2px dashed #e5e7eb;
            border-radius: 10px;
        }
        .auto-download-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body class="font-poppins bg-gray-50">
    <div class="min-h-screen py-8 px-4">
        
        <!-- Auto Download Message -->
        @if(isset($autoDownload) && $autoDownload)
        <div class="max-w-2xl mx-auto auto-download-message">
            <p>✅ Pembayaran berhasil! Invoice akan otomatis terdownload dalam <span id="countdown">5</span> detik...</p>
        </div>
        @endif

        <div class="max-w-2xl mx-auto print-area bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-primary py-6 px-8 text-center">
                <h1 class="text-3xl font-bold text-black">MestaKara</h1>
                <p class="text-black mt-1">Wisata dan Hiburan Keluarga</p>
                <p class="text-black text-sm mt-2">E-Ticket & Invoice</p>
            </div>
            
            <!-- Invoice Info -->
            <div class="p-8 border-b">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="font-semibold text-gray-600">INVOICE #</h3>
                        <p class="text-lg font-bold">{{ $invoiceNumber }}</p>
                    </div>
                    <div class="text-right">
                        <h3 class="font-semibold text-gray-600">TANGGAL</h3>
                        <p class="text-lg">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Customer Info -->
            <div class="p-8 border-b">
                <h3 class="font-semibold text-lg mb-4">Informasi Pemesan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Nama Lengkap</p>
                        <p class="font-medium">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">WhatsApp</p>
                        <p class="font-medium">{{ $order->whatsapp_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Domisili</p>
                        <p class="font-medium">{{ $order->domicile }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Cabang</p>
                        <p class="font-medium">{{ $order->branch ?? 'Cabang Utama' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tanggal Kunjungan</p>
                        <p class="font-medium">{{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Order Details -->
            <div class="p-8 border-b">
                <h3 class="font-semibold text-lg mb-4">Detail Pesanan</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="text-left p-3">Item</th>
                                <th class="text-center p-3">Qty</th>
                                <th class="text-right p-3">Harga</th>
                                <th class="text-right p-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="p-3">
                                    <p class="font-medium">{{ $promo->name }}</p>
                                    <p class="text-sm text-gray-600">Tiket masuk {{ $promo->name }}</p>
                                </td>
                                <td class="text-center p-3">{{ $order->ticket_quantity }}</td>
                                <td class="text-right p-3">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</td>
                                <td class="text-right p-3">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Total -->
            <div class="p-8 border-b">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="mt-2 text-right">
                    <span class="text-sm text-gray-600">Status: </span>
                    <span class="text-sm font-medium text-green-600">LUNAS</span>
                </div>
            </div>
            
           <!-- Barcode Section -->
            <div class="p-8 flex flex-col items-center justify-center text-center">
                <h3 class="font-semibold text-lg mb-4">Barcode E-Ticket</h3>
                <p class="text-gray-600 mb-4">Tunjukkan barcode ini saat check-in</p>
                
                <div class="barcode-container flex justify-center">
                    <svg id="barcode"></svg>
                </div>
                
                <div class="mt-6 bg-yellow-50 p-4 rounded-lg max-w-md w-full text-left">
                    <h4 class="font-semibold text-yellow-800">📋 Instruksi Kunjungan:</h4>
                    <ul class="text-sm text-yellow-700 mt-2 list-disc list-inside">
                        <li>Tunjukkan barcode kepada petugas untuk di-scan</li>
                        <li>E-ticket hanya dapat digunakan sekali</li>
                        <li>Valid untuk tanggal {{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</li>
                        <li>Jumlah pengunjung: {{ $order->ticket_quantity }} orang</li>
                        <li>Harap datang tepat waktu sesuai jadwal kunjungan</li>
                    </ul>
                </div>
            </div>

            <!-- Footer with integrated button -->
            <div class="bg-gray-50 p-8 text-center">
                <p class="text-gray-600">Terima kasih telah memilih MestaKara</p>
                <p class="text-sm text-gray-500 mt-2">
                    Untuk pertanyaan, hubungi WhatsApp: 
                    <a href="https://wa.me/62{{ preg_replace('/[^0-9]/', '', App\Models\Setting::get('contact_whatsapp', '812-3456-7890')) }}" 
                    class="text-green-600 font-medium hover:underline" target="_blank">
                        +62 {{ App\Models\Setting::get('contact_whatsapp', '812-3456-7890') }}
                    </a>
                </p>
                
                <!-- Integrated Back to Home Button -->
                <div class="mt-6 no-print">
                    <a href="{{ route('home') }}" 
                    class="inline-block bg-gray-200 text-gray-800 font-semibold py-3 px-6 rounded-lg hover:bg-gray-300 transition-colors text-center">
                        🏠 Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Generate barcode dengan data order number
        JsBarcode("#barcode", "{{ $order->order_number }}", {
            format: "CODE128",
            width: 2,
            height: 100,
            displayValue: false,
            fontSize: 16,
            margin: 10,
            background: "transparent"
        });
        
        // Auto download script
        @if(isset($autoDownload) && $autoDownload)
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        const countdownInterval = setInterval(function() {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                window.location.href = "{{ route('payment.invoice.autodownload', ['order_id' => $order->order_number]) }}";
            }
        }, 1000);

        // Optional: Skip countdown and download immediately
        setTimeout(function() {
            window.location.href = "{{ route('payment.invoice.autodownload', ['order_id' => $order->order_number]) }}";
        }, 5000);
        @endif
    </script>
</body>
</html>