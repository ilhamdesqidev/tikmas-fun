@extends('layouts.app')

@section('title', 'Management Voucher')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="p-3 bg-blue-600 rounded-lg shadow">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Management Voucher</h1>
                    <p class="text-gray-600 text-sm mt-0.5">Kelola voucher dan data klaim pengguna</p>
                </div>
            </div>
        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <!-- Navigation Tabs -->
            <div class="bg-white border-b border-gray-200 p-4">
                <div class="flex space-x-2">
                    <button onclick="switchTab('vouchers')" id="tabVouchers" class="flex-1 px-6 py-3 text-sm font-semibold rounded-lg transition-all duration-200 bg-blue-600 text-white">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Daftar Voucher
                        </div>
                    </button>
                    <button onclick="switchTab('claims')" id="tabClaims" class="flex-1 px-6 py-3 text-sm font-semibold rounded-lg transition-all duration-200 text-gray-700 bg-gray-100 hover:bg-gray-200">
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
                <!-- Messages -->
                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <h2 class="text-xl font-bold text-gray-900">Daftar Voucher</h2>
                            <p class="text-sm text-gray-600 mt-1">Kelola semua voucher Anda</p>
                        </div>
                        <button onclick="openCreateModal()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 font-semibold flex items-center shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Voucher
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Gambar Display</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Gambar Download</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Voucher</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal Kadaluarsa</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Diklaim</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($vouchers ?? [] as $index => $voucher)
                                    @php
                                        $isExpired = \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($voucher->expiry_date));
                                        $currentStatus = $isExpired ? 'kadaluarsa' : $voucher->status;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img src="{{ $voucher->image_url }}" alt="{{ $voucher->name }}" class="h-16 w-16 object-cover rounded-lg cursor-pointer border border-gray-200 hover:border-blue-500 transition-colors" onerror="this.src='https://via.placeholder.com/64?text=No+Image'" onclick="showImageModal('{{ $voucher->image_url }}', 'Gambar Display')">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($voucher->download_image)
                                                <img src="{{ $voucher->download_image_url }}" alt="{{ $voucher->name }} Download" class="h-16 w-16 object-cover rounded-lg cursor-pointer border border-gray-200 hover:border-green-500 transition-colors" onerror="this.src='https://via.placeholder.com/64?text=No+Image'" onclick="showImageModal('{{ $voucher->download_image_url }}', 'Gambar Download')">
                                            @else
                                                <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                    <span class="text-xs text-gray-500 font-medium">Sama</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $voucher->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button onclick="openDescriptionModal('{{ addslashes($voucher->name) }}', '{{ addslashes($voucher->deskripsi) }}')" 
                                                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Lihat
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
                                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    Aktif
                                                </span>
                                            @elseif($effectiveStatus === 'tidak_aktif')
                                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                    Tidak Aktif
                                                </span>
                                            @elseif($effectiveStatus === 'habis')
                                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-orange-100 text-orange-800 border border-orange-200">
                                                    Habis
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                    Kadaluarsa
                                                </span>
                                            @endif
                                            
                                            @if($effectiveStatus !== $voucher->status)
                                                <span class="block text-xs text-orange-600 mt-1 font-medium">
                                                    ⚠️ Auto-{{ $effectiveStatus === 'habis' ? 'sold out' : 'expired' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $expiryDate = \Carbon\Carbon::parse($voucher->expiry_date);
                                                $isExpired = \Carbon\Carbon::now()->startOfDay()->greaterThan($expiryDate);
                                            @endphp
                                            <span class="{{ $isExpired ? 'text-red-600 font-bold' : 'text-gray-600 font-medium' }}">
                                                {{ $expiryDate->format('d M Y') }}
                                            </span>
                                            @if($isExpired)
                                                <span class="block text-xs text-red-500 font-medium">
                                                    (Sudah Lewat)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold border border-blue-200">
                                                {{ $voucher->claims_count ?? 0 }} User
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button onclick='openEditModal(@json($voucher))' 
                                                        class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors font-semibold text-xs">
                                                    Edit
                                                </button>
                                                <button onclick="confirmDelete({{ $voucher->id }}, '{{ addslashes($voucher->name) }}')" 
                                                        class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition-colors font-semibold text-xs">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
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
                            <h2 class="text-xl font-bold text-gray-900">Data User Klaim Voucher</h2>
                            <p class="text-sm text-gray-600 mt-1">Total: <span class="font-bold text-blue-600">{{ isset($claims) ? $claims->count() : 0 }}</span> klaim terdaftar</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="text" id="searchClaim" placeholder="Cari nama atau nomor..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-64" onkeyup="searchClaims()">
                            <button onclick="searchClaims()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Table Claims -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama User</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No. Telepon</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Voucher</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode Unik</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal Klaim</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Expired Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="claimsTableBody">
                                    @forelse($claims ?? [] as $index => $claim)
                                    @php
                                        $voucherExpired = $claim->voucher && \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($claim->voucher->expiry_date));
                                        $isUsed = $claim->is_used || $claim->scanned_at;
                                    @endphp
                                    <tr class="claim-row hover:bg-gray-50 {{ $voucherExpired && !$isUsed ? 'bg-red-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                                    {{ strtoupper(substr($claim->user_name, 0, 1)) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $claim->user_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $claim->user_phone }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $claim->voucher->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 bg-gray-100 rounded font-mono text-xs font-bold text-gray-700 border border-gray-200">{{ $claim->unique_code }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                            {{ $claim->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($claim->voucher)
                                                @php
                                                    $expiryDate = \Carbon\Carbon::parse($claim->voucher->expiry_date);
                                                    $voucherExpired = \Carbon\Carbon::now()->startOfDay()->greaterThan($expiryDate);
                                                @endphp
                                                <span class="{{ $voucherExpired ? 'text-red-600 font-bold' : 'text-gray-600 font-medium' }}">
                                                    {{ $expiryDate->format('d M Y') }}
                                                </span>
                                                @if($voucherExpired)
                                                    <span class="block text-xs text-red-500 font-medium">
                                                        (Sudah Lewat)
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($isUsed)
                                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                    ✓ Terpakai
                                                </span>
                                                @if($claim->scanned_at)
                                                    <span class="block text-xs text-gray-500 mt-1 font-medium">
                                                        {{ $claim->scanned_at->format('d M Y H:i') }}
                                                    </span>
                                                @endif
                                            @elseif($voucherExpired)
                                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                    ⚠ Kadaluarsa
                                                </span>
                                                <span class="block text-xs text-red-500 mt-1 font-medium">
                                                    Voucher expired
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    ✓ Belum Terpakai
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
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

<!-- Modal Create Voucher -->
<div id="createVoucherModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-0 border border-gray-200 w-full max-w-3xl shadow-xl rounded-lg bg-white max-h-[95vh] overflow-hidden my-8">
        <div class="sticky top-0 z-10 bg-blue-600 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Tambah Voucher Baru</h3>
            <button type="button" onclick="closeCreateModal()" class="text-white hover:bg-blue-700 rounded-lg p-2 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.voucher.store') }}" method="POST" enctype="multipart/form-data" class="overflow-y-auto max-h-[calc(95vh-140px)]" id="createForm">
            @csrf
            
            <div class="p-6 space-y-5">
                <div>
                    <label for="create_name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Voucher <span class="text-red-500">*</span></label>
                    <input type="text" id="create_name" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           placeholder="Contoh: Diskon 50% Hari Kemerdekaan" required>
                </div>

                <div>
                    <label for="create_deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Voucher <span class="text-red-500">*</span></label>
                    <textarea id="create_deskripsi" name="deskripsi" rows="4"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Deskripsi detail tentang voucher" required>{{ old('deskripsi') }}</textarea>
                </div>

                <div>
                    <label for="create_status" class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select id="create_status" name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror" required>
                        <option value="">Pilih Status</option>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        <option value="kadaluarsa" {{ old('status') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                        <option value="habis" {{ old('status') == 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                    <p class="mt-2 text-xs text-gray-500">💡 Status akan otomatis berubah jika tanggal sudah lewat atau kuota habis</p>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Kuota <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-colors">
                            <input type="radio" name="quota_type" value="unlimited" class="text-blue-600 focus:ring-blue-500" required>
                            <span class="ml-2 text-sm font-medium text-gray-700">Unlimited</span>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-colors">
                            <input type="radio" name="quota_type" value="limited" class="text-blue-600 focus:ring-blue-500" required>
                            <span class="ml-2 text-sm font-medium text-gray-700">Terbatas</span>
                        </label>
                    </div>
                </div>

                <div id="quotaInputContainer" class="hidden">
                    <label for="create_quota" class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Kuota <span class="text-red-500">*</span></label>
                    <input type="number" id="create_quota" name="quota" min="1"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        placeholder="Contoh: 50">
                    <p class="mt-2 text-xs text-gray-500">💡 Masukkan jumlah voucher yang tersedia</p>
                </div>

                <div>
                    <label for="create_expiry_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Kadaluarsa <span class="text-red-500">*</span></label>
                    <input type="date" id="create_expiry_date" name="expiry_date" value="{{ old('expiry_date') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    <p class="mt-2 text-xs text-gray-500">⏰ Voucher akan otomatis kadaluarsa setelah tanggal ini</p>
                </div>

                <div>
                    <label for="create_image" class="block text-sm font-semibold text-gray-700 mb-2">
                        Gambar Voucher (Display) <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-3">📱 Gambar ini akan ditampilkan di halaman daftar voucher</p>
                    <div class="flex items-center justify-center w-full">
                        <label for="create_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 hover:border-blue-500 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-600"><span class="font-semibold">Klik untuk upload</span></p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 10MB)</p>
                            </div>
                            <input id="create_image" name="image" type="file" accept="image/*" class="hidden" onchange="previewCreateImage(event)" required>
                        </label>
                    </div>
                    <div id="createImagePreview" class="mt-3 hidden">
                        <img id="createPreview" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border border-gray-300">
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <label for="create_download_image" class="block text-sm font-semibold text-gray-700 mb-2">
                        🎁 Gambar Voucher (Download) <span class="text-gray-500 font-normal">(Opsional)</span>
                    </label>
                    <p class="text-xs text-blue-700 mb-3">
                        📸 Gambar ini akan digunakan sebagai background saat user download voucher (dengan barcode overlay). Jika tidak diisi, akan menggunakan gambar display.
                    </p>
                    <div class="flex items-center justify-center w-full">
                        <label for="create_download_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-blue-300 rounded-lg cursor-pointer bg-white hover:bg-blue-50 hover:border-blue-500 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mb-2 text-sm text-blue-700"><span class="font-semibold">Upload gambar khusus</span></p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 10MB)</p>
                            </div>
                            <input id="create_download_image" name="download_image" type="file" accept="image/*" class="hidden" onchange="previewCreateDownloadImage(event)">
                        </label>
                    </div>
                    <div id="createDownloadImagePreview" class="mt-3 hidden">
                        <p class="text-xs text-gray-600 mb-2">Preview gambar download:</p>
                        <img id="createDownloadPreview" src="" alt="Download Preview" class="w-full h-48 object-cover rounded-lg border border-blue-300">
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeCreateModal()" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    Simpan Voucher
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Voucher -->
<div id="editVoucherModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-0 border border-gray-200 w-full max-w-3xl shadow-xl rounded-lg bg-white max-h-[95vh] overflow-hidden my-8">
        <div class="sticky top-0 z-10 bg-indigo-600 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white">Edit Voucher</h3>
            <button type="button" onclick="closeEditModal()" class="text-white hover:bg-indigo-700 rounded-lg p-2 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="overflow-y-auto max-h-[calc(95vh-140px)]">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-5">
                <div>
                    <label for="edit_name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Voucher <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_name" name="name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                </div>

                <div>
                    <label for="edit_deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Voucher <span class="text-red-500">*</span></label>
                    <textarea id="edit_deskripsi" name="deskripsi" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required></textarea>
                </div>

                <div>
                    <label for="edit_status" class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select id="edit_status" name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                        <option value="aktif">Aktif</option>
                        <option value="tidak_aktif">Tidak Aktif</option>
                        <option value="kadaluarsa">Kadaluarsa</option>
                        <option value="habis">Habis</option>
                    </select>
                    <p class="mt-2 text-xs text-gray-500">💡 Status akan otomatis berubah jika tanggal sudah lewat atau kuota habis</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Kuota <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                            <input type="radio" name="quota_type" value="unlimited" class="text-indigo-600 focus:ring-indigo-500" required>
                            <span class="ml-2 text-sm font-medium text-gray-700">Unlimited</span>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition-colors">
                            <input type="radio" name="quota_type" value="limited" class="text-indigo-600 focus:ring-indigo-500" required>
                            <span class="ml-2 text-sm font-medium text-gray-700">Terbatas</span>
                        </label>
                    </div>
                </div>

                <div id="editQuotaInputContainer" class="hidden">
                    <label for="edit_quota" class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Kuota <span class="text-red-500">*</span></label>
                    <input type="number" id="edit_quota" name="quota" min="1"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                           placeholder="Contoh: 50">
                    <p class="mt-2 text-xs text-gray-500">💡 Masukkan jumlah voucher yang tersedia</p>
                </div>

                <div>
                    <label for="edit_expiry_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Kadaluarsa <span class="text-red-500">*</span></label>
                    <input type="date" id="edit_expiry_date" name="expiry_date" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                    <p class="mt-2 text-xs text-gray-500">⏰ Voucher akan otomatis kadaluarsa setelah tanggal ini</p>
                </div>

                <div>
                    <label for="edit_image" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Voucher (Display)</label>
                    <p class="text-xs text-gray-500 mb-3">Kosongkan jika tidak ingin mengubah gambar display</p>
                    
                    <div id="currentImageContainer" class="mb-3">
                        <p class="text-sm text-gray-600 mb-2">Gambar display saat ini:</p>
                        <img id="currentImage" src="" alt="Current" class="w-full h-48 object-cover rounded-lg border border-gray-300">
                    </div>
                    
                    <div class="flex items-center justify-center w-full">
                        <label for="edit_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 hover:border-indigo-500 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-600"><span class="font-semibold">Klik untuk upload</span> gambar baru</p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 10MB)</p>
                            </div>
                            <input id="edit_image" name="image" type="file" accept="image/*" class="hidden" onchange="previewEditImage(event)">
                        </label>
                    </div>
                    
                    <div id="editImagePreview" class="mt-3 hidden">
                        <p class="text-sm text-gray-600 mb-2">Preview gambar display baru:</p>
                        <img id="editPreview" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg border border-indigo-300">
                    </div>
                </div>

                <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                    <label for="edit_download_image" class="block text-sm font-semibold text-gray-700 mb-2">
                        🎁 Gambar Voucher (Download) <span class="text-gray-500 font-normal">(Opsional)</span>
                    </label>
                    <p class="text-xs text-indigo-700 mb-3">
                        📸 Gambar untuk background download dengan barcode. Kosongkan jika tidak ingin mengubah.
                    </p>
                    
                    <div id="currentDownloadImageContainer" class="mb-3">
                        <p class="text-sm text-gray-600 mb-2">Gambar download saat ini:</p>
                        <img id="currentDownloadImage" src="" alt="Current Download" class="w-full h-48 object-cover rounded-lg border border-indigo-300">
                    </div>
                    
                    <div class="flex items-center justify-center w-full">
                        <label for="edit_download_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-indigo-300 rounded-lg cursor-pointer bg-white hover:bg-indigo-50 hover:border-indigo-500 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mb-2 text-sm text-indigo-700"><span class="font-semibold">Upload gambar download baru</span></p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 10MB)</p>
                            </div>
                            <input id="edit_download_image" name="download_image" type="file" accept="image/*" class="hidden" onchange="previewEditDownloadImage(event)">
                        </label>
                    </div>
                    
                    <div id="editDownloadImagePreview" class="mt-3 hidden">
                        <p class="text-sm text-gray-600 mb-2">Preview gambar download baru:</p>
                        <img id="editDownloadPreview" src="" alt="Download Preview" class="w-full h-48 object-cover rounded-lg border border-indigo-400">
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-semibold">
                    Update Voucher
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Deskripsi -->
<div id="descriptionModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-0 border border-gray-200 w-full max-w-3xl shadow-xl rounded-lg bg-white overflow-hidden my-8">
        <div class="bg-gray-800 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white" id="descriptionTitle">Deskripsi Voucher</h3>
            <button type="button" onclick="closeDescriptionModal()" class="text-white hover:bg-gray-700 rounded-lg p-2 transition-colors">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p id="descriptionContent" class="text-gray-700 whitespace-pre-wrap leading-relaxed"></p>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
            <button type="button" onclick="closeDescriptionModal()" class="px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition-colors font-semibold">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Delete Confirmation -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-32 mx-auto p-0 border border-gray-200 w-full max-w-md shadow-xl rounded-lg bg-white overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col items-center text-center">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4">
                    <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Voucher</h3>
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
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-semibold">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Image Preview -->
<div id="imageModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-95 overflow-y-auto h-full w-full z-50" onclick="closeImageModal()">
    <div class="relative top-10 mx-auto p-5 w-full max-w-5xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-white" id="imageModalTitle">Preview Gambar</h3>
            <button type="button" onclick="closeImageModal()" class="text-white hover:bg-gray-800 rounded-lg p-2 transition-colors">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <img id="imageModalContent" src="" alt="Preview" class="w-full h-auto rounded-lg shadow-2xl border-2 border-white" onclick="event.stopPropagation()">
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
        vouchersTab.classList.add('bg-blue-600', 'text-white');
        vouchersTab.classList.remove('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
        claimsTab.classList.remove('bg-blue-600', 'text-white');
        claimsTab.classList.add('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
        vouchersContent.classList.remove('hidden');
        claimsContent.classList.add('hidden');
    } else {
        claimsTab.classList.add('bg-blue-600', 'text-white');
        claimsTab.classList.remove('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
        vouchersTab.classList.remove('bg-blue-600', 'text-white');
        vouchersTab.classList.add('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
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