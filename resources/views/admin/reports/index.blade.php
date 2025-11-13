@extends('layouts.app')

@section('title', 'Laporan Pemasukan Voucher')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">📊 Laporan Pemasukan Voucher</h1>
        <p class="text-gray-600">Dashboard analitik klaim voucher dan statistik pemasukan</p>
    </div>

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
                    <option value="30days">30 Hari Terakhir</option>
                    <option value="monthly">12 Bulan Terakhir</option>
                    <option value="yearly">5 Tahun Terakhir</option>
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
                @forelse($topVouchers as $index => $voucher)
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
                @forelse($recentClaims as $claim)
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

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
let claimChart = null;

// Initialize chart
async function loadChart(period = '30days') {
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

// Period filter change handler
document.getElementById('periodFilter')?.addEventListener('change', function(e) {
    loadChart(e.target.value);
});

// Load initial chart
document.addEventListener('DOMContentLoaded', function() {
    loadChart('30days');
});
</script>
@endsection