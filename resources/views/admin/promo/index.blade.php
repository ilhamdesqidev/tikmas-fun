@extends('layouts.app')

@section('title', 'Paket Promo')
@section('page-title', 'Paket Promo')
@section('page-description', 'Kelola paket promo dan penawaran khusus')

@section('content')
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="flex items-center space-x-4 mb-4 sm:mb-0">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Cari paket promo..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent w-64">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="coming_soon">Coming Soon</option>
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
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
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
        </div>

        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Draft</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $draftPromos }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Coming Soon</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $comingSoonPromos }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Aktif</p>
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
                    <p class="text-sm font-medium text-gray-600">Kadaluarsa</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $expiredPromos }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
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

    <!-- Bulk Actions -->
    <div class="bg-gray-50 rounded-lg p-4 mb-6 hidden" id="bulkActionsPanel">
        <div class="flex items-center justify-between">
            <div>
                <span id="selectedCount" class="font-medium text-gray-700">0 promo terpilih</span>
            </div>
            <div class="flex space-x-3">
                <select id="bulkActionSelect" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                    <option value="">Pilih Aksi</option>
                    <option value="publish_draft">Publish Draft</option>
                    <option value="activate">Aktifkan</option>
                    <option value="deactivate">Nonaktifkan</option>
                    <option value="delete">Hapus</option>
                </select>
                <button id="applyBulkAction" class="px-4 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 text-sm font-medium">
                    Terapkan
                </button>
                <button id="cancelBulkAction" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Promo Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="promosContainer">
        @foreach($promos as $promo)
        <div class="card rounded-xl overflow-hidden promo-card {{ $promo->status != 'active' ? 'opacity-75' : '' }}" data-status="{{ $promo->status }}" data-name="{{ strtolower($promo->name) }}">
            <div class="relative">
                <a href="{{ route('admin.promo.show', $promo->id) }}" class="block">
                    <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $promo->image_url }}')"></div>
                </a>
                <div class="absolute top-3 right-3">
                    <input type="checkbox" class="bulk-checkbox hidden" value="{{ $promo->id }}">
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="bg-primary bg-opacity-20 text-primary px-3 py-1 rounded-full text-sm font-medium">
                        {{ ucfirst($promo->category) }}
                    </span>
                    <div class="flex space-x-2">
                        <!-- Toggle Status Button -->
                        <button onclick="togglePromoStatus({{ $promo->id }})" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center justify-center w-9 h-9" title="Ubah Status">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </button>
                        
                        <a href="{{ route('admin.promo.edit', $promo->id) }}" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center justify-center w-9 h-9" title="Edit">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        
                        <form action="{{ route('admin.promo.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus promo ini?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center justify-center w-9 h-9" title="Hapus">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
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
                    <span class="{{ $promo->status_color }} text-white px-2 py-1 rounded text-xs font-medium">
                        {{ $promo->status_text }}
                    </span>
                    
                    @if($promo->featured)
                    <span class="bg-yellow-500 text-white px-2 py-1 rounded text-xs font-medium">Unggulan</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State -->
    @if($promos->count() == 0)
    <div class="text-center py-12">
        <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada promo</h3>
        <p class="text-gray-500 mb-6">Mulai dengan membuat promo pertama Anda.</p>
        <a href="{{ route('admin.promo.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 font-medium">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Promo Pertama
        </a>
    </div>
    @endif
@endsection

@section('extra-js')
<script>
    // Toggle status promo
    function togglePromoStatus(promoId) {
        if (!confirm('Apakah Anda yakin ingin mengubah status promo ini?')) {
            return;
        }

        fetch(`/admin/promo/${promoId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload(); // Reload untuk update tampilan
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status');
        });
    }

    // Bulk actions functionality
    document.addEventListener('DOMContentLoaded', function() {
        const bulkActionsPanel = document.getElementById('bulkActionsPanel');
        const bulkCheckboxes = document.querySelectorAll('.bulk-checkbox');
        const selectedCount = document.getElementById('selectedCount');
        const bulkActionSelect = document.getElementById('bulkActionSelect');
        const applyBulkAction = document.getElementById('applyBulkAction');
        const cancelBulkAction = document.getElementById('cancelBulkAction');

        // Toggle bulk selection
        document.addEventListener('click', function(e) {
            if (e.target.closest('.promo-card')) {
                const card = e.target.closest('.promo-card');
                const checkbox = card.querySelector('.bulk-checkbox');
                
                if (!e.target.closest('a') && !e.target.closest('button') && !e.target.closest('form')) {
                    checkbox.checked = !checkbox.checked;
                    checkbox.classList.toggle('hidden', !checkbox.checked);
                    updateBulkActions();
                }
            }
        });

        function updateBulkActions() {
            const selected = document.querySelectorAll('.bulk-checkbox:checked');
            const count = selected.length;
            
            selectedCount.textContent = `${count} promo terpilih`;
            
            if (count > 0) {
                bulkActionsPanel.classList.remove('hidden');
            } else {
                bulkActionsPanel.classList.add('hidden');
            }
        }

        // Apply bulk action
        applyBulkAction.addEventListener('click', function() {
            const action = bulkActionSelect.value;
            const selectedIds = Array.from(document.querySelectorAll('.bulk-checkbox:checked'))
                .map(checkbox => checkbox.value);
            
            if (!action) {
                alert('Pilih aksi terlebih dahulu');
                return;
            }

            if (!confirm(`Apakah Anda yakin ingin ${getActionText(action)} ${selectedIds.length} promo?`)) {
                return;
            }

            fetch('{{ route("admin.promo.bulk-action") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    action: action,
                    promo_ids: selectedIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses aksi');
            });
        });

        function getActionText(action) {
            const actions = {
                'publish_draft': 'mempublish',
                'activate': 'mengaktifkan',
                'deactivate': 'menonaktifkan',
                'delete': 'menghapus'
            };
            return actions[action] || 'memproses';
        }

        // Cancel bulk action
        cancelBulkAction.addEventListener('click', function() {
            bulkCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.classList.add('hidden');
            });
            bulkActionsPanel.classList.add('hidden');
            bulkActionSelect.value = '';
        });

        // Search and filter functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const promoCards = document.querySelectorAll('.promo-card');

        function filterPromos() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;

            promoCards.forEach(card => {
                const name = card.getAttribute('data-name');
                const status = card.getAttribute('data-status');
                
                const matchesSearch = name.includes(searchTerm);
                const matchesStatus = !statusValue || status === statusValue;
                
                if (matchesSearch && matchesStatus) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterPromos);
        statusFilter.addEventListener('change', filterPromos);
    });
</script>
@endsection