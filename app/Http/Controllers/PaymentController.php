<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\BarcodeGeneratorPNG;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Ambil dari config agar aman terhadap config cache
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');

        \Midtrans\Config::$serverKey  = $serverKey;
        \Midtrans\Config::$clientKey  = $clientKey;
        \Midtrans\Config::$isProduction = filter_var(config('midtrans.is_production', false), FILTER_VALIDATE_BOOLEAN);
        \Midtrans\Config::$isSanitized  = filter_var(config('midtrans.is_sanitized', true), FILTER_VALIDATE_BOOLEAN);
        \Midtrans\Config::$is3ds       = filter_var(config('midtrans.is_3ds', true), FILTER_VALIDATE_BOOLEAN);

        // Log bila ada yang kosong untuk debugging
        if (empty($serverKey) || empty($clientKey)) {
            \Log::error('Midtrans keys missing in config', [
                'server_key_set' => !empty($serverKey),
                'client_key_set' => !empty($clientKey),
                'env_midtrans_server' => env('MIDTRANS_SERVER_KEY') ? 'present' : 'missing',
            ]);
        }
    }

    // Method untuk menampilkan form checkout
    public function showCheckoutForm(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);
        return view('payment.checkout-form', compact('promo'));
    }

    // Method untuk memproses checkout dan mengarahkan ke halaman pembayaran
    public function processCheckout(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);

        // Validasi input
        $request->validate([
            'customer_name'   => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:15',
            'visit_date'      => 'required|date',
            'ticket_quantity' => 'required|integer|min:1',
        ]);

        // Generate order number
        $orderNumber = 'MK' . date('Ymd') . Str::random(4);

        // Hitung total harga
        $totalPrice = $request->ticket_quantity * $promo->promo_price;

        // Simpan order ke database
        $order = Order::create([
            'order_number'    => $orderNumber,
            'promo_id'        => $promo->id,
            'customer_name'   => $request->customer_name,
            'whatsapp_number' => $request->whatsapp_number,
            'branch'          => $request->branch,
            'visit_date'      => $request->visit_date,
            'ticket_quantity' => $request->ticket_quantity,
            'total_price'     => $totalPrice,
            'status'          => 'pending',
        ]);

        // Redirect ke halaman pembayaran
        return redirect()->route('payment.checkout', ['order_id' => $orderNumber]);
    }

    // Method untuk menampilkan halaman pembayaran dengan QRIS
    public function showCheckout($order_id)
    {
        $order = Order::where('order_number', $order_id)->firstOrFail();
        $promo = Promo::findOrFail($order->promo_id);

        // Jika order sudah sukses, redirect ke halaman finish
        if ($order->status === 'success') {
            return redirect()->route('payment.finish', ['order_id' => $order->order_number]);
        }

        // Jika sudah ada snap token, gunakan yang ada
        if ($order->snap_token) {
            $snapToken = $order->snap_token;
        } else {
            // Siapkan data untuk Midtrans
            $transactionDetails = [
                'order_id'     => $order->order_number,
                'gross_amount' => $order->total_price,
            ];

            $itemDetails = [
                [
                    'id'       => $promo->id,
                    'price'    => $promo->promo_price,
                    'quantity' => $order->ticket_quantity,
                    'name'     => $promo->name,
                ]
            ];

            $customerDetails = [
                'first_name' => $order->customer_name,
                'phone'      => $order->whatsapp_number,
                'email'      => 'customer@example.com',
            ];

            $transactionData = [
                'transaction_details' => $transactionDetails,
                'item_details'        => $itemDetails,
                'customer_details'    => $customerDetails,
                'enabled_payments'    => [
                    'qris', 
                    'gopay', 
                    'shopeepay',
                    'bank_transfer',
                    'credit_card'
                ],
                'callbacks' => [
                    'finish' => route('payment.finish'),
                    'unfinish' => route('payment.unfinish'),
                    'error' => route('payment.error'),
                ],
            ];

            $snapToken = null;

            try {
                $snapToken = Snap::getSnapToken($transactionData);
                $order->snap_token = $snapToken;
                $order->save();
            } catch (\Exception $e) {
                \Log::error('Midtrans error: ' . $e->getMessage());
                return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.');
            }
        }

        return view('payment.checkout', [
            'snapToken' => $snapToken,
            'clientKey' => env('MIDTRANS_CLIENT_KEY'),
            'order'     => $order,
            'promo'     => $promo,
        ]);
    }

    public function paymentFinish(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::where('order_number', $orderId)->firstOrFail();

        // Cek status langsung dari Midtrans API untuk memastikan
        $this->checkPaymentStatus($order);

        // Generate invoice number jika belum ada
        if (!$order->invoice_number) {
            $order->invoice_number = 'INV' . date('Ymd') . Str::upper(Str::random(6));
            $order->save();
        }

        // Jika status sukses, tampilkan halaman invoice dengan auto download script
        if ($order->status === 'success') {
            $promo = Promo::findOrFail($order->promo_id);
            $contactWhatsapp = Setting::get('contact_whatsapp', '812-3456-7890');
            
            return view('payment.invoice', [
                'order' => $order,
                'promo' => $promo,
                'invoiceNumber' => $order->invoice_number,
                'contactWhatsapp' => $contactWhatsapp,
                'autoDownload' => true // Flag untuk auto download
            ]);
        }

        // Jika masih pending, tampilkan waiting page
        return view('payment.waiting', compact('order'));
    }

    public function paymentUnfinish(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::where('order_number', $orderId)->firstOrFail();
        
        \Log::info('Payment Unfinish Accessed', ['order_id' => $orderId, 'current_status' => $order->status]);

        // Cek status terbaru dari Midtrans API
        $this->checkPaymentStatus($order);

        \Log::info('After API Check', ['order_id' => $orderId, 'new_status' => $order->status]);

        // Jika status masih pending, update menjadi canceled
        if ($order->status === 'pending') {
            $order->status = 'canceled';
            $order->save();
            \Log::info('Status updated to canceled', ['order_id' => $orderId]);
        }

        // Kirim data order ke view
        return view('payment.unfinish', compact('order'));
    }

    public function paymentError(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::where('order_number', $orderId)->firstOrFail();
        
        \Log::info('Payment Error Accessed', ['order_id' => $orderId, 'current_status' => $order->status]);

        // Cek status terbaru dari Midtrans API
        $this->checkPaymentStatus($order);

        \Log::info('After API Check', ['order_id' => $orderId, 'new_status' => $order->status]);

        // Jika status masih pending, update menjadi denied
        if ($order->status === 'pending') {
            $order->status = 'denied';
            $order->save();
            \Log::info('Status updated to denied', ['order_id' => $orderId]);
        }

        return view('payment.error', compact('order'));
    }

    /**
     * Check payment status directly from Midtrans API
     */
    private function checkPaymentStatus(Order $order)
    {
        try {
            $status = Transaction::status($order->order_number);
            
            $transactionStatus = $status->transaction_status;
            $fraudStatus = $status->fraud_status;

            \Log::info('Midtrans Status Check: ', [
                'order_id' => $order->order_number,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            $oldStatus = $order->status;

            // Update status berdasarkan response Midtrans
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $order->status = 'challenge';
                } else {
                    $order->status = 'success';
                }
            } else if ($transactionStatus == 'settlement') {
                $order->status = 'success';
            } else if ($transactionStatus == 'pending') {
                $order->status = 'pending';
            } else if ($transactionStatus == 'deny') {
                $order->status = 'denied';
            } else if ($transactionStatus == 'expire') {
                $order->status = 'expired';
            } else if ($transactionStatus == 'cancel') {
                $order->status = 'canceled';
            }

            // Hanya save jika status berubah
            if ($oldStatus !== $order->status) {
                $order->save();
                \Log::info('Order status updated from API: ' . $order->status);
            }

        } catch (\Exception $e) {
            \Log::error('Error checking payment status: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint untuk check status payment
     */
    public function checkStatus(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::where('order_number', $orderId)->firstOrFail();

        $this->checkPaymentStatus($order);

        return response()->json([
            'status' => $order->status,
            'order_number' => $order->order_number
        ]);
    }

    public function notificationHandler(Request $request)
    {
        try {
            $notif = new Notification();
            
            $orderId = $notif->order_id;
            $transactionStatus = $notif->transaction_status;
            $fraudStatus = $notif->fraud_status;

            \Log::info('Midtrans Notification Received: ', [
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            $order = Order::where('order_number', $orderId)->first();

            if (!$order) {
                \Log::error('Order not found: ' . $orderId);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Update status berdasarkan notifikasi Midtrans
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $order->status = 'challenge';
                } else {
                    $order->status = 'success';
                }
            } else if ($transactionStatus == 'settlement') {
                $order->status = 'success';
            } else if ($transactionStatus == 'pending') {
                $order->status = 'pending';
            } else if ($transactionStatus == 'deny') {
                $order->status = 'denied';
            } else if ($transactionStatus == 'expire') {
                $order->status = 'expired';
            } else if ($transactionStatus == 'cancel') {
                $order->status = 'canceled';
            }

            $order->save();

            \Log::info('Order status updated from notification: ' . $order->status);

            return response()->json(['message' => 'Notification processed successfully']);

        } catch (\Exception $e) {
            \Log::error('Notification handler error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    // Method untuk menangani hasil pembayaran sukses
    public function paymentSuccess(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::where('order_number', $orderId)->firstOrFail();

        return view('payment.success', compact('order'));
    }

    // Method untuk menangani processing checkout
    public function processPayment(Request $request)
    {
        // Logic untuk memproses pembayaran
        return response()->json(['message' => 'Payment processed']);
    }

    // Method untuk menangani cancel payment
    public function paymentCancel(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::where('order_number', $orderId)->firstOrFail();

        $order->status = 'canceled';
        $order->save();

        return view('payment.cancel', compact('order'));
    }

    // Method untuk menampilkan history pembayaran
    public function paymentHistory()
    {
        $orders = Order::where('customer_name', auth()->user()->name ?? '')
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('payment.history', compact('orders'));
    }

    // Method untuk menampilkan detail pembayaran
    public function paymentDetail($order_id)
    {
        $order = Order::where('order_number', $order_id)->firstOrFail();
        $promo = Promo::findOrFail($order->promo_id);

        return view('payment.detail', compact('order', 'promo'));
    }

    /**
     * Method untuk menampilkan invoice dan auto download
     */
    public function showInvoice($order_id)
    {
        $order = Order::where('order_number', $order_id)->firstOrFail();
        $promo = Promo::findOrFail($order->promo_id);

        // Jika belum ada invoice number, generate sekali lalu simpan
        if (!$order->invoice_number) {
            $order->invoice_number = 'INV' . date('Ymd') . Str::upper(Str::random(6));
            $order->save();
        }

        // Get contact WhatsApp from settings
        $contactWhatsapp = Setting::get('contact_whatsapp', '812-3456-7890');

        // Cek jika request ingin download PDF
        if (request()->has('download')) {
            return $this->downloadInvoice($order, $promo);
        }

        return view('payment.invoice', [
            'order' => $order,
            'promo' => $promo,
            'invoiceNumber' => $order->invoice_number,
            'contactWhatsapp' => $contactWhatsapp,
            'autoDownload' => request()->has('autodownload') // Flag untuk auto download
        ]);
    }

    /**
     * Generate barcode patterns untuk PDF
     */
    private function generateBarcodePattern($text)
    {
        // Simplified Code128 pattern generator for PDF
        $patterns = [
            '0' => '11011001100', '1' => '11001101100', '2' => '11001100110', 
            '3' => '10010011000', '4' => '10010001100', '5' => '10001001100',
            '6' => '10011001000', '7' => '10011000100', '8' => '10001100100',
            '9' => '11001001000', 'A' => '11001000100', 'B' => '11000100100',
            'C' => '10110011100', 'D' => '10011011100', 'E' => '10011001110',
            'F' => '10111001000', 'G' => '10011101000', 'H' => '10011100100',
            'I' => '11001110010', 'J' => '11001011100', 'K' => '11001001110',
            'L' => '11011100100', 'M' => '11001110100', 'N' => '11101101110',
            'O' => '11101001100', 'P' => '11100101100', 'Q' => '11100100110',
            'R' => '11101100100', 'S' => '11100110100', 'T' => '11100110010',
            'U' => '11011011000', 'V' => '11011000110', 'W' => '11000110110',
            'X' => '10100011000', 'Y' => '10001011000', 'Z' => '10001000110',
        ];
        
        $result = '11010010000'; // Start Code B
        
        for ($i = 0; $i < strlen($text); $i++) {
            $char = strtoupper($text[$i]);
            if (isset($patterns[$char])) {
                $result .= $patterns[$char];
            }
        }
        
        $result .= '1100011101011'; // Stop pattern
        
        return $result;
    }

    private function generateAsciiBarcode($text)
    {
        $barcode = '';
        $chars = str_split($text);
        
        foreach ($chars as $char) {
            // Convert character to binary-like pattern
            $ascii = ord($char);
            $binary = decbin($ascii);
            
            // Convert binary to barcode pattern
            for ($i = 0; $i < strlen($binary); $i++) {
                if ($binary[$i] === '1') {
                    $barcode .= '█';
                } else {
                    $barcode .= '▒';
                }
            }
            $barcode .= '░'; // Separator
        }
        
        return $barcode;
    }

    /**
     * Convert binary pattern to barcode bars for HTML/CSS
     */
    private function patternToBars($pattern, $height = 60)
    {
        $bars = '';
        $barWidth = 2;
        
        for ($i = 0; $i < strlen($pattern); $i++) {
            $color = ($pattern[$i] === '1') ? 'black' : 'white';
            $bars .= "<div style='display:inline-block; width:{$barWidth}px; height:{$height}px; background-color:{$color};'></div>";
        }
        
        return $bars;
    }

    /**
     * Method untuk download invoice PDF - FIXED VERSION dengan Barcode
     */
    public function downloadInvoice($order, $promo)
    {
        $invoiceNumber = $order->invoice_number;
        
        // Get contact WhatsApp from settings
        $contactWhatsapp = Setting::get('contact_whatsapp', '812-3456-7890');
        
        // Generate REAL barcode menggunakan library
        $generator = new BarcodeGeneratorHTML();
        $barcodeHTML = $generator->getBarcode($order->order_number, $generator::TYPE_CODE_128, 3, 60);
        
        // Alternative: Generate base64 image barcode
        $generatorPNG = new BarcodeGeneratorPNG();
        $barcodePNG = base64_encode($generatorPNG->getBarcode($order->order_number, $generatorPNG::TYPE_CODE_128, 3, 60));
        $barcodeImage = 'data:image/png;base64,' . $barcodePNG;
        
        $pdf = Pdf::loadView('payment.invoice-pdf', [
            'order' => $order,
            'promo' => $promo,
            'invoiceNumber' => $invoiceNumber,
            'contactWhatsapp' => $contactWhatsapp,
            'barcodeHTML' => $barcodeHTML,
            'barcodeImage' => $barcodeImage,
        ]);

        // Set options untuk PDF
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('dpi', 150);
        $pdf->setOption('defaultFont', 'sans-serif');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isPhpEnabled', true);

        // Bersihkan nama file dari karakter yang tidak diizinkan
        $cleanInvoiceNumber = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $invoiceNumber);
        $filename = "Invoice_{$cleanInvoiceNumber}_MestaKara.pdf";

        return $pdf->download($filename);
    }

    /**
     * Method khusus untuk auto download setelah pembayaran sukses
     */
    public function autoDownloadInvoice($order_id)
    {
        $order = Order::where('order_number', $order_id)->firstOrFail();
        $promo = Promo::findOrFail($order->promo_id);

        return $this->downloadInvoice($order, $promo);
    }

    // Method untuk export invoice
    public function exportInvoice($order_id)
    {
        $order = Order::where('order_number', $order_id)->firstOrFail();
        $promo = Promo::findOrFail($order->promo_id);
        $contactWhatsapp = Setting::get('contact_whatsapp', '812-3456-7890');

        return view('payment.invoice', compact('order', 'promo', 'contactWhatsapp'));
    }

    // Method untuk refund payment
    public function requestRefund(Request $request, $order_id)
    {
        $order = Order::where('order_number', $order_id)->firstOrFail();

        // Validasi request refund
        $request->validate([
            'refund_reason' => 'required|string|max:500'
        ]);

        // Logic untuk proses refund
        $order->refund_reason = $request->refund_reason;
        $order->refund_requested_at = now();
        $order->save();

        return response()->json(['message' => 'Refund request submitted']);
    }

    // Method untuk mengecek ketersediaan tiket
    public function checkAvailability(Request $request)
    {
        $visitDate = $request->visit_date;
        $quantity = $request->ticket_quantity;

        // Logic untuk cek ketersediaan tiket
        $available = true; // Ganti dengan logic sebenarnya

        return response()->json([
            'available' => $available,
            'message' => $available ? 'Tiket tersedia' : 'Tiket tidak tersedia untuk tanggal tersebut'
        ]);
    }

    /**
     * Method untuk menampilkan waiting page
     */
    public function showWaiting($order_id)
    {
        $order = Order::where('order_number', $order_id)->firstOrFail();
        return view('payment.waiting', compact('order'));
    }

    public function generateBarcodeFile($orderNumber)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($orderNumber, $generator::TYPE_CODE_128, 3, 60);
        
        // Save barcode ke storage/app/public/barcodes/
        $filename = 'barcode_' . $orderNumber . '.png';
        $path = storage_path('app/public/barcodes/' . $filename);
        
        // Pastikan folder ada
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        file_put_contents($path, $barcode);
        
        return $filename;
    }

    public function downloadInvoiceWithBarcodeFile($order, $promo)
    {
        $invoiceNumber = $order->invoice_number;
        
        // Get contact WhatsApp from settings
        $contactWhatsapp = Setting::get('contact_whatsapp', '812-3456-7890');
        
        // Generate dan save barcode file
        $barcodeFilename = $this->generateBarcodeFile($order->order_number);
        $barcodeUrl = asset('storage/barcodes/' . $barcodeFilename);
        
        // Convert image to base64 for PDF
        $barcodePath = storage_path('app/public/barcodes/' . $barcodeFilename);
        if (file_exists($barcodePath)) {
            $barcodeBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($barcodePath));
        } else {
            $barcodeBase64 = null;
        }
        
        $pdf = Pdf::loadView('payment.invoice-pdf', [
            'order' => $order,
            'promo' => $promo,
            'invoiceNumber' => $invoiceNumber,
            'contactWhatsapp' => $contactWhatsapp,
            'barcodeImage' => $barcodeBase64,
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('dpi', 150);
        $pdf->setOption('defaultFont', 'sans-serif');

        $cleanInvoiceNumber = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '-', $invoiceNumber);
        $filename = "Invoice_{$cleanInvoiceNumber}_MestaKara.pdf";

        return $pdf->download($filename);
    }
}