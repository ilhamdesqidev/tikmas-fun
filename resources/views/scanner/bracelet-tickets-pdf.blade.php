<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Gelang - {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: white;
            margin: 0;
            padding: 0;
            width: 297mm;
            height: 210mm;
        }

        .page {
            width: 297mm;
            height: 210mm;
            page-break-after: always;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(4, 1fr);
            gap: 0;
            padding: 0;
        }

        .ticket {
            width: 99mm;
            height: 52.5mm;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .ticket-image {
            max-width: 95%;
            max-height: 95%;
            object-fit: contain;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .page {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    @php
        $totalTickets = count($tickets);
        $ticketsPerPage = 12; // 3 kolom x 4 baris
        $totalPages = ceil($totalTickets / $ticketsPerPage);
    @endphp

    @for ($page = 0; $page < $totalPages; $page++)
        @php
            $startIndex = $page * $ticketsPerPage;
            $endIndex = min($startIndex + $ticketsPerPage, $totalTickets);
            $ticketsInThisPage = $endIndex - $startIndex;
        @endphp

        <div class="page">
            @for ($i = $startIndex; $i < $endIndex; $i++)
                @php $ticket = $tickets[$i]; @endphp
                    @if($bracelet_design_path && file_exists($bracelet_design_path))
                        <img src="data:image/{{ pathinfo($bracelet_design_path, PATHINFO_EXTENSION) }};base64,{{ base64_encode(file_get_contents($bracelet_design_path)) }}" 
                             class="ticket-image" 
                             alt="Desain Gelang">
                    @else
                        <!-- Fallback jika desain tidak tersedia -->
                        <div style="text-align: center; font-size: 14px;">
                            DESAIN GELANG<br>
                            {{ $i + 1 }}
                        </div>
                    @endif
            @endfor
        </div>
    @endfor
</body>
</html>