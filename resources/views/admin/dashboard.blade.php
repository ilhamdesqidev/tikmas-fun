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
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_revenue_formatted'] }}</p>
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
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
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
                <h3 class="text-lg font-semibold text-gray-900">Revenue</h3>
                <div class="flex items-center gap-3">
                    <select id="revenuePeriodFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                        <option value="daily" {{ $currentPeriod == 'daily' ? 'selected' : '' }}>Harian (30 Hari)</option>
                        <option value="weekly" {{ $currentPeriod == 'weekly' ? 'selected' : '' }}>Mingguan (12 Minggu)</option>
                        <option value="monthly" {{ $currentPeriod == 'monthly' ? 'selected' : '' }}>Bulanan (12 Bulan)</option>
                        <option value="quarterly" {{ $currentPeriod == 'quarterly' ? 'selected' : '' }}>3 Bulanan (4 Quarter)</option>
                        <option value="biannual" {{ $currentPeriod == 'biannual' ? 'selected' : '' }}>6 Bulanan (2 Semester)</option>
                        <option value="yearly" {{ $currentPeriod == 'yearly' ? 'selected' : '' }}>Tahunan (5 Tahun)</option>
                    </select>
                </div>
            </div>
            <div class="h-80 relative">
                <div id="chartLoader" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 hidden">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
                </div>
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
                        <p class="text-sm font-semibold text-gray-900">{{ $package['revenue_formatted'] }}</p>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WhatsApp</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visit Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                            @if($order->invoice_number)
                                <div class="text-xs text-blue-600">{{ $order->invoice_number }}</div>
                            @endif
                            <div class="text-xs text-gray-500">
                                {{ $order->created_at->format('d M Y H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm text-gray-900">{{ $order->customer_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->whatsapp_number) }}" target="_blank" 
                               class="text-sm text-green-600 hover:text-green-800 flex items-center gap-1">
                                <i class="fab fa-whatsapp"></i>
                                {{ $order->whatsapp_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($order->visit_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            {{ $order->ticket_quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
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
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            <div class="text-gray-500">
                                <i class="fas fa-inbox text-2xl mb-2"></i>
                                <p>Tidak ada pesanan terbaru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('extra-css')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Revenue Chart dengan Multi Period Filter
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    const loader = document.getElementById('chartLoader');
    let revenueChart = null;
    
    if (!ctx) {
        console.error('Canvas element not found');
        return;
    }

    // Function to initialize/update chart
    function initRevenueChart(data) {
        try {
            if (!data || data.length === 0) {
                console.warn('No revenue data available');
                const parent = ctx.parentElement;
                parent.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500"><p>Tidak ada data revenue</p></div>';
                return;
            }

            // Destroy existing chart
            if (revenueChart) {
                revenueChart.destroy();
            }

            // Create new chart
            revenueChart = new Chart(ctx, {
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
            
            console.log('Chart created successfully');
        } catch (error) {
            console.error('Error creating chart:', error);
        }
    }

    // Initialize with initial data
    const initialData = @json($revenueChart);
    initRevenueChart(initialData);
    
    // Period filter change handler
    document.getElementById('revenuePeriodFilter').addEventListener('change', function() {
        const period = this.value;
        
        // Show loader
        loader.classList.remove('hidden');
        
        // Fetch new data via AJAX
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
            // Hide loader
            loader.classList.add('hidden');
        });
    });
});
</script>
@endpush