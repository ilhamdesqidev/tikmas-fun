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
    </style>
  </head>
  <body class="font-poppins bg-gray-50 text-text-dark">
    <!-- Navbar -->
    <nav class="w-full py-3 sm:py-5 px-4 sm:px-7 flex items-center justify-between bg-white border-b border-gray-400 fixed top-0 left-0 right-0 z-50" style="border-bottom: 1px solid #597336;">
      <a href="/" class="text-2xl sm:text-3xl font-bold text-black italic">
        Mesta<span class="text-primary">Kara</span>.
      </a>
      
      <!-- Desktop Navigation -->
      <div class="hidden md:flex">
        <a href="/#home" class="text-black inline-block text-xl ml-0 px-4 hover:text-primary transition-all duration-500 relative group">
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
      </div>

      <!-- Navbar Extra -->
      <div class="flex items-center">
        <a href="#" class="text-black mx-1 sm:mx-2 hover:text-primary transition-all duration-500 p-2"><i data-feather="search" class="w-5 h-5 sm:w-6 sm:h-6"></i></a>
        <a href="#" class="text-black mx-1 sm:mx-2 hover:text-primary transition-all duration-500 p-2"><i data-feather="shopping-cart" class="w-5 h-5 sm:w-6 sm:h-6"></i></a>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-20 pb-16 px-4 sm:px-7">
      <div class="max-w-6xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6">
          <ol class="flex items-center space-x-2 text-sm text-gray-600">
            <li><a href="/" class="hover:text-primary transition-colors">Home</a></li>
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
                  <span>Terjual: <strong>{{ $promo->sold_count }}</strong></span>
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
                  <span>{{ $promo->quota - $promo->sold_count }} dari {{ $promo->quota }}</span>
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
        </div>
      </div>
    </main>

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

      // Checkout button functionality
      document.getElementById('checkout-btn')?.addEventListener('click', function() {
        // Simulasi proses checkout
        alert('Fitur checkout akan segera tersedia!');
        // Di sini bisa diarahkan ke halaman checkout atau modal checkout
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
    </script>
  </body>
</html>