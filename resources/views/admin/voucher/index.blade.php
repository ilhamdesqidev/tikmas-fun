@extends('layouts.app')

@section('title', 'Management Voucher')

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Simple Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Voucher Management</h1>
            <p class="text-gray-600 text-sm mt-1">Kelola voucher dan data klaim</p>
        </div>

        <!-- Messages -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Whoops!</strong> Ada beberapa masalah:
            <ul class="mt-2 ml-4 list-disc text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Main Card -->
        <div class="bg-white rounded-lg shadow">
            <!-- Simple Tabs -->
            <div class="border-b border-gray-200">
                <div class="flex">
                    <button onclick="switchTab('vouchers')" id="tabVouchers" 
                            class="px-6 py-3 font-medium text-sm border-b-2 border-blue-600 text-blue-600">
                        Vouchers ({{ isset($vouchers) ? $vouchers->count() : 0 }})
                    </button>
                    <button onclick="switchTab('claims')" id="tabClaims" 
                            class="px-6 py-3 font-medium text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                        Claims ({{ isset($claims) ? $claims->count() : 0 }})
                    </button>
                </div>
            </div>

            <div class="p-6">
                <!-- Vouchers Tab -->
                <div id="vouchersContent">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Daftar Voucher</h2>
                        <button onclick="openCreateModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded font-medium">
                            + Tambah Voucher
                        </button>
                    </div>

                    <!-- Vouchers Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Voucher</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Expired</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Claimed</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($vouchers ?? [] as $index => $voucher)
                                @php
                                    $isExpired = \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($voucher->expiry_date));
                                    $currentStatus = $isExpired ? 'kadaluarsa' : $voucher->status;
                                    $effectiveStatus = $currentStatus;
                                    if (!$voucher->is_unlimited && $voucher->remaining_quota <= 0) {
                                        $effectiveStatus = 'habis';
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $voucher->image_url }}" alt="{{ $voucher->name }}" 
                                                 class="h-12 w-12 object-cover rounded cursor-pointer" 
                                                 onerror="this.src='https://via.placeholder.com/48'" 
                                                 onclick="showImageModal('{{ $voucher->image_url }}', 'Gambar Display')">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $voucher->name }}</div>
                                                <button onclick="openDescriptionModal('{{ addslashes($voucher->name) }}', '{{ addslashes($voucher->deskripsi) }}')" 
                                                        class="text-xs text-blue-600 hover:text-blue-800">
                                                    Lihat deskripsi
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($effectiveStatus === 'aktif')
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Aktif</span>
                                        @elseif($effectiveStatus === 'tidak_aktif')
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Tidak Aktif</span>
                                        @elseif($effectiveStatus === 'habis')
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">Habis</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Kadaluarsa</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm {{ $isExpired ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                                        {{ \Carbon\Carbon::parse($voucher->expiry_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-900">{{ $voucher->claims_count ?? 0 }} user</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2">
                                            <button onclick='openEditModal(@json($voucher))' 
                                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                Edit
                                            </button>
                                            <button onclick="confirmDelete({{ $voucher->id }}, '{{ addslashes($voucher->name) }}')" 
                                                    class="text-sm text-red-600 hover:text-red-800 font-medium">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                        <p class="mb-2">Belum ada voucher</p>
                                        <button onclick="openCreateModal()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Tambah voucher pertama
                                        </button>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Claims Tab -->
                <div id="claimsContent" class="hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Data Klaim User</h2>
                        <input type="text" id="searchClaim" placeholder="Search..." 
                               class="px-3 py-2 border border-gray-300 rounded text-sm w-64" 
                               onkeyup="searchClaims()">
                    </div>

                    <!-- Claims Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">User</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Voucher</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Unique Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Claimed At</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="claimsTableBody">
                                @forelse($claims ?? [] as $index => $claim)
                                @php
                                    $voucherExpired = $claim->voucher && \Carbon\Carbon::now()->startOfDay()->greaterThan(\Carbon\Carbon::parse($claim->voucher->expiry_date));
                                    $isUsed = $claim->is_used || $claim->scanned_at;
                                @endphp
                                <tr class="claim-row hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $claim->user_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $claim->user_phone }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $claim->voucher->name ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $claim->unique_code }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $claim->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($isUsed)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Used</span>
                                        @elseif($voucherExpired)
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Expired</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                        Belum ada data klaim
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

<!-- Modal Create Voucher -->
<div id="createVoucherModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white">Tambah Voucher</h3>
                <button onclick="closeCreateModal()" class="text-white hover:bg-blue-700 rounded p-1">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.voucher.store') }}" method="POST" enctype="multipart/form-data" class="overflow-y-auto max-h-[calc(90vh-140px)]" id="createForm">
                @csrf
                
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Voucher *</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm" 
                               placeholder="Contoh: Diskon 50%" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
                        <textarea name="deskripsi" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm" 
                            placeholder="Deskripsi voucher" required>{{ old('deskripsi') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded text-sm" required>
                            <option value="">Pilih Status</option>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="tidak_aktif" {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Kuota *</label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="quota_type" value="unlimited" class="mr-2" required>
                                <span class="text-sm">Unlimited</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="quota_type" value="limited" class="mr-2" required>
                                <span class="text-sm">Terbatas</span>
                            </label>
                        </div>
                    </div>

                    <div id="quotaInputContainer" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Kuota *</label>
                        <input type="number" id="create_quota" name="quota" min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm" 
                            placeholder="50">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kadaluarsa *</label>
                        <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Display *</label>
                        <input id="create_image" name="image" type="file" accept="image/*" 
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm" 
                               onchange="previewCreateImage(event)" required>
                        <div id="createImagePreview" class="mt-2 hidden">
                            <img id="createPreview" src="" alt="Preview" class="w-full h-40 object-cover rounded">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Download (Optional)</label>
                        <input id="create_download_image" name="download_image" type="file" accept="image/*" 
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm" 
                               onchange="previewCreateDownloadImage(event)">
                        <p class="text-xs text-gray-500 mt-1">Gambar untuk background download dengan barcode</p>
                        <div id="createDownloadImagePreview" class="mt-2 hidden">
                            <img id="createDownloadPreview" src="" alt="Download Preview" class="w-full h-40 object-cover rounded">
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-2 border-t">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded text-sm font-medium hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-medium hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Voucher -->
<div id="editVoucherModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
            <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white">Edit Voucher</h3>
                <button onclick="closeEditModal()" class="text-white hover:bg-indigo-700 rounded p-1">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data" class="overflow-y-auto max-h-[calc(90vh-140px)]">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Voucher *</label>
                        <input type="text" id="edit_name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
                        <textarea id="edit_deskripsi" name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded text-sm" required></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select id="edit_status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded text-sm" required>
                            <option value="aktif">Aktif</option>
                            <option value="tidak_aktif">Tidak Aktif</option>
                            <option value="kadaluarsa">Kadaluarsa</option>
                            <option value="habis">Habis</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Kuota *</label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input type="radio" name="quota_type" value="unlimited" class="mr-2" required>
                                <span class="text-sm">Unlimited</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="quota_type" value="limited" class="mr-2" required>
                                <span class="text-sm">Terbatas</span>
                            </label>
                        </div>
                    </div>

                    <div id="editQuotaInputContainer" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Kuota *</label>
                        <input type="number" id="edit_quota" name="quota" min="1" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kadaluarsa *</label>
                        <input type="date" id="edit_expiry_date" name="expiry_date" class="w-full px-3 py-2 border border-gray-300 rounded text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Display</label>
                        <div id="currentImageContainer" class="mb-2">
                            <img id="currentImage" src="" alt="Current" class="w-full h-40 object-cover rounded">
                        </div>
                        <input id="edit_image" name="image" type="file" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded text-sm" onchange="previewEditImage(event)">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah</p>
                        <div id="editImagePreview" class="mt-2 hidden">
                            <img id="editPreview" src="" alt="Preview" class="w-full h-40 object-cover rounded">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Download</label>
                        <div id="currentDownloadImageContainer" class="mb-2 hidden">
                            <img id="currentDownloadImage" src="" alt="Current Download" class="w-full h-40 object-cover rounded">
                        </div>
                        <input id="edit_download_image" name="download_image" type="file" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded text-sm" onchange="previewEditDownloadImage(event)">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah</p>
                        <div id="editDownloadImagePreview" class="mt-2 hidden">
                            <img id="editDownloadPreview" src="" alt="Download Preview" class="w-full h-40 object-cover rounded">
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-2 border-t">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded text-sm font-medium hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded text-sm font-medium hover:bg-indigo-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Deskripsi -->
<div id="descriptionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
            <div class="bg-gray-800 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white" id="descriptionTitle">Deskripsi</h3>
                <button onclick="closeDescriptionModal()" class="text-white hover:bg-gray-700 rounded p-1">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <p id="descriptionContent" class="text-gray-700 text-sm whitespace-pre-wrap"></p>
            </div>
            <div class="bg-gray-50 px-6 py-4 border-t flex justify-end">
                <button onclick="closeDescriptionModal()" class="px-4 py-2 bg-gray-800 text-white rounded text-sm font-medium hover:bg-gray-900">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Hapus Voucher</h3>
                <p class="text-sm text-gray-600 mb-1">Apakah Anda yakin ingin menghapus</p>
                <p class="text-sm font-semibold text-gray-900 mb-4">"<span id="deleteVoucherName"></span>"?</p>
                <p class="text-xs text-red-600 mb-4">Tindakan ini tidak dapat dibatalkan</p>
                <div class="flex justify-center gap-2">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded text-sm font-medium hover:bg-gray-50">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded text-sm font-medium hover:bg-red-700">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Image Preview -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50" onclick="closeImageModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative max-w-4xl w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-white" id="imageModalTitle">Preview</h3>
                <button onclick="closeImageModal()" class="text-white hover:bg-gray-800 rounded p-2">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <img id="imageModalContent" src="" alt="Preview" class="w-full h-auto rounded-lg" onclick="event.stopPropagation()">
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