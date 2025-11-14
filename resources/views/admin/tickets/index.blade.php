@extends('layouts.app')

@section('title', 'Management Tiket')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Management Tiket</h1>
        <p class="text-gray-600">Kelola data tiket dan transaksi pengunjung</p>
    </div>

    <!-- Stats Cards - Simplified -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-600">Total Tiket</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-600">Success</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($statusCounts['success']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-600">Pending</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($statusCounts['pending']) }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="text-sm text-gray-600">Issues</p>
            <p class="text-2xl font-bold text-gray-900">
                {{ number_format($statusCounts['challenge'] + $statusCounts['denied'] + $statusCounts['expired'] + $statusCounts['canceled']) }}
            </p>
        </div>
    </div>

    <!-- Export Section - Simplified Dropdown -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Filter Promo -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Promo</label>
                <select id="exportPromoFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="all">Semua Promo</option>
                    @if(isset($promos))
                        @foreach($promos as $promo)
                            <option value="{{ $promo->id }}">{{ $promo->name }} ({{ $promo->orders_count }} tiket)</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Export Dropdown -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Export to Excel</label>
                <select id="exportStatusSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Status Export --</option>
                    <option value="all">📊 Semua Status ({{ number_format($totalOrders) }})</option>
                    <option value="success">✅ Success ({{ number_format($statusCounts['success']) }})</option>
                    <option value="pending">⏳ Pending ({{ number_format($statusCounts['pending']) }})</option>
                    <option value="challenge">⚠️ Challenge ({{ number_format($statusCounts['challenge']) }})</option>
                    <option value="denied">❌ Denied ({{ number_format($statusCounts['denied']) }})</option>
                    <option value="expired">⏰ Expired ({{ number_format($statusCounts['expired']) }})</option>
                    <option value="canceled">🚫 Canceled ({{ number_format($statusCounts['canceled']) }})</option>
                    <option value="multi-sheet">📑 Multi-Sheet (All Status)</option>
                </select>
            </div>

            <!-- Export Button -->
            <div class="flex items-end">
                <button onclick="handleExport()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export
                </button>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Status Filter -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}" 
                       class="px-3 py-1 rounded-lg text-sm {{ ($status ?? 'all') == 'all' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        All ({{ $totalOrders }})
                    </a>
                    @foreach($statusCounts as $statusKey => $count)
                    @if($count > 0)
                    <a href="{{ request()->fullUrlWithQuery(['status' => $statusKey]) }}" 
                       class="px-3 py-1 rounded-lg text-sm {{ ($status ?? '') == $statusKey ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        {{ ucfirst($statusKey) }} ({{ $count }})
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>

            <!-- Search Form -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <form method="GET" action="{{ route('admin.tickets.index') }}" class="flex gap-2">
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Cari nama, order number, whatsapp..." 
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Promo</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">WhatsApp</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Domisili</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Visit Date</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}
                        </td>
                        <td class="px-4 py-3">
                            @if($order->promo)
                                <div class="text-sm font-medium text-gray-900">{{ $order->promo->name }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst($order->promo->category) }}</div>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                            @if($order->invoice_number)
                                <div class="text-xs text-blue-600">{{ $order->invoice_number }}</div>
                            @endif
                            <div class="text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $order->customer_name }}</td>
                        <td class="px-4 py-3">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->whatsapp_number) }}" target="_blank" 
                               class="text-sm text-green-600 hover:text-green-800">
                                {{ $order->whatsapp_number }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $order->domicile ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 text-center">{{ $order->ticket_quantity }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900 text-right">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $statusColors = [
                                    'success' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'canceled' => 'bg-red-100 text-red-800',
                                    'challenge' => 'bg-orange-100 text-orange-800',
                                    'denied' => 'bg-red-100 text-red-800',
                                    'expired' => 'bg-gray-100 text-gray-800'
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="font-medium mb-2">Tidak ada data tiket</p>
                                @if(request('search') || request('status'))
                                    <a href="{{ route('admin.tickets.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
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

<!-- Loading Overlay -->
<div id="exportLoadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 shadow-xl">
        <div class="flex items-center space-x-3">
            <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <div>
                <p class="font-bold text-gray-800">Generating Excel...</p>
                <p class="text-sm text-gray-600">Mohon tunggu sebentar</p>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Handle export based on selected status
 */
function handleExport() {
    const statusSelect = document.getElementById('exportStatusSelect');
    const status = statusSelect.value;
    
    if (!status) {
        alert('Silakan pilih status export terlebih dahulu!');
        return;
    }
    
    if (status === 'multi-sheet') {
        exportAllStatuses();
    } else {
        exportTickets(status);
    }
    
    // Reset dropdown
    statusSelect.value = '';
}

/**
 * Export tickets by status dengan promo filter
 */
function exportTickets(status) {
    const promoId = document.getElementById('exportPromoFilter').value;
    const url = `{{ route('admin.tickets.export') }}?status=${status}&promo_id=${promoId}`;
    
    showExportLoading();
    window.location.href = url;
    setTimeout(hideExportLoading, 2000);
}

/**
 * Export all statuses in multiple sheets
 */
function exportAllStatuses() {
    const promoId = document.getElementById('exportPromoFilter').value;
    const url = `{{ route('admin.tickets.exportAll') }}?promo_id=${promoId}`;
    
    showExportLoading();
    window.location.href = url;
    setTimeout(hideExportLoading, 3000);
}

/**
 * Show/Hide loading indicator
 */
function showExportLoading() {
    document.getElementById('exportLoadingOverlay')?.classList.remove('hidden');
}

function hideExportLoading() {
    document.getElementById('exportLoadingOverlay')?.classList.add('hidden');
}

// Auto-hide loading on window focus
window.addEventListener('focus', function() {
    setTimeout(hideExportLoading, 1000);
});
</script>

@endsection