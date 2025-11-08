@extends('layouts.app')

@section('title', 'Management Voucher')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header dengan Gradient -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="p-3 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Management Voucher</h1>
                    <p class="text-gray-600 text-sm">Kelola voucher dan data klaim pengguna dengan mudah</p>
                </div>
            </div>
        </div>

        <!-- Content Card dengan Shadow Modern -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Navigation Tabs dengan Gradient -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-b border-gray-200">
                <div class="flex space-x-2">
                    <button onclick="switchTab('vouchers')" id="tabVouchers" class="flex-1 px-6 py-3.5 text-sm font-semibold rounded-xl transition-all duration-300 bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/30 transform hover:scale-[1.02]">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Daftar Voucher
                        </div>
                    </button>
                    <button onclick="switchTab('claims')" id="tabClaims" class="flex-1 px-6 py-3.5 text-sm font-semibold rounded-xl transition-all duration-300 text-gray-600 hover:bg-white hover:shadow-md">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Data User Klaim
                        </div>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <!-- Messages dengan Style Modern -->
                @if(session('success'))
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-800 px-6 py-4 rounded-lg mb-6 shadow-sm" role="alert">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-lg mb-6 shadow-sm" role="alert">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border-l-4 border-red-500 text-red-800 px-6 py-4 rounded-lg mb-6 shadow-sm" role="alert">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 mr-3 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <strong class="font-bold">Whoops!</strong>
                            <span class="block">Ada beberapa masalah dengan input Anda:</span>
                            <ul class="mt-2 ml-4 list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Vouchers Tab Content -->
                <div id="vouchersContent">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Daftar Voucher</h2>
                            <p class="text-sm text-gray-500 mt-1">Kelola semua voucher Anda</p>
                        </div>
                        <button onclick="openCreateModal()" class="group px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl transition-all duration-300 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 transform hover:scale-105 font-semibold">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2 transition-transform group-hover:rotate-90 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Voucher
                            </span>
                        </button>
                    </div>

                    <!-- Table dengan Border Radius Modern -->
                    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Gambar Display</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Gambar Download</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama Voucher</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal Kadaluarsa</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Diklaim</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($vouchers ?? [] as $index => $voucher)
                                    @php
                                        $isExpired = \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($voucher->expiry_date));
                                        $currentStatus = $isExpired ? 'kadaluarsa' : $voucher->status;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="relative group">
                                                <img src="{{ $voucher->image_url }}" alt="{{ $voucher->name }}" class="h-20 w-20 object-cover rounded-lg cursor-pointer ring-2 ring-gray-200 hover:ring-blue-400 transition-all duration-300 shadow-md hover:shadow-xl transform hover:scale-110" onerror="this.src='https://via.placeholder.com/80?text=No+Image'" onclick="showImageModal('{{ $voucher->image_url }}', 'Gambar Display')">
                                                <span class="absolute -bottom-1 -right-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold shadow-lg">Display</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="relative group">
                                                @if($voucher->download_image)
                                                    <img src="{{ $voucher->download_image_url }}" alt="{{ $voucher->name }} Download" class="h-20 w-20 object-cover rounded-lg cursor-pointer ring-2 ring-gray-200 hover:ring-green-400 transition-all duration-300 shadow-md hover:shadow-xl transform hover:scale-110" onerror="this.src='https://via.placeholder.com/80?text=No+Image'" onclick="showImageModal('{{ $voucher->download_image_url }}', 'Gambar Download')">
                                                    <span class="absolute -bottom-1 -right-1 bg-gradient-to-r from-green-500 to-green-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold shadow-lg">Download</span>
                                                @else
                                                    <div class="h-20 w-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                                                        <span class="text-xs text-gray-500 font-medium">Sama</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ $voucher->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <button onclick="openDescriptionModal('{{ addslashes($voucher->name) }}', '{{ addslashes($voucher->deskripsi) }}')" 
                                                    class="group px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg text-xs font-semibold transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Lihat Detail
                                                </span>
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $effectiveStatus = $currentStatus;
                                                if (!$voucher->is_unlimited && $voucher->remaining_quota <= 0) {
                                                    $effectiveStatus = 'habis';
                                                }
                                            @endphp
                                            
                                            @if($effectiveStatus === 'aktif')
                                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-200 shadow-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Aktif
                                                </span>
                                            @elseif($effectiveStatus === 'tidak_aktif')
                                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300 shadow-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Tidak Aktif
                                                </span>
                                            @elseif($effectiveStatus === 'habis')
                                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full bg-gradient-to-r from-orange-100 to-amber-100 text-orange-700 border border-orange-200 shadow-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Habis
                                                </span>
                                            @else
                                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full bg-gradient-to-r from-red-100 to-rose-100 text-red-700 border border-red-200 shadow-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Kadaluarsa
                                                </span>
                                            @endif
                                            
                                            @if($effectiveStatus !== $voucher->status)
                                                <span class="block text-xs text-orange-600 mt-1.5 font-medium">
                                                    ⚠️ Auto-{{ $effectiveStatus === 'habis' ? 'sold out' : 'expired' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $expiryDate = \Carbon\Carbon::parse($voucher->expiry_date);
                                                $isExpired = \Carbon\Carbon::now()->startOfDay()->greaterThan($expiryDate);
                                            @endphp
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 {{ $isExpired ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <div>
                                                    <span class="{{ $isExpired ? 'text-red-600 font-bold' : 'text-gray-600 font-medium' }}">
                                                        {{ $expiryDate->format('d M Y') }}
                                                    </span>
                                                    @if($isExpired)
                                                        <span class="block text-xs text-red-500 font-semibold">
                                                            (Sudah Lewat)
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="px-3 py-1.5 bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 rounded-full text-xs font-bold shadow-sm border border-blue-200">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                                {{ $voucher->claims_count ?? 0 }} User
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button onclick='openEditModal(@json($voucher))' 
                                                        class="group px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-700 rounded-lg transition-all duration-200 font-semibold">
                                                    <svg class="w-4 h-4 inline mr-1 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Edit
                                                </button>
                                                <button onclick="confirmDelete({{ $voucher->id }}, '{{ addslashes($voucher->name) }}')" 
                                                        class="group px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 rounded-lg transition-all duration-200 font-semibold">
                                                    <svg class="w-4 h-4 inline mr-1 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="p-4 bg-gray-100 rounded-full mb-4">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-gray-500 font-medium">Belum ada voucher yang tersedia</p>
                                                <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah Voucher" untuk membuat voucher baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Claims Tab Content -->
                <div id="claimsContent" class="hidden">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Data User Klaim Voucher</h2>
                            <p class="text-sm text-gray-500 mt-1">Total: <span class="font-bold text-blue-600">{{ isset($claims) ? $claims->count() : 0 }}</span> klaim terdaftar</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" id="searchClaim" placeholder="Cari nama atau nomor..." class="pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 w-64" onkeyup="searchClaims()">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <button onclick="searchClaims()" class="px-4 py-2.5 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white rounded-xl transition-all duration-300 shadow-md hover:shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Table Claims dengan Border Radius Modern -->
                    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama User</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No. Telepon</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Voucher</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Kode Unik</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Tanggal Klaim</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Expired Date</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="claimsTableBody">
                                    @forelse($claims ?? [] as $index => $claim)
                                    @php
                                        $voucherExpired = $claim->voucher && \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($claim->voucher->expiry_date));
                                        $isUsed = $claim->is_used || $claim->scanned_at;
                                    @endphp
                                    <tr class="claim-row hover:bg-gray-50 transition-colors duration-150 {{ $voucherExpired && !$isUsed ? 'bg-red-50/50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($claim->user_name, 0, 1)) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $claim->user_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center text-sm text-gray-900">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                <span class="font-medium">{{ $claim->user_phone }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <span class="font-semibold text-gray-900">{{ $claim->voucher->name ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1.5 bg-gradient-to-r from-gray-100 to-gray-200 rounded-lg font-mono text-xs font-bold text-gray-700 border border-gray-300 shadow-sm">
                                                {{ $claim->unique_code }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="font-medium">{{ $claim->created_at->format('d M Y H:i') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($claim->voucher)
                                                @php
                                                    $expiryDate = \Carbon\Carbon::parse($claim->voucher->expiry_date);
                                                    $voucherExpired = \Carbon\Carbon::now()->startOfDay()->greaterThan($expiryDate);
                                                @endphp
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 {{ $voucherExpired ? 'text-red-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <div>
                                                        <span class="{{ $voucherExpired ? 'text-red-600 font-bold' : 'text-gray-600 font-medium' }}">
                                                            {{ $expiryDate->format('d M Y') }}
                                                        </span>
                                                        @if($voucherExpired)
                                                            <span class="block text-xs text-red-500 font-semibold">
                                                                (Sudah Lewat)
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($isUsed)
                                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border border-gray-300 shadow-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Terpakai
                                                </span>
                                                @if($claim->scanned_at)
                                                    <span class="block text-xs text-gray-500 mt-1.5 font-medium">
                                                        {{ $claim->scanned_at->format('d M Y H:i') }}
                                                    </span>
                                                @endif
                                            @elseif($voucherExpired)
                                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full bg-gradient-to-r from-red-100 to-rose-100 text-red-700 border border-red-200 shadow-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Kadaluarsa
                                                </span>
                                                <span class="block text-xs text-red-500 mt-1.5 font-semibold">
                                                    Voucher expired
                                                </span>
                                            @else
                                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-200 shadow-sm">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Belum Terpakai
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="p-4 bg-gray-100 rounded-full mb-4">
                                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                </div>
                                                <p class="text-gray-500 font-medium">Belum ada user yang klaim voucher</p>
                                                <p class="text-gray-400 text-sm mt-1">Data klaim akan muncul di sini</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Voucher dengan Style Modern -->
<div id="createVoucherModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-0 border-0 w-full max-w-3xl shadow-2xl rounded-2xl bg-white max-h-[95vh] overflow-hidden my-8">
        <div class="sticky top-0 z-10 bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-5 border-b border-blue-400 flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 bg-white/20 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white">Tambah Voucher Baru</h3>
            </div>
            <button type="button" onclick="closeCreateModal()" class="text-white hover:bg-white/20 rounded-lg p-2 transition-all duration-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.voucher.store') }}" method="POST" enctype="multipart/form-data" class="overflow-y-auto max-h-[calc(95vh-80px)]" id="createForm">
            @csrf
            
            <div class="p-6 space-y-5">
                <div>
                    <label for="create_name" class="block text-sm font-bold text-gray-700 mb-2">Nama Voucher <span class="text-red-500">*</span></label>
                    <input type="text" id="create_name" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                           placeholder="Contoh: Diskon 50% Hari Kemerdekaan" required>
                </div>

                <div>
                    <label for="create_deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Voucher <span class="text-red-500">*</span></label>
                    <textarea id="create_deskripsi" name="deskripsi" rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                        placeholder="Deskripsi detail tentang voucher" required>{{ old('deskripsi') }}</textarea>
                </div>

                <div>
                    <label for="create_status" class="block text-sm font-bold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select id="create_status" name="status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('status') border-red-500 @enderror" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="kadaluarsa" {{ old('status') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                        <option value="habis" {{ old('status') == 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                    <p class="mt-2 text-xs text-blue-600 flex items-start">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Status akan otomatis berubah jika tanggal sudah lewat atau kuota habis
                    </p>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">Tipe Kuota <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 group">
                            <input type="radio" name="quota_type" value="unlimited" class="text-blue-600 focus:ring-blue-500 w-5 h-5" required>
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-gray-700 group-hover:text-blue-600">Unlimited</span>
                                <span class="block text-xs text-gray-500">Tanpa batas kuota</span>
                            </div>
                        </label>
                        <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 group">
                            <input type="radio" name="quota_type" value="limited" class="text-blue-600 focus:ring-blue-500 w-5 h-5" required>
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-gray-700 group-hover:text-blue-600">Terbatas</span>
                                <span class="block text-xs text-gray-500">Dengan batas kuota</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="quotaInputContainer" class="hidden">
                    <label for="create_quota" class="block text-sm font-bold text-gray-700 mb-2">Jumlah Kuota <span class="text-red-500">*</span></label>
                    <input type="number" id="create_quota" name="quota" min="1"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" 
                        placeholder="Contoh: 50">
                    <p class="mt-2 text-xs text-blue-600 flex items-start">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Masukkan jumlah voucher yang tersedia
                    </p>
                </div>

                <div>
                    <label for="create_expiry_date" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Kadaluarsa <span class="text-red-500">*</span></label>
                    <input type="date" id="create_expiry_date" name="expiry_date" value="{{ old('expiry_date') }}"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                    <p class="mt-2 text-xs text-blue-600 flex items-start">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        Voucher akan otomatis kadaluarsa setelah tanggal ini
                    </p>
                </div>

                <div>
                    <label for="create_image" class="block text-sm font-bold text-gray-700 mb-2">
                        Gambar Voucher (Display) <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-3 flex items-start">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                        </svg>
                        Gambar ini akan ditampilkan di halaman daftar voucher
                    </p>
                    <div class="flex items-center justify-center w-full">
                        <label for="create_image" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-blue-400 transition-all duration-200">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-600"><span class="font-semibold">Klik untuk upload</span></p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 10MB)</p>
                            </div>
                            <input id="create_image" name="image" type="file" accept="image/*" class="hidden" onchange="previewCreateImage(event)" required>
                        </label>
                    </div>
                    <div id="createImagePreview" class="mt-4 hidden">
                        <img id="createPreview" src="" alt="Preview" class="w-full h-56 object-cover rounded-xl border-2 border-blue-300 shadow-md">
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-5 rounded-xl border-2 border-blue-200">
                    <label for="create_download_image" class="block text-sm font-bold text-gray-700 mb-2">
                        🎁 Gambar Voucher (Download) <span class="text-gray-500 font-normal">(Opsional)</span>
                    </label>
                    <p class="text-xs text-blue-700 mb-4 flex items-start">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Gambar ini akan digunakan sebagai background saat user download voucher (dengan barcode overlay). Jika tidak diisi, akan menggunakan gambar display.
                    </p>
                    <div class="flex items-center justify-center w-full">
                        <label for="create_download_image" class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-blue-300 rounded-xl cursor-pointer bg-white hover:bg-blue-50 hover:border-blue-400 transition-all duration-200">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mb-2 text-sm text-blue-600"><span class="font-semibold">Upload gambar khusus</span></p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 10MB)</p>
                            </div>
                            <input id="create_download_image" name="download_image" type="file" accept="image/*" class="hidden" onchange="previewCreateDownloadImage(event)">
                        </label>
                    </div>
                    <div id="createDownloadImagePreview" class="mt-4 hidden">
                        <p class="text-xs text-gray-600 mb-2 font-medium">Preview gambar download:</p>
                        <img id="createDownloadPreview" src="" alt="Download Preview" class="w-full h-56 object-cover rounded-xl border-2 border-blue-400 shadow-md">
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeCreateModal()" class="px-6 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 font-semibold">
                    Simpan Voucher
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Voucher dengan Style Modern -->
<div id="editVoucherModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-0 border-0 w-full max-w-3xl shadow-2xl rounded-2xl bg-white max-h-[95vh] overflow-hidden my-8">
        <div class="sticky top-0 z-10 bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-5 border-b border-purple-400 flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 bg-white/20 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white">Edit Voucher</h3>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-white hover:bg-white/20 rounded-lg p-2 transition-all duration-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="overflow-y-auto max-h-[calc(95vh-80px)]">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-5">
                <div>
                    <label for="edit_name" class="block text-sm font-bold text-gray-700 mb-2">Nama Voucher <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_name" name="name" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200" required>
                </div>

                <div>
                    <label for="edit_deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Voucher <span class="text-red-500">*</span></label>
                    <textarea id="edit_deskripsi" name="deskripsi" rows="4" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200" required></textarea>
                </div>

                <div>
                    <label for="edit_status" class="block text-sm font-bold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select id="edit_status" name="status" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200" required>
                        <option value="aktif">Aktif</option>
                        <option value="tidak_aktif">Tidak Aktif</option>
                        <option value="kadaluarsa">Kadaluarsa</option>
                        <option value="habis">Habis</option>
                    </select>
                    <p class="mt-2 text-xs text-purple-600 flex items-start">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Status akan otomatis berubah jika tanggal sudah lewat atau kuota habis
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">Tipe Kuota <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-200 group">
                            <input type="radio" name="quota_type" value="unlimited" class="text-purple-600 focus:ring-purple-500 w-5 h-5" required>
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-gray-700 group-hover:text-purple-600">Unlimited</span>
                                <span class="block text-xs text-gray-500">Tanpa batas kuota</span>
                            </div>
                        </label>
                        <label class="relative flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-200 group">
                            <input type="radio" name="quota_type" value="limited" class="text-purple-600 focus:ring-purple-500 w-5 h-5" required>
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-gray-700 group-hover:text-purple-600">Terbatas</span>
                                <span class="block text-xs text-gray-500">Dengan batas kuota</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="editQuotaInputContainer" class="hidden">
                    <label for="edit_quota" class="block text-sm font-bold text-gray-700 mb-2">Jumlah Kuota <span class="text-red-500">*</span></label>
                    <input type="number" id="edit_quota" name="quota" min="1"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200" 
                           placeholder="Contoh: 50">
                    <p class="mt-2 text-xs text-purple-600 flex items-start">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Masukkan jumlah voucher yang tersedia
                    </p>
                </div>

                <div>
                    <label for="edit_expiry_date" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Kadaluarsa <span class="text-red-500">*</span></label>
                    <input type="date" id="edit_expiry_date" name="expiry_date" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200" required>
                    <p class="mt-2 text-xs text-purple-600 flex items-start">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        Voucher akan otomatis kadaluarsa setelah tanggal ini
                    </p>
                </div>

                <div>
                    <label for="edit_image" class="block text-sm font-bold text-gray-700 mb-2">Gambar Voucher (Display)</label>
                    <p class="text-xs text-gray-500 mb-3">Kosongkan jika tidak ingin mengubah gambar display</p>
                    
                    <div id="currentImageContainer" class="mb-4">
                        <p class="text-sm text-gray-600 mb-2 font-medium">Gambar display saat ini:</p>
                        <img id="currentImage" src="" alt="Current" class="w-full h-56 object-cover rounded-xl border-2 border-gray-300 shadow-md">
                    </div>
                    
                    <div class="flex items-center justify-center w-full">
                        <label for="edit_image" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-purple-400 transition-all duration-200">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-600"><span class="font-semibold">Klik untuk upload</span> gambar baru</p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 10MB)</p>
                            </div>
                            <input id="edit_image" name="image" type="file" accept="image/*" class="hidden" onchange="previewEditImage(event)">
                        </label>
                    </div>
                    
                    <div id="editImagePreview" class="mt-4 hidden">
                        <p class="text-sm text-gray-600 mb-2 font-medium">Preview gambar display baru:</p>
                        <img id="editPreview" src="" alt="Preview" class="w-full h-56 object-cover rounded-xl border-2 border-purple-300 shadow-md">
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-indigo-50 p-5 rounded-xl border-2 border-purple-200">
                    <label for="edit_download_image" class="block text-sm font-bold text-gray-700 mb-2">
                        🎁 Gambar Voucher (Download) <span class="text-gray-500 font-normal">(Opsional)</span>
                    </label>
                    <p class="text-xs text-purple-700 mb-4 flex items-start">
                        <svg class="w-4 h-4 mr-1 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Gambar untuk background download dengan barcode. Kosongkan jika tidak ingin mengubah.
                    </p>
                    
                    <div id="currentDownloadImageContainer" class="mb-4">
                        <p class="text-sm text-gray-600 mb-2 font-medium">Gambar download saat ini:</p>
                        <img id="currentDownloadImage" src="" alt="Current Download" class="w-full h-56 object-cover rounded-xl border-2 border-purple-300 shadow-md">
                    </div>
                    
                    <div class="flex items-center justify-center w-full">
                        <label for="edit_download_image" class="flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-purple-300 rounded-xl cursor-pointer bg-white hover:bg-purple-50 hover:border-purple-400 transition-all duration-200">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mb-2 text-sm text-purple-600"><span class="font-semibold">Upload gambar download baru</span></p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 10MB)</p>
                            </div>
                            <input id="edit_download_image" name="download_image" type="file" accept="image/*" class="hidden" onchange="previewEditDownloadImage(event)">
                        </label>
                    </div>
                    
                    <div id="editDownloadImagePreview" class="mt-4 hidden">
                        <p class="text-sm text-gray-600 mb-2 font-medium">Preview gambar download baru:</p>
                        <img id="editDownloadPreview" src="" alt="Download Preview" class="w-full h-56 object-cover rounded-xl border-2 border-purple-400 shadow-md">
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all duration-200 shadow-lg shadow-purple-500/30 hover:shadow-xl hover:shadow-purple-500/40 font-semibold">
                    Update Voucher
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Deskripsi dengan Style Modern -->
<div id="descriptionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-3xl shadow-2xl rounded-2xl bg-white overflow-hidden my-8">
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-5 border-b border-indigo-400 flex justify-between items-center">
            <div class="flex items-center">
                <div class="p-2 bg-white/20 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white" id="descriptionTitle">Deskripsi Voucher</h3>
            </div>
            <button type="button" onclick="closeDescriptionModal()" class="text-white hover:bg-white/20 rounded-lg p-2 transition-all duration-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <div class="bg-gradient-to-br from-gray-50 to-indigo-50 rounded-xl p-6 border-2 border-indigo-100">
                <p id="descriptionContent" class="text-gray-700 whitespace-pre-wrap leading-relaxed"></p>
            </div>
        </div>

        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t border-gray-200 flex justify-end">
            <button type="button" onclick="closeDescriptionModal()" class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200 shadow-lg shadow-indigo-500/30 font-semibold">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Delete Confirmation dengan Style Modern -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
    <div class="relative top-32 mx-auto p-0 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white overflow-hidden">
        <div class="p-8">
            <div class="flex flex-col items-center text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gradient-to-br from-red-100 to-red-200 mb-4">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Hapus Voucher</h3>
                <div class="px-4 py-3">
                    <p class="text-gray-600">
                        Apakah Anda yakin ingin menghapus voucher 
                    </p>
                    <p class="font-bold text-gray-900 mt-2">
                        "<span id="deleteVoucherName"></span>"?
                    </p>
                    <p class="text-sm text-red-600 mt-3">
                        ⚠️ Tindakan ini tidak dapat dibatalkan
                    </p>
                </div>
                <div class="flex justify-center space-x-3 w-full px-4 mt-4">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-6 py-2.5 bg-white border-2 border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 font-semibold">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-6 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-lg shadow-red-500/30 font-semibold">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Image Preview dengan Style Modern -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-95 backdrop-blur-md overflow-y-auto h-full w-full z-50" onclick="closeImageModal()">
    <div class="relative top-10 mx-auto p-5 w-full max-w-5xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-white flex items-center" id="imageModalTitle">
                <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Preview Gambar
            </h3>
            <button type="button" onclick="closeImageModal()" class="text-white hover:bg-white/20 rounded-lg p-2 transition-all duration-200">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <img id="imageModalContent" src="" alt="Preview" class="w-full h-auto rounded-2xl shadow-2xl border-4 border-white/20 transform transition-transform duration-300 hover:scale-105" onclick="event.stopPropagation()">
    </div>
</div>

<script>
// Tab Switching
function switchTab(tab) {
    const vouchersTab = document.getElementById('tabVouchers');
    const claimsTab = document.getElementById('tabClaims');
    const vouchersContent = document.getElementById('vouchersContent');
    const claimsContent = document.getElementById('claimsContent');

    if (tab === 'vouchers') {
        vouchersTab.classList.add('bg-gradient-to-r', 'from-blue-500', 'to-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-500/30');
        vouchersTab.classList.remove('text-gray-600', 'hover:bg-white', 'hover:shadow-md');
        claimsTab.classList.remove('bg-gradient-to-r', 'from-blue-500', 'to-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-500/30');
        claimsTab.classList.add('text-gray-600', 'hover:bg-white', 'hover:shadow-md');
        vouchersContent.classList.remove('hidden');
        claimsContent.classList.add('hidden');
    } else {
        claimsTab.classList.add('bg-gradient-to-r', 'from-blue-500', 'to-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-500/30');
        claimsTab.classList.remove('text-gray-600', 'hover:bg-white', 'hover:shadow-md');
        vouchersTab.classList.remove('bg-gradient-to-r', 'from-blue-500', 'to-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-500/30');
        vouchersTab.classList.add('text-gray-600', 'hover:bg-white', 'hover:shadow-md');
        claimsContent.classList.remove('hidden');
        vouchersContent.classList.add('hidden');
    }
}

// Search Claims
function searchClaims() {
    const search = document.getElementById('searchClaim').value.toLowerCase();
    const rows = document.querySelectorAll('.claim-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
}

// Quota Toggle Functions
function toggleQuotaInput() {
    const createModal = document.getElementById('createVoucherModal');
    if (!createModal) return;
    
    const quotaType = createModal.querySelector('input[name="quota_type"]:checked');
    if (!quotaType) return;
    
    const quotaInputContainer = document.getElementById('quotaInputContainer');
    const quotaInput = document.getElementById('create_quota');
    
    if (quotaInputContainer) {
        if (quotaType.value === 'limited') {
            quotaInputContainer.classList.remove('hidden');
            if (quotaInput) quotaInput.required = true;
        } else {
            quotaInputContainer.classList.add('hidden');
            if (quotaInput) {
                quotaInput.required = false;
                quotaInput.value = '';
            }
        }
    }
}

function toggleEditQuotaInput() {
    const editModal = document.getElementById('editVoucherModal');
    if (!editModal) return;
    
    const quotaType = editModal.querySelector('input[name="quota_type"]:checked');
    if (!quotaType) return;
    
    const quotaInputContainer = document.getElementById('editQuotaInputContainer');
    const quotaInput = document.getElementById('edit_quota');
    
    if (quotaInputContainer) {
        if (quotaType.value === 'limited') {
            quotaInputContainer.classList.remove('hidden');
            if (quotaInput) quotaInput.required = true;
        } else {
            quotaInputContainer.classList.add('hidden');
            if (quotaInput) {
                quotaInput.required = false;
                quotaInput.value = '';
            }
        }
    }
}

// Create Modal Functions
function openCreateModal() {
    document.getElementById('createVoucherModal').classList.remove('hidden');
    setTimeout(() => toggleQuotaInput(), 100);
}

function closeCreateModal() {
    document.getElementById('createVoucherModal').classList.add('hidden');
    const form = document.getElementById('createForm');
    if (form) form.reset();
    document.getElementById('createImagePreview')?.classList.add('hidden');
    document.getElementById('createDownloadImagePreview')?.classList.add('hidden');
}

function previewCreateImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('createPreview').src = e.target.result;
            document.getElementById('createImagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function previewCreateDownloadImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('createDownloadPreview').src = e.target.result;
            document.getElementById('createDownloadImagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

// Edit Modal Functions
function openEditModal(voucher) {
    document.getElementById('editVoucherModal').classList.remove('hidden');
    document.getElementById('edit_name').value = voucher.name;
    document.getElementById('edit_deskripsi').value = voucher.deskripsi;
    document.getElementById('edit_status').value = voucher.status;
    document.getElementById('edit_expiry_date').value = voucher.expiry_date;
    
    const editModal = document.getElementById('editVoucherModal');
    if (editModal) {
        const quotaType = voucher.is_unlimited ? 'unlimited' : 'limited';
        const quotaRadio = editModal.querySelector(`input[name="quota_type"][value="${quotaType}"]`);
        if (quotaRadio) quotaRadio.checked = true;
    }
    
    if (!voucher.is_unlimited && voucher.quota) {
        document.getElementById('edit_quota').value = voucher.quota;
    }
    
    document.getElementById('currentImage').src = voucher.image_url;
    
    if (voucher.download_image) {
        document.getElementById('currentDownloadImage').src = voucher.download_image_url;
        document.getElementById('currentDownloadImageContainer').classList.remove('hidden');
    } else {
        document.getElementById('currentDownloadImageContainer').classList.add('hidden');
    }
    
    document.getElementById('editForm').action = `/admin/voucher/${voucher.id}`;
    document.getElementById('editImagePreview')?.classList.add('hidden');
    document.getElementById('editDownloadImagePreview')?.classList.add('hidden');
    
    setTimeout(() => toggleEditQuotaInput(), 100);
}

function closeEditModal() {
    document.getElementById('editVoucherModal').classList.add('hidden');
}

function previewEditImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('editPreview').src = e.target.result;
            document.getElementById('editImagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

function previewEditDownloadImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('editDownloadPreview').src = e.target.result;
            document.getElementById('editDownloadImagePreview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}

// Description Modal
function openDescriptionModal(name, deskripsi) {
    document.getElementById('descriptionModal').classList.remove('hidden');
    document.getElementById('descriptionTitle').textContent = `Deskripsi: ${name}`;
    document.getElementById('descriptionContent').textContent = deskripsi;
}

function closeDescriptionModal() {
    document.getElementById('descriptionModal').classList.add('hidden');
}

// Delete Modal
function confirmDelete(id, name) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteVoucherName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/voucher/${id}`;
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Image Preview Modal
function showImageModal(url, title) {
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModalTitle').textContent = title;
    document.getElementById('imageModalContent').src = url;
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Close modals on outside click
    ['createVoucherModal','editVoucherModal','descriptionModal','deleteModal'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (id === 'createVoucherModal') closeCreateModal();
                    if (id === 'editVoucherModal') closeEditModal();
                    if (id === 'descriptionModal') closeDescriptionModal();
                    if (id === 'deleteModal') closeDeleteModal();
                }
            });
        }
    });

    // Create modal quota listeners
    const createModal = document.getElementById('createVoucherModal');
    if (createModal) {
        createModal.querySelectorAll('input[name="quota_type"]').forEach(radio => {
            radio.addEventListener('change', toggleQuotaInput);
        });
    }
    
    // Edit modal quota listeners
    const editModal = document.getElementById('editVoucherModal');
    if (editModal) {
        editModal.querySelectorAll('input[name="quota_type"]').forEach(radio => {
            radio.addEventListener('change', toggleEditQuotaInput);
        });
    }
    
    toggleQuotaInput();
    
    // Show modal if validation errors
    @if($errors->any())
        openCreateModal(); 
        setTimeout(() => {
            const oldQuotaType = '{{ old("quota_type", "unlimited") }}';
            const createModal = document.getElementById('createVoucherModal');
            if (createModal) {
                const quotaRadio = createModal.querySelector(`input[name="quota_type"][value="${oldQuotaType}"]`);
                if (quotaRadio) {
                    quotaRadio.checked = true;
                    toggleQuotaInput();
                }
            }
        }, 100);
    @endif

    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!document.getElementById('createVoucherModal').classList.contains('hidden')) {
                closeCreateModal();
            } else if (!document.getElementById('editVoucherModal').classList.contains('hidden')) {
                closeEditModal();
            } else if (!document.getElementById('descriptionModal').classList.contains('hidden')) {
                closeDescriptionModal();
            } else if (!document.getElementById('deleteModal').classList.contains('hidden')) {
                closeDeleteModal();
            } else if (!document.getElementById('imageModal').classList.contains('hidden')) {
                closeImageModal();
            }
        }
    });
});
</script>
@endsection