@extends('layouts.app')

@section('title', 'Management Tiket')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header with Gradient -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl p-8 shadow-lg">
            <h1 class="text-3xl font-bold text-white mb-2">🎫 Management Tiket</h1>
            <p class="text-blue-100">Kelola data tiket dan transaksi pengunjung dengan mudah</p>
        </div>
    </div>

    <!-- Stats Cards - Enhanced with Gradient & Icons -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <span class="text-3xl">📊</span>
            </div>
            <p class="text-sm text-blue-100 mb-1">Total Tiket</p>
            <p class="text-3xl font-bold">{{ number_format($totalOrders) }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-3xl">✅</span>
            </div>
            <p class="text-sm text-green-100 mb-1">Success</p>
            <p class="text-3xl font-bold">{{ number_format($statusCounts['success']) }}</p>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-3xl">⏳</span>
            </div>
            <p class="text-sm text-yellow-100 mb-1">Pending</p>
            <p class="text-3xl font-bold">{{ number_format($statusCounts['pending']) }}</p>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <span class="text-3xl">⚠️</span>
            </div>
            <p class="text-sm text-red-100 mb-1">Issues</p>
            <p class="text-3xl font-bold">
                {{ number_format($statusCounts['challenge'] + $statusCounts['denied'] + $statusCounts['expired'] + $statusCounts['canceled']) }}
            </p>
        </div>
    </div>

    <!-- Export Section - Premium Design -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl p-3 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Export Data ke Excel</h3>
                <p class="text-sm text-gray-500">Pilih filter dan status untuk export data</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Filter Promo with Icon -->
            <div>
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-lg">🎯</span>
                    Filter by Promo
                </label>
                <select id="exportPromoFilter" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 hover:bg-white">
                    <option value="all">📋 Semua Promo</option>
                    @if(isset($promos))
                        @foreach($promos as $promo)
                            <option value="{{ $promo->id }}">{{ $promo->name }} ({{ $promo->orders_count }})</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Export Dropdown with Icon -->
            <div>
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                    <span class="text-lg">📊</span>
                    Status Export
                </label>
                <select id="exportStatusSelect" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 hover:bg-white">
                    <option value="">-- Pilih Status --</option>
                    <option value="all">📊 Semua ({{ number_format($totalOrders) }})</option>
                    <option value="success">✅ Success ({{ number_format($statusCounts['success']) }})</option>
                    <option value="pending">⏳ Pending ({{ number_format($statusCounts['pending']) }})</option>
                    <option value="challenge">⚠️ Challenge ({{ number_format($statusCounts['challenge']) }})</option>
                    <option value="denied">❌ Denied ({{ number_format($statusCounts['denied']) }})</option>
                    <option value="expired">⏰ Expired ({{ number_format($statusCounts['expired']) }})</option>
                    <option value="canceled">🚫 Canceled ({{ number_format($statusCounts['canceled']) }})</option>
                    <option value="multi-sheet">📑 Multi-Sheet</option>
                </select>
            </div>

            <!-- Export Button -->
            <div class="flex items-end">
                <button onclick="handleExport()" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </button>
            </div>
        </div>
        
        <!-- Info Box -->
        <div class="mt-4 bg-gradient-to-r from-blue-50 to-purple-50 border-l-4 border-blue-500 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-blue-900">
                    <span class="font-semibold">💡 Tips:</span> Pilih promo untuk filter spesifik, lalu pilih status dan klik Export. File Excel dengan format profesional!
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search - Enhanced -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border border-gray-100">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Status Filter -->
            <div>
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                    <span class="text-lg">🔍</span>
                    Filter Status
                </label>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}" 
                       class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ ($status ?? 'all') == 'all' ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All ({{ $totalOrders }})
                    </a>
                    @foreach($statusCounts as $statusKey => $count)
                    @if($count > 0)
                    <a href="{{ request()->fullUrlWithQuery(['status' => $statusKey]) }}" 
                       class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200 {{ ($status ?? '') == $statusKey ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ ucfirst($statusKey) }} ({{ $count }})
                    </a>
                    @endif
                    @endforeach
                </div>
            </div>

            <!-- Search Form -->
            <div>
                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-3">
                    <span class="text-lg">🔎</span>
                    Search Data
                </label>
                <form method="GET" action="{{ route('admin.tickets.index') }}" class="flex gap-2">
                    <div class="flex-1 relative">
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}" 
                               placeholder="Cari nama, order number, whatsapp..." 
                               class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 hover:bg-white">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button type="submit" class="px-5 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:from-blue-600 hover:to-purple-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    @if($search || $status)
                    <a href="{{ route('admin.tickets.index') }}" class="px-5 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-200 shadow-lg">
                        Reset
                    </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Table - Premium Design -->
    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Promo</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">WhatsApp</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Domisili</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Visit Date</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-200">
                        <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                            {{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}
                        </td>
                        <td class="px-6 py-4">
                            @if($order->promo)
                                <div class="text-sm font-semibold text-gray-900">{{ $order->promo->name }}</div>
                                <div class="text-xs text-gray-500 bg-gray-100 inline-block px-2 py-0.5 rounded-full mt-1">{{ ucfirst($order->promo->category) }}</div>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">{{ $order->order_number }}</div>
                            @if($order->invoice_number)
                                <div class="text-xs text-blue-600 font-medium">{{ $order->invoice_number }}</div>
                            @endif
                            <div class="text-xs text-gray-500 mt-1">
                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $order->created_at->format('d M Y H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->whatsapp_number) }}" target="_blank" 
                               class="inline-flex items-center gap-1 text-sm text-green-600 hover:text-green-800 font-medium bg-green-50 px-3 py-1 rounded-lg hover:bg-green-100 transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                {{ $order->whatsapp_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $order->domicile ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-700 rounded-lg font-bold">
                                {{ $order->ticket_quantity }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900 text-right">
                            <div class="bg-green-50 text-green-700 px-3 py-1 rounded-lg inline-block">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusConfig = [
                                    'success' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => '✅'],
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'icon' => '⏳'],
                                    'canceled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => '🚫'],
                                    'challenge' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'icon' => '⚠️'],
                                    'denied' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => '❌'],
                                    'expired' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => '⏰']
                                ];
                                $config = $statusConfig[$order->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => '•'];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-3 py-2 text-xs rounded-xl font-bold {{ $config['bg'] }} {{ $config['text'] }}">
                                <span>{{ $config['icon'] }}</span>
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="bg-gray-100 rounded-full p-6 mb-4">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                </div>
                                <p class="text-xl font-bold text-gray-800 mb-2">Tidak ada data tiket</p>
                                <p class="text-gray-500 mb-4">Data yang Anda cari tidak ditemukan</p>
                                @if(request('search') || request('status'))
                                    <a href="{{ route('admin.tickets.index') }}" 
                                       class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white rounded-xl hover:from-blue-600 hover:to-purple-600 transition-all duration-200 shadow-lg">
                                        Tampilkan Semua Tiket
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
    
    <!-- Pagination - Enhanced -->
    <div class="flex items-center justify-between mt-8 bg-white rounded-xl p-4 shadow-lg border border-gray-100">
        <div class="text-sm text-gray-600 font-medium">
            <span class="text-gray-800 font-bold">{{ $orders->firstItem() ?? 0 }}</span> - 
            <span class="text-gray-800 font-bold">{{ $orders->lastItem() ?? 0 }}</span> dari 
            <span class="text-gray-800 font-bold">{{ $orders->total() }}</span> hasil
        </div>
        <div>
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Loading Overlay - Premium Design -->
<div id="exportLoadingOverlay" class="hidden fixed inset-0 bg-gradient-to-br from-blue-900 to-purple-900 bg-opacity-95 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-10 shadow-2xl max-w-md mx-4 transform scale-100 transition-all">
        <div class="flex flex-col items-center space-y-6">
            <!-- Animated Icon -->
            <div class="relative">
                <svg class="animate-spin h-20 w-20 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Text Content -->
            <div class="text-center">
                <p class="text-2xl font-bold text-gray-800 mb-2">📊 Generating Excel...</p>
                <p class="text-sm text-gray-600 mb-4">Mohon tunggu, sedang memproses data</p>
                
                <!-- Progress Dots -->
                <div class="flex justify-center space-x-2">
                    <div class="w-3 h-3 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                    <div class="w-3 h-3 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-3 h-3 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
            
            <!-- Tips -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4 w-full">
                <p class="text-xs text-center text-gray-600">
                    ✨ File Excel sedang disiapkan dengan format profesional
                </p>
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