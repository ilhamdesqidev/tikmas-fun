<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoiceNumber }} - MestaKara</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background: white;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .header {
            background: #FFD700;
            padding: 30px;
            text-align: center;
            color: black;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        
        .content {
            padding: 30px;
        }
        
        .section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .section:last-child {
            border-bottom: none;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .barcode-container {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            border: 2px dashed #e5e7eb;
            border-radius: 5px;
            background: white;
        }
        
        .barcode-image {
            max-width: 100%;
            height: auto;
            margin: 15px 0;
        }
        
        .total {
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        
        .instruction-box {
            background-color: #fffbeb;
            border: 1px solid #f59e0b;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>MestaKara</h1>
            <p>Wisata dan Hiburan Keluarga</p>
            <p><strong>E-Ticket & Invoice</strong></p>
        </div>
        
        <div class="content">
            <!-- Invoice Info -->
            <div class="section">
                <div class="grid-2">
                    <div>
                        <h3>INVOICE #</h3>
                        <p style="font-size: 18px; font-weight: bold;">{{ $invoiceNumber }}</p>
                    </div>
                    <div style="text-align: right;">
                        <h3>TANGGAL</h3>
                        <p style="font-size: 18px;">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Customer Info -->
            <div class="section">
                <h3>Informasi Pemesan</h3>
                <div class="grid-2">
                    <div>
                        <p><strong>Nama Lengkap:</strong> {{ $order->customer_name }}</p>
                        <p><strong>WhatsApp:</strong> {{ $order->whatsapp_number }}</p>
                    </div>
                    <div>
                        <p><strong>Cabang:</strong> {{ $order->branch ?? 'Cabang Utama' }}</p>
                        <p><strong>Tanggal Kunjungan:</strong> {{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Order Details -->
            <div class="section">
                <h3>Detail Pesanan</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Harga</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>{{ $promo->name }}</strong><br>
                                <small>Tiket masuk {{ $promo->name }}</small>
                            </td>
                            <td class="text-center">{{ $order->ticket_quantity }}</td>
                            <td class="text-right">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Total -->
            <div class="section">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 18px; font-weight: bold;">Total Pembayaran</span>
                    <span class="total">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                <div style="text-align: right; margin-top: 10px;">
                    <strong>Status: </strong>
                    <span style="color: #10b981; font-weight: bold;">LUNAS</span>
                </div>
            </div>
            
            <!-- Barcode Section dengan REAL BARCODE -->
            <div class="section" style="text-align: center;">
                <h3>Barcode E-Ticket</h3>
                <p>Scan barcode ini saat check-in</p>
                
                <div class="barcode-container">
                    @if(isset($barcodeImage))
                        <!-- Real scannable barcode image -->
                        <img src="{{ $barcodeImage }}" alt="Barcode" class="barcode-image" style="max-width: 300px; height: auto;">
                    @elseif(isset($barcodeHTML))
                        <!-- HTML barcode fallback -->
                        <div style="display: inline-block;">
                            {!! $barcodeHTML !!}
                        </div>
                    @else
                        <!-- Emergency fallback -->
                        <div style="border: 2px solid #000; padding: 10px; background: #000; color: #fff; font-family: monospace; font-size: 14px; letter-spacing: 1px;">
                            BARCODE: {{ $order->order_number }}
                        </div>
                    @endif
                    
                    <!-- Order number di bawah barcode -->
                    <div style="font-family: 'Courier New', monospace; font-size: 14px; font-weight: bold; margin-top: 10px; letter-spacing: 2px;">
                        {{ $order->order_number }}
                    </div>
                </div>
                
                <div class="instruction-box">
                    <h4>📋 Instruksi Kunjungan:</h4>
                    <ul style="text-align: left; padding-left: 20px;">
                        <li><strong>SCAN BARCODE</strong> kepada petugas untuk check-in</li>
                        <li>E-ticket hanya dapat digunakan sekali</li>
                        <li>Valid untuk tanggal {{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</li>
                        <li>Jumlah pengunjung: {{ $order->ticket_quantity }} orang</li>
                        <li>Harap datang tepat waktu sesuai jadwal kunjungan</li>
                        <li>Jika barcode tidak bisa di-scan, tunjukkan kode: <strong>{{ $order->order_number }}</strong></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih telah memilih MestaKara</p>
            <p>Untuk pertanyaan, hubungi WhatsApp: +62 812-3456-7890</p>
            <p>Invoice generated on: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>