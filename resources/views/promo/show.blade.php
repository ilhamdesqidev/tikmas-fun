<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $promo->name }} - MestaKara</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
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
      
      /* Mobile optimization */
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
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      }
      
      /* Modal styles */
      .modal {
        transition: opacity 0.25s ease;
      }
      .modal-active {
        overflow-x: hidden;
        overflow-y: visible !important;
      }
      
      /* Error message style */
      .error-message {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: none;
      }
      
      /* Alert animation */
      @keyframes slideDown {
        from {
          opacity: 0;
          transform: translateY(-20px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      
      .alert-animate {
        animation: slideDown 0.3s ease-out;
      }
    </style>
  </head>
  <body class="font-poppins bg-gray-50 text-text-dark">
    <!-- Navbar -->
    <nav class="w-full py-3 sm:py-5 px-4 sm:px-7 flex items-center justify-between bg-white border-b border-gray-400 fixed top-0 left-0 right-0 z-50" style="border-bottom: 1px solid #597336;">
      <a href="/" class="text-2xl sm:text-3xl font-bold text-black italic">
        Mesta<span class="text-primary">Kara</span>.
      </a>
      
      <!-- Desktop Navigation -->
      <div class="hidden md:flex mr-72">
        <a href="/dashboard" class="text-black inline-block text-xl ml-0 px-4 hover:text-primary transition-all duration-500 relative group">
          Home
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="/#about" class="text-black inline-block text-xl ml-0 px-4 hover:text-primary transition-all duration-500 relative group">
          Tentang Kami
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="/#menu" class="text-black inline-block text-xl ml-0 px-4 hover:text-primary transition-all duration-500 relative group">
          Promo
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
         <a href="{{route ('wahana.index') }}" class="text-black inline-block text-xl ml-0 px-4 hover:text-primary transition-all duration-500 relative group">
          Wahana
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-24 pb-16 px-4 sm:px-7">
      <div class="max-w-6xl mx-auto">
        
        <!-- Alert Error -->
        @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative alert-animate" role="alert">
          <strong class="font-bold">Error!</strong>
          <span class="block sm:inline ml-2">{{ session('error') }}</span>
          <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.remove()">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
              <title>Close</title>
              <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
          </span>
        </div>
        @endif

        <!-- Alert Success -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative alert-animate" role="alert">
          <strong class="font-bold">Sukses!</strong>
          <span class="block sm:inline ml-2">{{ session('success') }}</span>
          <span class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer" onclick="this.parentElement.remove()">
            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
              <title>Close</title>
              <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
          </span>
        </div>
        @endif
        
        <!-- Breadcrumb -->
        <nav class="mb-6">
          <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="/dashboard" class="hover:text-primary transition-colors">Home</a></li>
            <li><i data-feather="chevron-right" class="w-4 h-4"></i></li>
            <li><a href="/#menu" class="hover:text-primary transition-colors">Promo</a></li>
            <li><i data-feather="chevron-right" class="w-4 h-4"></i></li>
            <li class="text-text-dark">{{ Str::limit($promo->name, 30) }}</li>
          </ol>
        </nav>

        <div class="flex flex-col lg:flex-row gap-6">
          <!-- Bagian Kiri: Gambar dan Info Utama -->
          <div class="w-full lg:w-2/3">
            <div class="card rounded-xl overflow-hidden mb-6">
              <img src="{{ asset('storage/' . $promo->image) }}" alt="{{ $promo->name }}" class="w-full h-64 object-cover">
            </div>
            
            <div class="card rounded-xl p-6 mb-6">
              <h2 class="text-2xl font-bold text-gray-900 mb-4">Deskripsi Promo</h2>
              <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($promo->description)) !!}
              </div>
            </div>
            
            <div class="card rounded-xl p-6">
              <h2 class="text-2xl font-bold text-gray-900 mb-4">Syarat dan Ketentuan</h2>
              <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($promo->terms_conditions)) !!}
              </div>
            </div>
          </div>
          
          <!-- Bagian Kanan: Info Samping -->
          <div class="w-full lg:w-1/3">
            <div class="card rounded-xl p-6 sticky top-24">
              <div class="flex items-center justify-between mb-4">
                <span class="bg-primary bg-opacity-20 text-primary px-3 py-1 rounded-full text-sm font-medium">
                  {{ ucfirst($promo->category) }}
                </span>
                @if($promo->featured)
                <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-medium">Unggulan</span>
                @endif
              </div>
              
              <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $promo->name }}</h1>
              
              <div class="flex items-center justify-between mb-6">
                <div>
                  <span class="text-gray-400 text-sm line-through">Rp {{ number_format($promo->original_price, 0, ',', '.') }}</span>
                  <div class="text-3xl font-bold text-primary">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</div>
                </div>
                <div class="text-right">
                  <div class="bg-primary bg-opacity-20 rounded-lg px-3 py-2">
                    <span class="text-primary font-bold">{{ $promo->discount_percent }}% OFF</span>
                  </div>
                </div>
              </div>
              
              <div class="space-y-4 mb-6">
                <div class="flex items-center text-sm text-gray-600">
                  <i data-feather="calendar" class="w-4 h-4 mr-2"></i>
                  <span>Mulai: <strong>{{ \Carbon\Carbon::parse($promo->start_date)->format('d M Y') }}</strong></span>
                </div>
                
                @if($promo->end_date)
                <div class="flex items-center text-sm text-gray-600">
                  <i data-feather="calendar" class="w-4 h-4 mr-2"></i>
                  <span>Berakhir: <strong>{{ \Carbon\Carbon::parse($promo->end_date)->format('d M Y') }}</strong></span>
                </div>
                @endif
                
                <div class="flex items-center text-sm text-gray-600">
                    <i data-feather="users" class="w-4 h-4 mr-2"></i>
                    <span>Terjual: <strong>{{ $promo->actual_sold_count }}</strong></span>
                </div>
                
                @if($promo->quota)
                <div class="flex items-center text-sm text-gray-600">
                  <i data-feather="box" class="w-4 h-4 mr-2"></i>
                  <span>Kuota: <strong>{{ $promo->quota }}</strong></span>
                </div>
                @endif
              </div>
              
              <!-- Progress Bar untuk Kuota -->
              @if($promo->quota)
              <div class="mb-6">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                  <span>Tersisa</span>
                  <span class="font-semibold">{{ $promo->quota - $promo->sold_count }} dari {{ $promo->quota }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div class="bg-primary h-2 rounded-full" style="width: {{ min(100, ($promo->sold_count / $promo->quota) * 100) }}%"></div>
                </div>
              </div>
              @endif
              
              <!-- Tombol Checkout -->
              <div class="space-y-3">
                @if($promo->quota && $promo->sold_count >= $promo->quota)
                  <button class="w-full bg-gray-400 text-white py-3 rounded-lg font-semibold cursor-not-allowed">
                    Promo Habis
                  </button>
                @else
                  <button id="checkout-btn" class="w-full bg-primary text-black py-3 rounded-lg font-semibold hover:bg-yellow-500 transition-colors duration-300">
                    Checkout Sekarang
                  </button>
                @endif
                
                <a href="/#menu" class="block w-full text-center text-gray-700 border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition-colors duration-300">
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
      
      <div class="modal-container bg-white w-11/12 md:max-w-2xl mx-auto rounded-xl shadow-lg z-50 overflow-y-auto max-h-screen">
        <div class="modal-content py-4 px-6">
          <!-- Modal Header -->
          <div class="flex justify-between items-center pb-3 border-b">
            <h3 class="text-2xl font-bold text-text-dark">Form Pemesanan Tiket</h3>
            <button id="modal-close" class="text-gray-500 hover:text-gray-700">
              <i data-feather="x" class="w-6 h-6"></i>
            </button>
          </div>
          
          <!-- Modal Body -->
          <div class="my-4">
            <form id="checkout-form" action="{{ route('checkout.process', $promo->id) }}" method="POST" class="space-y-4">
              @csrf
              
              <!-- No Pemesanan (Auto-generated) -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Pemesanan</label>
                <input type="text" id="order-number" class="w-full px-4 py-2 bg-gray-100 rounded-lg" readonly>
              </div>
              
              <!-- Nama Pemesan -->
              <div>
                <label for="customer-name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pemesan <span class="text-red-500">*</span></label>
                <input type="text" id="customer-name" name="customer_name" value="{{ old('customer_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
              </div>
              
              <!-- No WhatsApp -->
              <div>
                <label for="whatsapp-display" class="block text-sm font-medium text-gray-700 mb-1">
                  No. WhatsApp <span class="text-red-500">*</span>
                </label>
                <div class="flex">
                  <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-lg">
                    +62
                  </span>
                  <input type="text" id="whatsapp-display"
                    class="w-full px-4 py-2 border border-gray-300 rounded-r-lg focus:ring-primary focus:border-primary"
                    placeholder="81234567890" pattern="[1-9][0-9]{8,11}" inputmode="numeric" value="{{ old('whatsapp_number') ? substr(old('whatsapp_number'), 2) : '' }}" required>
                  <!-- Hidden input yang akan dikirim ke server dengan format 62xxx -->
                  <input type="hidden" id="whatsapp-number" name="whatsapp_number">
                </div>
                <p class="text-xs text-gray-500 mt-1">Contoh: 81234567890 (tanpa 0 di depan)</p>
              </div>
              
              <!-- Cabang (Fixed Value) -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
                <input type="text" value="Agrowisata Gunung Mas" class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg" readonly>
                <input type="hidden" id="branch" name="branch" value="Agrowisata Gunung Mas">
              </div>
              
              <!-- Tanggal Kunjungan -->
              <div>
                <label for="visit-date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                <input type="date" id="visit-date" name="visit_date" value="{{ old('visit_date') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                <p class="text-xs text-gray-500 mt-1">Pilih tanggal antara {{ \Carbon\Carbon::parse($promo->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($promo->end_date)->format('d M Y') }}</p>
                <p id="date-error" class="error-message">Tanggal yang dipilih harus dalam periode promo.</p>
              </div>
              
              <div class="mb-3 flex items-center justify-between">
                <!-- Label di kiri -->
                <label for="ticket-quantity" class="text-sm font-medium text-gray-700">
                  Jumlah Tiket <span class="text-red-500">*</span>
                </label>

                <!-- Counter di kanan -->
                <div class="flex items-center gap-2">
                  <button type="button" id="decrement" 
                    class="w-9 h-9 flex items-center justify-center rounded-md bg-gray-200 hover:bg-gray-300 font-bold transition-colors">-</button>

                  <input 
                    type="number" 
                    id="ticket-quantity" 
                    name="ticket_quantity" 
                    min="1" 
                    @if($promo->quota)
                    max="{{ $promo->quota - $promo->sold_count }}"
                    @endif
                    value="{{ old('ticket_quantity', 1) }}" 
                    class="w-14 text-center border rounded-md"
                    required
                  >

                  <button type="button" id="increment" 
                    class="w-9 h-9 flex items-center justify-center rounded-md bg-gray-200 hover:bg-gray-300 font-bold transition-colors">+</button>
                </div>
              </div>
              
              @if($promo->quota)
              <p class="text-xs text-gray-500 text-right">Maksimal {{ $promo->quota - $promo->sold_count }} tiket</p>
              @endif
              
              <!-- Informasi Harga -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between mb-2">
                  <span class="text-gray-600">Harga per tiket:</span>
                  <span class="font-medium" id="price-per-ticket">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between font-bold text-lg">
                  <span>Total Harga:</span>
                  <span class="text-primary" id="total-price">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</span>
                </div>
              </div>
              
              <!-- Modal Footer -->
              <div class="flex justify-end space-x-3 pt-4 border-t">
                <button id="cancel-btn" type="button" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                  Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors font-semibold">
                  Beli Sekarang
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-black text-white pt-8 sm:pt-12 pb-6 sm:pb-8">
      <div class="container mx-auto px-4 sm:px-6">
        <!-- Footer Content -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 sm:mb-12">
          <!-- Brand -->
          <div class="mb-6 sm:mb-8 md:mb-0 text-center md:text-left">
            <h3 class="text-2xl sm:text-3xl font-bold italic mb-3 sm:mb-4">
              Mesta<span class="text-white">Kara</span>.
            </h3>
            <p class="max-w-xs text-base sm:text-lg opacity-90">
              Menyajikan wahana menyenangkan dengan keseruan yang tak terlupakan.
            </p>
          </div>
          
          <!-- Quick Links -->
          <div class="mb-6 sm:mb-8 md:mb-0">
            <h4 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6 text-center md:text-left">Tautan Cepat</h4>
            <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-8">
              <a href="/#home" class="hover:text-gray-200 transition-colors duration-300 text-base sm:text-lg">Home</a>
              <a href="/#about" class="hover:text-gray-200 transition-colors duration-300 text-base sm:text-lg">Tentang Kami</a>
              <a href="/#menu" class="hover:text-gray-200 transition-colors duration-300 text-base sm:text-lg">Promo</a>
            </div>
          </div>
          
          <!-- Social Media -->
          <div>
            <h4 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6 text-center md:text-left">Ikuti Kami</h4>
            <div class="flex justify-center md:justify-start space-x-4 sm:space-x-6">
              <a href="https://www.instagram.com/wisataagro8/?hl=id" class="bg-white bg-opacity-20 p-2 sm:p-3 rounded-full hover:bg-opacity-30 transition-all duration-300">
                <i data-feather="instagram" class="w-5 h-5 sm:w-6 sm:h-6"></i>
              </a>
              <a href="https://twitter.com/agrowisata_n8" class="bg-white bg-opacity-20 p-2 sm:p-3 rounded-full hover:bg-opacity-30 transition-all duration-300">
                <i data-feather="twitter" class="w-5 h-5 sm:w-6 sm:h-6"></i>
              </a>
              <a href="https://www.facebook.com/AgrowisataN8/" class="bg-white bg-opacity-20 p-2 sm:p-3 rounded-full hover:bg-opacity-30 transition-all duration-300">
                <i data-feather="facebook" class="w-5 h-5 sm:w-6 sm:h-6"></i>
              </a>
            </div>
          </div>
        </div>
        
        <!-- Divider -->
        <div class="border-t border-white border-opacity-30 my-6 sm:my-8"></div>
        
        <!-- Copyright -->
        <div class="flex flex-col md:flex-row justify-between items-center text-center md:text-left">
          <p class="text-xs sm:text-sm md:text-base opacity-80 mb-3 md:mb-0">
            &copy; 2025 Tiketmas. All rights reserved.
          </p>
          <p class="text-xs sm:text-sm md:text-base opacity-80">
            Created by <a href="#" class="font-bold hover:underline">Mestakara</a>
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
        
        const sequentialNum = orderCounter.toString().padStart(2, '0');
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
      
      // Data kuota dari PHP
      const promoQuota = {{ $promo->quota ?? 'null' }};
      const promoSoldCount = {{ $promo->sold_count }};
      const remainingQuota = promoQuota ? promoQuota - promoSoldCount : null;
      
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
        let value = e.target.value.replace(/\D/g, '');
        
        if (cleanedText.startsWith('0')) {
          cleanedText = cleanedText.substring(1);
        }
        
        e.target.value = cleanedText;
        whatsappInput.value = cleanedText ? '62' + cleanedText : '';
      });
      
      // Set min and max date for visit date based on promo period
      function setVisitDateRange() {
        const startDate = new Date('{{ $promo->start_date }}');
        const endDate = new Date('{{ $promo->end_date }}');
        
        const formatDate = (date) => {
          const year = date.getFullYear();
          const month = (date.getMonth() + 1).toString().padStart(2, '0');
          const day = date.getDate().toString().padStart(2, '0');
          return `${year}-${month}-${day}`;
        };
        
        visitDateField.min = formatDate(startDate);
        visitDateField.max = formatDate(endDate);
        
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
        
        endDate.setHours(23, 59, 59, 999);
        
        if (selectedDate < startDate || selectedDate > endDate) {
          dateError.style.display = 'block';
          visitDateField.classList.add('border-red-500');
          return false;
        } else {
          dateError.style.display = 'none';
          visitDateField.classList.remove('border-red-500');
          return true;
        }
      }
      
      // Validasi kuota tiket
      function validateTicketQuantity() {
        const quantity = parseInt(ticketQuantity.value) || 1;
        
        // Jika promo tidak ada kuota (unlimited), return true
        if (remainingQuota === null) {
          return true;
        }
        
        // Cek jika melebihi sisa kuota
        if (quantity > remainingQuota) {
          alert(`Maaf, kuota hanya tersisa ${remainingQuota} tiket. Silakan kurangi jumlah tiket.`);
          ticketQuantity.value = remainingQuota > 0 ? remainingQuota : 1;
          calculateTotalPrice();
          return false;
        }
        
        return true;
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
      
      // Update total price when quantity changes dengan validasi kuota
      ticketQuantity.addEventListener('input', function() {
        validateTicketQuantity();
        calculateTotalPrice();
      });
      
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
        
        // Validate ticket quantity
        if (!validateTicketQuantity()) {
          e.preventDefault();
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
        let current = parseInt(ticketQuantity.value);
        
        // Cek kuota sebelum increment
        if (remainingQuota !== null && current >= remainingQuota) {
          alert(`Maaf, kuota hanya tersisa ${remainingQuota} tiket.`);
          return;
        }
        
        ticketQuantity.value = current + 1;
        validateTicketQuantity();
        calculateTotalPrice();
      });

      decrementBtn.addEventListener('click', () => {
        let current = parseInt(ticketQuantity.value);
        if (current > 1) {
          ticketQuantity.value = current - 1;
          calculateTotalPrice();
        }
      });
    </script>
  </body>
</html>