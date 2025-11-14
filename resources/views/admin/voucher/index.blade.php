@extends('layouts.app')

@section('title', 'Management Voucher')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Management Voucher
        </h1>
        <p class="text-gray-600 mt-2">Kelola voucher dan data klaim pengguna dengan mudah</p>
    </div>

    <!-- Stats Cards -->
    @if(isset($claimsStats))
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Klaim</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($claimsStats['total']) }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Belum Terpakai</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($claimsStats['active']) }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-100 text-sm font-medium">Sudah Terpakai</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($claimsStats['used']) }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Kadaluarsa</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($claimsStats['expired']) }}</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Content Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200">
            <div class="flex">
                <button onclick="switchTab('vouchers')" id="tabVouchers" 
                        class="flex-1 px-6 py-4 text-center font-semibold transition-all duration-200 border-b-2 border-blue-500 text-blue-600 bg-blue-50">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <span>Daftar Voucher</span>
                        <span class="ml-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">{{ isset($vouchers) ? $vouchers->count() : 0 }}</span>
                    </div>
                </button>
                <button onclick="switchTab('claims')" id="tabClaims" 
                        class="flex-1 px-6 py-4 text-center font-semibold transition-all duration-200 border-b-2 border-transparent text-gray-600 hover:bg-gray-50">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Data User Klaim</span>
                        <span class="ml-2 bg-gray-500 text-white text-xs px-2 py-1 rounded-full">{{ isset($claims) ? $claims->count() : 0 }}</span>
                    </div>
                </button>
            </div>
        </div>

        <div class="p-6">
            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg animate-fade-in">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg animate-fade-in">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-red-800 font-medium">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="flex-1">
                        <strong class="text-red-800 font-bold">Terdapat Kesalahan!</strong>
                        <ul class="mt-2 ml-4 list-disc text-sm text-red-700">
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
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Daftar Voucher</h2>
                        <p class="text-sm text-gray-600 mt-1">Kelola semua voucher yang tersedia</p>
                    </div>
                    <button onclick="openCreateModal()" 
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Voucher
                    </button>
                </div>

                <!-- Table Vouchers -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Gambar</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Info Voucher</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kuota</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Expired</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Diklaim</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($vouchers ?? [] as $index => $voucher)
                            @php
                                $isExpired = \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($voucher->expiry_date));
                                $effectiveStatus = $isExpired ? 'kadaluarsa' : $voucher->status;
                                if (!$voucher->is_unlimited && $voucher->remaining_quota <= 0) {
                                    $effectiveStatus = 'habis';
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-2">
                                        <div class="relative group">
                                            <img src="{{ $voucher->image_url }}" alt="{{ $voucher->name }}" 
                                                 class="h-20 w-20 object-cover rounded-lg cursor-pointer shadow-md hover:shadow-xl transition duration-200" 
                                                 onerror="this.src='https://via.placeholder.com/80?text=No+Image'" 
                                                 onclick="showImageModal('{{ $voucher->image_url }}', 'Gambar Display')">
                                            <span class="absolute bottom-1 right-1 bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full shadow">Display</span>
                                        </div>
                                        @if($voucher->download_image)
                                        <div class="relative group">
                                            <img src="{{ $voucher->download_image_url }}" alt="{{ $voucher->name }} Download" 
                                                 class="h-20 w-20 object-cover rounded-lg cursor-pointer shadow-md hover:shadow-xl transition duration-200" 
                                                 onerror="this.src='https://via.placeholder.com/80?text=No+Image'" 
                                                 onclick="showImageModal('{{ $voucher->download_image_url }}', 'Gambar Download')">
                                            <span class="absolute bottom-1 right-1 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full shadow">Download</span>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $voucher->name }}</div>
                                    <button onclick="openDescriptionModal('{{ addslashes($voucher->name) }}', '{{ addslashes($voucher->deskripsi) }}')" 
                                            class="mt-2 inline-flex items-center text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Lihat Deskripsi
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($voucher->is_unlimited)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Unlimited
                                        </span>
                                    @else
                                        <div class="flex items-center">
                                            <span class="text-gray-700 font-medium">{{ $voucher->remaining_quota }}</span>
                                            <span class="text-gray-500 mx-1">/</span>
                                            <span class="text-gray-500">{{ $voucher->quota }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                            @php
                                                $percentage = $voucher->quota > 0 ? ($voucher->remaining_quota / $voucher->quota) * 100 : 0;
                                                $barColor = $percentage > 50 ? 'bg-green-500' : ($percentage > 20 ? 'bg-yellow-500' : 'bg-red-500');
                                            @endphp
                                            <div class="{{ $barColor }} h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $expiryDate = \Carbon\Carbon::parse($voucher->expiry_date);
                                        $daysLeft = \Carbon\Carbon::now()->diffInDays($expiryDate, false);
                                    @endphp
                                    <div class="flex flex-col">
                                        <span class="{{ $isExpired ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                            {{ $expiryDate->format('d M Y') }}
                                        </span>
                                        @if($isExpired)
                                            <span class="text-xs text-red-500 mt-1">Sudah Lewat</span>
                                        @elseif($daysLeft <= 7)
                                            <span class="text-xs text-orange-500 mt-1">{{ $daysLeft }} hari lagi</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($effectiveStatus === 'aktif')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                            Aktif
                                        </span>
                                    @elseif($effectiveStatus === 'tidak_aktif')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800">
                                            <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                                            Tidak Aktif
                                        </span>
                                    @elseif($effectiveStatus === 'habis')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800">
                                            <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                                            Habis
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                            Kadaluarsa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-800 rounded-full text-sm font-bold">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        {{ $voucher->claims_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick='openEditModal(@json($voucher))' 
                                                class="inline-flex items-center px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded-lg transition duration-200 shadow hover:shadow-md">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </button>
                                        <button onclick="confirmDelete({{ $voucher->id }}, '{{ addslashes($voucher->name) }}')" 
                                                class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-lg transition duration-200 shadow hover:shadow-md">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        <p class="text-gray-500 font-medium">Belum ada voucher yang tersedia</p>
                                        <button onclick="openCreateModal()" class="mt-4 text-blue-600 hover:text-blue-800 font-medium">
                                            Tambah voucher pertama Anda
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Claims Tab Content -->
            <div id="claimsContent" class="hidden">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Data User Klaim Voucher</h2>
                        <p class="text-sm text-gray-600 mt-1">Total: <span class="font-bold text-blue-600">{{ isset($claims) ? number_format($claims->count()) : 0 }}</span> klaim</p>
                    </div>
                    <div class="flex gap-2">
                        <div class="relative">
                            <input type="text" id="searchClaim" 
                                   placeholder="Cari nama, nomor, domisili..." 
                                   class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   onkeyup="searchClaims()">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Export Section -->
                <div class="mb-6 bg-gradient-to-r from-green-50 to-blue-50 p-6 rounded-xl border-2 border-green-200 shadow-md">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="bg-green-500 rounded-full p-3 shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-800">📊 Export Data ke CSV</h3>
                                <p class="text-sm text-gray-600">File CSV dengan format rapi & siap dibuka di Excel</p>
                            </div>
                        </div>
                        <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow animate-pulse">SIMPLE</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                        <!-- Export All -->
                        <a href="{{ route('admin.voucher.export', ['status' => 'all']) }}" 
                           class="group flex flex-col items-center justify-center px-4 py-4 bg-white hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 text-gray-700 hover:text-white rounded-xl transition-all duration-300 shadow-md hover:shadow-xl border-2 border-blue-200 hover:border-blue-500 transform hover:scale-105">
                            <svg class="w-8 h-8 mb-2 text-blue-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm font-bold text-center">Semua Data</span>
                            <span class="text-xs text-gray-500 group-hover:text-white mt-1">{{ isset($claims) ? $claims->count() : 0 }} klaim</span>
                        </a>
                        
                        <!-- Export Active -->
                        <a href="{{ route('admin.voucher.export', ['status' => 'active']) }}" 
                           class="group flex flex-col items-center justify-center px-4 py-4 bg-white hover:bg-gradient-to-r hover:from-green-500 hover:to-green-600 text-gray-700 hover:text-white rounded-xl transition-all duration-300 shadow-md hover:shadow-xl border-2 border-green-200 hover:border-green-500 transform hover:scale-105">
                            <svg class="w-8 h-8 mb-2 text-green-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-bold text-center">Belum Terpakai</span>
                            <span class="text-xs text-gray-500 group-hover:text-white mt-1">{{ isset($claimsStats) ? $claimsStats['active'] : 0 }} klaim</span>
                        </a>
                        
                        <!-- Export Used -->
                        <a href="{{ route('admin.voucher.export', ['status' => 'used']) }}" 
                           class="group flex flex-col items-center justify-center px-4 py-4 bg-white hover:bg-gradient-to-r hover:from-gray-500 hover:to-gray-600 text-gray-700 hover:text-white rounded-xl transition-all duration-300 shadow-md hover:shadow-xl border-2 border-gray-200 hover:border-gray-500 transform hover:scale-105">
                            <svg class="w-8 h-8 mb-2 text-gray-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm font-bold text-center">Sudah Terpakai</span>
                            <span class="text-xs text-gray-500 group-hover:text-white mt-1">{{ isset($claimsStats) ? $claimsStats['used'] : 0 }} klaim</span>
                        </a>
                        
                        <!-- Export Expired -->
                        <a href="{{ route('admin.voucher.export', ['status' => 'expired']) }}" 
                           class="group flex flex-col items-center justify-center px-4 py-4 bg-white hover:bg-gradient-to-r hover:from-red-500 hover:to-red-600 text-gray-700 hover:text-white rounded-xl transition-all duration-300 shadow-md hover:shadow-xl border-2 border-red-200 hover:border-red-500 transform hover:scale-105">
                            <svg class="w-8 h-8 mb-2 text-red-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-bold text-center">Kadaluarsa</span>
                            <span class="text-xs text-gray-500 group-hover:text-white mt-1">{{ isset($claimsStats) ? $claimsStats['expired'] : 0 }} klaim</span>
                        </a>
                    </div>
                    
                    <div class="mt-4 p-3 bg-white bg-opacity-70 rounded-lg border border-green-300">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-gray-700">
                                <span class="font-semibold">Format CSV Profesional:</span>
                                <ul class="mt-1 ml-4 list-disc text-xs space-y-1">
                                    <li>Header & footer dengan border ASCII art yang rapi</li>
                                    <li>Emoji untuk status (✅ Aktif, ⚠️ Kadaluarsa, 🟢 Belum Terpakai)</li>
                                    <li>Statistik ringkasan lengkap di bawah data</li>
                                    <li>UTF-8 BOM untuk karakter Indonesia</li>
                                    <li>Siap dibuka di Excel/Google Sheets tanpa error</li>
                                    <li>Petunjuk penggunaan di bagian footer</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Claims -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nama User</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Domisili</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">WhatsApp</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Voucher</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kode Unik</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Tgl Klaim</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Expired</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="claimsTableBody">
                            @forelse($claims ?? [] as $index => $claim)
                            @php
                                $voucherExpired = $claim->voucher && \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($claim->voucher->expiry_date));
                                $isUsed = $claim->is_used || $claim->scanned_at;
                            @endphp
                            <tr class="claim-row hover:bg-gray-50 transition duration-150 {{ $voucherExpired && !$isUsed ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 rounded-full p-2 mr-3">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900">{{ $claim->user_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $claim->user_domisili ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $claim->user_phone) }}" target="_blank"
                                       class="flex items-center text-sm text-green-600 hover:text-green-800 font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                        {{ $claim->user_phone }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-900">{{ $claim->voucher->name ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 rounded-lg font-mono text-xs font-medium text-gray-700 border border-gray-300">
                                        {{ $claim->unique_code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $claim->created_at->format('d M Y') }}
                                    <div class="text-xs text-gray-400">{{ $claim->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($claim->voucher)
                                        @php
                                            $expiryDate = \Carbon\Carbon::parse($claim->voucher->expiry_date);
                                            $voucherExpired = \Carbon\Carbon::now()->startOfDay()->greaterThan($expiryDate);
                                        @endphp
                                        <span class="{{ $voucherExpired ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                            {{ $expiryDate->format('d M Y') }}
                                        </span>
                                        @if($voucherExpired)
                                            <div class="text-xs text-red-500 mt-1">Sudah Lewat</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($isUsed)
                                        <div class="flex flex-col items-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800 border border-gray-300">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Terpakai
                                            </span>
                                            @if($claim->scanned_at)
                                                <span class="text-xs text-gray-500 mt-1">
                                                    {{ $claim->scanned_at->format('d M Y H:i') }}
                                                </span>
                                            @endif
                                        </div>
                                    @elseif($voucherExpired)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Kadaluarsa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Belum Terpakai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <p class="text-gray-500 font-medium">Belum ada user yang klaim voucher</p>
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

<!-- Modal Create Voucher -->
<div id="createVoucherModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 rounded-t-2xl flex justify-between items-center z-10">
            <h3 class="text-xl font-bold flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Voucher Baru
            </h3>
            <button type="button" onclick="closeCreateModal()" class="text-white hover:text-gray-200 transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.voucher.store') }}" method="POST" enctype="multipart/form-data" class="p-6" id="createForm">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="create_name" class="block text-sm font-bold text-gray-700 mb-2">
                        Nama Voucher <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="create_name" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="Contoh: Diskon 50% Hari Kemerdekaan" required>
                </div>

                <div>
                    <label for="create_deskripsi" class="block text-sm font-bold text-gray-700 mb-2">
                        Deskripsi Voucher <span class="text-red-500">*</span>
                    </label>
                    <textarea id="create_deskripsi" name="deskripsi" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Deskripsi detail tentang voucher" required>{{ old('deskripsi') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="create_status" class="block text-sm font-bold text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="create_status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Pilih Status</option>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">💡 Status otomatis berubah saat expired/habis</p>
                    </div>

                    <div>
                        <label for="create_expiry_date" class="block text-sm font-bold text-gray-700 mb-2">
                            Tanggal Kadaluarsa <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="create_expiry_date" name="expiry_date" value="{{ old('expiry_date') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">
                        Tipe Kuota <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="quota_type" value="unlimited" class="mr-3" required>
                            <div>
                                <span class="font-semibold text-gray-900">Unlimited</span>
                                <p class="text-xs text-gray-500">Tanpa batas</p>
                            </div>
                        </label>
                        <label class="relative flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="quota_type" value="limited" class="mr-3" required>
                            <div>
                                <span class="font-semibold text-gray-900">Terbatas</span>
                                <p class="text-xs text-gray-500">Dengan kuota</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="quotaInputContainer" class="hidden">
                    <label for="create_quota" class="block text-sm font-bold text-gray-700 mb-2">
                        Jumlah Kuota <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="create_quota" name="quota" min="1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        placeholder="Contoh: 50">
                </div>

                <div>
                    <label for="create_image" class="block text-sm font-bold text-gray-700 mb-2">
                        Gambar Display <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input id="create_image" name="image" type="file" accept="image/*" class="hidden" onchange="previewCreateImage(event)" required>
                        <label for="create_image" class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-sm text-gray-600 font-medium">Upload Gambar Display</p>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG (Max 10MB)</p>
                        </label>
                    </div>
                    <div id="createImagePreview" class="mt-3 hidden">
                        <img id="createPreview" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border-2 border-blue-300">
                    </div>
                </div>

                <div>
                    <label for="create_download_image" class="block text-sm font-bold text-gray-700 mb-2">
                        Gambar Download <span class="text-gray-500">(Opsional)</span>
                    </label>
                    <p class="text-xs text-gray-600 mb-2">Gambar khusus untuk download dengan barcode overlay</p>
                    <div class="relative">
                        <input id="create_download_image" name="download_image" type="file" accept="image/*" class="hidden" onchange="previewCreateDownloadImage(event)">
                        <label for="create_download_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-blue-300 border-dashed rounded-xl cursor-pointer bg-blue-50 hover:bg-blue-100 transition">
                            <svg class="w-10 h-10 text-blue-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-sm text-blue-600 font-medium">Upload Gambar Download</p>
                        </label>
                    </div>
                    <div id="createDownloadImagePreview" class="mt-3 hidden">
                        <img id="createDownloadPreview" src="" alt="Download Preview" class="w-full h-48 object-cover rounded-lg border-2 border-blue-400">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                <button type="button" onclick="closeCreateModal()" 
                        class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-lg">
                    Simpan Voucher
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Voucher -->
<div id="editVoucherModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 rounded-t-2xl flex justify-between items-center z-10">
            <h3 class="text-xl font-bold flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Voucher
            </h3>
            <button type="button" onclick="closeEditModal()" class="text-white hover:text-gray-200 transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label for="edit_name" class="block text-sm font-bold text-gray-700 mb-2">Nama Voucher</label>
                    <input type="text" id="edit_name" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="edit_deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="edit_deskripsi" name="deskripsi" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_status" class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                        <select id="edit_status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="aktif">Aktif</option>
                            <option value="tidak_aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_expiry_date" class="block text-sm font-bold text-gray-700 mb-2">Expired</label>
                        <input type="date" id="edit_expiry_date" name="expiry_date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">Tipe Kuota</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="quota_type" value="unlimited" class="mr-3" required>
                            <div>
                                <span class="font-semibold">Unlimited</span>
                                <p class="text-xs text-gray-500">Tanpa batas</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="quota_type" value="limited" class="mr-3" required>
                            <div>
                                <span class="font-semibold">Terbatas</span>
                                <p class="text-xs text-gray-500">Dengan kuota</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="editQuotaInputContainer" class="hidden">
                    <label for="edit_quota" class="block text-sm font-bold text-gray-700 mb-2">Jumlah Kuota</label>
                    <input type="number" id="edit_quota" name="quota" min="1" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Gambar Display Saat Ini</label>
                    <img id="currentImage" src="" alt="Current" class="w-full h-48 object-cover rounded-lg border">
                    <input id="edit_image" name="image" type="file" accept="image/*" class="mt-3 w-full px-4 py-2 border border-gray-300 rounded-lg" onchange="previewEditImage(event)">
                    <div id="editImagePreview" class="mt-3 hidden">
                        <img id="editPreview" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border-2 border-blue-300">
                    </div>
                </div>

                <div id="currentDownloadImageContainer">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Gambar Download Saat Ini</label>
                    <img id="currentDownloadImage" src="" alt="Current Download" class="w-full h-48 object-cover rounded-lg border">
                    <input id="edit_download_image" name="download_image" type="file" accept="image/*" class="mt-3 w-full px-4 py-2 border border-gray-300 rounded-lg" onchange="previewEditDownloadImage(event)">
                    <div id="editDownloadImagePreview" class="mt-3 hidden">
                        <img id="editDownloadPreview" src="" alt="Download Preview" class="w-full h-48 object-cover rounded-lg border-2 border-blue-400">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
                <button type="button" onclick="closeEditModal()" class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 transition shadow-lg">
                    Update Voucher
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Description -->
<div id="descriptionModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 rounded-t-2xl flex justify-between items-center">
            <h3 id="descriptionTitle" class="text-xl font-bold">Deskripsi Voucher</h3>
            <button type="button" onclick="closeDescriptionModal()" class="text-white hover:text-gray-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p id="descriptionContent" class="text-gray-700 whitespace-pre-wrap leading-relaxed"></p>
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="closeDescriptionModal()" class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md">
        <div class="p-6">
            <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-100 rounded-full">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 text-center mt-4">Hapus Voucher</h3>
            <p class="text-gray-600 text-center mt-2">
                Yakin ingin menghapus voucher "<span id="deleteVoucherName" class="font-semibold text-gray-900"></span>"?
            </p>
            <p class="text-sm text-red-600 text-center mt-1">Tindakan ini tidak dapat dibatalkan!</p>
            
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Image Preview -->
<div id="imageModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-95 overflow-y-auto h-full w-full z-50" onclick="closeImageModal()">
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative max-w-5xl w-full" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-4">
                <h3 id="imageModalTitle" class="text-2xl font-bold text-white">Preview Gambar</h3>
                <button onclick="closeImageModal()" class="text-white hover:text-gray-300 transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <img id="imageModalContent" src="" alt="Preview" class="w-full h-auto rounded-xl shadow-2xl">
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>

<script>
// Tab Switching
function switchTab(tab) {
    const vouchersTab = document.getElementById('tabVouchers');
    const claimsTab = document.getElementById('tabClaims');
    const vouchersContent = document.getElementById('vouchersContent');
    const claimsContent = document.getElementById('claimsContent');

    if (tab === 'vouchers') {
        vouchersTab.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
        vouchersTab.classList.remove('border-transparent', 'text-gray-600');
        claimsTab.classList.remove('border-blue-500', 'text-blue-600', 'bg-blue-50');
        claimsTab.classList.add('border-transparent', 'text-gray-600');
        vouchersContent.classList.remove('hidden');
        claimsContent.classList.add('hidden');
    } else {
        claimsTab.classList.add('border-blue-500', 'text-blue-600', 'bg-blue-50');
        claimsTab.classList.remove('border-transparent', 'text-gray-600');
        vouchersTab.classList.remove('border-blue-500', 'text-blue-600', 'bg-blue-50');
        vouchersTab.classList.add('border-transparent', 'text-gray-600');
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

// Quota Toggle
function toggleQuotaInput() {
    const quotaType = document.querySelector('#createVoucherModal input[name="quota_type"]:checked');
    const container = document.getElementById('quotaInputContainer');
    const input = document.getElementById('create_quota');
    
    if (quotaType && quotaType.value === 'limited') {
        container.classList.remove('hidden');
        input.required = true;
    } else {
        container.classList.add('hidden');
        input.required = false;
    }
}

function toggleEditQuotaInput() {
    const quotaType = document.querySelector('#editVoucherModal input[name="quota_type"]:checked');
    const container = document.getElementById('editQuotaInputContainer');
    const input = document.getElementById('edit_quota');
    
    if (quotaType && quotaType.value === 'limited') {
        container.classList.remove('hidden');
        input.required = true;
    } else {
        container.classList.add('hidden');
        input.required = false;
    }
}

// Modal Functions
function openCreateModal() {
    document.getElementById('createVoucherModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createVoucherModal').classList.add('hidden');
    document.getElementById('createForm').reset();
    document.getElementById('createImagePreview').classList.add('hidden');
    document.getElementById('createDownloadImagePreview').classList.add('hidden');
}

function openEditModal(voucher) {
    document.getElementById('editVoucherModal').classList.remove('hidden');
    document.getElementById('edit_name').value = voucher.name;
    document.getElementById('edit_deskripsi').value = voucher.deskripsi;
    document.getElementById('edit_status').value = voucher.status;
    document.getElementById('edit_expiry_date').value = voucher.expiry_date;
    
    const quotaType = voucher.is_unlimited ? 'unlimited' : 'limited';
    document.querySelector(`#editVoucherModal input[name="quota_type"][value="${quotaType}"]`).checked = true;
    
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
    toggleEditQuotaInput();
}

function closeEditModal() {
    document.getElementById('editVoucherModal').classList.add('hidden');
}

function openDescriptionModal(name, deskripsi) {
    document.getElementById('descriptionModal').classList.remove('hidden');
    document.getElementById('descriptionTitle').textContent = `Deskripsi: ${name}`;
    document.getElementById('descriptionContent').textContent = deskripsi;
}

function closeDescriptionModal() {
    document.getElementById('descriptionModal').classList.add('hidden');
}

function confirmDelete(id, name) {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteVoucherName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/voucher/${id}`;
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function showImageModal(url, title) {
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModalTitle').textContent = title;
    document.getElementById('imageModalContent').src = url;
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Image Previews
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

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Quota listeners
    document.querySelectorAll('#createVoucherModal input[name="quota_type"]').forEach(radio => {
        radio.addEventListener('change', toggleQuotaInput);
    });
    
    document.querySelectorAll('#editVoucherModal input[name="quota_type"]').forEach(radio => {
        radio.addEventListener('change', toggleEditQuotaInput);
    });
    
    @if($errors->any())
        openCreateModal();
    @endif
    
    // ESC to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
            closeDescriptionModal();
            closeDeleteModal();
            closeImageModal();
        }
    });
});
</script>
@endsection