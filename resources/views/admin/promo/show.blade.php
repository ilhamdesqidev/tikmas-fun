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
            
            <div class="card rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Syarat dan Ketentuan</h2>
                <div class="prose max-w-none text-gray-700">
                    <ul class="list-disc pl-5 space-y-2">
                        <li>Promo hanya berlaku untuk pembelian online melalui website resmi</li>
                        <li>Promo tidak dapat digabungkan dengan penawaran lainnya</li>
                        <li>Pembayaran harus dilakukan dalam waktu 24 jam setelah pemesanan</li>
                        <li>Pembatalan pemesanan dikenakan biaya administrasi 10% dari total harga</li>
                        <li>Promo dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya</li>
                        <li>Perusahaan berhak menolak pemesanan yang tidak memenuhi syarat dan ketentuan</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Bagian Kanan: Info Samping -->
        <div class="w-full lg:w-1/3">
            <div class="card rounded-xl p-6 sticky top-6">
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
                        <i data-feather="calendar" class="w-4 h-4 mr-2"></i>
                        <span>Mulai: <strong>{{ $promo->start_date->format('d M Y') }}</strong></span>
                    </div>
                    
                    @if($promo->end_date)
                    <div class="flex items-center text-sm text-gray-600">
                        <i data-feather="calendar" class="w-4 h-4 mr-2"></i>
                        <span>Berakhir: <strong>{{ $promo->end_date->format('d M Y') }}</strong></span>
                    </div>
                    @endif
                    
                    <div class="flex items-center text-sm text-gray-600">
                        <i data-feather="users" class="w-4 h-4 mr-2"></i>
                        <span>Terjual: <strong>{{ $promo->sold_count }}</strong></span>
                    </div>
                    
                    @if($promo->quota)
                    <div class="flex items-center text-sm text-gray-600">
                        <i data-feather="box" class="w-4 h-4 mr-2"></i>
                        <span>Kuota: <strong>{{ $promo->quota }}</strong></span>
                    </div>
                    @endif
                </div>
                
                <div class="flex justify-between items-center mb-6">
                    @if($promo->status == 'active')
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium">Aktif</span>
                    @elseif($promo->status == 'inactive')
                        <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-xs font-medium">Tidak Aktif</span>
                    @else
                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-medium">Kadaluarsa</span>
                    @endif
                    
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
            <div class="card rounded-xl p-6 mt-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik Promo</h3>
                
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Target Penjualan</span>
                            <span>{{ $promo->quota ? $promo->quota : 'Tidak Terbatas' }}</span>
                        </div>
                        @if($promo->quota)
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: {{ min(100, ($promo->sold_count / $promo->quota) * 100) }}%"></div>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $promo->sold_count }} dari {{ $promo->quota }} terjual ({{ round(($promo->sold_count / $promo->quota) * 100) }}%)
                        </div>
                        @endif
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Pendapatan</span>
                            <span>Rp {{ number_format($promo->sold_count * $promo->promo_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Penghematan Pelanggan</span>
                            <span>Rp {{ number_format($promo->sold_count * ($promo->original_price - $promo->promo_price), 0, ',', '.') }}</span>
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

@section('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection