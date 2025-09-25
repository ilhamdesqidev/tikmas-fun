@extends('layouts.app')

@section('title', 'Detail Tiket - ' . $order->order_number)
@section('page-title', 'Detail Tiket')
@section('page-description', 'Detail informasi pemesanan tiket.')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pemesanan</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Order Number</th>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <th>Invoice Number</th>
                                    <td>
                                        @if($order->invoice_number)
                                            {{ $order->invoice_number }}
                                        @else
                                            <span class="text-warning">Belum generate</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Customer Name</th>
                                    <td>{{ $order->customer_name }}</td>
                                </tr>
                                <tr>
                                    <th>WhatsApp Number</th>
                                    <td>
                                        <a href="https://wa.me/{{ $order->whatsapp_number }}" 
                                           target="_blank" class="btn btn-sm btn-success">
                                            <i class="fab fa-whatsapp"></i> {{ $order->whatsapp_number }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Promo</th>
                                    <td>
                                        @if($order->promo)
                                            {{ $order->promo->name }} - Rp {{ number_format($order->promo->promo_price, 0, ',', '.') }}
                                        @else
                                            <span class="text-danger">Promo tidak ditemukan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Branch</th>
                                    <td>{{ $order->branch }}</td>
                                </tr>
                                <tr>
                                    <th>Visit Date</th>
                                    <td>{{ $order->visit_date->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Ticket Quantity</th>
                                    <td><span class="badge badge-primary">{{ $order->ticket_quantity }} tiket</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-center text-muted">Total Harga</span>
                                    <span class="info-box-number text-center text-primary mb-0">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status & Aksi</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Status Pembayaran:</label>
                        <span class="badge badge-{{ $order->status == 'success' ? 'success' : 
                               ($order->status == 'pending' ? 'warning' : 
                               ($order->status == 'canceled' ? 'danger' : 'secondary')) }} badge-lg">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <div class="form-group">
                        <label>Tanggal Pemesanan:</label>
                        <p>{{ $order->created_at->format('d F Y H:i:s') }}</p>
                    </div>
                    
                    <div class="form-group">
                        <label>Terakhir Update:</label>
                        <p>{{ $order->updated_at->format('d F Y H:i:s') }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="btn-group-vertical w-100">
                        <a href="{{ route('payment.invoice', $order->order_number) }}" 
                           target="_blank" class="btn btn-primary mb-2">
                            <i class="fas fa-file-invoice"></i> Lihat Invoice
                        </a>
                        
                        <button type="button" class="btn btn-warning mb-2" 
                                data-toggle="modal" data-target="#statusModal">
                            <i class="fas fa-edit"></i> Ubah Status
                        </button>
                        
                        <a href="https://wa.me/{{ $order->whatsapp_number }}?text=Halo%20{{ urlencode($order->customer_name) }}%2C%20terkait%20pemesanan%20tiket%20{{ $order->order_number }}"
                           target="_blank" class="btn btn-success mb-2">
                            <i class="fab fa-whatsapp"></i> Hubungi via WhatsApp
                        </a>
                        
                        <form action="{{ route('admin.tickets.destroy', $order->order_number) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Hapus tiket ini?')">
                                <i class="fas fa-trash"></i> Hapus Tiket
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Status -->
<div class="modal fade" id="statusModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ubah Status Tiket</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('admin.tickets.update-status', $order->order_number) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">Pilih Status Baru:</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="success" {{ $order->status == 'success' ? 'selected' : '' }}>Success</option>
                            <option value="challenge" {{ $order->status == 'challenge' ? 'selected' : '' }}>Challenge</option>
                            <option value="denied" {{ $order->status == 'denied' ? 'selected' : '' }}>Denied</option>
                            <option value="expired" {{ $order->status == 'expired' ? 'selected' : '' }}>Expired</option>
                            <option value="canceled" {{ $order->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection