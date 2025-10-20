@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome back! Here\'s what\'s happening with your business.')

@section('content')
    @php
        // Mengambil data pesanan terbaru dari database
        $recentOrders = \App\Models\Order::with('promo')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    @endphp

    <!-- Stats Cards - Mobile: 2 cols, Tablet: 2 cols, Desktop: 4 cols -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Card 1 -->
        <div class="card rounded-lg sm:rounded-xl p-3 sm:p-4 md:p-6">
            <div class="flex flex-col space-y-2 sm:space-y-3">
                <div class="flex items-center justify-between">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Tiket Terjual</p>
                    <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900">{{ number_format($stats['total_tickets_sold']) }}</p>
                    <span class="text-green-600 text-xs font-medium mt-1 inline-block">+{{ $stats['tickets_sold_change'] }}%</span>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="card rounded-lg sm:rounded-xl p-3 sm:p-4 md:p-6">
            <div class="flex flex-col space-y-2 sm:space-y-3">
                <div class="flex items-center justify-between">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-sm sm:text-base md:text-2xl font-bold text-gray-900 truncate">{{ $stats['total_revenue_formatted'] }}</p>
                    <span class="text-green-600 text-xs font-medium mt-1 inline-block">+{{ $stats['revenue_change'] }}%</span>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="card rounded-lg sm:rounded-xl p-3 sm:p-4 md:p-6">
            <div class="flex flex-col space-y-2 sm:space-y-3">
                <div class="flex items-center justify-between">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Promo Aktif</p>
                    <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900">{{ $stats['active_promos'] }}</p>
                    <span class="text-green-600 text-xs font-medium mt-1 inline-block">{{ $stats['promos_change'] }} baru</span>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="card rounded-lg sm:rounded-xl p-3 sm:p-4 md:p-6">
            <div class="flex flex-col space-y-2 sm:space-y-3">
                <div class="flex items-center justify-between">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Customers</p>
                    <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900">{{ number_format($stats['total_customers']) }}</p>
                    <span class="text-green-600 text-xs font-medium mt-1 inline-block">+{{ $stats['customers_change'] }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Packages - Mobile First -->
    <div class="card rounded-lg sm:rounded-xl p-4 sm:p-5 md:p-6 mb-6 md:mb-8 lg:hidden">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Paket Populer</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @forelse($popularPackages as $package)
            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                <div class="flex items-start justify-between mb-2">
                    <h4 class="text-sm font-semibold text-gray-900 flex-1 pr-2">{{ $package['name'] }}</h4>
                    <div class="flex items-center justify-center w-8 h-8 bg-white rounded-full border-2 border-blue-500 flex-shrink-0">
                        <span class="text-xs font-bold text-blue-600">{{ $loop->iteration }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-200">
                    <span class="text-xs text-gray-600">{{ $package['sold'] }} terjual</span>
                    <span class="text-sm font-bold text-gray-900">{{ $package['revenue_formatted'] }}</span>
                </div>
            </div>
            @empty
            <div class="col-span-2 text-center py-8 text-gray-500">
                <p class="text-sm">Tidak ada data paket populer</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="card rounded-lg sm:rounded-xl p-4 sm:p-5 md:p-6 mb-6 md:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 md:mb-6 gap-3">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Grafik Revenue</h3>
            <select id="revenuePeriodFilter" class="text-xs sm:text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer bg-white w-full sm:w-auto">
                <option value="daily" {{ $currentPeriod == 'daily' ? 'selected' : '' }}>📅 Harian (30 Hari)</option>
                <option value="weekly" {{ $currentPeriod == 'weekly' ? 'selected' : '' }}>📊 Mingguan (12 Minggu)</option>
                <option value="monthly" {{ $currentPeriod == 'monthly' ? 'selected' : '' }}>📈 Bulanan (12 Bulan)</option>
            </select>
        </div>
        <div class="h-64 sm:h-72 md:h-80 relative">
            <div id="chartLoader" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 hidden z-10 rounded-lg">
                <div class="flex flex-col items-center gap-2">
                    <div class="animate-spin rounded-full h-8 w-8 sm:h-10 sm:w-10 border-b-2 border-blue-600"></div>
                    <span class="text-xs text-gray-600">Loading data...</span>
                </div>
            </div>
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Popular Packages - Desktop Only -->
    <div class="hidden lg:block card rounded-xl p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Paket Populer</h3>
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
            @forelse($popularPackages as $package)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center gap-3 flex-1">
                    <div class="flex items-center justify-center w-10 h-10 bg-white rounded-full border-2 border-blue-500 flex-shrink-0">
                        <span class="text-sm font-bold text-blue-600">{{ $loop->iteration }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 truncate">{{ $package['name'] }}</h4>
                        <p class="text-xs text-gray-600 mt-0.5">{{ $package['sold'] }} terjual</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-3">
                    <p class="text-sm font-semibold text-gray-900">{{ $package['revenue_formatted'] }}</p>
                </div>
            </div>
            @empty
            <div class="col-span-2 text-center py-8 text-gray-500">
                <p>Tidak ada data paket populer</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card rounded-lg sm:rounded-xl overflow-hidden">
        <div class="flex items-center justify-between p-4 sm:p-5 md:p-6 border-b border-gray-200 bg-gray-50">
            <div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Pesanan Terbaru</h3>
                <p class="text-xs text-gray-600 mt-0.5 hidden sm:block">5 Transaksi terakhir</p>
            </div>
            <a href="{{ route('admin.tickets.index') }}" class="text-primary hover:text-yellow-600 text-xs sm:text-sm font-medium flex items-center gap-1">
                <span>Lihat Semua</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
        
        <!-- Mobile Card View -->
        <div class="block lg:hidden divide-y divide-gray-100">
            @forelse($recentOrders as $order)
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-bold text-gray-900">#{{ $order->order_number }}</span>
                            @php
                                $statusColors = [
                                    'success' => 'text-green-700 bg-green-50',
                                    'pending' => 'text-yellow-700 bg-yellow-50',
                                    'canceled' => 'text-red-700 bg-red-50',
                                    'challenge' => 'text-orange-700 bg-orange-50',
                                    'denied' => 'text-red-700 bg-red-50',
                                    'expired' => 'text-gray-700 bg-gray-50'
                                ];
                            @endphp
                            <span class="px-2 py-0.5 text-xs rounded-full font-medium {{ $statusColors[$order->status] ?? 'text-gray-700 bg-gray-50' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        @if($order->invoice_number)
                            <div class="text-xs text-blue-600 mb-1">{{ $order->invoice_number }}</div>
                        @endif
                        <div class="text-xs text-gray-500">
                            {{ $order->created_at->format('d M Y • H:i') }}
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                        <div class="text-xs text-gray-600 mt-0.5">{{ $order->ticket_quantity }} tiket</div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-white rounded-full flex items-center justify-center border border-gray-200 flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-gray-900 truncate flex-1">{{ $order->customer_name }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between text-xs">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->whatsapp_number) }}" target="_blank" 
                           class="text-green-600 hover:text-green-800 flex items-center gap-1.5 font-medium">
                            <i class="fab fa-whatsapp text-sm"></i>
                            <span>{{ $order->whatsapp_number }}</span>
                        </a>
                        <div class="flex items-center gap-1 text-gray-600">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center text-gray-500">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-inbox text-3xl text-gray-400"></i>
                </div>
                <p class="text-sm font-medium">Tidak ada pesanan terbaru</p>
                <p class="text-xs text-gray-400 mt-1">Pesanan akan muncul di sini</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Visit Date</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">#{{ $order->order_number }}</div>
                            @if($order->invoice_number)
                                <div class="text-xs text-blue-600 font-medium">{{ $order->invoice_number }}</div>
                            @endif
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $order->created_at->format('d M Y • H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-gray-200 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->whatsapp_number) }}" target="_blank" 
                               class="text-sm text-green-600 hover:text-green-800 flex items-center gap-1.5 font-medium">
                                <i class="fab fa-whatsapp"></i>
                                {{ $order->whatsapp_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-center">
                            {{ $order->ticket_quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <span class="text-sm font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
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
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-inbox text-3xl text-gray-400"></i>
                            </div>
                            <p class="text-sm font-medium">Tidak ada pesanan terbaru</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    const loader = document.getElementById('chartLoader');
    let revenueChart = null;
    
    if (!ctx) {
        console.error('Canvas element not found');
        return;
    }

    function initRevenueChart(data) {
        try {
            if (!data || data.length === 0) {
                console.warn('No revenue data available');
                const parent = ctx.parentElement;
                parent.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500"><p class="text-sm">Tidak ada data revenue</p></div>';
                return;
            }

            if (revenueChart) {
                revenueChart.destroy();
            }

            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;

            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(item => item.label),
                    datasets: [{
                        label: 'Revenue',
                        data: data.map(item => parseFloat(item.revenue) || 0),
                        borderColor: '#CFD916',
                        backgroundColor: 'rgba(207, 217, 22, 0.1)',
                        borderWidth: isMobile ? 2 : 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#CFD916',
                        pointBorderColor: '#fff',
                        pointBorderWidth: isMobile ? 1 : 2,
                        pointHoverBackgroundColor: '#CFD916',
                        pointHoverBorderColor: '#fff',
                        pointRadius: isMobile ? 3 : 4,
                        pointHoverRadius: isMobile ? 5 : 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.85)',
                            padding: isMobile ? 10 : 12,
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#CFD916',
                            borderWidth: 1,
                            displayColors: false,
                            titleFont: {
                                size: isMobile ? 11 : 12,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: isMobile ? 10 : 11
                            },
                            callbacks: {
                                label: function(context) {
                                    let value = context.parsed.y;
                                    return 'Revenue: Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6B7280',
                                font: {
                                    size: isMobile ? 9 : isTablet ? 10 : 11,
                                    weight: '500'
                                },
                                maxRotation: isMobile ? 45 : 0,
                                minRotation: isMobile ? 45 : 0,
                                autoSkip: true,
                                maxTicksLimit: isMobile ? 6 : 12
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#F3F4F6',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6B7280',
                                font: {
                                    size: isMobile ? 9 : isTablet ? 10 : 11
                                },
                                callback: function(value) {
                                    if (value >= 1000000000) {
                                        return 'Rp ' + (value / 1000000000).toFixed(1) + 'M';
                                    } else if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
                                    } else if (value >= 1000) {
                                        return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                    }
                                    return 'Rp ' + value;
                                },
                                maxTicksLimit: isMobile ? 4 : 6
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    }
                }
            });
            
            console.log('Chart created successfully');
        } catch (error) {
            console.error('Error creating chart:', error);
        }
    }

    const initialData = @json($revenueChart);
    initRevenueChart(initialData);
    
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            if (revenueChart) {
                const currentData = revenueChart.data.datasets[0].data.map((value, index) => ({
                    label: revenueChart.data.labels[index],
                    revenue: value
                }));
                initRevenueChart(currentData);
            }
        }, 250);
    });
    
    document.getElementById('revenuePeriodFilter').addEventListener('change', function() {
        const period = this.value;
        loader.classList.remove('hidden');
        
        fetch(`{{ route('admin.dashboard.revenue') }}?period=${period}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.data) {
                initRevenueChart(data.data);
            } else {
                console.error('Invalid data format received');
            }
        })
        .catch(error => {
            console.error('Error fetching revenue data:', error);
            alert('Gagal memuat data. Silakan refresh halaman.');
        })
        .finally(() => {
            loader.classList.add('hidden');
        });
    });
});
</script>
@endpush