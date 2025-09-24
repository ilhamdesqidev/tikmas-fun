<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Belum Selesai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        
        .status-icon {
            font-size: 4rem;
            color: #ffc107;
            margin-bottom: 1rem;
        }
        
        h1 {
            color: #333;
            margin-bottom: 1rem;
        }
        
        p {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        
        .order-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            text-align: left;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="status-icon">🕐</div>
        <h1>Pembayaran Belum Selesai</h1>
        <p>Pembayaran Anda belum selesai diproses. Silakan selesaikan pembayaran atau coba lagi.</p>
        
        <div class="order-info">
            <strong>Detail Pesanan:</strong><br>
            No. Order: {{ $order->order_number }}<br>
            Nama: {{ $order->customer_name }}<br>
            Jumlah Tiket: {{ $order->ticket_quantity }}<br>
            Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}
        </div>
        
        <div class="btn-group">
            <a href="{{ route('payment.checkout', ['order_id' => $order->order_number]) }}" class="btn btn-primary">
                Coba Lagi
            </a>
            <a href="{{ url('/') }}" class="btn btn-secondary">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>