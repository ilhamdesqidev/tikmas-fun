@extends('layouts.app')

@section('title', 'Management Voucher')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Management Voucher</h1>
        <p class="text-gray-600">Kelola voucher dan data klaim pengguna</p>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Navigation Tabs -->
        <div class="flex space-x-1 mb-6 bg-gray-100 p-1 rounded-lg">
            <button onclick="switchTab('vouchers')" id="tabVouchers" class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-md transition-all duration-200 bg-blue-500 text-white shadow-sm">
                <div class="flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Daftar Voucher
                </div>
            </button>
            <button onclick="switchTab('claims')" id="tabClaims" class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-md transition-all duration-200 text-gray-600 hover:bg-gray-200">
                <div class="flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Data User Klaim
                </div>
            </button>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Whoops!</strong>
            <span class="block sm:inline">Ada beberapa masalah dengan input Anda:</span>
            <ul class="mt-2 ml-4 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Vouchers Tab Content -->
        <div id="vouchersContent">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-700">Daftar Voucher</h2>
                <button onclick="openCreateModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    + Tambah Voucher
                </button>
            </div>

            <!-- Table Vouchers -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar Display</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar Download</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Voucher</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Kadaluarsa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diklaim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($vouchers ?? [] as $index => $voucher)
                        @php
                            $isExpired = \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($voucher->expiry_date));
                            $currentStatus = $isExpired ? 'kadaluarsa' : $voucher->status;
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="relative group">
                                    <img src="{{ $voucher->image_url }}" alt="{{ $voucher->name }}" class="h-16 w-16 object-cover rounded cursor-pointer" onerror="this.src='https://via.placeholder.com/64?text=No+Image'" onclick="showImageModal('{{ $voucher->image_url }}', 'Gambar Display')">
                                    <span class="absolute bottom-0 right-0 bg-blue-500 text-white text-xs px-1 rounded">Display</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="relative group">
                                    @if($voucher->download_image)
                                        <img src="{{ $voucher->download_image_url }}" alt="{{ $voucher->name }} Download" class="h-16 w-16 object-cover rounded cursor-pointer" onerror="this.src='https://via.placeholder.com/64?text=No+Image'" onclick="showImageModal('{{ $voucher->download_image_url }}', 'Gambar Download')">
                                        <span class="absolute bottom-0 right-0 bg-green-500 text-white text-xs px-1 rounded">Download</span>
                                    @else
                                        <div class="h-16 w-16 bg-gray-100 rounded flex items-center justify-center">
                                            <span class="text-xs text-gray-400">Sama</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $voucher->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <button onclick="openDescriptionModal('{{ addslashes($voucher->name) }}', '{{ addslashes($voucher->deskripsi) }}')" 
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition duration-200">
                                    Lihat Deskripsi
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
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @elseif($effectiveStatus === 'tidak_aktif')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Tidak Aktif
                                    </span>
                                @elseif($effectiveStatus === 'habis')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Habis
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Kadaluarsa
                                    </span>
                                @endif
                                
                                @if($effectiveStatus !== $voucher->status)
                                    <span class="block text-xs text-orange-600 mt-1">
                                        ⚠️ Auto-{{ $effectiveStatus === 'habis' ? 'sold out' : 'expired' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $expiryDate = \Carbon\Carbon::parse($voucher->expiry_date);
                                    $isExpired = \Carbon\Carbon::now()->startOfDay()->greaterThan($expiryDate);
                                @endphp
                                <span class="{{ $isExpired ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                    {{ $expiryDate->format('d M Y') }}
                                </span>
                                @if($isExpired)
                                    <span class="block text-xs text-red-500">
                                        (Sudah Lewat)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                    {{ $voucher->claims_count ?? 0 }} User
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick='openEditModal(@json($voucher))' 
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                    Edit
                                </button>
                                <button onclick="confirmDelete({{ $voucher->id }}, '{{ addslashes($voucher->name) }}')" 
                                        class="text-red-600 hover:text-red-900">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                Belum ada voucher yang tersedia
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Claims Tab Content -->
        <div id="claimsContent" class="hidden">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-700">Data User Klaim Voucher</h2>
                    <p class="text-sm text-gray-500 mt-1">Total: <span class="font-semibold">{{ isset($claims) ? $claims->count() : 0 }}</span> klaim</p>
                </div>
                <div class="flex space-x-2">
                    <input type="text" id="searchClaim" placeholder="Cari nama, nomor, atau domisili..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" onkeyup="searchClaims()">
                    <button onclick="searchClaims()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Table Claims -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Domisili</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. WhatsApp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voucher</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Unik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Klaim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expired Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="claimsTableBody">
                        @forelse($claims ?? [] as $index => $claim)
                        @php
                            $voucherExpired = $claim->voucher && \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($claim->voucher->expiry_date));
                            $isUsed = $claim->is_used || $claim->scanned_at;
                        @endphp
                        <tr class="claim-row {{ $voucherExpired && !$isUsed ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $claim->user_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $claim->user_domisili ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>{{ $claim->user_phone }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $claim->voucher->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 py-1 bg-gray-100 rounded font-mono text-xs">{{ $claim->unique_code }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $claim->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($claim->voucher)
                                    @php
                                        $expiryDate = \Carbon\Carbon::parse($claim->voucher->expiry_date);
                                        $voucherExpired = \Carbon\Carbon::now()->startOfDay()->greaterThan($expiryDate);
                                    @endphp
                                    <span class="{{ $voucherExpired ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                        {{ $expiryDate->format('d M Y') }}
                                    </span>
                                    @if($voucherExpired)
                                        <span class="block text-xs text-red-500">
                                            (Sudah Lewat)
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($isUsed)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        ✓ Terpakai
                                    </span>
                                    @if($claim->scanned_at)
                                        <span class="block text-xs text-gray-500 mt-1">
                                            {{ $claim->scanned_at->format('d M Y H:i') }}
                                        </span>
                                    @endif
                                @elseif($voucherExpired)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        ⚠ Kadaluarsa
                                    </span>
                                    <span class="block text-xs text-red-500 mt-1">
                                        Voucher expired
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        ✓ Belum Terpakai
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                Belum ada user yang klaim voucher
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Voucher -->
<div id="createVoucherModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-900">Tambah Voucher Baru</h3>
            <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.voucher.store') }}" method="POST" enctype="multipart/form-data" class="mt-4" id="createForm">
            @csrf
            
            <div class="mb-4">
                <label for="create_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Voucher <span class="text-red-500">*</span></label>
                <input type="text" id="create_name" name="name" value="{{ old('name') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       placeholder="Contoh: Diskon 50% Hari Kemerdekaan" required>
            </div>

            <div class="mb-4">
                <label for="create_deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Voucher <span class="text-red-500">*</span></label>
                <textarea id="create_deskripsi" name="deskripsi" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Deskripsi detail tentang voucher" required>{{ old('deskripsi') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="create_status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                <select id="create_status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror" required>
                    <option value="">Pilih Status</option>
                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    <option value="kadaluarsa" {{ old('status') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                    <option value="habis" {{ old('status') == 'habis' ? 'selected' : '' }}>Habis</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">💡 Status akan otomatis berubah jika tanggal sudah lewat atau kuota habis</p>
                @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Kuota <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="quota_type" value="unlimited" class="text-blue-600 focus:ring-blue-500" required>
                        <span class="ml-2 text-sm font-medium text-gray-700">Unlimited</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="quota_type" value="limited" class="text-blue-600 focus:ring-blue-500" required>
                        <span class="ml-2 text-sm font-medium text-gray-700">Terbatas</span>
                    </label>
                </div>
            </div>

            <div id="quotaInputContainer" class="mb-4 hidden">
                <label for="create_quota" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Kuota <span class="text-red-500">*</span></label>
                <input type="number" id="create_quota" name="quota" min="1"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Contoh: 50">
                <p class="mt-1 text-xs text-gray-500">💡 Masukkan jumlah voucher yang tersedia</p>
            </div>

            <div class="mb-4">
                <label for="create_expiry_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kadaluarsa <span class="text-red-500">*</span></label>
                <input type="date" id="create_expiry_date" name="expiry_date" value="{{ old('expiry_date') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <p class="mt-1 text-xs text-gray-500">⏰ Voucher akan otomatis kadaluarsa setelah tanggal ini</p>
            </div>

            <div class="mb-4">
                <label for="create_image" class="block text-sm font-medium text-gray-700 mb-2">
                    Gambar Voucher (Display) <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-gray-500 mb-2">📱 Gambar ini akan ditampilkan di halaman daftar voucher</p>
                <div class="flex items-center justify-center w-full">
                    <label for="create_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 10MB)</p>
                        </div>
                        <input id="create_image" name="image" type="file" accept="image/*" class="hidden" onchange="previewCreateImage(event)" required>
                    </label>
                </div>
                <div id="createImagePreview" class="mt-3 hidden">
                    <img id="createPreview" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                </div>
            </div>

            <div class="mb-4 bg-blue-50 p-4 rounded-lg border border-blue-200">
                <label for="create_download_image" class="block text-sm font-medium text-gray-700 mb-2">
                    🎁 Gambar Voucher (Download) <span class="text-gray-500">(Opsional)</span>
                </label>
                <p class="text-xs text-blue-600 mb-3">
                    📸 Gambar ini akan digunakan sebagai background saat user download voucher (dengan barcode overlay). 
                    <br>
                    💡 Jika tidak diisi, akan menggunakan gambar display. Rekomendasi ukuran: 800x600px
                </p>
                <div class="flex items-center justify-center w-full">
                    <label for="create_download_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-blue-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-blue-50">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mb-2 text-sm text-blue-600"><span class="font-semibold">Upload gambar khusus</span></p>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 10MB)</p>
                        </div>
                        <input id="create_download_image" name="download_image" type="file" accept="image/*" class="hidden" onchange="previewCreateDownloadImage(event)">
                    </label>
                </div>
                <div id="createDownloadImagePreview" class="mt-3 hidden">
                    <p class="text-xs text-gray-600 mb-2">Preview gambar download:</p>
                    <img id="createDownloadPreview" src="" alt="Download Preview" class="w-full h-48 object-cover rounded-lg border-2 border-blue-300">
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                    Simpan Voucher
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Voucher -->
<div id="editVoucherModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-900">Edit Voucher</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Voucher <span class="text-red-500">*</span></label>
                <input type="text" id="edit_name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Voucher <span class="text-red-500">*</span></label>
                <textarea id="edit_deskripsi" name="deskripsi" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
            </div>

            <div class="mb-4">
                <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                <select id="edit_status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="aktif">Aktif</option>
                    <option value="tidak_aktif">Tidak Aktif</option>
                    <option value="kadaluarsa">Kadaluarsa</option>
                    <option value="habis">Habis</option>
                </select>
                <p class="mt-1 text-xs text-gray-500">💡 Status akan otomatis berubah jika tanggal sudah lewat atau kuota habis</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Kuota <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="quota_type" value="unlimited" class="text-blue-600 focus:ring-blue-500" required>
                        <span class="ml-2 text-sm font-medium text-gray-700">Unlimited</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="quota_type" value="limited" class="text-blue-600 focus:ring-blue-500" required>
                        <span class="ml-2 text-sm font-medium text-gray-700">Terbatas</span>
                    </label>
                </div>
            </div>

            <div id="editQuotaInputContainer" class="mb-4 hidden">
                <label for="edit_quota" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Kuota <span class="text-red-500">*</span></label>
                <input type="number" id="edit_quota" name="quota" min="1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       placeholder="Contoh: 50">
                <p class="mt-1 text-xs text-gray-500">💡 Masukkan jumlah voucher yang tersedia</p>
            </div>

            <div class="mb-4">
                <label for="edit_expiry_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kadaluarsa <span class="text-red-500">*</span></label>
                <input type="date" id="edit_expiry_date" name="expiry_date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <p class="mt-1 text-xs text-gray-500">⏰ Voucher akan otomatis kadaluarsa setelah tanggal ini</p>
            </div>

            <div class="mb-4">
                <label for="edit_image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Voucher (Display)</label>
                <p class="text-xs text-gray-500 mb-2">Kosongkan jika tidak ingin mengubah gambar display</p>
                
                <div id="currentImageContainer" class="mb-3">
                    <p class="text-sm text-gray-600 mb-2">Gambar display saat ini:</p>
                    <img id="currentImage" src="" alt="Current" class="w-full h-48 object-cover rounded-lg">
                </div>
                
                <div class="flex items-center justify-center w-full">
                    <label for="edit_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> gambar baru</p>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 10MB)</p>
                        </div>
                        <input id="edit_image" name="image" type="file" accept="image/*" class="hidden" onchange="previewEditImage(event)">
                    </label>
                </div>
                
                <div id="editImagePreview" class="mt-3 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview gambar display baru:</p>
                    <img id="editPreview" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                </div>
            </div>

            <div class="mb-4 bg-blue-50 p-4 rounded-lg border border-blue-200">
                <label for="edit_download_image" class="block text-sm font-medium text-gray-700 mb-2">
                    🎁 Gambar Voucher (Download) <span class="text-gray-500">(Opsional)</span>
                </label>
                <p class="text-xs text-blue-600 mb-3">
                    📸 Gambar untuk background download dengan barcode. Kosongkan jika tidak ingin mengubah.
                </p>
                
                <div id="currentDownloadImageContainer" class="mb-3">
                    <p class="text-sm text-gray-600 mb-2">Gambar download saat ini:</p>
                    <img id="currentDownloadImage" src="" alt="Current Download" class="w-full h-48 object-cover rounded-lg border-2 border-blue-300">
                </div>
                
                <div class="flex items-center justify-center w-full">
                    <label for="edit_download_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-blue-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-blue-50">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mb-2 text-sm text-blue-600"><span class="font-semibold">Upload gambar download baru</span></p>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG (MAX. 10MB)</p>
                        </div>
                        <input id="edit_download_image" name="download_image" type="file" accept="image/*" class="hidden" onchange="previewEditDownloadImage(event)">
                    </label>
                </div>
                
                <div id="editDownloadImagePreview" class="mt-3 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview gambar download baru:</p>
                    <img id="editDownloadPreview" src="" alt="Download Preview" class="w-full h-48 object-cover rounded-lg border-2 border-blue-300">
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                    Update Voucher
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Deskripsi -->
<div id="descriptionModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-xl font-semibold text-gray-900" id="descriptionTitle">Deskripsi Voucher</h3>
            <button type="button" onclick="closeDescriptionModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="mt-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <p id="descriptionContent" class="text-gray-700 whitespace-pre-wrap"></p>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t">
            <button type="button" onclick="closeDescriptionModal()" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Delete Confirmation -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-5">Hapus Voucher</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus voucher "<span id="deleteVoucherName" class="font-semibold"></span>"? 
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="flex justify-center space-x-3 px-4 py-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Image Preview -->
<div id="imageModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-90 overflow-y-auto h-full w-full z-50" onclick="closeImageModal()">
    <div class="relative top-10 mx-auto p-5 w-full max-w-4xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-white" id="imageModalTitle">Preview Gambar</h3>
            <button type="button" onclick="closeImageModal()" class="text-white hover:text-gray-300">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <img id="imageModalContent" src="" alt="Preview" class="w-full h-auto rounded-lg shadow-2xl" onclick="event.stopPropagation()">
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
        vouchersTab.classList.add('bg-blue-500', 'text-white', 'shadow-sm');
        vouchersTab.classList.remove('text-gray-600', 'hover:bg-gray-200');
        claimsTab.classList.remove('bg-blue-500', 'text-white', 'shadow-sm');
        claimsTab.classList.add('text-gray-600', 'hover:bg-gray-200');
        vouchersContent.classList.remove('hidden');
        claimsContent.classList.add('hidden');
    } else {
        claimsTab.classList.add('bg-blue-500', 'text-white', 'shadow-sm');
        claimsTab.classList.remove('text-gray-600', 'hover:bg-gray-200');
        vouchersTab.classList.remove('bg-blue-500', 'text-white', 'shadow-sm');
        vouchersTab.classList.add('text-gray-600', 'hover:bg-gray-200');
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