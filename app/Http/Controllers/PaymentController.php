<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promo;
use App\Models\Order;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

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
                'email'      => 'customer@example.com', // Tambahkan email
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
                    'unfinish' => route('payment.unfinish'), // Tambahkan ini
                    'error' => route('payment.error'), // Tambahkan ini
                ],
            ];

            // Default snapToken null
            $snapToken = null;

            try {
                // Dapatkan Snap Token dari Midtrans
                $snapToken = Snap::getSnapToken($transactionData);

                // Simpan snap token ke order
                $order->snap_token = $snapToken;
                $order->save();
            } catch (\Exception $e) {
                // Catat error ke log supaya bisa dicek
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
    $order   = Order::where('order_number', $orderId)->firstOrFail();

    // Status order ditentukan dari webhook, bukan dari sini
    return view('payment.finish', compact('order'));
}

public function paymentUnfinish(Request $request)
{
    $orderId = $request->order_id;
    $order = Order::where('order_number', $orderId)->firstOrFail();
    return view('payment.unfinish', compact('order'));
}

public function paymentError(Request $request)
{
    $orderId = $request->order_id;
    $order = Order::where('order_number', $orderId)->firstOrFail();
    return view('payment.error', compact('order'));
}


public function notificationHandler(Request $request)
{
    $notif = new \Midtrans\Notification();

    $orderId = $notif->order_id;
    $transactionStatus = $notif->transaction_status;
    $fraudStatus = $notif->fraud_status;

    $order = Order::where('order_number', $orderId)->first();

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    if ($transactionStatus == 'capture') {
        if ($fraudStatus == 'challenge') {
            $order->status = 'challenge';
        } else if ($fraudStatus == 'accept') {
            $order->status = 'paid';
        }
    } else if ($transactionStatus == 'settlement') {
        $order->status = 'paid';
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

    return response()->json(['message' => 'Notification processed']);
}

}