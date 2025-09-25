@extends('layouts.app')

@section('title', 'Manajemen Tiket')
@section('page-title', 'Manajemen Tiket')
@section('page-description', 'Kelola semua pemesanan tiket di sini.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Header Section -->
    <div class="bg-white shadow-lg border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-3xl font-bold text-gray-900 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        @yield('page-title')
                    </h1>
                    <p class="text-gray-600 mt-2 text-lg">@yield('page-description')</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-chart-line text-blue-500"></i>
                        </div>
                        <div class="pl-10 pr-4 py-2 bg-blue-50 rounded-lg">
                            <span class="text-sm font-medium text-blue-700">Total Orders: 142</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 px-8 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="mb-4 lg:mb-0">
                        <h3 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-ticket-alt mr-3 text-blue-200"></i>
                            Daftar Semua Tiket
                        </h3>
                        <p class="text-blue-100 mt-1">Kelola dan pantau status semua pemesanan</p>
                    </div>
                    
                    <!-- Enhanced Search Form -->
                    <form method="GET" action="{{ route('admin.tickets.index') }}" class="flex items-center space-x-3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari order/customer..." 
                                   class="w-80 pl-12 pr-4 py-3 bg-white/95 backdrop-blur-sm border-0 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-white/30 focus:bg-white shadow-lg transition-all duration-300">
                        </div>
                        <button type="submit" class="bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-xl font-medium transition-all duration-300 backdrop-blur-sm shadow-lg hover:shadow-xl">
                            <i class="fas fa-search mr-2"></i>
                            Cari
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Card Body -->
            <div class="p-8">
                <!-- Enhanced Filter Status -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-filter mr-2 text-blue-500"></i>
                        Filter Status
                    </h4>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.tickets.index') }}" 
                           class="group relative bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-600 hover:to-blue-700 transform hover:-translate-y-1 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <i class="fas fa-list mr-2"></i>
                            Semua 
                            <span class="ml-2 bg-white/20 px-2 py-1 rounded-lg text-sm">({{ \App\Models\Order::count() }})</span>
                        </a>
                        
                        @php
                            $statusConfig = [
                                'pending' => ['icon' => 'clock', 'color' => 'yellow', 'from' => 'yellow-400', 'to' => 'orange-500'],
                                'success' => ['icon' => 'check-circle', 'color' => 'green', 'from' => 'green-400', 'to' => 'emerald-600'],
                                'challenge' => ['icon' => 'exclamation-triangle', 'color' => 'orange', 'from' => 'orange-400', 'to' => 'red-500'],
                                'denied' => ['icon' => 'times-circle', 'color' => 'red', 'from' => 'red-400', 'to' => 'red-600'],
                                'expired' => ['icon' => 'hourglass-end', 'color' => 'gray', 'from' => 'gray-400', 'to' => 'gray-600'],
                                'canceled' => ['icon' => 'ban', 'color' => 'red', 'from' => 'red-500', 'to' => 'red-700']
                            ];
                        @endphp
                        
                        @foreach(['pending', 'success', 'challenge', 'denied', 'expired', 'canceled'] as $status)
                            <a href="{{ route('admin.tickets.index', ['status' => $status]) }}" 
                               class="group relative bg-gradient-to-r from-{{ $statusConfig[$status]['from'] }} to-{{ $statusConfig[$status]['to'] }} {{ request('status') == $status ? '' : 'opacity-75 hover:opacity-100' }} text-white px-6 py-3 rounded-xl font-medium transform hover:-translate-y-1 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <i class="fas fa-{{ $statusConfig[$status]['icon'] }} mr-2"></i>
                                {{ ucfirst($status) }}
                                <span class="ml-2 bg-white/20 px-2 py-1 rounded-lg text-sm">({{ \App\Models\Order::where('status', $status)->count() }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <!-- Enhanced Table -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="overflow-x-auto custom-scrollbar" style="max-height: 600px;">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-50 to-blue-50 sticky top-0 z-10">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        <i class="fas fa-hashtag mr-2"></i>Order Number
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        <i class="fas fa-file-invoice mr-2"></i>Invoice
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        <i class="fas fa-user mr-2"></i>Customer
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        <i class="fas fa-tags mr-2"></i>Promo
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        <i class="fas fa-map-marker-alt mr-2"></i>Branch
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        <i class="fas fa-calendar mr-2"></i>Visit Date
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        <i class="fas fa-ticket-alt mr-2"></i>Quantity
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        <i class="fas fa-money-bill mr-2"></i>Total Price
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        <i class="fas fa-flag mr-2"></i>Status
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        <i class="fas fa-clock mr-2"></i>Order Date
                                    </th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                        <i class="fas fa-cogs mr-2"></i>Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($orders as $order)
                                <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-300 group">
                                    <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                                                    <i class="fas fa-receipt text-white text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $order->order_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        @if($order->invoice_number)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                                <i class="fas fa-file-invoice-dollar mr-1"></i>
                                                {{ $order->invoice_number }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                <i class="fas fa-clock mr-1"></i>
                                                Belum ada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-400 to-pink-400 flex items-center justify-center">
                                                    <i class="fas fa-user text-white text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <a href="https://wa.me/{{ $order->whatsapp_number }}" target="_blank" 
                                           class="inline-flex items-center px-3 py-2 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg transition-all duration-300 transform hover:scale-105 border border-green-200">
                                            <i class="fab fa-whatsapp text-lg mr-2"></i>
                                            <span class="font-medium">{{ $order->whatsapp_number }}</span>
                                        </a>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        @if($order->promo)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                                <i class="fas fa-percentage mr-1"></i>
                                                {{ $order->promo->name }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                                <i class="fas fa-times mr-1"></i>
                                                Promo tidak ditemukan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="fas fa-building text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-900 font-medium">{{ $order->branch }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-check text-blue-500 mr-2"></i>
                                            <span class="text-sm text-gray-900 font-medium">{{ $order->visit_date->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800 border border-gray-200">
                                            <i class="fas fa-ticket-alt mr-1"></i>
                                            {{ $order->ticket_quantity }} tiket
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="text-sm font-bold text-green-600">
                                            <i class="fas fa-rupiah-sign mr-1"></i>
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        @php
                                            $statusStyles = [
                                                'success' => 'bg-green-100 text-green-800 border-green-200',
                                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'canceled' => 'bg-red-100 text-red-800 border-red-200',
                                                'challenge' => 'bg-orange-100 text-orange-800 border-orange-200',
                                                'denied' => 'bg-red-100 text-red-800 border-red-200',
                                                'expired' => 'bg-gray-100 text-gray-800 border-gray-200'
                                            ];
                                            $statusIcons = [
                                                'success' => 'check-circle',
                                                'pending' => 'clock',
                                                'canceled' => 'times-circle',
                                                'challenge' => 'exclamation-triangle',
                                                'denied' => 'ban',
                                                'expired' => 'hourglass-end'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusStyles[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                            <i class="fas fa-{{ $statusIcons[$order->status] ?? 'question' }} mr-1"></i>
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-calendar-plus mr-2"></i>
                                            {{ $order->created_at->format('d M Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <!-- Detail Button -->
                                            <a href="{{ route('admin.tickets.show', $order->order_number) }}" 
                                               class="group relative bg-blue-500 hover:bg-blue-600 text-white p-2.5 rounded-lg transition-all duration-300 transform hover:scale-110 shadow-lg hover:shadow-xl" 
                                               title="Detail">
                                                <i class="fas fa-eye text-sm"></i>
                                                <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300">Detail</span>
                                            </a>
                                            
                                            <!-- Invoice Button -->
                                            <a href="{{ route('payment.invoice', $order->order_number) }}" 
                                               target="_blank"
                                               class="group relative bg-green-500 hover:bg-green-600 text-white p-2.5 rounded-lg transition-all duration-300 transform hover:scale-110 shadow-lg hover:shadow-xl" 
                                               title="Invoice">
                                                <i class="fas fa-file-invoice text-sm"></i>
                                                <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300">Invoice</span>
                                            </a>
                                            
                                            <!-- Edit Status Button -->
                                            <button type="button" 
                                                    class="group relative bg-yellow-500 hover:bg-yellow-600 text-white p-2.5 rounded-lg transition-all duration-300 transform hover:scale-110 shadow-lg hover:shadow-xl" 
                                                    onclick="openStatusModal('{{ $order->order_number }}', '{{ $order->status }}', {{ $order->id }})"
                                                    title="Ubah Status">
                                                <i class="fas fa-edit text-sm"></i>
                                                <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300">Edit</span>
                                            </button>
                                            
                                            <!-- Delete Button -->
                                            <form action="{{ route('admin.tickets.destroy', $order->order_number) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="group relative bg-red-500 hover:bg-red-600 text-white p-2.5 rounded-lg transition-all duration-300 transform hover:scale-110 shadow-lg hover:shadow-xl" 
                                                        onclick="return confirm('Hapus tiket ini?')" 
                                                        title="Hapus">
                                                    <i class="fas fa-trash text-sm"></i>
                                                    <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300">Hapus</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-500 mb-2">Tidak ada data tiket</h3>
                                            <p class="text-gray-400">Belum ada pemesanan tiket yang masuk</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Enhanced Pagination -->
                <div class="flex flex-col sm:flex-row items-center justify-between mt-8 bg-gray-50 px-6 py-4 rounded-xl">
                    <div class="flex items-center text-sm text-gray-700 mb-4 sm:mb-0">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Menampilkan <span class="font-medium text-blue-600">1</span> sampai <span class="font-medium text-blue-600">10</span> dari <span class="font-medium text-blue-600">142</span> hasil
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Status Change Modal -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 backdrop-blur-sm">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95" id="modalContent">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-edit mr-3"></i>
                        Ubah Status Tiket
                    </h3>
                    <button onclick="closeStatusModal()" class="text-white hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <form action="" method="POST" id="statusForm" class="p-8">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-flag mr-2 text-blue-500"></i>
                        Status Saat Ini:
                    </label>
                    <div id="currentStatusDisplay" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-green-100 text-green-800 border-2 border-green-200">
                        <i class="fas fa-check-circle mr-2"></i>
                        Success
                    </div>
                </div>
                <div class="mb-8">
                    <label for="newStatus" class="block text-sm font-bold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-exchange-alt mr-2 text-blue-500"></i>
                        Pilih Status Baru:
                    </label>
                    <div class="relative">
                        <select name="status" id="newStatus" required
                                class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 appearance-none">
                            <option value="pending">🕒 Pending</option>
                            <option value="success">✅ Success</option>
                            <option value="challenge">⚠️ Challenge</option>
                            <option value="denied">❌ Denied</option>
                            <option value="expired">⏰ Expired</option>
                            <option value="canceled">🚫 Canceled</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeStatusModal()" 
                            class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                        <i class="fas fa-save mr-2"></i>
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentOrderNumber = '';

    function openStatusModal(orderNumber, currentStatus, orderId) {
        currentOrderNumber = orderNumber;
        const modal = document.getElementById('statusModal');
        const form = document.getElementById('statusForm');
        const currentStatusDisplay = document.getElementById('currentStatusDisplay');
        const newStatusSelect = document.getElementById('newStatus');
        const modalContent = document.getElementById('modalContent');
        
        // Update form action
        form.action = `/admin/tickets/${orderNumber}/update-status`;
        
        // Update current status display
        const statusConfig = {
            'success': { class: 'bg-green-100 text-green-800 border-green-200', icon: 'check-circle', label: 'Success' },
            'pending': { class: 'bg-yellow-100 text-yellow-800 border-yellow-200', icon: 'clock', label: 'Pending' },
            'canceled': { class: 'bg-red-100 text-red-800 border-red-200', icon: 'times-circle', label: 'Canceled' },
            'challenge': { class: 'bg-orange-100 text-orange-800 border-orange-200', icon: 'exclamation-triangle', label: 'Challenge' },
            'denied': { class: 'bg-red-100 text-red-800 border-red-200', icon: 'ban', label: 'Denied' },
            'expired': { class: 'bg-gray-100 text-gray-800 border-gray-200', icon: 'hourglass-end', label: 'Expired' }
        };
        
        const config = statusConfig[currentStatus] || statusConfig['pending'];
        currentStatusDisplay.className = `inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold border-2 ${config.class}`;
        currentStatusDisplay.innerHTML = `<i class="fas fa-${config.icon} mr-2"></i>${config.label}`;
        
        // Set select value
        newStatusSelect.value = currentStatus;
        
        // Show modal with animation
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closeStatusModal() {
        const modal = document.getElementById('statusModal');
        const modalContent = document.getElementById('modalContent');
        
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Close modal when clicking outside
    document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStatusModal();
        }
    });

    // Auto refresh every 30 seconds
    setTimeout(function() {
        // window.location.reload();
        console.log('Auto refresh would happen here');
    }, 30000);

    // Add fade-in animation to table rows
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            setTimeout(() => {
                row.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });
    });

    // Search functionality enhancement
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            if (e.target.value.length > 0) {
                e.target.classList.add('ring-4', 'ring-blue-500/20', 'border-blue-500');
            } else {
                e.target.classList.remove('ring-4', 'ring-blue-500/20', 'border-blue-500');
            }
        });
    }

    // Status filter enhancement
    const filterButtons = document.querySelectorAll('a[href*="status="]');
    filterButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.classList.add('shadow-2xl');
        });
        
        button.addEventListener('mouseleave', function() {
            this.classList.remove('shadow-2xl');
        });
    });
</script>
@endsection