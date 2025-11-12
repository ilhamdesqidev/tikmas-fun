@extends('layouts.app')

@section('title', 'Manajemen Tiket')
@section('page-title', 'Manajemen Tiket')
@section('page-description', 'Kelola semua pemesanan tiket di sini.')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header Section -->
    <div class="border-b border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Manajemen Tiket</h1>
                    <p class="text-gray-600 mt-1">Kelola semua pemesanan tiket</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Total Orders</p>
                    <p class="text-xl font-semibold text-gray-900">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-6">
        
        <!-- Search & Filter -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Search -->
                <form method="GET" action="{{ route('admin.tickets.index') }}" class="flex items-center gap-3">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari order atau customer..." 
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 w-80">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Cari
                    </button>
                </form>

                <div class="flex items-center gap-3">
                    <!-- Export Button -->
                    <div class="relative">
                        <button type="button" 
                                id="exportDropdownButton"
                                onclick="toggleExportDropdown()"
                                class="px-4 py-2 bg-green-600 text-white hover:bg-green-700 rounded-lg flex items-center gap-2 min-w-[140px]">
                            <i class="fas fa-download text-sm"></i>
                            <span>Export</span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="exportChevron"></i>
                        </button>

                        <!-- Export Dropdown Menu -->
                        <div id="exportDropdown" 
                             class="absolute right-0 mt-1 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-10 hidden">
                            <div class="py-2">
                                <!-- Promo Filter Section -->
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <label class="text-xs font-medium text-gray-500 uppercase block mb-2">Filter by Promo</label>
                                    <select id="exportPromoFilter" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <option value="all">Semua Promo</option>
                                        @foreach($promos as $promo)
                                            <option value="{{ $promo->id }}">
                                                {{ $promo->name }} ({{ $promo->orders_count ?? 0 }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Export Options -->
                                <div class="mt-2">
                                    <!-- Export All with Separation -->
                                    <a href="#" 
                                       onclick="exportAllSheets(event)"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 border-b border-gray-100">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="font-medium text-green-700">
                                                    <i class="fas fa-file-excel mr-1"></i>
                                                    Excel - Semua Status
                                                </div>
                                                <div class="text-xs text-gray-500">Terpisah per status dalam 1 file</div>
                                            </div>
                                        </div>
                                    </a>

                                    <div class="px-4 py-2 text-xs font-medium text-gray-500 uppercase">
                                        Export CSV Per Status
                                    </div>

                                    <!-- Export by Status -->
                                    @php
                                        $statuses = [
                                            'success' => ['label' => 'Success', 'color' => 'green'],
                                            'pending' => ['label' => 'Pending', 'color' => 'yellow'],
                                            'expired' => ['label' => 'Expired', 'color' => 'gray'],
                                            'canceled' => ['label' => 'Canceled', 'color' => 'red'],
                                            'used' => ['label' => 'Used', 'color' => 'blue'],
                                        ];
                                    @endphp
                                    
                                    @foreach($statuses as $status => $config)
                                        <a href="#" 
                                           onclick="exportByStatus(event, '{{ $status }}')"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full bg-{{ $config['color'] }}-500"></div>
                                                    <span><i class="fas fa-file-csv mr-1 text-xs"></i>{{ $config['label'] }}</span>
                                                </div>
                                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                    {{ $statusCounts[$status] ?? 0 }}
                                                </span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Filter Dropdown -->
                    <div class="relative">
                        <button type="button" 
                                id="filterDropdownButton"
                                onclick="toggleFilterDropdown()"
                                class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg flex items-center gap-2 min-w-[140px]">
                            <i class="fas fa-filter text-sm"></i>
                            <span id="filterButtonText">
                                @if(request('status'))
                                    @php
                                        $currentStatus = request('status');
                                        $statusLabels = [
                                            'pending' => 'Pending',
                                            'success' => 'Success',
                                            'expired' => 'Expired',
                                            'canceled' => 'Canceled',
                                            'used' => 'Used',
                                        ];
                                    @endphp
                                    {{ $statusLabels[$currentStatus] ?? ucfirst($currentStatus) }}
                                @else
                                    Filter Status
                                @endif
                            </span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="filterChevron"></i>
                        </button>

                        <!-- Filter Dropdown Menu -->
                        <div id="filterDropdown" 
                             class="absolute right-0 mt-1 w-56 bg-white border border-gray-200 rounded-lg shadow-lg z-10 hidden">
                            <div class="py-2">
                                <!-- All Orders Option -->
                                <a href="{{ route('admin.tickets.index') }}" 
                                   class="block px-4 py-2 text-sm {{ !request('status') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <div class="flex items-center justify-between">
                                        <span>Semua Status</span>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                            {{ $totalOrders }}
                                        </span>
                                    </div>
                                </a>

                                <hr class="my-1">

                                <!-- Status Options -->
                                @foreach($statuses as $status => $config)
                                    <a href="{{ route('admin.tickets.index', ['status' => $status]) }}" 
                                       class="block px-4 py-2 text-sm {{ request('status') == $status ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-{{ $config['color'] }}-500"></div>
                                                <span>{{ $config['label'] }}</span>
                                            </div>
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                {{ $statusCounts[$status] ?? 0 }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Filter Display -->
            @if(request('status'))
                <div class="mt-3 flex items-center gap-2">
                    <span class="text-sm text-gray-600">Filter aktif:</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full flex items-center gap-2">
                        {{ $statusLabels[request('status')] ?? ucfirst(request('status')) }}
                        <a href="{{ route('admin.tickets.index') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </span>
                </div>
            @endif
        </div>

        <!-- Table -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paket Promo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">WhatsApp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Domisili</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Visit Date</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}
                            </td>
                            <td class="px-6 py-4">
                                @if($order->promo)
                                    <div class="text-sm font-medium text-gray-900">{{ $order->promo->name }}</div>
                                    <div class="text-xs text-gray-500">{{ ucfirst($order->promo->category) }}</div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                                @if($order->invoice_number)
                                    <div class="text-xs text-blue-600">{{ $order->invoice_number }}</div>
                                @endif
                                <div class="text-xs text-gray-500">
                                    {{ $order->created_at->format('d M Y H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->whatsapp_number) }}" target="_blank" 
                                   class="text-sm text-green-600 hover:text-green-800 flex items-center gap-1">
                                    <i class="fab fa-whatsapp"></i>
                                    {{ $order->whatsapp_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $order->domicile ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 text-center">
                                {{ $order->ticket_quantity }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'success' => 'text-green-700 bg-green-50 border border-green-200',
                                        'pending' => 'text-yellow-700 bg-yellow-50 border border-yellow-200',
                                        'canceled' => 'text-red-700 bg-red-50 border border-red-200',
                                        'challenge' => 'text-orange-700 bg-orange-50 border border-orange-200',
                                        'denied' => 'text-red-700 bg-red-50 border border-red-200',
                                        'expired' => 'text-gray-700 bg-gray-50 border border-gray-200'
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-xs rounded-full font-medium {{ $statusColors[$order->status] ?? 'text-gray-700 bg-gray-50 border border-gray-200' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-inbox text-2xl mb-2"></i>
                                    <p>Tidak ada data tiket</p>
                                    @if(request('search') || request('status'))
                                        <a href="{{ route('admin.tickets.index') }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">
                                            Tampilkan semua tiket
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="flex items-center justify-between mt-6 text-sm text-gray-700">
            <div>
                Menampilkan {{ $orders->firstItem() ?? 0 }} sampai {{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} hasil
            </div>
            <div>
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    // Export functions
    function exportAllSheets(event) {
        event.preventDefault();
        const promoId = document.getElementById('exportPromoFilter').value;
        const url = '{{ route("admin.tickets.exportAll") }}' + '?promo_id=' + promoId;
        window.location.href = url;
        
        // Close dropdown after short delay
        setTimeout(() => {
            document.getElementById('exportDropdown').classList.add('hidden');
            document.getElementById('exportChevron').classList.remove('rotate-180');
        }, 300);
    }

    function exportByStatus(event, status) {
        event.preventDefault();
        const promoId = document.getElementById('exportPromoFilter').value;
        const url = '{{ route("admin.tickets.export") }}' + '?status=' + status + '&promo_id=' + promoId;
        window.location.href = url;
        
        // Close dropdown after short delay
        setTimeout(() => {
            document.getElementById('exportDropdown').classList.add('hidden');
            document.getElementById('exportChevron').classList.remove('rotate-180');
        }, 300);
    }

    // Export dropdown functionality
    function toggleExportDropdown() {
        const dropdown = document.getElementById('exportDropdown');
        const chevron = document.getElementById('exportChevron');
        const filterDropdown = document.getElementById('filterDropdown');
        
        // Close filter dropdown if open
        filterDropdown.classList.add('hidden');
        document.getElementById('filterChevron').classList.remove('rotate-180');
        
        dropdown.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }

    // Filter dropdown functionality
    function toggleFilterDropdown() {
        const dropdown = document.getElementById('filterDropdown');
        const chevron = document.getElementById('filterChevron');
        const exportDropdown = document.getElementById('exportDropdown');
        
        // Close export dropdown if open
        exportDropdown.classList.add('hidden');
        document.getElementById('exportChevron').classList.remove('rotate-180');
        
        dropdown.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const filterButton = document.getElementById('filterDropdownButton');
        const filterDropdown = document.getElementById('filterDropdown');
        const filterChevron = document.getElementById('filterChevron');
        
        const exportButton = document.getElementById('exportDropdownButton');
        const exportDropdown = document.getElementById('exportDropdown');
        const exportChevron = document.getElementById('exportChevron');
        
        // Close filter dropdown
        if (!filterButton.contains(event.target) && !filterDropdown.contains(event.target)) {
            filterDropdown.classList.add('hidden');
            filterChevron.classList.remove('rotate-180');
        }
        
        // Close export dropdown
        if (!exportButton.contains(event.target) && !exportDropdown.contains(event.target)) {
            exportDropdown.classList.add('hidden');
            exportChevron.classList.remove('rotate-180');
        }
    });
</script>
@endsection