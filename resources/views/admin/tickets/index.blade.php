@extends('layouts.app')

@section('title', 'Management Tiket')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Management Tiket</h1>
        <p class="text-gray-600">Kelola data tiket dan transaksi pengunjung</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Tiket</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Success</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($statusCounts['success']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($statusCounts['pending']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-500">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Issues</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($statusCounts['challenge'] + $statusCounts['denied'] + $statusCounts['expired'] + $statusCounts['canceled']) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">📊 Export Data Tiket</h2>
        
        <!-- Single Status Export -->
        <div class="mb-6">
            <h3 class="text-md font-semibold mb-3">Export per Status</h3>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.tickets.export', ['status' => 'all']) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Semua CSV
                </a>
                
                <a href="{{ route('admin.tickets.export', ['status' => 'success']) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Export Success CSV
                </a>
                
                <a href="{{ route('admin.tickets.export', ['status' => 'pending']) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Export Pending CSV
                </a>

                <a href="{{ route('admin.tickets.export', ['status' => 'challenge']) }}" 
                   class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Export Challenge CSV
                </a>

                <a href="{{ route('admin.tickets.export', ['status' => 'expired']) }}" 
                   class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Export Expired CSV
                </a>
            </div>
        </div>

        <!-- All Status Export -->
        <div class="mb-6">
            <h3 class="text-md font-semibold mb-3">Export Semua Status</h3>
            <a href="{{ route('admin.tickets.exportAll') }}" 
               class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Export All Status (Multi-section CSV)
            </a>
            <p class="text-sm text-gray-600 mt-1">File CSV dengan semua status dalam satu file terpisah per section</p>
        </div>

        <!-- Filter by Promo -->
        @if(isset($promos) && $promos->count() > 0)
        <div class="mb-4">
            <h3 class="text-md font-semibold mb-3">Export by Promo</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($promos as $promo)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded border">
                    <div>
                        <span class="font-medium text-gray-800">{{ $promo->name }}</span>
                        <span class="text-sm text-gray-500 ml-2">({{ $promo->orders_count }} orders)</span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.tickets.export', ['status' => 'all', 'promo_id' => $promo->id]) }}" 
                           class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded transition duration-200">
                            CSV
                        </a>
                        <a href="{{ route('admin.tickets.exportAll', ['promo_id' => $promo->id]) }}" 
                           class="px-3 py-1 bg-purple-500 hover:bg-purple-600 text-white text-sm rounded transition duration-200">
                            All CSV
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Status Filter -->
            <div class="flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700">Filter Status:</label>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}" 
                       class="px-3 py-1 rounded-full text-sm {{ ($status ?? 'all') == 'all' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        All ({{ $totalOrders }})
                    </a>
                    @foreach($statusCounts as $statusKey => $count)
                    @if($count > 0)
                    <a href="{{ request()->fullUrlWithQuery(['status' => $statusKey]) }}" 
                       class="px-3 py-1 rounded-full text-sm {{ ($status ?? '') == $statusKey ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        {{ ucfirst($statusKey) }} ({{ $count }})
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.tickets.index') }}" class="flex gap-2">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari nama, order number, whatsapp..." 
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                @if($search || $status)
                <a href="{{ route('admin.tickets.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                    Reset
                </a>
                @endif
            </form>
        </div>
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