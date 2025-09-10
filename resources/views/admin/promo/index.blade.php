@extends('layouts.app')

@section('title', 'Paket Promo')
@section('page-title', 'Paket Promo')
@section('page-description', 'Kelola paket promo dan penawaran khusus')

@section('content')
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="flex items-center space-x-4 mb-4 sm:mb-0">
            <div class="relative">
                <input type="text" placeholder="Cari paket promo..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent w-64">
                <i data-feather="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
            </div>
            <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
                <option value="expired">Kadaluarsa</option>
            </select>
        </div>
        <div class="flex space-x-3">
            <button class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                <i data-feather="download" class="w-4 h-4 mr-2"></i>
                Export
            </button>
            <a href="{{ route('admin.promo.create') }}" class="flex items-center px-4 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 font-medium">
                <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                Tambah Promo
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Promo</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $promos->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-feather="gift" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm font-medium">+2 dari bulan lalu</span>
            </div>
        </div>

        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Promo Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $promos->where('status', 'active')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-feather="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm font-medium">{{ $activePercentage }}% dari total</span>
            </div>
        </div>

        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Penjualan</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i data-feather="trending-up" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm font-medium">+12.5% dari bulan lalu</span>
            </div>
        </div>

        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Avg. Diskon</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $averageDiscount }}%</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i data-feather="percent" class="w-6 h-6 text-red-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-red-600 text-sm font-medium">-5% dari bulan lalu</span>
            </div>
        </div>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Promo Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($promos as $promo)
        <div class="card rounded-xl overflow-hidden {{ $promo->status != 'active' ? 'opacity-75' : '' }}">
            <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $promo->image_url }}')"></div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="bg-primary bg-opacity-20 text-primary px-3 py-1 rounded-full text-sm font-medium">
                        {{ ucfirst($promo->category) }}
                    </span>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.promo.edit', $promo->id) }}" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                            <i data-feather="edit-2" class="w-4 h-4 text-gray-600"></i>
                        </a>
                        <form action="{{ route('admin.promo.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus promo ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                                <i data-feather="trash-2" class="w-4 h-4 text-gray-600"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $promo->name }}</h3>
                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($promo->description, 80) }}</p>
                
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-gray-400 text-sm line-through">Rp {{ number_format($promo->original_price, 0, ',', '.') }}</span>
                        <div class="text-2xl font-bold text-primary">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="bg-primary bg-opacity-20 rounded-lg px-3 py-2">
                            <span class="text-primary font-bold">{{ $promo->discount_percent }}% OFF</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between text-sm text-gray-500 mb-2">
                    <span><i data-feather="calendar" class="w-4 h-4 inline mr-1"></i>Mulai: {{ $promo->start_date->format('d M Y') }}</span>
                    @if($promo->end_date)
                    <span><i data-feather="calendar" class="w-4 h-4 inline mr-1"></i>Akhir: {{ $promo->end_date->format('d M Y') }}</span>
                    @endif
                </div>
                
                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                    <span><i data-feather="users" class="w-4 h-4 inline mr-1"></i>{{ $promo->sold_count }} terjual</span>
                    @if($promo->quota)
                    <span><i data-feather="box" class="w-4 h-4 inline mr-1"></i>Kuota: {{ $promo->quota }}</span>
                    @endif
                </div>
                
                <div class="flex justify-between items-center">
                    @if($promo->status == 'active')
                        <span class="bg-green-500 text-white px-2 py-1 rounded text-xs font-medium">Aktif</span>
                    @elseif($promo->status == 'inactive')
                        <span class="bg-gray-500 text-white px-2 py-1 rounded text-xs font-medium">Tidak Aktif</span>
                    @else
                        <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-medium">Kadaluarsa</span>
                    @endif
                    
                    @if($promo->featured)
                    <span class="bg-yellow-500 text-white px-2 py-1 rounded text-xs font-medium">Unggulan</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection

@section('extra-js')
<script>
    function deletePromo(id) {
        if (confirm('Apakah Anda yakin ingin menghapus promo ini?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection