@extends('layouts.app')

@section('title', $promo->name . ' - Detail Promo')
@section('page-title', 'Detail Paket Promo')
@section('page-description', 'Informasi lengkap paket promo')

@section('content')
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Bagian Kiri: Gambar dan Info Utama -->
        <div class="w-full lg:w-2/3">
            <div class="card rounded-xl overflow-hidden mb-6">
                <div class="h-64 bg-cover bg-center" style="background-image: url('{{ $promo->image_url }}')"></div>
            </div>
            
            <div class="card rounded-xl p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Deskripsi Promo</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($promo->description)) !!}
                </div>
            </div>

            @if($promo->bracelet_design)
            <div class="card rounded-xl p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Desain Gelang</h2>
                <div class="text-center">
                    <img src="{{ $promo->bracelet_design_url }}" alt="Desain Gelang {{ $promo->name }}" 
                        class="max-w-full h-auto mx-auto rounded-lg shadow-lg max-h-96">
                    <p class="text-sm text-gray-500 mt-2">Desain gelang yang akan digunakan untuk promo ini</p>
                </div>
            </div>
            @endif
            
            <div class="card rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Syarat dan Ketentuan</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($promo->terms_conditions)) !!}
                </div>
            </div>
        </div>
            <div class="w-full lg:w-1/3">
                <div class="space-y-6 sticky top-6">
                    <!-- Card Utama -->
                    <div class="card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="bg-primary bg-opacity-20 text-primary px-3 py-1 rounded-full text-sm font-medium">
                                {{ ucfirst($promo->category) }}
                            </span>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.promo.edit', $promo->id) }}" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.promo.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus promo ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $promo->name }}</h1>
                        
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <span class="text-gray-400 text-sm line-through">Rp {{ number_format($promo->original_price, 0, ',', '.') }}</span>
                                <div class="text-3xl font-bold text-primary">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</div>
                            </div>
                            <div class="text-right">
                                <div class="bg-primary bg-opacity-20 rounded-lg px-3 py-2">
                                    <span class="text-primary font-bold">{{ $promo->discount_percent }}% OFF</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Mulai: <strong>{{ $promo->start_date->format('d M Y') }}</strong></span>
                            </div>
                            
                            @if($promo->end_date)
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Berakhir: <strong>{{ $promo->end_date->format('d M Y') }}</strong></span>
                            </div>
                            @endif
                            
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <span>Total Orders: <strong class="text-blue-600">{{ $promo->orders()->where('status', 'success')->count() }}</strong></span>
                            </div>
                            
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                                <span>Tiket Terjual: <strong class="text-green-600">{{ $promo->actual_sold_count }}</strong></span>
                            </div>
                            
                            @if($promo->quota)
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                </svg>
                                <span>Kuota: <strong>{{ $promo->quota }}</strong></span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="flex justify-between items-center mb-6">
                            <span class="{{ $promo->status_color }} text-white px-3 py-1 rounded-full text-xs font-medium">
                                {{ $promo->status_text }}
                            </span>
                            
                            @if($promo->featured)
                            <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-medium">Unggulan</span>
                            @endif
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.promo.index') }}" class="flex-1 text-center px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                Kembali
                            </a>
                            <a href="{{ route('admin.promo.edit', $promo->id) }}" class="flex-1 text-center px-4 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 font-medium">
                                Edit Promo
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistik Promo -->
                    <div class="card rounded-xl p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik Promo</h3>
                        
                        <div class="space-y-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-blue-600 font-medium">Total Orders</span>
                                    <span class="text-2xl font-bold text-blue-700">{{ $promo->orders()->where('status', 'success')->count() }}</span>
                                </div>
                                <p class="text-xs text-blue-500">Pesanan berhasil</p>
                            </div>

                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-green-600 font-medium">Tiket Terjual</span>
                                    <span class="text-2xl font-bold text-green-700">{{ $promo->actual_sold_count }}</span>
                                </div>
                                <p class="text-xs text-green-500">Total tiket dari semua orders</p>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Target Penjualan</span>
                                    <span>{{ $promo->quota ? $promo->quota : 'Tidak Terbatas' }}</span>
                                </div>
                                @if($promo->quota)
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full" style="width: {{ min(100, ($promo->actual_sold_count / $promo->quota) * 100) }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $promo->actual_sold_count }} dari {{ $promo->quota }} terjual ({{ $promo->quota > 0 ? round(($promo->actual_sold_count / $promo->quota) * 100) : 0 }}%)
                                </div>
                                @endif
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Total Pendapatan</span>
                                    <span class="font-bold text-blue-600">Rp {{ number_format($promo->total_revenue, 0, ',', '.') }}</span>
                                </div>
                                <p class="text-xs text-gray-500">Dari orders yang berhasil</p>
                            </div>
                            
                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Estimasi Jika Harga Normal</span>
                                    <span>Rp {{ number_format($promo->actual_sold_count * $promo->original_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <div class="bg-yellow-50 rounded-lg p-3">
                                <div class="flex justify-between text-sm text-yellow-700 mb-1">
                                    <span class="font-medium">Total Penghematan Pelanggan</span>
                                    <span class="font-bold">Rp {{ number_format($promo->actual_sold_count * ($promo->original_price - $promo->promo_price), 0, ',', '.') }}</span>
                                </div>
                                <p class="text-xs text-yellow-600">Hemat yang didapat pelanggan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-css')
<style>
    .prose ul {
        list-style-type: disc;
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .prose li {
        margin-bottom: 0.5rem;
    }
</style>
@endsection