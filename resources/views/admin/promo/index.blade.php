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
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
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
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Export
            </button>
            <a href="{{ route('admin.promo.create') }}" class="flex items-center px-4 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
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
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
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
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
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
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
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
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5 0L15 10m-1 4l-6-6m5.5 0L9 14"></path>
                    </svg>
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
            <a href="{{ route('admin.promo.show', $promo->id) }}" class="block">
                <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $promo->image_url }}')"></div>
            </a>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="bg-primary bg-opacity-20 text-primary px-3 py-1 rounded-full text-sm font-medium">
                        {{ ucfirst($promo->category) }}
                    </span>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.promo.edit', $promo->id) }}" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center justify-center w-9 h-9">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <form action="{{ route('admin.promo.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus promo ini?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center justify-center w-9 h-9">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                
                <a href="{{ route('admin.promo.show', $promo->id) }}" class="block hover:text-primary transition-colors">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $promo->name }}</h3>
                </a>
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
                    <span>
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Mulai: {{ $promo->start_date->format('d M Y') }}
                    </span>
                    @if($promo->end_date)
                    <span>
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Akhir: {{ $promo->end_date->format('d M Y') }}
                    </span>
                    @endif
                </div>
                
                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                    <span>
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        {{ $promo->actual_sold_count }} terjual
                    </span>
                    @if($promo->quota)
                    <span>
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                        Kuota: {{ $promo->quota }}
                    </span>
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
    // Pastikan Feather icons ter-render
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    function deletePromo(id) {
        if (confirm('Apakah Anda yakin ingin menghapus promo ini?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
