@extends('layouts.app')

@section('title', 'Management Voucher')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Modern Header with Gradient -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        Voucher Management
                    </h1>
                    <p class="text-slate-600 mt-2">Kelola dan monitor voucher Anda dengan mudah</p>
                </div>
                <div class="hidden md:flex items-center gap-4">
                    <div class="bg-white rounded-xl px-6 py-3 shadow-sm border border-slate-200">
                        <div class="text-xs text-slate-500">Total Vouchers</div>
                        <div class="text-2xl font-bold text-slate-900">{{ isset($vouchers) ? $vouchers->count() : 0 }}</div>
                    </div>
                    <div class="bg-white rounded-xl px-6 py-3 shadow-sm border border-slate-200">
                        <div class="text-xs text-slate-500">Total Claims</div>
                        <div class="text-2xl font-bold text-slate-900">{{ isset($claims) ? $claims->count() : 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-6 py-4 rounded-xl mb-6 shadow-lg">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-gradient-to-r from-red-500 to-rose-500 text-white px-6 py-4 rounded-xl mb-6 shadow-lg">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-white border-l-4 border-red-500 px-6 py-4 rounded-xl mb-6 shadow-lg">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <strong class="text-red-700 font-semibold">Ada beberapa masalah:</strong>
                    <ul class="mt-2 space-y-1 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Card with Modern Design -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <!-- Stylish Tabs -->
            <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-6 py-4">
                <div class="flex gap-2">
                    <button onclick="switchTab('vouchers')" id="tabVouchers" 
                            class="px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-300 bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/30">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span>Vouchers</span>
                            <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs">{{ isset($vouchers) ? $vouchers->count() : 0 }}</span>
                        </div>
                    </button>
                    <button onclick="switchTab('claims')" id="tabClaims" 
                            class="px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-300 text-slate-600 hover:bg-white hover:text-slate-900">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span>Claims</span>
                            <span class="bg-slate-200 px-2 py-0.5 rounded-full text-xs">{{ isset($claims) ? $claims->count() : 0 }}</span>
                        </div>
                    </button>
                </div>
            </div>

            <div class="p-8">
                <!-- Vouchers Tab -->
                <div id="vouchersContent">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Daftar Voucher</h2>
                            <p class="text-slate-600 mt-1">Kelola semua voucher aktif Anda</p>
                        </div>
                        <button onclick="openCreateModal()" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/30 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/40 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Voucher
                        </button>
                    </div>

                    <!-- Modern Grid Layout -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($vouchers ?? [] as $voucher)
                        @php
                            $isExpired = \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($voucher->expiry_date));
                            $currentStatus = $isExpired ? 'kadaluarsa' : $voucher->status;
                            $effectiveStatus = $currentStatus;
                            if (!$voucher->is_unlimited && $voucher->remaining_quota <= 0) {
                                $effectiveStatus = 'habis';
                            }
                        @endphp
                        <div class="group bg-gradient-to-br from-white to-slate-50 rounded-2xl shadow-lg border border-slate-200 overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                            <!-- Image -->
                            <div class="relative h-48 overflow-hidden cursor-pointer" onclick="showImageModal('{{ $voucher->image_url }}', '{{ $voucher->name }}')">
                                <img src="{{ $voucher->image_url }}" alt="{{ $voucher->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
                                     onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                
                                <!-- Status Badge -->
                                <div class="absolute top-4 right-4">
                                    @if($effectiveStatus === 'aktif')
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-green-500 text-white shadow-lg backdrop-blur-sm">
                                            ✓ Aktif
                                        </span>
                                    @elseif($effectiveStatus === 'tidak_aktif')
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-slate-500 text-white shadow-lg backdrop-blur-sm">
                                            Tidak Aktif
                                        </span>
                                    @elseif($effectiveStatus === 'habis')
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-orange-500 text-white shadow-lg backdrop-blur-sm">
                                            Habis
                                        </span>
                                    @else
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-red-500 text-white shadow-lg backdrop-blur-sm">
                                            Kadaluarsa
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-slate-900 mb-2 line-clamp-1">{{ $voucher->name }}</h3>
                                
                                <div class="flex items-center gap-4 text-sm text-slate-600 mb-4">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="{{ $isExpired ? 'text-red-600 font-semibold' : '' }}">
                                            {{ \Carbon\Carbon::parse($voucher->expiry_date)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span class="font-semibold text-blue-600">{{ $voucher->claims_count ?? 0 }}</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2 pt-4 border-t border-slate-200">
                                    <button onclick="openDescriptionModal('{{ addslashes($voucher->name) }}', '{{ addslashes($voucher->deskripsi) }}')" 
                                            class="flex-1 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Lihat
                                    </button>
                                    <button onclick='openEditModal(@json($voucher))' 
                                            class="flex-1 px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button onclick="confirmDelete({{ $voucher->id }}, '{{ addslashes($voucher->name) }}')" 
                                            class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-sm font-medium transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full py-20 text-center">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 mb-6">
                                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900 mb-2">Belum Ada Voucher</h3>
                            <p class="text-slate-600 mb-6">Mulai dengan membuat voucher pertama Anda</p>
                            <button onclick="openCreateModal()" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/30 transition-all duration-300">
                                + Buat Voucher Pertama
                            </button>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Claims Tab -->
                <div id="claimsContent" class="hidden">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Data Klaim User</h2>
                            <p class="text-slate-600 mt-1">Monitor semua aktivitas klaim voucher</p>
                        </div>
                        <div class="relative w-full sm:w-auto">
                            <input type="text" id="searchClaim" placeholder="Cari nama, nomor, atau voucher..." 
                                   class="w-full sm:w-80 px-4 py-3 pl-11 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   onkeyup="searchClaims()">
                            <svg class="absolute left-4 top-3.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Claims Table -->
                    <div class="overflow-hidden rounded-xl border border-slate-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-gradient-to-r from-slate-50 to-slate-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Voucher</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Kode Unik</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Tanggal Klaim</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100" id="claimsTableBody">
                                    @forelse($claims ?? [] as $claim)
                                    @php
                                        $voucherExpired = $claim->voucher && \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($claim->voucher->expiry_date));
                                        $isUsed = $claim->is_used || $claim->scanned_at;
                                    @endphp
                                    <tr class="claim-row hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                                    {{ strtoupper(substr($claim->user_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold text-slate-900">{{ $claim->user_name }}</div>
                                                    <div class="text-xs text-slate-500">{{ $claim->user_phone }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-slate-900">{{ $claim->voucher->name ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1.5 bg-slate-100 rounded-lg font-mono text-xs font-bold text-slate-700">{{ $claim->unique_code }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-slate-900">{{ $claim->created_at->format('d M Y') }}</div>
                                            <div class="text-xs text-slate-500">{{ $claim->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($isUsed)
                                                <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-full bg-slate-100 text-slate-700">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Terpakai
                                                </span>
                                            @elseif($voucherExpired)
                                                <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-full bg-red-100 text-red-700">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Kadaluarsa
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold rounded-full bg-green-100 text-green-700">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Aktif
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-20 text-center">
                                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-slate-900 mb-2">Belum Ada Klaim</h3>
                                            <p class="text-slate-600">Data klaim voucher akan muncul di sini</p>
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
<div id="createVoucherModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto z-50 p-4">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden transform transition-all">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">✨ Tambah Voucher Baru</h3>
                <button onclick="closeCreateModal()" class="text-white/80 hover:text-white hover:bg-white/20 rounded-lg p-2 transition-all">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.voucher.store') }}" method="POST" enctype="multipart/form-data" class="overflow-y-auto max-h-[calc(90vh-100px)]" id="createForm">
                @csrf
                
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Voucher *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                               placeholder="Contoh: Diskon 50% Hari Kemerdekaan" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Voucher *</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                            placeholder="Deskripsi detail tentang voucher" required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Status *</label>
                            <select name="status" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Kadaluarsa *</label>
                            <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" required>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-5 rounded-xl border border-blue-200">
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Tipe Kuota *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex items-center justify-center p-4 border-2 border-blue-200 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-white transition-all group">
                                <input type="radio" name="quota_type" value="unlimited" class="sr-only" required>
                                <div class="text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-blue-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                    </svg>
                                    <span class="font-semibold text-slate-700">Unlimited</span>
                                </div>
                            </label>
                            <label class="relative flex items-center justify-center p-4 border-2 border-blue-200 rounded-xl cursor-pointer hover:border-blue-500 hover:bg-white transition-all group">
                                <input type="radio" name="quota_type" value="limited" class="sr-only" required>
                                <div class="text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-blue-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-semibold text-slate-700">Terbatas</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="quotaInputContainer" class="hidden">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Kuota *</label>
                        <input type="number" id="create_quota" name="quota" min="1"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
                            placeholder="Contoh: 50">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Voucher (Display) *</label>
                        <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 hover:border-blue-500 transition-all">
                            <input id="create_image" name="image" type="file" accept="image/*" class="hidden" onchange="previewCreateImage(event)" required>
                            <label for="create_image" class="cursor-pointer block text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm text-slate-600 mb-1"><span class="font-semibold text-blue-600">Klik untuk upload</span> atau drag & drop</p>
                                <p class="text-xs text-slate-500">PNG, JPG, JPEG (MAX. 10MB)</p>
                            </label>
                        </div>
                        <div id="createImagePreview" class="mt-3 hidden">
                            <img id="createPreview" src="" alt="Preview" class="w-full h-48 object-cover rounded-xl border-2 border-blue-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Download (Optional)</label>
                        <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 hover:border-indigo-500 transition-all">
                            <input id="create_download_image" name="download_image" type="file" accept="image/*" class="hidden" onchange="previewCreateDownloadImage(event)">
                            <label for="create_download_image" class="cursor-pointer block text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-slate-600 mb-1">Upload gambar khusus untuk download</p>
                                <p class="text-xs text-slate-500">Akan digunakan sebagai background dengan barcode overlay</p>
                            </label>
                        </div>
                        <div id="createDownloadImagePreview" class="mt-3 hidden">
                            <img id="createDownloadPreview" src="" alt="Download Preview" class="w-full h-48 object-cover rounded-xl border-2 border-indigo-200">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t border-slate-200">
                    <button type="button" onclick="closeCreateModal()" class="px-6 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl font-semibold hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 transition-all">
                        Simpan Voucher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Voucher -->
<div id="editVoucherModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto z-50 p-4">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden transform transition-all">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">✏️ Edit Voucher</h3>
                <button onclick="closeEditModal()" class="text-white/80 hover:text-white hover:bg-white/20 rounded-lg p-2 transition-all">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data" class="overflow-y-auto max-h-[calc(90vh-100px)]">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Voucher *</label>
                        <input type="text" id="edit_name" name="name" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Voucher *</label>
                        <textarea id="edit_deskripsi" name="deskripsi" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" required></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Status *</label>
                            <select id="edit_status" name="status" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" required>
                                <option value="aktif">Aktif</option>
                                <option value="tidak_aktif">Tidak Aktif</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                                <option value="habis">Habis</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Kadaluarsa *</label>
                            <input type="date" id="edit_expiry_date" name="expiry_date" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" required>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-5 rounded-xl border border-indigo-200">
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Tipe Kuota *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex items-center justify-center p-4 border-2 border-indigo-200 rounded-xl cursor-pointer hover:border-indigo-500 hover:bg-white transition-all">
                                <input type="radio" name="quota_type" value="unlimited" class="sr-only" required>
                                <span class="font-semibold text-slate-700">Unlimited</span>
                            </label>
                            <label class="relative flex items-center justify-center p-4 border-2 border-indigo-200 rounded-xl cursor-pointer hover:border-indigo-500 hover:bg-white transition-all">
                                <input type="radio" name="quota_type" value="limited" class="sr-only" required>
                                <span class="font-semibold text-slate-700">Terbatas</span>
                            </label>
                        </div>
                    </div>

                    <div id="editQuotaInputContainer" class="hidden">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Kuota *</label>
                        <input type="number" id="edit_quota" name="quota" min="1" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Display</label>
                        <div id="currentImageContainer" class="mb-3">
                            <p class="text-xs text-slate-600 mb-2">Gambar saat ini:</p>
                            <img id="currentImage" src="" alt="Current" class="w-full h-40 object-cover rounded-xl border-2 border-slate-200">
                        </div>
                        <input id="edit_image" name="image" type="file" accept="image/*" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" onchange="previewEditImage(event)">
                        <p class="text-xs text-slate-500 mt-2">Kosongkan jika tidak ingin mengubah</p>
                        <div id="editImagePreview" class="mt-3 hidden">
                            <img id="editPreview" src="" alt="Preview" class="w-full h-40 object-cover rounded-xl border-2 border-indigo-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Gambar Download</label>
                        <div id="currentDownloadImageContainer" class="mb-3 hidden">
                            <p class="text-xs text-slate-600 mb-2">Gambar download saat ini:</p>
                            <img id="currentDownloadImage" src="" alt="Current Download" class="w-full h-40 object-cover rounded-xl border-2 border-slate-200">
                        </div>
                        <input id="edit_download_image" name="download_image" type="file" accept="image/*" class="w-full px-4 py-3 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" onchange="previewEditDownloadImage(event)">
                        <p class="text-xs text-slate-500 mt-2">Kosongkan jika tidak ingin mengubah</p>
                        <div id="editDownloadImagePreview" class="mt-3 hidden">
                            <img id="editDownloadPreview" src="" alt="Download Preview" class="w-full h-40 object-cover rounded-xl border-2 border-indigo-200">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t border-slate-200">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl font-semibold hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-indigo-500/30 transition-all">
                        Update Voucher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Deskripsi -->
<div id="descriptionModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto z-50 p-4">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all">
            <div class="bg-gradient-to-r from-slate-700 to-slate-900 px-6 py-5 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white" id="descriptionTitle">📄 Deskripsi Voucher</h3>
                <button onclick="closeDescriptionModal()" class="text-white/80 hover:text-white hover:bg-white/20 rounded-lg p-2 transition-all">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="bg-slate-50 rounded-xl p-5 border border-slate-200">
                    <p id="descriptionContent" class="text-slate-700 whitespace-pre-wrap leading-relaxed"></p>
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-end">
                <button onclick="closeDescriptionModal()" class="px-6 py-3 bg-gradient-to-r from-slate-700 to-slate-900 text-white rounded-xl font-semibold hover:from-slate-800 hover:to-black transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm overflow-y-auto z-50 p-4">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all">
            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-red-500 to-rose-600 mb-5 shadow-lg shadow-red-500/30">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-3">Hapus Voucher?</h3>
                <p class="text-slate-600 mb-2">Apakah Anda yakin ingin menghapus voucher</p>
                <p class="text-lg font-bold text-slate-900 mb-4">"<span id="deleteVoucherName"></span>"</p>
                <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-6">
                    <p class="text-sm text-red-700 font-medium">⚠️ Tindakan ini tidak dapat dibatalkan</p>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-6 py-3 bg-white border border-slate-300 text-slate-700 rounded-xl font-semibold hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-red-600 to-rose-600 text-white rounded-xl font-semibold hover:from-red-700 hover:to-rose-700 shadow-lg shadow-red-500/30 transition-all">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Image Preview -->
<div id="imageModal" class="hidden fixed inset-0 bg-black/90 z-50 p-4" onclick="closeImageModal()">
    <div class="flex items-center justify-center min-h-screen">
        <div class="relative max-w-5xl w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white" id="imageModalTitle">🖼️ Preview Gambar</h3>
                <button onclick="closeImageModal()" class="text-white/80 hover:text-white hover:bg-white/20 rounded-lg p-2 transition-all">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <img id="imageModalContent" src="" alt="Preview" class="w-full h-auto rounded-2xl shadow-2xl" onclick="event.stopPropagation()">
        </div>
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
        vouchersTab.classList.add('border-blue-600', 'text-blue-600');
        vouchersTab.classList.remove('border-transparent', 'text-gray-500');
        claimsTab.classList.remove('border-blue-600', 'text-blue-600');
        claimsTab.classList.add('border-transparent', 'text-gray-500');
        vouchersContent.classList.remove('hidden');
        claimsContent.classList.add('hidden');
    } else {
        claimsTab.classList.add('border-blue-600', 'text-blue-600');
        claimsTab.classList.remove('border-transparent', 'text-gray-500');
        vouchersTab.classList.remove('border-blue-600', 'text-blue-600');
        vouchersTab.classList.add('border-transparent', 'text-gray-500');
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
    document.getElementById('descriptionTitle').textContent = name;
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