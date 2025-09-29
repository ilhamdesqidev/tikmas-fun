@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome back! Here\'s what\'s happening with your business.')

@section('content')
    @php
        // Fallback data jika variabel tidak tersedia
        $fallbackStats = [
            'total_tickets_sold' => 1250,
            'total_revenue' => 1250000000,
            'active_promos' => 8,
            'total_customers' => 850,
            'tickets_sold_change' => 12,
            'revenue_change' => 18,
            'promos_change' => 2,
            'customers_change' => 8,
        ];
        
        $fallbackRecentOrders = [
            ['id' => 'ORD-001', 'customer_name' => 'Ahmad Rizki', 'package' => 'Paket Keluarga', 'amount' => 450000, 'status' => 'completed', 'date' => '2024-01-15 14:30'],
            ['id' => 'ORD-002', 'customer_name' => 'Sari Dewi', 'package' => 'Paket Wisata', 'amount' => 320000, 'status' => 'pending', 'date' => '2024-01-15 12:15'],
            ['id' => 'ORD-003', 'customer_name' => 'Budi Santoso', 'package' => 'Paket Premium', 'amount' => 650000, 'status' => 'processing', 'date' => '2024-01-14 16:45'],
            ['id' => 'ORD-004', 'customer_name' => 'Maya Sari', 'package' => 'Paket Keluarga', 'amount' => 450000, 'status' => 'completed', 'date' => '2024-01-14 11:20'],
            ['id' => 'ORD-005', 'customer_name' => 'Rizki Pratama', 'package' => 'Paket Wisata', 'amount' => 320000, 'status' => 'completed', 'date' => '2024-01-13 09:30'],
        ];
        
        $fallbackPopularPackages = [
            ['name' => 'Paket Keluarga', 'sold' => 450, 'revenue' => 202500000, 'growth' => '+15%'],
            ['name' => 'Paket Premium', 'sold' => 320, 'revenue' => 208000000, 'growth' => '+22%'],
            ['name' => 'Paket Wisata', 'sold' => 280, 'revenue' => 89600000, 'growth' => '+8%'],
            ['name' => 'Paket Hemat', 'sold' => 200, 'revenue' => 60000000, 'growth' => '+5%'],
        ];
        
        $fallbackMonthlyRevenue = [
            ['month' => 'Jan', 'revenue' => 120000000],
            ['month' => 'Feb', 'revenue' => 150000000],
            ['month' => 'Mar', 'revenue' => 180000000],
            ['month' => 'Apr', 'revenue' => 220000000],
            ['month' => 'May', 'revenue' => 250000000],
            ['month' => 'Jun', 'revenue' => 280000000],
            ['month' => 'Jul', 'revenue' => 320000000],
            ['month' => 'Aug', 'revenue' => 350000000],
            ['month' => 'Sep', 'revenue' => 380000000],
            ['month' => 'Oct', 'revenue' => 410000000],
            ['month' => 'Nov', 'revenue' => 450000000],
            ['month' => 'Dec', 'revenue' => 500000000],
        ];

        $stats = $stats ?? $fallbackStats;
        $recentOrders = $recentOrders ?? $fallbackRecentOrders;
        $popularPackages = $popularPackages ?? $fallbackPopularPackages;
        $monthlyRevenue = $monthlyRevenue ?? $fallbackMonthlyRevenue;
    @endphp

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Tiket Terjual</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_tickets_sold']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm font-medium">{{ $stats['tickets_sold_change'] }}% dari bulan lalu</span>
            </div>
        </div>

        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'] / 1000000, 1) }}M</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm font-medium">{{ $stats['revenue_change'] }}% dari bulan lalu</span>
            </div>
        </div>

        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Promo Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_promos'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm font-medium">{{ $stats['promos_change'] }} promo baru</span>
            </div>
        </div>

        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Customers</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_customers']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm font-medium">{{ $stats['customers_change'] }}% dari bulan lalu</span>
            </div>
        </div>
    </div>

    <!-- Charts and Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 card rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Revenue Bulanan</h3>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
            </div>
            <div class="h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Popular Packages -->
        <div class="card rounded-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Paket Populer</h3>
            <div class="space-y-4">
                @forelse($popularPackages as $package)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900">{{ $package['name'] }}</h4>
                        <p class="text-xs text-gray-600">{{ $package['sold'] }} terjual</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($package['revenue'] / 1000000, 1) }}M</p>
                        <p class="text-xs text-green-600">{{ $package['growth'] }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-gray-500">
                    <p>Tidak ada data paket populer</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card rounded-xl">
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h3>
            <a href="{{ route('admin.tickets.index') }}" class="text-primary hover:text-yellow-600 text-sm font-medium">
                Lihat Semua
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $order['id'] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-900">{{ $order['customer_name'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $order['package'] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">Rp {{ number_format($order['amount']) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order['status'] === 'completed')
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Selesai</span>
                            @elseif($order['status'] === 'pending')
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Diproses</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ date('d/m/Y H:i', strtotime($order['date'])) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                                <button class="text-green-600 hover:text-green-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button class="text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada pesanan terbaru
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.promo.index') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
            </svg>
            <div>
                <p class="font-medium">Kelola Promo</p>
                <p class="text-xs text-blue-100">Buat & edit promo</p>
            </div>
        </a>
        
        <a href="{{ route('admin.tickets.index') }}" class="flex items-center p-4 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white hover:from-green-600 hover:to-green-700 transition-all duration-200">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
            </svg>
            <div>
                <p class="font-medium">Tiket</p>
                <p class="text-xs text-green-100">Kelola tiket</p>
            </div>
        </a>
        
        <a href="{{ route('admin.customers.index') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl text-white hover:from-purple-600 hover:to-purple-700 transition-all duration-200">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            <div>
                <p class="font-medium">Customers</p>
                <p class="text-xs text-purple-100">Data customer</p>
            </div>
        </a>
        
        <a href="{{ route('admin.reports.index') }}" class="flex items-center p-4 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl text-white hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <div>
                <p class="font-medium">Laporan</p>
                <p class="text-xs text-yellow-100">Analytics & laporan</p>
            </div>
        </a>
    </div>
@endsection

@section('extra-css')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('extra-js')
<script>
// Revenue Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json(array_column($monthlyRevenue, 'month')),
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: @json(array_column($monthlyRevenue, 'revenue')),
                    borderColor: '#CFD916',
                    backgroundColor: 'rgba(207, 217, 22, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#CFD916',
                    pointBorderColor: '#CFD916',
                    pointHoverBackgroundColor: '#CFD916',
                    pointHoverBorderColor: '#CFD916',
                    pointRadius: 6,
                    pointHoverRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6B7280'
                        }
                    },
                    y: {
                        grid: {
                            color: '#F3F4F6'
                        },
                        ticks: {
                            color: '#6B7280',
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(0) + 'M';
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        hoverRadius: 8
                    }
                }
            }
        });
    }

    // Auto refresh every 30 seconds for real-time data
    setInterval(function() {
        // In a real application, you would fetch new data here
        console.log('Refreshing dashboard data...');
    }, 30000);
});
</script>
@endsection