@extends('layouts.app')

@section('title', 'Laporan Pemasukan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">📊 Laporan Pemasukan</h1>
        <p class="text-gray-600">Dashboard analitik klaim voucher dan statistik promo</p>
    </div>

    <!-- Navigation Tabs -->
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button 
                    onclick="switchTab('voucher')" 
                    id="tab-voucher"
                    class="tab-button border-b-2 border-blue-500 text-blue-600 py-4 px-1 text-center font-medium text-sm whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span>Laporan Voucher</span>
                    </div>
                </button>
                
                <button 
                    onclick="switchTab('promo')" 
                    id="tab-promo"
                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-center border-b-2 font-medium text-sm whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>
                        <span>Laporan Promo</span>
                    </div>
                </button>
            </nav>
        </div>
    </div>

    <!-- Voucher Content -->
    <div id="content-voucher" class="tab-content">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Vouchers -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-semibold mb-1">Total Voucher</p>
                        <p class="text-3xl font-bold">{{ $totalVouchers }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Vouchers -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-semibold mb-1">Voucher Aktif</p>
                        <p class="text-3xl font-bold">{{ $activeVouchers }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Claims -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-semibold mb-1">Total Klaim</p>
                        <p class="text-3xl font-bold">{{ $totalClaims }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- This Month Claims -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-semibold mb-1">Klaim Bulan Ini</p>
                        <p class="text-3xl font-bold">{{ $claimsThisMonth }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-1">Grafik Trend Klaim Voucher</h2>
                    <p class="text-sm text-gray-600">Monitoring aktivitas klaim dalam periode tertentu</p>
                </div>
                
                <!-- Period Filter -->
                <div class="flex items-center space-x-2 mt-4 md:mt-0">
                    <label class="text-sm font-medium text-gray-700">Periode:</label>
                    <select id="periodFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="daily">30 Hari Terakhir</option>
                        <option value="weekly">12 Minggu Terakhir</option>
                        <option value="monthly">12 Bulan Terakhir</option>
                    </select>
                </div>
            </div>

            <!-- Chart Info -->
            <div class="flex items-center space-x-4 mb-4 p-4 bg-blue-50 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm text-gray-700">
                        Total klaim dalam periode ini: <span id="totalClaims" class="font-bold text-blue-600">-</span>
                    </p>
                </div>
            </div>

            <!-- Chart Canvas -->
            <div class="relative" style="height: 400px;">
                <canvas id="claimChart"></canvas>
            </div>

            <!-- Loading State -->
            <div id="chartLoading" class="hidden absolute inset-0 flex items-center justify-center bg-white bg-opacity-75">
                <div class="text-center">
                    <svg class="animate-spin h-12 w-12 text-blue-500 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-600">Memuat data...</p>
                </div>
            </div>
        </div>

        <!-- Top Vouchers & Recent Claims -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top 5 Vouchers -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <svg class="w-6 h-6 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-800">Top 5 Voucher Terpopuler</h3>
                </div>
                
                <div class="space-y-3">
                    @forelse(($topVouchers ?? []) as $index => $voucher)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-3">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 font-bold text-sm">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ Str::limit($voucher->name, 30) }}</p>
                                <p class="text-xs text-gray-500">{{ $voucher->claims_count }} klaim</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($voucher->status === 'aktif') bg-green-100 text-green-800
                                @elseif($voucher->status === 'habis') bg-orange-100 text-orange-800
                                @elseif($voucher->status === 'kadaluarsa') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($voucher->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-8">Belum ada data voucher</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Claims -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-800">Klaim Terbaru</h3>
                </div>
                
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse(($recentClaims ?? []) as $claim)
                    <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <p class="font-semibold text-gray-800 text-sm">{{ $claim->user_name }}</p>
                                <span class="text-xs text-gray-500">{{ $claim->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-gray-600 mb-1">{{ $claim->voucher->name ?? '-' }}</p>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 text-xs font-mono bg-gray-200 text-gray-700 rounded">
                                    {{ $claim->unique_code }}
                                </span>
                                @if($claim->is_used || $claim->scanned_at)
                                <span class="px-2 py-0.5 text-xs font-semibold bg-gray-200 text-gray-700 rounded">Terpakai</span>
                                @else
                                <span class="px-2 py-0.5 text-xs font-semibold bg-green-100 text-green-700 rounded">Aktif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-8">Belum ada klaim terbaru</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Promo Content -->
    <div id="content-promo" class="tab-content hidden">
        <!-- Summary Cards for Promo -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Tickets Sold -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-semibold mb-1">Total Tiket Terjual</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['total_tickets_sold'] ?? 0) }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-blue-100 text-xs">{{ $stats['tickets_sold_change'] ?? 0 }}% dari bulan lalu</span>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-semibold mb-1">Total Revenue</p>
                        <p class="text-3xl font-bold">{{ $stats['total_revenue_formatted'] ?? 'Rp 0' }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-green-100 text-xs">{{ $stats['revenue_change'] ?? 0 }}% dari bulan lalu</span>
                </div>
            </div>

            <!-- Active Promos -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-semibold mb-1">Promo Aktif</p>
                        <p class="text-3xl font-bold">{{ $stats['active_promos'] ?? 0 }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-yellow-100 text-xs">{{ $stats['promos_change'] ?? 0 }} promo baru</span>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-semibold mb-1">Total Customers</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['total_customers'] ?? 0) }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-purple-100 text-xs">{{ $stats['customers_change'] ?? 0 }}% dari bulan lalu</span>
                </div>
            </div>
        </div>

        <!-- Revenue Chart for Promo -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800 mb-1">Grafik Revenue Promo</h2>
                    <p class="text-sm text-gray-600">Monitoring pendapatan dari promo dalam periode tertentu</p>
                </div>
                
                <!-- Period Filter -->
                <div class="flex items-center space-x-2 mt-4 md:mt-0">
                    <label class="text-sm font-medium text-gray-700">Periode:</label>
                    <select id="promoPeriodFilter" class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="daily" {{ $currentPeriod == 'daily' ? 'selected' : '' }}>Harian (30 Hari)</option>
                        <option value="weekly" {{ $currentPeriod == 'weekly' ? 'selected' : '' }}>Mingguan (12 Minggu)</option>
                        <option value="monthly" {{ $currentPeriod == 'monthly' ? 'selected' : '' }}>Bulanan (12 Bulan)</option>
                    </select>
                </div>
            </div>

            <!-- Chart Canvas -->
            <div class="relative" style="height: 400px;">
                <div id="promoChartLoader" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 hidden">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
                </div>
                <canvas id="promoRevenueChart"></canvas>
            </div>
        </div>

        <!-- Popular Packages -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-800">Paket Promo Terpopuler</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse(($popularPackages ?? []) as $package)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900">{{ $package['name'] }}</h4>
                        <p class="text-xs text-gray-600">{{ $package['sold'] }} terjual</p>
                        <p class="text-sm font-semibold text-gray-900 mt-1">{{ $package['revenue_formatted'] }}</p>
                    </div>
                </div>
                @empty
                <div class="col-span-4 text-center py-4 text-gray-500">
                    <p>Tidak ada data paket populer</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
let claimChart = null;
let promoChart = null;
let currentTab = 'voucher';

// Tab Switching Function
function switchTab(tab) {
    currentTab = tab;
    
    // Update tab buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-blue-500', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    const activeTab = document.getElementById(`tab-${tab}`);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-blue-500', 'text-blue-600');
    
    // Toggle content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    document.getElementById(`content-${tab}`).classList.remove('hidden');
    
    // Load charts if needed
    if (tab === 'promo' && !promoChart) {
        loadPromoChart('{{ $currentPeriod }}');
    }
}

// Initialize Voucher chart
async function loadChart(period = 'daily') {
    const loading = document.getElementById('chartLoading');
    loading?.classList.remove('hidden');
    
    try {
        const response = await fetch(`{{ route('admin.reports.data') }}?period=${period}`);
        const data = await response.json();
        
        // Update total
        document.getElementById('totalClaims').textContent = data.total.toLocaleString();
        
        // Destroy existing chart
        if (claimChart) {
            claimChart.destroy();
        }
        
        // Create new chart
        const ctx = document.getElementById('claimChart').getContext('2d');
        claimChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { size: 14, weight: 'bold' },
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14 },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                return `Klaim: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 12 }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11 },
                            maxRotation: 45,
                            minRotation: 0
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
        
    } catch (error) {
        console.error('Error loading chart:', error);
        alert('Gagal memuat data grafik');
    } finally {
        loading?.classList.add('hidden');
    }
}

// Initialize Promo chart
async function loadPromoChart(period = 'monthly') {
    const loader = document.getElementById('promoChartLoader');
    loader?.classList.remove('hidden');
    
    try {
        const response = await fetch(`{{ route('admin.dashboard.revenue') }}?period=${period}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const result = await response.json();
        
        if (!result.success || !result.data) {
            throw new Error('Invalid data format');
        }
        
        const data = result.data;
        
        // Destroy existing chart
        if (promoChart) {
            promoChart.destroy();
        }
        
        // Create new chart
        const ctx = document.getElementById('promoRevenueChart').getContext('2d');
        promoChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => item.label),
                datasets: [{
                    label: 'Revenue',
                    data: data.map(item => parseFloat(item.revenue) || 0),
                    borderColor: '#CFD916',
                    backgroundColor: 'rgba(207, 217, 22, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#CFD916',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#CFD916',
                    pointHoverBorderColor: '#fff',
                    pointRadius: 5,
                    pointHoverRadius: 7,
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
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#CFD916',
                        borderWidth: 1,
                        displayColors: false,
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
                                size: 11,
                                weight: '500'
                            }
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
                                size: 11
                            },
                            callback: function(value) {
                                if (value >= 1000000000000) {
                                    return 'Rp ' + (value / 1000000000000).toFixed(1) + 'T';
                                } else if (value >= 1000000000) {
                                    return 'Rp ' + (value / 1000000000).toFixed(1) + 'M';
                                } else if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                }
                                return 'Rp ' + value;
                            },
                            maxTicksLimit: 6
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                }
            }
        });
        
    } catch (error) {
        console.error('Error loading promo chart:', error);
        alert('Gagal memuat data grafik promo');
    } finally {
        loader?.classList.add('hidden');
    }
}

// Period filter change handler for voucher
document.getElementById('periodFilter')?.addEventListener('change', function(e) {
    loadChart(e.target.value);
});

// Period filter change handler for promo
document.getElementById('promoPeriodFilter')?.addEventListener('change', function(e) {
    loadPromoChart(e.target.value);
});

// Load initial chart on page load
document.addEventListener('DOMContentLoaded', function() {
    loadChart('daily');
});
</script>
@endsection