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

    <!-- Export Section - UPGRADED! -->
    <div class="mb-6 bg-gradient-to-br from-blue-50 via-purple-50 to-green-50 p-6 rounded-xl border border-blue-200 shadow-md">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-800">📊 Export Data Tiket ke Excel</h3>
            </div>
            <span class="text-xs text-gray-600 bg-white px-3 py-1 rounded-full shadow-sm">✨ Professional Excel Format</span>
        </div>
        
        <!-- Filter Promo -->
        <div class="mb-4 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
            <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                🎯 Filter by Promo (Opsional)
            </label>
            <select id="exportPromoFilter" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                <option value="all">📋 Semua Promo</option>
                @if(isset($promos))
                    @foreach($promos as $promo)
                        <option value="{{ $promo->id }}">
                            {{ $promo->name }} ({{ $promo->orders_count }} tiket)
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        
        <!-- Export Buttons -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
            <!-- Export All Status -->
            <button onclick="exportTickets('all')" 
                    class="flex flex-col items-center justify-center px-4 py-4 bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group">
                <svg class="w-5 h-5 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-xs font-bold">📊 Semua</span>
                <span class="text-xs opacity-80">{{ number_format($totalOrders) }}</span>
            </button>
            
            <!-- Export Success -->
            <button onclick="exportTickets('success')" 
                    class="flex flex-col items-center justify-center px-4 py-4 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group">
                <svg class="w-5 h-5 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs font-bold">✅ Success</span>
                <span class="text-xs opacity-80">{{ number_format($statusCounts['success']) }}</span>
            </button>
            
            <!-- Export Pending -->
            <button onclick="exportTickets('pending')" 
                    class="flex flex-col items-center justify-center px-4 py-4 bg-gradient-to-br from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group">
                <svg class="w-5 h-5 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs font-bold">⏳ Pending</span>
                <span class="text-xs opacity-80">{{ number_format($statusCounts['pending']) }}</span>
            </button>
            
            <!-- Export Challenge -->
            <button onclick="exportTickets('challenge')" 
                    class="flex flex-col items-center justify-center px-4 py-4 bg-gradient-to-br from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group">
                <svg class="w-5 h-5 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span class="text-xs font-bold">⚠️ Challenge</span>
                <span class="text-xs opacity-80">{{ number_format($statusCounts['challenge']) }}</span>
            </button>
            
            <!-- Export Denied -->
            <button onclick="exportTickets('denied')" 
                    class="flex flex-col items-center justify-center px-4 py-4 bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group">
                <svg class="w-5 h-5 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs font-bold">❌ Denied</span>
                <span class="text-xs opacity-80">{{ number_format($statusCounts['denied']) }}</span>
            </button>
            
            <!-- Export Expired -->
            <button onclick="exportTickets('expired')" 
                    class="flex flex-col items-center justify-center px-4 py-4 bg-gradient-to-br from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group">
                <svg class="w-5 h-5 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs font-bold">⏰ Expired</span>
                <span class="text-xs opacity-80">{{ number_format($statusCounts['expired']) }}</span>
            </button>
            
            <!-- Export Canceled -->
            <button onclick="exportTickets('canceled')" 
                    class="flex flex-col items-center justify-center px-4 py-4 bg-gradient-to-br from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group">
                <svg class="w-5 h-5 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
                <span class="text-xs font-bold">🚫 Canceled</span>
                <span class="text-xs opacity-80">{{ number_format($statusCounts['canceled']) }}</span>
            </button>
            
            <!-- Export All with Multiple Sheets -->
            <button onclick="exportAllStatuses()" 
                    class="flex flex-col items-center justify-center px-4 py-4 bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group">
                <svg class="w-5 h-5 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span class="text-xs font-bold">📑 Multi-Sheet</span>
                <span class="text-xs opacity-80">All Status</span>
            </button>
        </div>
        
        <div class="flex items-start space-x-2 bg-blue-50 border border-blue-200 rounded-lg p-3">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-xs text-blue-800">
                <p class="font-semibold mb-1">💡 Tips Export Excel:</p>
                <ul class="list-disc list-inside space-y-0.5 ml-2">
                    <li>Pilih promo untuk filter data spesifik (opsional)</li>
                    <li>Klik status untuk export single status</li>
                    <li>Klik "Multi-Sheet" untuk export semua status dalam 1 file</li>
                    <li>File Excel dengan styling profesional & color-coded! 🎨</li>
                </ul>
            </div>
        </div>
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
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-md">
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
                    <tr class="hover:bg-gray-50 transition-colors">
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
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-lg font-medium mb-2">Tidak ada data tiket</p>
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

<!-- Loading Overlay -->
<div id="exportLoadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 shadow-2xl max-w-sm mx-4">
        <div class="flex flex-col items-center space-y-4">
            <svg class="animate-spin h-12 w-12 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <div class="text-center">
                <p class="text-xl font-bold text-gray-800 mb-1">📊 Generating Excel...</p>
                <p class="text-sm text-gray-600">Mohon tunggu, proses sedang berjalan</p>
                <div class="mt-3 flex justify-center space-x-1">
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0s"></div>
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Export tickets by status dengan promo filter
 */
function exportTickets(status) {
    const promoId = document.getElementById('exportPromoFilter').value;
    const url = `{{ route('admin.tickets.export') }}?status=${status}&promo_id=${promoId}`;
    
    // Show loading indicator
    showExportLoading();
    
    // Trigger download
    window.location.href = url;
    
    // Hide loading after delay
    setTimeout(hideExportLoading, 2000);
}

/**
 * Export all statuses in multiple sheets
 */
function exportAllStatuses() {
    const promoId = document.getElementById('exportPromoFilter').value;
    const url = `{{ route('admin.tickets.exportAll') }}?promo_id=${promoId}`;
    
    // Show loading indicator dengan pesan khusus
    showExportLoading('multi');
    
    // Trigger download
    window.location.href = url;
    
    // Hide loading after delay (lebih lama untuk multi-sheet)
    setTimeout(hideExportLoading, 3000);
}

/**
 * Show loading indicator
 */
function showExportLoading(type = 'single') {
    const overlay = document.getElementById('exportLoadingOverlay');
    if (overlay) {
        overlay.classList.remove('hidden');
        
        // Update message untuk multi-sheet
        if (type === 'multi') {
            const messageEl = overlay.querySelector('p.text-xl');
            if (messageEl) {
                messageEl.textContent = '📑 Generating Multi-Sheet Excel...';
            }
            const subMessageEl = overlay.querySelector('p.text-sm');
            if (subMessageEl) {
                subMessageEl.textContent = 'File lebih besar, mohon tunggu sebentar...';
            }
        }
    }
}

/**
 * Hide loading indicator
 */
function hideExportLoading() {
    const overlay = document.getElementById('exportLoadingOverlay');
    if (overlay) {
        overlay.classList.add('hidden');
        
        // Reset message
        const messageEl = overlay.querySelector('p.text-xl');
        if (messageEl) {
            messageEl.textContent = '📊 Generating Excel...';
        }
        const subMessageEl = overlay.querySelector('p.text-sm');
        if (subMessageEl) {
            subMessageEl.textContent = 'Mohon tunggu, proses sedang berjalan';
        }
    }
}

// Close loading overlay when clicking outside (optional)
document.getElementById('exportLoadingOverlay')?.addEventListener('click', function(e) {
    if (e.target === this) {
        hideExportLoading();
    }
});

// Auto-hide loading jika ada error (fallback)
window.addEventListener('focus', function() {
    setTimeout(function() {
        const overlay = document.getElementById('exportLoadingOverlay');
        if (overlay && !overlay.classList.contains('hidden')) {
            hideExportLoading();
        }
    }, 1000);
});
</script>

@endsection