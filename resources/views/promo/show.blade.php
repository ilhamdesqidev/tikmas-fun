<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $promo->name }} - MestaKara</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,600;0,700;1,700&display=swap"
      rel="stylesheet"
    />
    <!-- feather icon -->
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: '#CFD916',
              'primary-dark': '#B8C214',
              'text-dark': '#333333',
            },
            fontFamily: {
              'poppins': ['Poppins', 'sans-serif'],
            },
          }
        }
      }
    </script>
    <style>
      html {
        scroll-behavior: smooth;
      }
      
      .hero-bg {
        background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("/assets/img/mainimg.jpg");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
      }
      
      @media (max-width: 768px) {
        .hero-bg {
          background-attachment: scroll;
        }
      }
      
      .prose ul {
        list-style-type: disc;
        padding-left: 1.5rem;
        margin-bottom: 1rem;
      }
      .prose li {
        margin-bottom: 0.5rem;
      }
      
      .card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
      }
      
      .card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
      }
      
      .modal {
        transition: opacity 0.25s ease;
      }
      .modal-active {
        overflow-x: hidden;
        overflow-y: visible !important;
      }
      
      .error-message {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: none;
      }
      
      .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.875rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
      }
      
      .info-item {
        display: flex;
        align-items: center;
        padding: 0.875rem;
        background: #f9fafb;
        border-radius: 12px;
        transition: all 0.2s ease;
      }
      
      .info-item:hover {
        background: #f3f4f6;
        transform: translateX(4px);
      }
      
      .price-card {
        background: linear-gradient(135deg, #CFD916 0%, #E8F35E 100%);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
      }
      
      .btn-primary {
        background: #CFD916;
        color: #000;
        font-weight: 600;
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(207, 217, 22, 0.3);
      }
      
      .btn-primary:hover {
        background: #B8C214;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(207, 217, 22, 0.4);
      }
      
      .btn-secondary {
        background: white;
        color: #374151;
        font-weight: 500;
        padding: 0.875rem 1.5rem;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
      }
      
      .btn-secondary:hover {
        border-color: #CFD916;
        background: #fafafa;
      }
      
      .progress-bar {
        height: 8px;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 9999px;
        overflow: hidden;
      }
      
      .progress-fill {
        height: 100%;
        background: #000;
        border-radius: 9999px;
        transition: width 0.5s ease;
      }
      
      .image-container {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
      }
      
      .image-container img {
        transition: transform 0.5s ease;
      }
      
      .image-container:hover img {
        transform: scale(1.05);
      }
      
      @media (max-width: 1024px) {
        .sticky {
          position: relative !important;
          top: 0 !important;
        }
      }
      
      .counter-btn {
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        user-select: none;
      }
      
      .counter-btn:hover {
        background: #CFD916;
        color: #000;
      }
      
      .counter-btn:active {
        transform: scale(0.95);
      }
      
      input[type="number"]::-webkit-inner-spin-button,
      input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
      
      input[type="number"] {
        -moz-appearance: textfield;
      }
    </style>
  </head>
  <body class="font-poppins bg-gray-50 text-text-dark">
    <!-- Navbar -->
    <nav class="w-full py-4 px-4 sm:px-8 flex items-center justify-between bg-white border-b-2 border-primary fixed top-0 left-0 right-0 z-50 shadow-sm">
      <a href="/" class="text-2xl sm:text-3xl font-bold text-black italic">
        Mesta<span class="text-primary">Kara</span>.
      </a>
      
      <!-- Desktop Navigation -->
      <div class="hidden md:flex items-center gap-1">
        <a href="/dashboard" class="text-black inline-block text-base font-medium px-4 py-2 hover:text-primary transition-all duration-300 relative group rounded-lg">
          Home
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="/#about" class="text-black inline-block text-base font-medium px-4 py-2 hover:text-primary transition-all duration-300 relative group rounded-lg">
          Tentang Kami
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="/#menu" class="text-black inline-block text-base font-medium px-4 py-2 hover:text-primary transition-all duration-300 relative group rounded-lg">
          Promo
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="{{route ('wahana.index') }}" class="text-black inline-block text-base font-medium px-4 py-2 hover:text-primary transition-all duration-300 relative group rounded-lg">
          Wahana
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
      </div>

      <!-- Navbar Extra -->
      <div class="flex items-center gap-2">
        <a href="#" class="text-black hover:text-primary transition-all duration-300 p-2 rounded-lg hover:bg-gray-100"><i data-feather="search" class="w-5 h-5"></i></a>
        <a href="#" class="text-black hover:text-primary transition-all duration-300 p-2 rounded-lg hover:bg-gray-100"><i data-feather="shopping-cart" class="w-5 h-5"></i></a>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-28 pb-20 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-8">
          <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="/dashboard" class="hover:text-primary transition-colors font-medium">Home</a></li>
            <li><i data-feather="chevron-right" class="w-4 h-4"></i></li>
            <li><a href="/#menu" class="hover:text-primary transition-colors font-medium">Promo</a></li>
            <li><i data-feather="chevron-right" class="w-4 h-4"></i></li>
            <li class="text-text-dark font-semibold">{{ Str::limit($promo->name, 30) }}</li>
          </ol>
        </nav>

        <div class="flex flex-col lg:flex-row gap-8">
          <!-- Bagian Kiri: Gambar dan Info Utama -->
          <div class="w-full lg:w-2/3 space-y-6">
            <!-- Image Card -->
            <div class="card p-4">
              <div class="image-container">
                <img src="{{ asset('storage/' . $promo->image) }}" alt="{{ $promo->name }}" class="w-full h-72 sm:h-96 object-cover">
              </div>
            </div>
            
            <!-- Description Card -->
            <div class="card p-6 sm:p-8">
              <div class="flex items-center gap-3 mb-4">
                <div class="w-1 h-8 bg-primary rounded-full"></div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Deskripsi Promo</h2>
              </div>
              <div class="prose max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($promo->description)) !!}
              </div>
            </div>
            
            <!-- Terms & Conditions Card -->
            <div class="card p-6 sm:p-8">
              <div class="flex items-center gap-3 mb-4">
                <div class="w-1 h-8 bg-primary rounded-full"></div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Syarat dan Ketentuan</h2>
              </div>
              <div class="prose max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($promo->terms_conditions)) !!}
              </div>
            </div>
          </div>
          
          <!-- Bagian Kanan: Info Samping -->
          <div class="w-full lg:w-1/3">
            <div class="card p-6 sticky top-28 space-y-6">
              <!-- Badges -->
              <div class="flex items-center gap-2 flex-wrap">
                <span class="badge bg-primary bg-opacity-20 text-primary">
                  <i data-feather="tag" class="w-4 h-4 mr-1"></i>
                  {{ ucfirst($promo->category) }}
                </span>
                @if($promo->featured)
                <span class="badge bg-gradient-to-r from-yellow-400 to-yellow-500 text-white">
                  <i data-feather="star" class="w-4 h-4 mr-1"></i>
                  Unggulan
                </span>
                @endif
              </div>
              
              <!-- Title -->
              <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">{{ $promo->name }}</h1>
              </div>
              
              <!-- Price Card -->
              <div class="price-card">
                <div class="flex items-center justify-between mb-3">
                  <div>
                    <p class="text-sm text-black opacity-60 mb-1">Harga Normal</p>
                    <span class="text-black text-lg line-through font-medium">Rp {{ number_format($promo->original_price, 0, ',', '.') }}</span>
                  </div>
                  <div class="bg-black bg-opacity-20 rounded-xl px-4 py-2">
                    <span class="text-black font-bold text-lg">{{ $promo->discount_percent }}%</span>
                  </div>
                </div>
                <div class="flex items-baseline gap-2">
                  <span class="text-sm text-black opacity-80 font-medium">Harga Promo</span>
                </div>
                <div class="text-4xl font-bold text-black">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</div>
              </div>
              
              <!-- Info Details -->
              <div class="space-y-3">
                <div class="info-item">
                  <i data-feather="calendar" class="w-5 h-5 mr-3 text-primary"></i>
                  <div class="flex-1">
                    <p class="text-xs text-gray-500 mb-0.5">Periode Mulai</p>
                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($promo->start_date)->format('d M Y') }}</p>
                  </div>
                </div>
                
                @if($promo->end_date)
                <div class="info-item">
                  <i data-feather="calendar" class="w-5 h-5 mr-3 text-primary"></i>
                  <div class="flex-1">
                    <p class="text-xs text-gray-500 mb-0.5">Periode Berakhir</p>
                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($promo->end_date)->format('d M Y') }}</p>
                  </div>
                </div>
                @endif
                
                <div class="info-item">
                  <i data-feather="users" class="w-5 h-5 mr-3 text-primary"></i>
                  <div class="flex-1">
                    <p class="text-xs text-gray-500 mb-0.5">Total Terjual</p>
                    <p class="font-semibold text-gray-900">{{ $promo->actual_sold_count }} Tiket</p>
                  </div>
                </div>
                
                @if($promo->quota)
                <div class="info-item">
                  <i data-feather="box" class="w-5 h-5 mr-3 text-primary"></i>
                  <div class="flex-1">
                    <p class="text-xs text-gray-500 mb-0.5">Kuota Tersedia</p>
                    <p class="font-semibold text-gray-900">{{ $promo->quota }} Tiket</p>
                  </div>
                </div>
                @endif
              </div>
              
              <!-- Progress Bar -->
              @if($promo->quota)
              <div>
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                  <span class="font-medium">Tersisa</span>
                  <span class="font-bold text-primary">{{ $promo->quota - $promo->sold_count }} dari {{ $promo->quota }}</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill" style="width: {{ min(100, ($promo->sold_count / $promo->quota) * 100) }}%"></div>
                </div>
              </div>
              @endif
              
              <!-- Action Buttons -->
              <div class="space-y-3 pt-4">
                @if($promo->quota && $promo->sold_count >= $promo->quota)
                  <button class="w-full bg-gray-400 text-white py-4 rounded-xl font-semibold cursor-not-allowed flex items-center justify-center gap-2">
                    <i data-feather="x-circle" class="w-5 h-5"></i>
                    Promo Habis
                  </button>
                @else
                  <button id="checkout-btn" class="btn-primary w-full flex items-center justify-center gap-2">
                    <i data-feather="shopping-bag" class="w-5 h-5"></i>
                    Checkout Sekarang
                  </button>
                @endif
                
                <a href="/#menu" class="btn-secondary w-full flex items-center justify-center gap-2">
                  <i data-feather="arrow-left" class="w-5 h-5"></i>
                  Kembali ke Promo
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal Form Checkout -->
    <div id="checkout-modal" class="modal fixed inset-0 w-full h-full flex items-center justify-center z-50 opacity-0 invisible transition-opacity duration-300">
      <div class="modal-overlay absolute inset-0 bg-black opacity-50"></div>
      
      <div class="modal-container bg-white w-11/12 md:max-w-2xl mx-auto rounded-2xl shadow-2xl z-50 overflow-y-auto max-h-[90vh]">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-gradient-to-r from-primary to-yellow-400">
            <h3 class="text-2xl font-bold text-black">Form Pemesanan Tiket</h3>
            <button id="modal-close" class="text-black hover:text-gray-700 transition-colors">
              <i data-feather="x" class="w-6 h-6"></i>
            </button>
          </div>
          
          <!-- Modal Body -->
          <div class="p-6">
            <form id="checkout-form" action="{{ route('checkout.process', $promo->id) }}" method="POST" class="space-y-5">
              @csrf
              
              <!-- No Pemesanan -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">No. Pemesanan</label>
                <input type="text" id="order-number" class="w-full px-4 py-3 bg-gray-100 rounded-lg font-mono text-lg font-semibold" readonly>
              </div>
              
              <!-- Nama Pemesan -->
              <div>
                <label for="customer-name" class="block text-sm font-semibold text-gray-700 mb-2">
                  Nama Pemesan <span class="text-red-500">*</span>
                </label>
                <input type="text" id="customer-name" name="customer_name" 
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all" 
                  placeholder="Masukkan nama lengkap" required>
              </div>
              
              <!-- No WhatsApp -->
              <div>
                <label for="whatsapp-display" class="block text-sm font-semibold text-gray-700 mb-2">
                  No. WhatsApp <span class="text-red-500">*</span>
                </label>
                <div class="flex">
                  <span class="inline-flex items-center px-4 text-base font-semibold text-gray-900 bg-gray-200 border-2 border-r-0 border-gray-200 rounded-l-lg">
                    +62
                  </span>
                  <input type="text" id="whatsapp-display"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-r-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                    placeholder="81234567890" pattern="[1-9][0-9]{8,11}" inputmode="numeric" required>
                  <input type="hidden" id="whatsapp-number" name="whatsapp_number">
                </div>
                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                  <i data-feather="info" class="w-3 h-3"></i>
                  Contoh: 81234567890 (tanpa 0 di depan)
                </p>
              </div>
              
              <!-- Cabang -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Unit <span class="text-red-500">*</span></label>
                <input type="text" value="Agrowisata Gunung Mas" class="w-full px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-lg font-medium" readonly>
                <input type="hidden" id="branch" name="branch" value="Agrowisata Gunung Mas">
              </div>
              
              <!-- Tanggal Kunjungan -->
              <div>
                <label for="visit-date" class="block text-sm font-semibold text-gray-700 mb-2">
                  Tanggal Kunjungan <span class="text-red-500">*</span>
                </label>
                <input type="date" id="visit-date" name="visit_date" 
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition-all" required>
                <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                  <i data-feather="calendar" class="w-3 h-3"></i>
                  Pilih tanggal: {{ \Carbon\Carbon::parse($promo->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($promo->end_date)->format('d M Y') }}
                </p>
                <p id="date-error" class="error-message flex items-center gap-1">
                  <i data-feather="alert-circle" class="w-4 h-4"></i>
                  Tanggal yang dipilih harus dalam periode promo.
                </p>
              </div>
              
              <!-- Jumlah Tiket -->
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Jumlah Tiket <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center justify-center gap-4 bg-gray-50 p-4 rounded-lg">
                  <button type="button" id="decrement" class="counter-btn">
                    <i data-feather="minus" class="w-5 h-5"></i>
                  </button>
                  <input type="number" id="ticket-quantity" name="ticket_quantity" min="1" value="1" 
                    class="w-20 text-center text-2xl font-bold border-2 border-gray-200 rounded-lg py-2" required>
                  <button type="button" id="increment" class="counter-btn">
                    <i data-feather="plus" class="w-5 h-5"></i>
                  </button>
                </div>
              </div>
              
              <!-- Informasi Harga -->
              <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-xl border-2 border-gray-200">
                <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-300">
                  <span class="text-gray-600 font-medium">Harga per tiket:</span>
                  <span class="font-bold text-lg" id="price-per-ticket">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-lg font-bold text-gray-900">Total Harga:</span>
                  <span class="text-3xl font-bold text-primary" id="total-price">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</span>
                </div>
              </div>
              
              <!-- Modal Footer -->
              <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                <button id="cancel-btn" type="button" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-semibold">
                  Batal
                </button>
                <button type="submit" class="btn-primary flex items-center justify-center gap-2">
                  <i data-feather="check-circle" class="w-5 h-5"></i>
                  Beli Sekarang
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-900 to-black text-white pt-12 pb-8">
      <div class="container mx-auto px-4 sm:px-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-12">
          <!-- Brand -->
          <div class="mb-8 md:mb-0 text-center md:text-left">
            <h3 class="text-3xl font-bold italic mb-4">
              Mesta<span class="text-primary">Kara</span>.
            </h3>
            <p class="max-w-xs text-base opacity-90 leading-relaxed">
              Menyajikan wahana menyenangkan dengan keseruan yang tak terlupakan.
            </p>
          </div>
          
          <!-- Quick Links -->
          <div class="mb-8 md:mb-0">
            <h4 class="text-xl font-semibold mb-6 text-center md:text-left">Tautan Cepat</h4>
            <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-8">
              <a href="/#home" class="hover:text-primary transition-colors duration-300 text-base font-medium">Home</a>
              <a href="/#about" class="hover:text-primary transition-colors duration-300 text-base font-medium">Tentang Kami</a>
              <a href="/#menu" class="hover:text-primary transition-colors duration-300 text-base font-medium">Promo</a>
            </div>
          </div>
          
          <!-- Social Media -->
          <div>
            <h4 class="text-xl font-semibold mb-6 text-center md:text-left">Ikuti Kami</h4>
            <div class="flex justify-center md:justify-start space-x-4">
              <a href="https://www.instagram.com/wisataagro8/?hl=id" class="bg-white bg-opacity-10 p-3 rounded-full hover:bg-primary hover:bg-opacity-100 transition-all duration-300 group">
                <i data-feather="instagram" class="w-6 h-6 group-hover:text-black"></i>
              </a>
              <a href="https://twitter.com/agrowisata_n8" class="bg-white bg-opacity-10 p-3 rounded-full hover:bg-primary hover:bg-opacity-100 transition-all duration-300 group">
                <i data-feather="twitter" class="w-6 h-6 group-hover:text-black"></i>
              </a>
              <a href="https://www.facebook.com/AgrowisataN8/" class="bg-white bg-opacity-10 p-3 rounded-full hover:bg-primary hover:bg-opacity-100 transition-all duration-300 group">
                <i data-feather="facebook" class="w-6 h-6 group-hover:text-black"></i>
              </a>
            </div>
          </div>
        </div>
        
        <div class="border-t border-white border-opacity-20 my-8"></div>
        
        <!-- Copyright -->
        <div class="flex flex-col md:flex-row justify-between items-center text-center md:text-left">
          <p class="text-sm opacity-80 mb-3 md:mb-0">
            &copy; 2025 Tiketmas. All rights reserved.
          </p>
          <p class="text-sm opacity-80">
            Created by <a href="#" class="font-bold hover:text-primary transition-colors">Mestakara</a>
          </p>
        </div>
      </div>
    </footer>

    <script>
      // Initialize Feather icons
      feather.replace();

      // Generate order number function dengan urutan
      let orderCounter = 1;
      
      function generateOrderNumber() {
        const now = new Date();
        const year = now.getFullYear().toString().substr(-2);
        const month = (now.getMonth() + 1).toString().padStart(2, '0');
        const day = now.getDate().toString().padStart(2, '0');
        
        // Format counter dengan 2 digit (01, 02, ...)
        const sequentialNum = orderCounter.toString().padStart(2, '0');
        
        // Increment counter
        orderCounter++;
        
        return `MK${year}${month}${day}${sequentialNum}`;
      }

      // Modal functionality
      const modal = document.getElementById('checkout-modal');
      const checkoutBtn = document.getElementById('checkout-btn');
      const closeModalBtn = document.getElementById('modal-close');
      const cancelBtn = document.getElementById('cancel-btn');
      const orderNumberField = document.getElementById('order-number');
      const ticketQuantity = document.getElementById('ticket-quantity');
      const totalPriceElement = document.getElementById('total-price');
      const visitDateField = document.getElementById('visit-date');
      const dateError = document.getElementById('date-error');
      const whatsappDisplay = document.getElementById('whatsapp-display');
      const whatsappInput = document.getElementById('whatsapp-number');
      const pricePerTicket = {{ $promo->promo_price }};
      
      // Format number to Rupiah
      function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', { 
          style: 'currency', 
          currency: 'IDR',
          minimumFractionDigits: 0 
        }).format(amount);
      }
      
      // Validasi input WhatsApp - hanya angka dan tidak bisa diawali 0
      whatsappDisplay.addEventListener('input', function(e) {
        // Hapus semua karakter non-digit
        let value = e.target.value.replace(/\D/g, '');
        
        // Jika diawali 0, hapus 0 tersebut
        if (value.startsWith('0')) {
          value = value.substring(1);
        }
        
        // Update display input value
        e.target.value = value;
        
        // Update hidden input dengan format 62 + nomor (ini yang akan dikirim ke server)
        if (value) {
          whatsappInput.value = '62' + value;
        } else {
          whatsappInput.value = '';
        }
      });
      
      // Prevent paste dengan angka 0 di depan
      whatsappDisplay.addEventListener('paste', function(e) {
        e.preventDefault();
        let pastedText = (e.clipboardData || window.clipboardData).getData('text');
        let cleanedText = pastedText.replace(/\D/g, '');
        
        // Hapus 0 di depan jika ada
        if (cleanedText.startsWith('0')) {
          cleanedText = cleanedText.substring(1);
        }
        
        e.target.value = cleanedText;
        whatsappInput.value = cleanedText ? '62' + cleanedText : '';
      });
      
      // Set min and max date for visit date based on promo period
      function setVisitDateRange() {
        // Get promo dates from PHP variables
        const startDate = new Date('{{ $promo->start_date }}');
        const endDate = new Date('{{ $promo->end_date }}');
        
        // Format dates to YYYY-MM-DD for input[type="date"]
        const formatDate = (date) => {
          const year = date.getFullYear();
          const month = (date.getMonth() + 1).toString().padStart(2, '0');
          const day = date.getDate().toString().padStart(2, '0');
          return `${year}-${month}-${day}`;
        };
        
        // Set min and max attributes
        visitDateField.min = formatDate(startDate);
        visitDateField.max = formatDate(endDate);
        
        // Set default value to today if within range, otherwise set to start date
        const today = new Date();
        if (today >= startDate && today <= endDate) {
          visitDateField.value = formatDate(today);
        } else {
          visitDateField.value = formatDate(startDate);
        }
      }
      
      // Validate selected date is within promo period
      function validateVisitDate() {
        const selectedDate = new Date(visitDateField.value);
        const startDate = new Date('{{ $promo->start_date }}');
        const endDate = new Date('{{ $promo->end_date }}');
        
        // Reset end of day for endDate to include the entire last day
        endDate.setHours(23, 59, 59, 999);
        
        if (selectedDate < startDate || selectedDate > endDate) {
          dateError.style.display = 'flex';
          visitDateField.classList.add('border-red-500');
          return false;
        } else {
          dateError.style.display = 'none';
          visitDateField.classList.remove('border-red-500');
          return true;
        }
      }
      
      // Calculate total price
      function calculateTotalPrice() {
        const quantity = parseInt(ticketQuantity.value) || 1;
        const total = quantity * pricePerTicket;
        totalPriceElement.textContent = formatRupiah(total);
      }
      
      // Show modal
      function showModal() {
        orderNumberField.value = generateOrderNumber();
        setVisitDateRange();
        calculateTotalPrice();
        modal.classList.remove('invisible');
        setTimeout(() => {
          modal.classList.add('opacity-100');
          document.body.classList.add('modal-active');
        }, 10);
        // Re-initialize feather icons in modal
        feather.replace();
      }
      
      // Hide modal
      function hideModal() {
        modal.classList.remove('opacity-100');
        setTimeout(() => {
          modal.classList.add('invisible');
          document.body.classList.remove('modal-active');
        }, 300);
      }
      
      // Event listeners
      checkoutBtn?.addEventListener('click', showModal);
      closeModalBtn.addEventListener('click', hideModal);
      cancelBtn.addEventListener('click', hideModal);
      
      // Close modal when clicking outside
      modal.addEventListener('click', (e) => {
        if (e.target === modal) hideModal();
      });
      
      // Update total price when quantity changes
      ticketQuantity.addEventListener('input', calculateTotalPrice);
      
      // Validate date when changed
      visitDateField.addEventListener('change', validateVisitDate);
      
      // Form submission handler
      document.getElementById('checkout-form').addEventListener('submit', function(e) {
        // Validate date first
        if (!validateVisitDate()) {
          e.preventDefault();
          alert('Tanggal kunjungan tidak valid. Silakan pilih tanggal dalam periode promo.');
          return;
        }
        
        // Validate WhatsApp number
        const whatsappValue = whatsappDisplay.value;
        if (!whatsappValue || whatsappValue.length < 9) {
          e.preventDefault();
          alert('Nomor WhatsApp tidak valid. Minimal 9 digit dan tidak boleh diawali 0.');
          whatsappDisplay.focus();
          return;
        }
        
        // Form akan di-submit secara normal ke action yang telah ditentukan
      });

      // Smooth scrolling untuk anchor links
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const targetId = this.getAttribute('href');
          if (targetId === '#') return;
          
          const target = document.querySelector(targetId);
          if (target) {
            const navbarHeight = document.querySelector('nav').offsetHeight;
            const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
            
            window.scrollTo({
              top: targetPosition,
              behavior: 'smooth'
            });
          }
        });
      });

      // Increment and Decrement buttons for ticket quantity
      const incrementBtn = document.getElementById('increment');
      const decrementBtn = document.getElementById('decrement');

      incrementBtn.addEventListener('click', () => {
        ticketQuantity.value = parseInt(ticketQuantity.value) + 1;
        calculateTotalPrice();
        feather.replace();
      });

      decrementBtn.addEventListener('click', () => {
        let current = parseInt(ticketQuantity.value);
        if (current > 1) {
          ticketQuantity.value = current - 1;
          calculateTotalPrice();
        }
        feather.replace();
      });

      // Navbar scroll effect
      let lastScroll = 0;
      const navbar = document.querySelector('nav');
      
      window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll <= 0) {
          navbar.classList.remove('shadow-md');
          navbar.classList.add('shadow-sm');
        } else {
          navbar.classList.remove('shadow-sm');
          navbar.classList.add('shadow-md');
        }
        
        lastScroll = currentScroll;
      });

      // Add animation on scroll for cards
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }
        });
      }, observerOptions);

      document.querySelectorAll('.card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
      });
    </script>
  </body>
</html>