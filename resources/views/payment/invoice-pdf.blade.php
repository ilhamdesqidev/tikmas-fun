<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoiceNumber }} - Mestakara</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            background: #fafafa;
            line-height: 1.5;
            font-size: 14px;
            padding: 20px;
        }
        
        .invoice-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .invoice-header {
            padding: 25px 30px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }
        
        .invoice-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #222;
            margin-bottom: 5px;
        }
        
        .invoice-header p {
            color: #666;
            font-size: 14px;
        }
        
        .invoice-body {
            padding: 30px;
        }
        
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .invoice-info div {
            flex: 1;
        }
        
        .info-label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #222;
        }
        
        .customer-info {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #222;
            display: flex;
            align-items: center;
        }
        
        .section-title::before {
            content: "";
            display: inline-block;
            width: 4px;
            height: 16px;
            background: #4f46e5;
            margin-right: 10px;
            border-radius: 2px;
        }
        
        .customer-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .detail-item {
            margin-bottom: 10px;
        }
        
        .detail-label {
            font-size: 13px;
            color: #666;
            margin-bottom: 3px;
        }
        
        .detail-value {
            font-size: 15px;
            font-weight: 500;
            color: #222;
        }
        
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .order-table th {
            text-align: left;
            padding: 12px 15px;
            background: #f8f9fa;
            font-weight: 600;
            color: #444;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .order-table td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .total-label {
            font-size: 16px;
            font-weight: 600;
        }
        
        .total-amount {
            font-size: 20px;
            font-weight: 700;
            color: #4f46e5;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #ecfdf5;
            color: #065f46;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
        
        .barcode-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .barcode-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .barcode-subtitle {
            font-size: 13px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .barcode-container {
            display: inline-block;
            padding: 15px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }
        
        .barcode-placeholder {
            width: 200px;
            height: 80px;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-family: monospace;
            letter-spacing: 2px;
        }
        
        .instructions {
            background: #f0f9ff;
            padding: 20px;
            border-radius: 6px;
            margin-top: 20px;
        }
        
        .instructions-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #0369a1;
        }
        
        .instructions ul {
            list-style: none;
        }
        
        .instructions li {
            padding: 5px 0;
            display: flex;
            align-items: flex-start;
        }
        
        .instructions li::before {
            content: "•";
            color: #0369a1;
            font-weight: bold;
            margin-right: 8px;
        }
        
        .invoice-footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 13px;
        }
        
        .footer-contact {
            margin-top: 5px;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .invoice-body {
                padding: 20px;
            }
            
            .invoice-info {
                flex-direction: column;
                gap: 15px;
            }
            
            .customer-details {
                grid-template-columns: 1fr;
            }
            
            .order-table {
                display: block;
                overflow-x: auto;
            }
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .invoice-wrapper {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper">
        <!-- Header -->
        <div class="invoice-header">
            <h1>Mestakara</h1>
            <p>Wisata dan Hiburan Keluarga • E-Ticket & Invoice</p>
        </div>
        
        <!-- Body -->
        <div class="invoice-body">
            <!-- Invoice Info -->
            <div class="invoice-info">
                <div>
                    <div class="info-label">Invoice #</div>
                    <div class="info-value">{{ $invoiceNumber }}</div>
                </div>
                <div style="text-align: right;">
                    <div class="info-label">Tanggal</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</div>
                </div>
            </div>
            
            <!-- Customer Info -->
            <div class="customer-info">
                <div class="section-title">Informasi Pemesan</div>
                <div class="customer-details">
                    <div class="detail-item">
                        <div class="detail-label">Nama Lengkap</div>
                        <div class="detail-value">{{ $order->customer_name }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">WhatsApp</div>
                        <div class="detail-value">{{ $order->whatsapp_number }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Cabang</div>
                        <div class="detail-value">{{ $order->branch ?? 'Cabang Utama' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Kunjungan</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Order Details -->
            <div class="section-title">Detail Pesanan</div>
            <table class="order-table">
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
                            <strong>{{ $promo->name }}</strong>
                            <div style="font-size: 13px; color: #666; margin-top: 3px;">
                                Tiket masuk {{ $promo->name }}
                            </div>
                        </td>
                        <td class="text-center">{{ $order->ticket_quantity }}</td>
                        <td class="text-right">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Total -->
            <div class="total-section">
                <div class="total-label">Total Pembayaran</div>
                <div>
                    <span class="total-amount">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    <span class="status-badge">LUNAS</span>
                </div>
            </div>
            
            <!-- Barcode -->
            <div class="barcode-section">
                <div class="barcode-title">Barcode E-Ticket</div>
                <div class="barcode-subtitle">Tunjukkan barcode ini saat check-in</div>
                
                <div class="barcode-container">
                    @if(isset($barcodeImage))
                        <img src="{{ $barcodeImage }}" alt="Barcode" style="max-width: 100%; height: auto;">
                    @else
                        <div class="barcode-placeholder">
                            BARCODE: {{ $order->order_number }}
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="instructions">
                <div class="instructions-title">Instruksi Kunjungan</div>
                <ul>
                    <li>Tunjukkan barcode kepada pelugas untuk di-scan</li>
                    <li>E-ticket hanya dapat digunakan sekali</li>
                    <li>Valid untuk kunjungan {{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</li>
                    <li>Jumlah pengunjung {{ $order->ticket_quantity }} orang</li>
                    <li>Harap datang tepat waktu sesuai jadwal kunjungan</li>
                </ul>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="invoice-footer">
            <p>Terima kasih telah memilih Mestakara</p>
            <p class="footer-contact">Untuk pertanyaan, hubungi WhatsApp: +62 812-3456-7890</p>
            <p style="margin-top: 5px;">Invoice generated on: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>