<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\Order;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    // Method untuk menampilkan form checkout
    public function showCheckoutForm(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);
        return view('promo.checkout-form', compact('promo'));
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
            'ticket_quantity' => 'required|integer|min:1|max:10',
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

        // Jika status masih pending, arahkan ke waiting page
        if ($order->status === 'pending') {
            return view('payment.waiting', compact('order'));
        }

        // Jika sukses, tampilkan halaman finish dengan countdown
        return view('payment.finish', compact('order'));
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

    // Method untuk menangani hasil pembayaran sukses (jika ada route yang memanggil ini)
    public function paymentSuccess(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::where('order_number', $orderId)->firstOrFail();

        return view('payment.success', compact('order'));
    }

    // Method untuk menangani processing checkout (jika ada route yang memanggil ini)
    public function processPayment(Request $request)
    {
        // Logic untuk memproses pembayaran
        return response()->json(['message' => 'Payment processed']);
    }

    // Method untuk menangani cancel payment (jika ada route yang memanggil ini)
    public function paymentCancel(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::where('order_number', $orderId)->firstOrFail();

        $order->status = 'canceled';
        $order->save();

        return view('payment.cancel', compact('order'));
    }

    // Method untuk menampilkan history pembayaran (jika diperlukan)
    public function paymentHistory()
    {
        $orders = Order::where('customer_name', auth()->user()->name ?? '')
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('payment.history', compact('orders'));
    }

    // Method untuk menampilkan detail pembayaran (jika diperlukan)
    public function paymentDetail($order_id)
    {
        $order = Order::where('order_number', $order_id)->firstOrFail();
        $promo = Promo::findOrFail($order->promo_id);

        return view('payment.detail', compact('order', 'promo'));
    }

    // Method untuk export invoice (jika diperlukan)
    public function exportInvoice($order_id)
    {
        $order = Order::where('order_number', $order_id)->firstOrFail();
        $promo = Promo::findOrFail($order->promo_id);

        // Logic untuk export invoice PDF
        // return PDF::loadView('payment.invoice', compact('order', 'promo'))->download('invoice-'.$order_id.'.pdf');
        
        return view('payment.invoice', compact('order', 'promo'));
    }

    // Method untuk refund payment (jika diperlukan)
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

    // Method untuk mengecek ketersediaan tiket (jika diperlukan)
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

    // Tambahkan method ini di PaymentController
    public function showInvoice($order_id)
    {
        $order = Order::where('order_number', $order_id)->firstOrFail();
        $promo = Promo::findOrFail($order->promo_id);

        // Jika belum ada invoice number, generate sekali lalu simpan
        if (!$order->invoice_number) {
            $order->invoice_number = 'INV/' . date('Ymd') . '/' . Str::upper(Str::random(6));
            $order->save();
        }

        return view('payment.invoice', [
            'order' => $order,
            'promo' => $promo,
            'invoiceNumber' => $order->invoice_number, // selalu ambil dari DB
        ]);
    }

}