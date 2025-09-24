<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - MestaKara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="font-poppins bg-gray-50">
    <!-- Navbar -->
    <nav class="w-full py-3 sm:py-5 px-4 sm:px-7 flex items-center justify-between bg-white border-b border-gray-400 fixed top-0 left-0 right-0 z-50" style="border-bottom: 1px solid #597336;">
      <a href="/" class="text-2xl sm:text-3xl font-bold text-black italic">
        Mesta<span class="text-primary text-yellow-200">Kara</span>.
      </a>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-16 px-4 sm:px-7">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-800">Pembayaran Tiket</h1>
                    <p class="text-gray-600">Selesaikan pembayaran Anda untuk melanjutkan</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Detail Pesanan -->
                        <div>
                            <h2 class="text-lg font-semibold mb-4">Detail Pesanan</h2>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">No. Pesanan:</span>
                                    <span class="font-medium">{{ $order->order_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nama Pemesan:</span>
                                    <span class="font-medium">{{ $order->customer_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Promo:</span>
                                    <span class="font-medium">{{ $promo->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Jumlah Tiket:</span>
                                    <span class="font-medium">{{ $order->ticket_quantity }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal Kunjungan:</span>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between pt-3 border-t border-gray-200">
                                    <span class="text-lg font-bold">Total:</span>
                                    <span class="text-lg font-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- QRIS Payment -->
                        <div>
                            <h2 class="text-lg font-semibold mb-4">Metode Pembayaran</h2>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-center text-gray-600 mb-4">Scan QRIS berikut untuk melakukan pembayaran</p>
                                
                                <div id="snap-container" class="flex justify-center p-4 bg-white rounded-lg border border-gray-200">
                                    @if($snapToken)
                                        <!-- Snap container akan diisi oleh Midtrans -->
                                    @else
                                        <p class="text-red-500 font-semibold">Gagal memuat QRIS, silakan refresh halaman.</p>
                                    @endif
                                </div>
                                
                                <p class="text-center text-sm text-gray-500 mt-4">
                                    Pembayaran akan diproses secara otomatis. Simpan bukti pembayaran Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 text-center">
                        <a href="{{ route('home') }}" class="inline-block text-gray-600 hover:text-primary">
                            <i data-feather="arrow-left" class="w-4 h-4 inline mr-1"></i> Kembali ke Beranda
                        </a>
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
                    // Langsung redirect ke finish
                    window.location.href = "{{ route('payment.finish') }}?order_id={{ $order->order_number }}";
                },
                onPending: function(result){
                    console.log("pending", result);
                    window.location.href = "{{ route('payment.finish') }}?order_id={{ $order->order_number }}";
                },
                onError: function(result){
                    console.log("error", result);
                    window.location.href = "{{ route('payment.error') }}?order_id={{ $order->order_number }}";
                },
                onClose: function(){
                    console.log("customer closed the popup without finishing the payment");
                    // PASTIKAN redirect ke unfinish dengan order_id
                    window.location.href = "{{ route('payment.unfinish') }}?order_id={{ $order->order_number }}";
                }
            });
        @endif
    });
</script>
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>