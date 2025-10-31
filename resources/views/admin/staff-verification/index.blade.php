@extends('layouts.app')

@section('title', 'Manajemen Kode Staff')
@section('page-title', 'Verifikasi Staff')
@section('page-description', 'Kelola kode akses untuk staff scanner')

@section('content')
<div class="space-y-6">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
        </button>
    </div>
    @endif

    @if(session('warning'))
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('warning') }}</span>
        <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-yellow-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Error!</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
        </button>
    </div>
    @endif

    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Kode Staff Scanner</h2>
            <p class="text-gray-600 mt-1">Kelola akses staff untuk sistem scanner tiket</p>
        </div>
        <button onclick="openAddModal()" class="bg-primary hover:bg-yellow-500 text-black font-semibold px-6 py-3 rounded-lg flex items-center space-x-2 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Tambah Kode Baru</span>
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Kode</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $staffCodes->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Kode Aktif</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-1">{{ $staffCodes->where('is_active', true)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Kode Nonaktif</p>
                    <h3 class="text-2xl font-bold text-red-600 mt-1">{{ $staffCodes->where('is_active', false)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Penggunaan</p>
                    <h3 class="text-2xl font-bold text-purple-600 mt-1">{{ $staffCodes->sum('usage_count') }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Kode Staff</h3>
                <div class="flex space-x-3">
                    <select id="bulkAction" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary">
                        <option value="">Bulk Action</option>
                        <option value="activate">Aktifkan</option>
                        <option value="deactivate">Nonaktifkan</option>
                        <option value="delete">Hapus</option>
                    </select>
                    <button onclick="executeBulkAction()" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        Terapkan
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)" class="rounded border-gray-300">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scanner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penggunaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Digunakan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($staffCodes as $staff)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="staff-checkbox rounded border-gray-300" value="{{ $staff->id }}">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <code class="bg-gray-100 px-3 py-1 rounded font-mono text-sm font-semibold">{{ $staff->code }}</code>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $staff->name }}</div>
                            @if($staff->description)
                            <div class="text-xs text-gray-500 mt-1">{{ $staff->description }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @if($staff->canScanTickets())
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">
                                    🎫 Tiket
                                </span>
                                @endif
                                @if($staff->canScanVouchers())
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded">
                                    🏷️ Voucher
                                </span>
                                @endif
                                @if(!$staff->canScanTickets() && !$staff->canScanVouchers())
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                                    - Tidak ada -
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-medium rounded-full 
                                {{ $staff->role == 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $staff->role == 'supervisor' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $staff->role == 'scanner' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ ucfirst($staff->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($staff->is_active)
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full flex items-center w-fit">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Aktif
                            </span>
                            @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full flex items-center w-fit">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Nonaktif
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-900 font-medium">{{ $staff->usage_count }}</span>
                            <span class="text-xs text-gray-500"> kali</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">
                                {{ $staff->last_used_at ? $staff->last_used_at->diffForHumans() : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <button onclick='openEditModal(@json($staff))' 
                                    class="text-blue-600 hover:text-blue-900 transition-colors" 
                                    title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.staff.verification.toggle', $staffCode->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-900">
                                        {{ $staffCode->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <!-- Atau untuk method toggleStatus -->
                                <form action="{{ route('admin.staff.verification.toggle-status', $staffCode->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-900">
                                        {{ $staffCode->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <button onclick="confirmDelete({{ $staff->id }})" 
                                    class="text-red-600 hover:text-red-900 transition-colors" 
                                    title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-lg font-medium">Belum ada kode staff</p>
                            <p class="text-sm mt-1">Tambahkan kode baru untuk mulai mengelola akses staff</p>
                            <button onclick="openAddModal()" class="mt-4 bg-primary hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded-lg inline-flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Tambah Kode</span>
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div id="staffModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
            <div class="flex justify-between items-center">
                <h3 id="modalTitle" class="text-xl font-bold text-gray-900">Tambah Kode Staff</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <form id="staffForm" method="POST" onsubmit="return validateForm(event)">
            @csrf
            <input type="hidden" id="method" name="_method" value="POST">
            
            <div class="p-6 space-y-4">
                <!-- Error Alert in Modal -->
                <div id="modalError" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline" id="modalErrorMessage"></span>
                    <button type="button" onclick="document.getElementById('modalError').classList.add('hidden')" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                        </svg>
                    </button>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Staff <span class="text-red-500">*</span>
                    </label>
                    <div class="flex space-x-2">
                        <input type="text" 
                            id="code" 
                            name="code" 
                            required 
                            maxlength="20"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent uppercase"
                            placeholder="STAFF001"
                            oninput="handleCodeInput(this)"
                            onblur="checkCodeAvailability()">
                        <button type="button" 
                            onclick="openCodeGenerator()" 
                            class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition-colors whitespace-nowrap"
                            title="Buka Code Generator">
                            <span class="hidden sm:inline">Generator</span>
                            <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Code validation feedback -->
                    <div id="codeValidation" class="mt-1 text-xs hidden">
                        <span id="codeValidationMessage"></span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Kode unik untuk identifikasi staff (3-20 karakter, huruf & angka)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Staff <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                        id="name" 
                        name="name" 
                        required
                        maxlength="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="John Doe">
                    <p class="text-xs text-gray-500 mt-1">Nama lengkap staff (maksimal 100 karakter)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select id="role" 
                        name="role" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="scanner">Scanner - Scan tiket promo</option>
                        <option value="scanner">scanner - Scan Voucher</option>
                        <option value="admin">Admin - Scan Tiket promo dan Voucher</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih role sesuai tugas staff</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea id="description" 
                        name="description" 
                        rows="3"
                        maxlength="255"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                        placeholder="Deskripsi tambahan (opsional)"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Informasi tambahan tentang staff (maksimal 255 karakter)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Akses Scanner <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-3 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" 
                                id="access_tickets" 
                                name="access_permissions[tickets]" 
                                value="1"
                                class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
                            <div class="flex-1">
                                <label for="access_tickets" class="text-sm font-medium text-gray-900 cursor-pointer">
                                    Scan Tiket
                                </label>
                                <p class="text-xs text-gray-500 mt-0.5">Akses untuk scan barcode tiket masuk pengunjung</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <input type="checkbox" 
                                id="access_vouchers" 
                                name="access_permissions[vouchers]" 
                                value="1"
                                class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
                            <div class="flex-1">
                                <label for="access_vouchers" class="text-sm font-medium text-gray-900 cursor-pointer">
                                    Scan Voucher
                                </label>
                                <p class="text-xs text-gray-500 mt-0.5">Akses untuk scan barcode voucher/promo</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        ⚠️ Pilih minimal satu akses untuk staff scanner
                    </p>
                </div>
            </div>

            <div class="p-6 border-t border-gray-200 flex justify-end space-x-3 sticky bottom-0 bg-white">
                <button type="button" 
                    onclick="closeModal()" 
                    class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </button>
                <button type="submit" 
                    class="bg-primary hover:bg-yellow-500 text-black font-semibold px-6 py-2 rounded-lg transition-colors"
                    id="submitBtn">
                    <span id="submitBtnText">Simpan</span>
                    <span id="submitBtnLoading" class="hidden">
                        <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Code Generator Modal -->
<div id="codeGeneratorModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 sticky top-0 bg-white">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Code Generator</h3>
                    <p class="text-sm text-gray-600 mt-1">Buat kode staff secara otomatis atau manual</p>
                </div>
                <button onclick="closeCodeGenerator()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Format Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Format Kode</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <button type="button" 
                        onclick="selectGeneratorFormat('random')" 
                        id="formatRandom"
                        class="generator-format-btn p-4 border-2 border-gray-300 rounded-lg hover:border-primary transition-colors text-left">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900">Random</h4>
                                <p class="text-xs text-gray-600 mt-1">Generate kode acak</p>
                            </div>
                        </div>
                    </button>

                    <button type="button" 
                        onclick="selectGeneratorFormat('sequential')" 
                        id="formatSequential"
                        class="generator-format-btn p-4 border-2 border-gray-300 rounded-lg hover:border-primary transition-colors text-left">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900">Sequential</h4>
                                <p class="text-xs text-gray-600 mt-1">Urutan berdasarkan nomor</p>
                            </div>
                        </div>
                    </button>

                    <button type="button" 
                        onclick="selectGeneratorFormat('custom')" 
                        id="formatCustom"
                        class="generator-format-btn p-4 border-2 border-primary rounded-lg hover:border-primary transition-colors text-left bg-primary bg-opacity-5">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-gray-900">Custom</h4>
                                <p class="text-xs text-gray-600 mt-1">Buat kode manual</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Prefix Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prefix (Opsional)</label>
                <input type="text" 
                    id="codePrefix" 
                    maxlength="10"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary uppercase"
                    placeholder="STAFF, ADMIN, SCAN, dll"
                    oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')"
                    onkeyup="updateGeneratorPreview()">
                <p class="text-xs text-gray-500 mt-1">Awalan kode (contoh: STAFF, ADMIN, SCAN)</p>
            </div>

            <!-- Length Input -->
            <div id="lengthSection">
                <label class="block text-sm font-medium text-gray-700 mb-2">Panjang Kode (setelah prefix)</label>
                <div class="flex items-center space-x-4">
                    <input type="range" 
                        id="codeLength" 
                        min="3" 
                        max="10" 
                        value="6"
                        class="flex-1"
                        oninput="updateLengthDisplay(); updateGeneratorPreview()">
                    <span id="lengthDisplay" class="text-sm font-semibold text-gray-700 w-8 text-center">6</span>
                </div>
            </div>

            <!-- Preview -->
            <div class="bg-gray-50 rounded-lg p-4 border-2 border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-2">Preview</label>
                <div class="flex items-center justify-between">
                    <code id="codePreview" class="text-lg font-mono font-bold text-gray-900">STAFF######</code>
                    <button type="button" 
                        onclick="generateCodeFromGenerator()" 
                        class="bg-primary hover:bg-yellow-500 text-black font-semibold px-4 py-2 rounded-lg transition-colors text-sm">
                        Generate
                    </button>
                </div>
            </div>

            <!-- Generated Code Result -->
            <div id="generatedResult" class="hidden bg-green-50 border-2 border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-800 mb-1">Kode berhasil di-generate!</p>
                        <code id="generatedCode" class="text-xl font-mono font-bold text-green-900"></code>
                    </div>
                    <button type="button" 
                        onclick="useGeneratedCode()" 
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors">
                        Gunakan
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 flex justify-end space-x-3 sticky bottom-0 bg-white">
            <button type="button" 
                onclick="closeCodeGenerator()" 
                class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-600 text-center mb-6">
                Apakah Anda yakin ingin menghapus kode staff ini? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex space-x-3">
                <button type="button" 
                    onclick="closeDeleteModal()" 
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </button>
                <button type="button" 
                    onclick="executeDelete()" 
                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>


<script>
let deleteId = null;
let selectedFormat = 'custom';
let checkCodeTimeout = null;

// Open Add Modal
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Kode Staff';
    document.getElementById('staffForm').action = '{{ route("admin.staff.verification.store") }}';
    document.getElementById('method').value = 'POST';
    document.getElementById('staffForm').reset();
    document.getElementById('modalError').classList.add('hidden');
    document.getElementById('codeValidation').classList.add('hidden');
    document.getElementById('staffModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Open Edit Modal
function openEditModal(staff) {
    document.getElementById('modalTitle').textContent = 'Edit Kode Staff';
    document.getElementById('staffForm').action = `/admin/staff/verification/${staff.id}`;
    document.getElementById('method').value = 'PUT';
    document.getElementById('code').value = staff.code;
    document.getElementById('name').value = staff.name;
    document.getElementById('role').value = staff.role;
    document.getElementById('description').value = staff.description || '';
    
    // Handle access permissions
    const permissions = staff.access_permissions || {};
    document.getElementById('access_tickets').checked = permissions.tickets || false;
    document.getElementById('access_vouchers').checked = permissions.vouchers || false;
    
    document.getElementById('modalError').classList.add('hidden');
    document.getElementById('codeValidation').classList.add('hidden');
    document.getElementById('staffModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Close Modal
function closeModal() {
    document.getElementById('staffModal').classList.add('hidden');
    document.getElementById('staffForm').reset();
    document.getElementById('modalError').classList.add('hidden');
    document.getElementById('codeValidation').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Handle Code Input
function handleCodeInput(input) {
    // Auto uppercase
    input.value = input.value.toUpperCase();
    
    // Only allow letters and numbers
    input.value = input.value.replace(/[^A-Z0-9]/g, '');
    
    // Clear previous timeout
    if (checkCodeTimeout) {
        clearTimeout(checkCodeTimeout);
    }
    
    // Check availability after 500ms of no typing
    if (input.value.length >= 3) {
        checkCodeTimeout = setTimeout(() => {
            checkCodeAvailability();
        }, 500);
    } else {
        document.getElementById('codeValidation').classList.add('hidden');
    }
}

// Check Code Availability
async function checkCodeAvailability() {
    const codeInput = document.getElementById('code');
    const code = codeInput.value.trim();
    const validationDiv = document.getElementById('codeValidation');
    const validationMessage = document.getElementById('codeValidationMessage');
    
    if (code.length < 3) {
        validationDiv.classList.add('hidden');
        return;
    }
    
    // Check if editing (skip check for same code)
    const method = document.getElementById('method').value;
    if (method === 'PUT') {
        const form = document.getElementById('staffForm');
        const currentCode = form.action.split('/').pop();
        // Skip check if it's the same code being edited
        // We'll let server-side validation handle this
    }
    
    try {
        const response = await fetch('{{ route("admin.staff.verification.check") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: code })
        });
        
        const data = await response.json();
        
        validationDiv.classList.remove('hidden');
        
        if (data.available) {
            validationMessage.textContent = '✓ Kode tersedia';
            validationMessage.className = 'text-green-600 font-medium';
            codeInput.classList.remove('border-red-300');
            codeInput.classList.add('border-green-300');
        } else {
            validationMessage.textContent = '✗ Kode sudah digunakan';
            validationMessage.className = 'text-red-600 font-medium';
            codeInput.classList.remove('border-green-300');
            codeInput.classList.add('border-red-300');
        }
    } catch (error) {
        console.error('Error checking code:', error);
    }
}

// ============ CODE GENERATOR FUNCTIONS ============

// Open Code Generator Modal
function openCodeGenerator() {
    closeModal(); // pastikan modal lain ditutup
    document.getElementById('codeGeneratorModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    selectGeneratorFormat('custom');
    loadQuickSuggestions();
}

// Close Code Generator Modal
function closeCodeGenerator() {
    document.getElementById('codeGeneratorModal').classList.add('hidden');
    document.getElementById('generatedResult').classList.add('hidden');
    document.body.style.overflow = 'hidden'; // Keep modal open
}

// Select Generator Format
function selectGeneratorFormat(format) {
    selectedFormat = format;
    
    // Update button styles
    const buttons = document.querySelectorAll('.generator-format-btn');
    buttons.forEach(btn => {
        btn.classList.remove('border-primary', 'bg-primary', 'bg-opacity-5');
        btn.classList.add('border-gray-300');
    });
    
    const selectedBtn = document.getElementById('format' + format.charAt(0).toUpperCase() + format.slice(1));
    selectedBtn.classList.remove('border-gray-300');
    selectedBtn.classList.add('border-primary', 'bg-primary', 'bg-opacity-5');
    
    // Show/hide length section for custom format
    if (format === 'custom') {
        document.getElementById('lengthSection').classList.add('hidden');
    } else {
        document.getElementById('lengthSection').classList.remove('hidden');
    }
    
    updateGeneratorPreview();
}

// Update Length Display
function updateLengthDisplay() {
    const length = document.getElementById('codeLength').value;
    document.getElementById('lengthDisplay').textContent = length;
}

// Update Generator Preview
function updateGeneratorPreview() {
    const prefix = document.getElementById('codePrefix').value || 'STAFF';
    const length = document.getElementById('codeLength').value;
    
    let preview = prefix;
    
    if (selectedFormat === 'sequential') {
        preview += '0'.repeat(length);
    } else if (selectedFormat === 'random') {
        preview += '#'.repeat(length);
    } else {
        preview += '...';
    }
    
    document.getElementById('codePreview').textContent = preview;
}

// Generate Code from Generator
async function generateCodeFromGenerator() {
    const prefix = document.getElementById('codePrefix').value || '';
    const length = document.getElementById('codeLength').value;
    
    try {
        const response = await fetch('{{ route("admin.staff.verification.generate.custom") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                prefix: prefix,
                format: selectedFormat,
                length: length
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('generatedCode').textContent = data.code;
            document.getElementById('generatedResult').classList.remove('hidden');
            
            // Add animation
            const resultDiv = document.getElementById('generatedResult');
            resultDiv.classList.add('animate-pulse');
            setTimeout(() => {
                resultDiv.classList.remove('animate-pulse');
            }, 1000);
        }
    } catch (error) {
        console.error('Error generating code:', error);
        alert('Gagal generate kode. Silakan coba lagi.');
    }
}

// Use Generated Code
function useGeneratedCode() {
    const generatedCode = document.getElementById('generatedCode').textContent;
    document.getElementById('code').value = generatedCode;
    closeCodeGenerator();
    // Buka modal tambah staff agar input terlihat
    document.getElementById('staffModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    checkCodeAvailability();
    // Visual feedback
    const codeInput = document.getElementById('code');
    codeInput.classList.add('ring-2', 'ring-green-500');
    setTimeout(() => {
        codeInput.classList.remove('ring-2', 'ring-green-500');
    }, 1000);
}

// Load Quick Suggestions
async function loadQuickSuggestions() {
    const role = document.getElementById('role').value || 'scanner';
    
    try {
        const response = await fetch('{{ route("admin.staff.verification.suggestions") }}?role=' + role);
        const data = await response.json();
        
        const suggestionsDiv = document.getElementById('quickSuggestions');
        suggestionsDiv.innerHTML = '';
        
        data.suggestions.forEach(code => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-mono font-semibold text-gray-900 transition-colors';
            button.textContent = code;
            button.onclick = () => useSuggestion(code);
            suggestionsDiv.appendChild(button);
        });
    } catch (error) {
        console.error('Error loading suggestions:', error);
    }
}

// Use Suggestion
function useSuggestion(code) {
    document.getElementById('code').value = code;
    closeCodeGenerator();
    checkCodeAvailability();
    
    // Visual feedback
    const codeInput = document.getElementById('code');
    codeInput.classList.add('ring-2', 'ring-blue-500');
    setTimeout(() => {
        codeInput.classList.remove('ring-2', 'ring-blue-500');
    }, 1000);
}

// Validate Form
function validateForm(event) {
    const code = document.getElementById('code').value.trim();
    const name = document.getElementById('name').value.trim();
    const role = document.getElementById('role').value;
    const ticketsAccess = document.getElementById('access_tickets').checked;
    const vouchersAccess = document.getElementById('access_vouchers').checked;
    
    if (!code) {
        showModalError('Kode staff wajib diisi');
        event.preventDefault();
        return false;
    }
    
    if (code.length < 3) {
        showModalError('Kode staff minimal 3 karakter');
        event.preventDefault();
        return false;
    }
    
    if (!/^[A-Z0-9]+$/.test(code)) {
        showModalError('Kode staff hanya boleh huruf kapital dan angka');
        event.preventDefault();
        return false;
    }
    
    if (!name) {
        showModalError('Nama staff wajib diisi');
        event.preventDefault();
        return false;
    }
    
    if (!role) {
        showModalError('Role wajib dipilih');
        event.preventDefault();
        return false;
    }
    
    // Validate at least one access permission is selected
    if (!ticketsAccess && !vouchersAccess) {
        showModalError('Pilih minimal satu akses scanner (Tiket atau Voucher)');
        event.preventDefault();
        return false;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitBtnLoading = document.getElementById('submitBtnLoading');
    
    submitBtn.disabled = true;
    submitBtnText.classList.add('hidden');
    submitBtnLoading.classList.remove('hidden');
    
    return true;
}

// Show Modal Error
function showModalError(message) {
    const modalError = document.getElementById('modalError');
    const modalErrorMessage = document.getElementById('modalErrorMessage');
    
    modalErrorMessage.textContent = message;
    modalError.classList.remove('hidden');
    
    // Scroll to error
    modalError.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        modalError.classList.add('hidden');
    }, 5000);
}

// Toggle Select All
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.staff-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}

// Execute Bulk Action
function executeBulkAction() {
    const action = document.getElementById('bulkAction').value;
    
    if (!action) {
        alert('Pilih aksi terlebih dahulu!');
        return;
    }

    const selectedIds = Array.from(document.querySelectorAll('.staff-checkbox:checked'))
        .map(cb => cb.value);

    if (selectedIds.length === 0) {
        alert('Pilih minimal satu kode staff!');
        return;
    }

    const actionText = {
        'activate': 'mengaktifkan',
        'deactivate': 'menonaktifkan',
        'delete': 'menghapus'
    };

    const confirmMessage = action === 'delete' 
        ? `Yakin ingin menghapus ${selectedIds.length} kode staff terpilih? Tindakan ini tidak dapat dibatalkan.`
        : `Yakin ingin ${actionText[action]} ${selectedIds.length} kode staff terpilih?`;

    if (!confirm(confirmMessage)) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.staff.verification.bulk") }}';
    
    form.innerHTML = `
        @csrf
        <input type="hidden" name="action" value="${action}">
        ${selectedIds.map(id => `<input type="hidden" name="ids[]" value="${id}">`).join('')}
    `;
    
    document.body.appendChild(form);
    form.submit();
}

// Confirm Delete
function confirmDelete(id) {
    deleteId = id;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Close Delete Modal
function closeDeleteModal() {
    deleteId = null;
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Execute Delete
function executeDelete() {
    if (!deleteId) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/staff/verification/${deleteId}`;
    form.innerHTML = `
        @csrf
        @method('DELETE')
    `;
    document.body.appendChild(form);
    form.submit();
}

// Close modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
        closeDeleteModal();
        closeCodeGenerator();
    }
});

// Close modal on backdrop click
document.getElementById('staffModal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        closeModal();
    }
});

document.getElementById('deleteModal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        closeDeleteModal();
    }
});

document.getElementById('codeGeneratorModal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        closeCodeGenerator();
    }
});

// Auto hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('[role="alert"]');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // Update suggestions when role changes
    document.getElementById('role')?.addEventListener('change', function() {
        if (document.getElementById('codeGeneratorModal').classList.contains('hidden') === false) {
            loadQuickSuggestions();
        }
    });
});

// Generate Quick Code Button (tambahan untuk kemudahan)
function quickGenerateCode() {
    const role = document.getElementById('role').value;
    const prefixes = {
        'admin': 'ADMIN',
        'supervisor': 'SUPER',
        'scanner': 'SCAN'
    };
    
    const prefix = prefixes[role] || 'STAFF';
    const random = Math.floor(Math.random() * 900) + 100; // 100-999
    const code = prefix + random;
    
    document.getElementById('code').value = code;
    checkCodeAvailability();
    
    // Visual feedback
    const codeInput = document.getElementById('code');
    codeInput.classList.add('ring-2', 'ring-primary');
    setTimeout(() => {
        codeInput.classList.remove('ring-2', 'ring-primary');
    }, 1000);
}
</script>
@endsection