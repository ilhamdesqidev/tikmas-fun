@extends('layouts.admin')

@section('title', 'Management Voucher')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Management Voucher</h1>
        <p class="text-gray-600">Kelola voucher dan promo yang tersedia</p>
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-700">Daftar Voucher</h2>
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                + Tambah Voucher
            </button>
        </div>

        <!-- Isi konten tabel di sini -->
        <div class="text-center py-8 text-gray-500">
            Konten voucher akan ditampilkan di sini
        </div>
    </div>
</div>
@endsection