<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoiceNumber }} - MestaKara</title>
    <style>
        @page {
            margin: 0;
            size: A4;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            background: white;
            line-height: 1.5;
            font-size: 11pt;
        }
        
        .invoice-container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            background: white;
        }
        
        /* Header */
        .invoice-header {
            background: #CFD916;
            padding: 35px 45px;
            color: #1a1a1a;
            border-bottom: 3px solid #b8c414;
        }
        
        .header-flex {
            display: table;
            width: 100%;
        }
        
        .header-left, .header-right {
            display: table-cell;
            vertical-align: middle;
        }
        
        .header-right {
            text-align: right;
        }
        
        .company-name {
            font-size: 28pt;
            font-weight: 700;
            margin-bottom: 3px;
        }
        
        .company-tagline {
            font-size: 10pt;
            color: #4a4a4a;
        }
        
        .invoice-type {
            background: white;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 9pt;
            font-weight: 700;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* Body */
        .invoice-body {
            padding: 35px 45px;
        }
        
        /* Invoice Info Bar */
        .info-bar {
            background: #f8f9fa;
            padding: 20px 25px;
            border-radius: 6px;
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-item {
            display: table-cell;
            width: 33.33%;
            vertical-align: top;
        }
        
        .info-label {
            font-size: 9pt;
            color: #6c757d;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 12pt;
            font-weight: 700;
            color: #1a1a1a;
        }
        
        .status-badge {
            display: inline-block;
            background: #d4edda;
            color: #155724;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 9pt;
            font-weight: 700;
            border: 1px solid #c3e6cb;
        }
        
        /* Customer Section */
        .section-box {
            margin: 25px 0;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .section-header {
            background: #f8f9fa;
            padding: 12px 20px;
            border-bottom: 1px solid #e9ecef;
            font-size: 11pt;
            font-weight: 700;
            color: #495057;
        }
        
        .section-content {
            padding: 20px;
        }
        
        .detail-grid {
            display: table;
            width: 100%;
        }
        
        .detail-row {
            display: table-row;
        }
        
        .detail-cell {
            display: table-cell;
            padding: 8px 15px 8px 0;
            width: 50%;
            vertical-align: top;
        }
        
        .detail-label {
            font-size: 9pt;
            color: #6c757d;
            margin-bottom: 3px;
            font-weight: 500;
        }
        
        .detail-value {
            font-size: 11pt;
            font-weight: 600;
            color: #1a1a1a;
        }
        
        /* Order Table */
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .order-table thead {
            background: #343a40;
            color: white;
        }
        
        .order-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 10pt;
        }
        
        .order-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            font-size: 10pt;
        }
        
        .order-table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .item-name {
            font-weight: 700;
            color: #1a1a1a;
            font-size: 11pt;
            margin-bottom: 2px;
        }
        
        .item-desc {
            font-size: 9pt;
            color: #6c757d;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        /* Total Box */
        .total-box {
            background: #f8f9fa;
            border: 2px solid #CFD916;
            border-radius: 6px;
            padding: 20px 25px;
            margin: 25px 0;
        }
        
        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        
        .total-row:last-child {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 2px solid #dee2e6;
        }
        
        .total-label, .total-value {
            display: table-cell;
            vertical-align: middle;
        }
        
        .total-label {
            font-size: 11pt;
            color: #495057;
        }
        
        .total-value {
            text-align: right;
            font-size: 11pt;
            font-weight: 600;
        }
        
        .grand-total .total-label {
            font-size: 13pt;
            font-weight: 700;
            color: #1a1a1a;
        }
        
        .grand-total .total-value {
            font-size: 16pt;
            font-weight: 700;
            color: #1a1a1a;
        }
        
        /* Barcode Section */
        .barcode-section {
            text-align: center;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 6px;
            margin: 25px 0;
            border: 1px solid #e9ecef;
        }
        
        .barcode-title {
            font-size: 12pt;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 4px;
        }
        
        .barcode-subtitle {
            font-size: 9pt;
            color: #6c757d;
            margin-bottom: 18px;
        }
        
        .barcode-wrapper {
            display: inline-block;
            padding: 15px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }
        
        .barcode-wrapper img {
            display: block;
            max-width: 280px;
            height: auto;
        }
        
        .barcode-id {
            margin-top: 10px;
            font-size: 8pt;
            color: #adb5bd;
            font-family: monospace;
            letter-spacing: 1px;
        }
        
        /* Instructions */
        .instructions-box {
            background: #fff9e6;
            border: 1px solid #ffe69c;
            border-left: 4px solid #ffc107;
            padding: 18px 22px;
            border-radius: 4px;
            margin: 20px 0;
        }
        
        .instructions-title {
            font-size: 11pt;
            font-weight: 700;
            color: #856404;
            margin-bottom: 10px;
        }
        
        .instructions-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .instructions-list li {
            padding: 5px 0;
            font-size: 10pt;
            color: #856404;
            position: relative;
            padding-left: 18px;
        }
        
        .instructions-list li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #ffc107;
            font-weight: 700;
            font-size: 14pt;
            line-height: 10pt;
        }
        
        /* Footer */
        .invoice-footer {
            background: #343a40;
            color: white;
            padding: 25px 45px;
            text-align: center;
        }
        
        .footer-thanks {
            font-size: 11pt;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .footer-contact {
            font-size: 9pt;
            color: #dee2e6;
            margin: 4px 0;
        }
        
        .footer-contact a {
            color: #CFD916;
            text-decoration: none;
            font-weight: 600;
        }
        
        .footer-divider {
            height: 1px;
            background: #495057;
            margin: 15px 0;
        }
        
        .footer-timestamp {
            font-size: 8pt;
            color: #adb5bd;
        }
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="header-flex">
                <div class="header-left">
                    <div class="company-name">MestaKara</div>
                    <div class="company-tagline">Wisata dan Hiburan Keluarga</div>
                </div>
                <div class="header-right">
                    <div class="invoice-type">E-TICKET</div>
                </div>
            </div>
        </div>
        
        <!-- Body -->
        <div class="invoice-body">
            <!-- Invoice Info Bar -->
            <div class="info-bar">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Invoice Number</div>
                        <div class="info-value">#{{ $invoiceNumber }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</div>
                    </div>
                    <div class="info-item" style="text-align: right;">
                        <div class="info-label">Status</div>
                        <div><span class="status-badge">LUNAS</span></div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Section -->
            <div class="section-box">
                <div class="section-header">Informasi Pemesan</div>
                <div class="section-content">
                    <div class="detail-grid">
                        <div class="detail-row">
                            <div class="detail-cell">
                                <div class="detail-label">Nama Lengkap</div>
                                <div class="detail-value">{{ $order->customer_name }}</div>
                            </div>
                            <div class="detail-cell">
                                <div class="detail-label">Nomor WhatsApp</div>
                                <div class="detail-value">{{ $order->whatsapp_number }}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-cell">
                                <div class="detail-label">Cabang</div>
                                <div class="detail-value">{{ $order->branch ?? 'Cabang Utama' }}</div>
                            </div>
                            <div class="detail-cell">
                                <div class="detail-label">Tanggal Kunjungan</div>
                                <div class="detail-value">{{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Details -->
            <div class="section-box">
                <div class="section-header">Detail Pesanan</div>
                <div class="section-content" style="padding: 0;">
                    <table class="order-table" style="border: none;">
                        <thead>
                            <tr>
                                <th style="width: 50%;">Item</th>
                                <th class="text-center" style="width: 15%;">Qty</th>
                                <th class="text-right" style="width: 17.5%;">Harga</th>
                                <th class="text-right" style="width: 17.5%;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="item-name">{{ $promo->name }}</div>
                                    <div class="item-desc">Tiket masuk wahana {{ $promo->name }}</div>
                                </td>
                                <td class="text-center" style="font-weight: 700;">{{ $order->ticket_quantity }}</td>
                                <td class="text-right">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</td>
                                <td class="text-right" style="font-weight: 700;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Total -->
            <div class="total-box">
                <div class="total-row">
                    <div class="total-label">Subtotal</div>
                    <div class="total-value">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                </div>
                <div class="total-row">
                    <div class="total-label">Pajak & Biaya Admin</div>
                    <div class="total-value">Rp 0</div>
                </div>
                <div class="total-row grand-total">
                    <div class="total-label">TOTAL PEMBAYARAN</div>
                    <div class="total-value">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                </div>
            </div>
            
            <!-- Barcode -->
            <div class="barcode-section">
                <div class="barcode-title">E-Ticket Barcode</div>
                <div class="barcode-subtitle">Tunjukkan barcode ini saat check-in</div>
                
                <div class="barcode-wrapper">
                    @if(isset($barcodeImage))
                        <img src="{{ $barcodeImage }}" alt="Barcode">
                    @else
                        <div style="padding: 25px; background: #f5f5f5;">
                            <div style="font-family: monospace; font-size: 12pt; font-weight: 700; color: #666; letter-spacing: 2px;">
                                {{ $order->order_number }}
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="barcode-id">ORDER: {{ $order->order_number }}</div>
            </div>
            
            <!-- Instructions -->
            <div class="instructions-box">
                <div class="instructions-title">Panduan Penggunaan</div>
                <ul class="instructions-list">
                    <li>Tunjukkan barcode kepada petugas untuk di-scan</li>
                    <li>E-ticket berlaku untuk satu kali kunjungan</li>
                    <li>Valid untuk tanggal: <strong>{{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</strong></li>
                    <li>Jumlah pengunjung: <strong>{{ $order->ticket_quantity }} orang</strong></li>
                    <li>Harap datang tepat waktu sesuai jadwal kunjungan</li>
                </ul>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-thanks">Terima kasih telah memilih MestaKara</div>
            <div class="footer-contact">
                Hubungi kami: WhatsApp 
                <a href="https://wa.me/62{{ preg_replace('/[^0-9]/', '', $contactWhatsapp ?? '8123456789') }}">
                    +62 {{ $contactWhatsapp ?? '812-3456-7890' }}
                </a>
            </div>
            <div class="footer-divider"></div>
            <div class="footer-timestamp">
                Generated: {{ \Carbon\Carbon::now()->format('d M Y, H:i') }} WIB
            </div>
        </div>
    </div>
</body>
</html>