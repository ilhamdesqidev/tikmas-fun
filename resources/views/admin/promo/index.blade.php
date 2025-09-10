@extends('layouts.app')

@section('title', 'Paket Promo')
@section('page-title', 'Paket Promo')
@section('page-description', 'Kelola paket promo dan penawaran khusus')

@section('content')
    <!-- Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="flex items-center space-x-4 mb-4 sm:mb-0">
            <div class="relative">
                <input type="text" placeholder="Cari paket promo..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent w-64">
                <i data-feather="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
            </div>
            <select class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
                <option value="expired">Kadaluarsa</option>
            </select>
        </div>
        <div class="flex space-x-3">
            <button class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                <i data-feather="download" class="w-4 h-4 mr-2"></i>
                Export
            </button>
            <button onclick="openCreateModal()" class="flex items-center px-4 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 font-medium">
                <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                Tambah Promo
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Promo</p>
                    <p class="text-2xl font-bold text-gray-900">12</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-feather="gift" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm font-medium">+2 dari bulan lalu</span>
            </div>
        </div>

        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Promo Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">8</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-feather="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm font-medium">66.7% dari total</span>
            </div>
        </div>

        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Penjualan</p>
                    <p class="text-2xl font-bold text-gray-900">Rp 45.2M</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i data-feather="trending-up" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-green-600 text-sm font-medium">+12.5% dari bulan lalu</span>
            </div>
        </div>

        <div class="card rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Avg. Diskon</p>
                    <p class="text-2xl font-bold text-gray-900">25%</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i data-feather="percent" class="w-6 h-6 text-red-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-red-600 text-sm font-medium">-5% dari bulan lalu</span>
            </div>
        </div>
    </div>

    <!-- Promo Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Promo Card 1 -->
        <div class="card rounded-xl overflow-hidden">
            <div class="promo-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                        Weekend Special
                    </span>
                    <div class="flex space-x-2">
                        <button onclick="editPromo(1)" class="p-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                            <i data-feather="edit-2" class="w-4 h-4 text-white"></i>
                        </button>
                        <button onclick="deletePromo(1)" class="p-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                            <i data-feather="trash-2" class="w-4 h-4 text-white"></i>
                        </button>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Paket Family Weekend</h3>
                <p class="text-white text-opacity-90 text-sm mb-4">Nikmati liburan keluarga dengan diskon spesial untuk tiket masuk dan wahana pilihan</p>
                
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-white text-opacity-70 text-sm line-through">Rp 500.000</span>
                        <div class="text-2xl font-bold text-white">Rp 375.000</div>
                    </div>
                    <div class="text-right">
                        <div class="bg-white bg-opacity-20 rounded-lg px-3 py-2">
                            <span class="text-white font-bold">25% OFF</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between text-sm text-white text-opacity-90">
                    <span><i data-feather="calendar" class="w-4 h-4 inline mr-1"></i>Valid: 1-31 Des 2024</span>
                    <span><i data-feather="users" class="w-4 h-4 inline mr-1"></i>125 terjual</span>
                </div>
                
                <div class="mt-4">
                    <span class="bg-green-500 text-white px-2 py-1 rounded text-xs font-medium">Aktif</span>
                </div>
            </div>
        </div>

        <!-- Promo Card 2 -->
        <div class="card rounded-xl overflow-hidden">
            <div class="promo-card-alt p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                        Student Discount
                    </span>
                    <div class="flex space-x-2">
                        <button onclick="editPromo(2)" class="p-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                            <i data-feather="edit-2" class="w-4 h-4 text-white"></i>
                        </button>
                        <button onclick="deletePromo(2)" class="p-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                            <i data-feather="trash-2" class="w-4 h-4 text-white"></i>
                        </button>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Promo Pelajar</h3>
                <p class="text-white text-opacity-90 text-sm mb-4">Khusus pelajar dengan menunjukkan kartu pelajar valid</p>
                
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-white text-opacity-70 text-sm line-through">Rp 75.000</span>
                        <div class="text-2xl font-bold text-white">Rp 50.000</div>
                    </div>
                    <div class="text-right">
                        <div class="bg-white bg-opacity-20 rounded-lg px-3 py-2">
                            <span class="text-white font-bold">33% OFF</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between text-sm text-white text-opacity-90">
                    <span><i data-feather="calendar" class="w-4 h-4 inline mr-1"></i>Valid: Selamanya</span>
                    <span><i data-feather="users" class="w-4 h-4 inline mr-1"></i>89 terjual</span>
                </div>
                
                <div class="mt-4">
                    <span class="bg-green-500 text-white px-2 py-1 rounded text-xs font-medium">Aktif</span>
                </div>
            </div>
        </div>

        <!-- Promo Card 3 -->
        <div class="card rounded-xl overflow-hidden">
            <div class="promo-card-premium p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                        Premium Package
                    </span>
                    <div class="flex space-x-2">
                        <button onclick="editPromo(3)" class="p-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                            <i data-feather="edit-2" class="w-4 h-4 text-white"></i>
                        </button>
                        <button onclick="deletePromo(3)" class="p-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                            <i data-feather="trash-2" class="w-4 h-4 text-white"></i>
                        </button>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">All-Inclusive VIP</h3>
                <p class="text-white text-opacity-90 text-sm mb-4">Semua wahana + makan siang + foto profesional + guide pribadi</p>
                
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-white text-opacity-70 text-sm line-through">Rp 1.200.000</span>
                        <div class="text-2xl font-bold text-white">Rp 950.000</div>
                    </div>
                    <div class="text-right">
                        <div class="bg-white bg-opacity-20 rounded-lg px-3 py-2">
                            <span class="text-white font-bold">21% OFF</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between text-sm text-white text-opacity-90">
                    <span><i data-feather="calendar" class="w-4 h-4 inline mr-1"></i>Valid: 15 Des - 15 Jan</span>
                    <span><i data-feather="users" class="w-4 h-4 inline mr-1"></i>23 terjual</span>
                </div>
                
                <div class="mt-4">
                    <span class="bg-green-500 text-white px-2 py-1 rounded text-xs font-medium">Aktif</span>
                </div>
            </div>
        </div>

        <!-- Promo Card 4 - Inactive -->
        <div class="card rounded-xl overflow-hidden opacity-75">
            <div class="bg-gray-500 p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                        Early Bird
                    </span>
                    <div class="flex space-x-2">
                        <button onclick="editPromo(4)" class="p-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                            <i data-feather="edit-2" class="w-4 h-4 text-white"></i>
                        </button>
                        <button onclick="deletePromo(4)" class="p-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-all">
                            <i data-feather="trash-2" class="w-4 h-4 text-white"></i>
                        </button>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Promo Tahun Baru</h3>
                <p class="text-white text-opacity-90 text-sm mb-4">Dapatkan diskon super untuk pembelian tiket di awal tahun</p>
                
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-white text-opacity-70 text-sm line-through">Rp 300.000</span>
                        <div class="text-2xl font-bold text-white">Rp 200.000</div>
                    </div>
                    <div class="text-right">
                        <div class="bg-white bg-opacity-20 rounded-lg px-3 py-2">
                            <span class="text-white font-bold">33% OFF</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between text-sm text-white text-opacity-90">
                    <span><i data-feather="calendar" class="w-4 h-4 inline mr-1"></i>Expired: 31 Jan 2024</span>
                    <span><i data-feather="users" class="w-4 h-4 inline mr-1"></i>456 terjual</span>
                </div>
                
                <div class="mt-4">
                    <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-medium">Kadaluarsa</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="promoModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b">
                    <h2 id="modalTitle" class="text-xl font-bold text-gray-900">Tambah Paket Promo</h2>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <i data-feather="x" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <form id="promoForm" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Promo</label>
                            <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="weekend">Weekend Special</option>
                                <option value="student">Student Discount</option>
                                <option value="premium">Premium Package</option>
                                <option value="early_bird">Early Bird</option>
                                <option value="group">Group Package</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Normal</label>
                            <input type="number" name="original_price" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Harga Promo</label>
                            <input type="number" name="promo_price" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Diskon (%)</label>
                            <input type="number" name="discount_percent" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                            <input type="date" name="start_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir</label>
                            <input type="date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kuota (opsional)</label>
                            <input type="number" name="quota" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="featured" name="featured" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                        <label for="featured" class="ml-2 text-sm font-medium text-gray-700">Tampilkan sebagai promo unggulan</label>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" onclick="closeModal()" class="px-6 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 font-medium">
                            Simpan Promo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extra-js')
// Modal functionality
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Paket Promo';
    document.getElementById('promoForm').reset();
    document.getElementById('promoModal').classList.remove('hidden');
}

function editPromo(id) {
    document.getElementById('modalTitle').textContent = 'Edit Paket Promo';
    // Here you would typically load the promo data
    document.getElementById('promoModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('promoModal').classList.add('hidden');
}

function deletePromo(id) {
    if (confirm('Apakah Anda yakin ingin menghapus promo ini?')) {
        showToast('Promo berhasil dihapus', 'success');
        // Here you would make the delete request
    }
}

// Auto calculate discount percentage
document.addEventListener('DOMContentLoaded', function() {
    const originalPrice = document.querySelector('input[name="original_price"]');
    const promoPrice = document.querySelector('input[name="promo_price"]');
    const discountPercent = document.querySelector('input[name="discount_percent"]');

    function calculateDiscount() {
        if (originalPrice.value && promoPrice.value) {
            const original = parseFloat(originalPrice.value);
            const promo = parseFloat(promoPrice.value);
            const discount = ((original - promo) / original * 100).toFixed(0);
            discountPercent.value = discount;
        }
    }

    originalPrice?.addEventListener('input', calculateDiscount);
    promoPrice?.addEventListener('input', calculateDiscount);
});

// Form submission
document.getElementById('promoForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    showToast('Promo berhasil disimpan!', 'success');
    closeModal();
});

// Close modal when clicking outside
document.getElementById('promoModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
@endsection