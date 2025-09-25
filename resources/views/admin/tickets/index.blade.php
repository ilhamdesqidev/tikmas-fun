@extends('layouts.app')

@section('title', 'Manajemen Tiket')
@section('page-title', 'Manajemen Tiket')
@section('page-description', 'Kelola semua pemesanan tiket di sini.')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header Section -->
    <div class="border-b border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Manajemen Tiket</h1>
                    <p class="text-gray-600 mt-1">Kelola semua pemesanan tiket</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Total Orders</p>
                    <p class="text-xl font-semibold text-gray-900">142</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-6">
        
        <!-- Search & Filter -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Search -->
                <form method="GET" action="{{ route('admin.tickets.index') }}" class="flex items-center gap-3">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari order atau customer..." 
                           class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 w-80">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Cari
                    </button>
                </form>

                <!-- Status Filter Dropdown -->
                <div class="relative">
                    <button type="button" 
                            id="filterDropdownButton"
                            onclick="toggleFilterDropdown()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg flex items-center gap-2 min-w-[140px]">
                        <i class="fas fa-filter text-sm"></i>
                        <span id="filterButtonText">
                            @if(request('status'))
                                @php
                                    $currentStatus = request('status');
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'success' => 'Success',
                                        'challenge' => 'Challenge',
                                        'denied' => 'Denied',
                                        'expired' => 'Expired',
                                        'canceled' => 'Canceled'
                                    ];
                                @endphp
                                {{ $statusLabels[$currentStatus] ?? ucfirst($currentStatus) }}
                            @else
                                Filter Status
                            @endif
                        </span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" id="filterChevron"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="filterDropdown" 
                         class="absolute right-0 mt-1 w-56 bg-white border border-gray-200 rounded-lg shadow-lg z-10 hidden">
                        <div class="py-2">
                            <!-- All Orders Option -->
                            <a href="{{ route('admin.tickets.index') }}" 
                               class="block px-4 py-2 text-sm {{ !request('status') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                <div class="flex items-center justify-between">
                                    <span>Semua Status</span>
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                        {{ \App\Models\Order::count() }}
                                    </span>
                                </div>
                            </a>

                            <hr class="my-1">

                            <!-- Status Options -->
                            @php
                                $statuses = [
                                    'pending' => ['label' => 'Pending', 'color' => 'yellow'],
                                    'success' => ['label' => 'Success', 'color' => 'green'],
                                    'challenge' => ['label' => 'Challenge', 'color' => 'orange'],
                                    'denied' => ['label' => 'Denied', 'color' => 'red'],
                                    'expired' => ['label' => 'Expired', 'color' => 'gray'],
                                    'canceled' => ['label' => 'Canceled', 'color' => 'red']
                                ];
                            @endphp
                            
                            @foreach($statuses as $status => $config)
                                <a href="{{ route('admin.tickets.index', ['status' => $status]) }}" 
                                   class="block px-4 py-2 text-sm {{ request('status') == $status ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-{{ $config['color'] }}-500"></div>
                                            <span>{{ $config['label'] }}</span>
                                        </div>
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                            {{ \App\Models\Order::where('status', $status)->count() }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Filter Display -->
            @if(request('status'))
                <div class="mt-3 flex items-center gap-2">
                    <span class="text-sm text-gray-600">Filter aktif:</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full flex items-center gap-2">
                        {{ $statusLabels[request('status')] ?? ucfirst(request('status')) }}
                        <a href="{{ route('admin.tickets.index') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                    </span>
                </div>
            @endif
        </div>

        <!-- Table -->
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">WhatsApp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Visit Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                                @if($order->invoice_number)
                                    <div class="text-xs text-blue-600">{{ $order->invoice_number }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="https://wa.me/{{ $order->whatsapp_number }}" target="_blank" 
                                   class="text-sm text-green-600 hover:text-green-800">
                                    {{ $order->whatsapp_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $order->branch }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $order->visit_date->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $order->ticket_quantity }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'success' => 'text-green-700 bg-green-50',
                                        'pending' => 'text-yellow-700 bg-yellow-50',
                                        'canceled' => 'text-red-700 bg-red-50',
                                        'challenge' => 'text-orange-700 bg-orange-50',
                                        'denied' => 'text-red-700 bg-red-50',
                                        'expired' => 'text-gray-700 bg-gray-50'
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$order->status] ?? 'text-gray-700 bg-gray-50' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-inbox text-2xl mb-2"></i>
                                    <p>Tidak ada data tiket</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="flex items-center justify-between mt-6 text-sm text-gray-700">
            <div>
                Menampilkan 1 sampai 10 dari 142 hasil
            </div>
            <div>
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Status Change Modal -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium">Ubah Status</h3>
                    <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form action="" method="POST" id="statusForm" class="p-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Saat Ini</label>
                    <div id="currentStatusDisplay" class="text-sm text-gray-600">Success</div>
                </div>
                <div class="mb-6">
                    <label for="newStatus" class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                    <select name="status" id="newStatus" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="pending">Pending</option>
                        <option value="success">Success</option>
                        <option value="challenge">Challenge</option>
                        <option value="denied">Denied</option>
                        <option value="expired">Expired</option>
                        <option value="canceled">Canceled</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeStatusModal()" 
                            class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Filter dropdown functionality
    function toggleFilterDropdown() {
        const dropdown = document.getElementById('filterDropdown');
        const chevron = document.getElementById('filterChevron');
        
        dropdown.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const button = document.getElementById('filterDropdownButton');
        const dropdown = document.getElementById('filterDropdown');
        const chevron = document.getElementById('filterChevron');
        
        if (!button.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
            chevron.classList.remove('rotate-180');
        }
    });

    // Status modal functionality
    function openStatusModal(orderNumber, currentStatus, orderId) {
        const modal = document.getElementById('statusModal');
        const form = document.getElementById('statusForm');
        const currentStatusDisplay = document.getElementById('currentStatusDisplay');
        const newStatusSelect = document.getElementById('newStatus');
        
        form.action = `/admin/tickets/${orderNumber}/update-status`;
        currentStatusDisplay.textContent = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
        newStatusSelect.value = currentStatus;
        
        modal.classList.remove('hidden');
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeStatusModal();
        }
    });
</script>
@endsection