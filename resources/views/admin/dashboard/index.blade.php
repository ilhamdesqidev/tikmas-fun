@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome back! Here\'s what\'s happening with your business.')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Tiket Terjual</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_tickets_sold']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-feather="ticket" class="w-6 h-6 text-blue-600"></i>
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
                    <i data-feather="dollar-sign" class="w-6 h-6 text-green-600"></i>
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
                    <i data-feather="gift" class="w-6 h-6 text-yellow-600"></i>
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
                    <i data-feather="users" class="w-6 h-6 text-purple-600"></i>
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
                @foreach($popularPackages as $package)
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
                @endforeach
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
                    @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $order['id'] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                    <i data-feather="user" class="w-4 h-4 text-gray-600"></i>
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
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800">
                                    <i data-feather="edit" class="w-4 h-4"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.promo.index') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-200">
            <i data-feather="gift" class="w-6 h-6 mr-3"></i>
            <div>
                <p class="font-medium">Kelola Promo</p>
                <p class="text-xs text-blue-100">Buat & edit promo</p>
            </div>
        </a>
        
        <a href="{{ route('admin.tickets.index') }}" class="flex items-center p-4 bg-gradient-to-r from-green-500 to-green-600 rounded-xl text-white hover:from-green-600 hover:to-green-700 transition-all duration-200">
            <i data-feather="ticket" class="w-6 h-6 mr-3"></i>
            <div>
                <p class="font-medium">Tiket</p>
                <p class="text-xs text-green-100">Kelola tiket</p>
            </div>
        </a>
        
        <a href="{{ route('admin.customers.index') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl text-white hover:from-purple-600 hover:to-purple-700 transition-all duration-200">
            <i data-feather="users" class="w-6 h-6 mr-3"></i>
            <div>
                <p class="font-medium">Customers</p>
                <p class="text-xs text-purple-100">Data customer</p>
            </div>
        </a>
        
        <a href="{{ route('admin.reports.index') }}" class="flex items-center p-4 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl text-white hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200">
            <i data-feather="bar-chart-2" class="w-6 h-6 mr-3"></i>
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
// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
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

// Auto refresh every 30 seconds for real-time data
setInterval(function() {
    // In a real application, you would fetch new data here
    console.log('Refreshing dashboard data...');
}, 30000);
@endsection