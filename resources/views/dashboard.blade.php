<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $settings['site_name'] ?? 'MestaKara' }} - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: '{{ $settings["primary_color"] ?? "#CFD916" }}',
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
      /* ==================== GLOBAL STYLES ==================== */
      html {
        scroll-behavior: smooth;
      }
      
      * {
        box-sizing: border-box;
      }
      
      body {
        overflow-x: hidden;
        max-width: 100vw;
        font-family: 'Poppins', sans-serif;
      }
      
      /* ==================== HERO SECTION ==================== */
      .hero-bg {
        background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
          url("{{ isset($settings['hero_background_path']) ? asset('storage/' . $settings['hero_background_path']) : '/assets/img/mainimg.jpg' }}");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
      }
      
      @media (max-width: 768px) {
        .hero-bg {
          background-attachment: scroll;
        }
      }
      
      .hero h1 {
        text-shadow: 2px 2px 4px rgba(1, 1, 3, 0.7);
      }
      
      .hero p {
        text-shadow: 1px 1px 3px rgba(1, 1, 3, 0.5);
      }
      
      .hero .cta {
        box-shadow: 1px 1px 3px rgba(1, 1, 3, 0.5);
      }

      /* ==================== FACILITY CAROUSEL STYLES ==================== */
      .facility-carousel {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        max-width: 100%;
      }

      .facility-images {
        display: flex;
        transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        width: {{ $facilities->count() * 100 }}%;
      }

      .facility-slide {
        flex: 0 0 {{ $facilities->count() > 0 ? (100 / $facilities->count()) : 100 }}%;
        position: relative;
      }

      .facility-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        display: block;
      }

      .facility-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        padding: 2rem 1.5rem 1rem;
        color: white;
      }

      .facility-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
      }

      .facility-description {
        font-size: 1rem;
        opacity: 0.95;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        line-height: 1.4;
      }

      .carousel-indicators {
        display: flex;
        gap: 8px;
        justify-content: center;
        margin-top: 1rem;
      }

      .indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #d1d5db;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .indicator.active {
        background: {{ $settings['primary_color'] ?? '#CFD916' }};
        transform: scale(1.3);
      }

      @media (max-width: 1024px) {
        .facility-image {
          height: 350px;
        }
      }

      @media (max-width: 768px) {
        .facility-image {
          height: 280px;
        }
        
        .facility-title {
          font-size: 1.25rem;
        }
        
        .facility-description {
          font-size: 0.875rem;
        }
        
        .facility-overlay {
          padding: 1.5rem 1rem 0.75rem;
        }
      }

      @media (max-width: 480px) {
        .facility-image {
          height: 240px;
        }
        
        .facility-title {
          font-size: 1.125rem;
        }
        
        .facility-description {
          font-size: 0.8rem;
        }
      }

      /* ==================== PROMO SLIDER STYLES ==================== */
      .promo-slider {
        overflow: hidden;
        position: relative;
        padding: 2rem 0;
        max-width: 100%;
      }

      .promo-container {
        display: flex;
        transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        gap: 2rem;
        padding: 0 calc(50% - 175px);
      }

      .promo-card {
        min-width: 350px;
        max-width: 350px;
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.5s ease;
        position: relative;
        margin-bottom: 10px;
        transform: scale(0.85);
        filter: blur(2px);
        opacity: 0.6;
        flex-shrink: 0;
      }

      .promo-card.active {
        transform: scale(1);
        filter: blur(0px);
        opacity: 1;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
      }

      .promo-card.clickable:hover {
        transform: scale(1.02);
        cursor: pointer;
      }

      .promo-card.non-clickable {
        cursor: not-allowed;
      }

      .promo-disabled {
        opacity: 0.7;
        filter: grayscale(0.3);
      }

      .promo-overlay-disabled {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        z-index: 5;
      }

      .status-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 12px;
        z-index: 10;
      }

      .badge-coming-soon {
        background: #3b82f6;
        color: white;
      }

      .badge-expired {
        background: #6b7280;
        color: white;
      }

      .badge-sold-out {
        background: #dc2626;
        color: white;
      }

      .badge-discount {
        background: {{ $settings['primary_color'] ?? '#CFD916' }};
        color: #000;
      }

      .featured-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #ff4757;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 12px;
        z-index: 10;
      }

      .promo-image {
        height: 250px;
        overflow: hidden;
        position: relative;
      }

      .promo-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
      }

      .promo-card.clickable:hover .promo-image img {
        transform: scale(1.05);
      }

      .nav-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.95);
        border: 2px solid {{ $settings['primary_color'] ?? '#CFD916' }};
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 20;
        backdrop-filter: blur(10px);
      }

      .nav-button:hover {
        background: {{ $settings['primary_color'] ?? '#CFD916' }};
        color: #000;
        transform: translateY(-50%) scale(1.1);
      }

      .nav-button.prev {
        left: 20px;
      }

      .nav-button.next {
        right: 20px;
      }

      .nav-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
      }

      .nav-button:disabled:hover {
        background: rgba(255, 255, 255, 0.95);
        color: inherit;
        transform: translateY(-50%) scale(1);
      }

      .dots-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 2rem;
        flex-wrap: wrap;
      }

      .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ddd;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .dot.active {
        background: {{ $settings['primary_color'] ?? '#CFD916' }};
        transform: scale(1.2);
      }

      @media (max-width: 1024px) {
        .promo-container {
          padding: 0 calc(50% - 165px);
        }
        
        .promo-card {
          min-width: 330px;
          max-width: 330px;
        }
      }

      @media (max-width: 768px) {
        .promo-card {
          min-width: 300px;
          max-width: 300px;
        }

        .nav-button {
          width: 40px;
          height: 40px;
        }

        .nav-button.prev {
          left: 10px;
        }

        .nav-button.next {
          right: 10px;
        }

        .promo-container {
          padding: 0 calc(50% - 150px);
          gap: 1.5rem;
        }
        
        .promo-image {
          height: 200px;
        }
      }

      @media (max-width: 480px) {
        .promo-card {
          min-width: 280px;
          max-width: 280px;
        }
        
        .promo-container {
          padding: 0 calc(50% - 140px);
          gap: 1rem;
        }
        
        .nav-button {
          width: 35px;
          height: 35px;
        }
      }

      @media (max-width: 375px) {
        .promo-card {
          min-width: 260px;
          max-width: 260px;
        }
        
        .promo-container {
          padding: 0 calc(50% - 130px);
        }
      }
    </style>
  </head>
  <body class="font-poppins bg-white text-text-dark">
    <!-- Overlay -->
    <div id="overlay" class="hidden fixed top-0 left-0 w-full h-screen bg-black bg-opacity-50 z-40 transition-opacity duration-300"></div>

    <!-- Navbar -->
    <nav class="w-full py-3 sm:py-4 lg:py-5 px-4 sm:px-6 lg:px-7 flex items-center justify-between bg-white border-b border-gray-400 fixed top-0 left-0 right-0 z-50" style="border-bottom: 1px solid #597336;">
      <a href="#" class="text-xl sm:text-2xl lg:text-3xl font-bold text-black italic">
        {{ $settings['site_name'] ?? 'MestaKara' }}<span class="text-primary">.</span>
      </a>
      
      <!-- Desktop Navigation -->
      <div id="navbar-nav" class="hidden md:flex">
        <a href="#home" class="text-black inline-block text-base lg:text-xl ml-0 px-3 lg:px-4 hover:text-primary transition-all duration-500 relative group">
          Home
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="#about" class="text-black inline-block text-base lg:text-xl ml-0 px-3 lg:px-4 hover:text-primary transition-all duration-500 relative group">
          Tentang Kami
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="#menu" class="text-black inline-block text-base lg:text-xl ml-0 px-3 lg:px-4 hover:text-primary transition-all duration-500 relative group">
          Promo
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="wahana" class="text-black inline-block text-base lg:text-xl ml-0 px-3 lg:px-4 hover:text-primary transition-all duration-500 relative group">
          Wahana
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
      </div>

      <!-- Mobile Navigation -->
      <div id="mobile-nav" class="fixed top-0 -right-full w-full sm:w-80 h-screen bg-black transition-all duration-300 z-50 pt-16" style="box-shadow: -5px 0 15px rgba(0, 0, 0, 0.5);">
        <div id="close-menu" class="absolute top-4 sm:top-6 right-4 sm:right-6 text-white cursor-pointer text-3xl sm:text-4xl touch-manipulation">
          <i data-feather="x"></i>
        </div>
        
        <div class="flex flex-col px-4">
          <a href="#home" class="block mx-2 sm:mx-6 my-4 sm:my-6 lg:my-8 py-3 sm:py-4 text-xl sm:text-2xl lg:text-3xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-4 touch-manipulation">Home</a>
          <a href="#about" class="block mx-2 sm:mx-6 my-4 sm:my-6 lg:my-8 py-3 sm:py-4 text-xl sm:text-2xl lg:text-3xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-4 touch-manipulation">Tentang Kami</a>
          <a href="#menu" class="block mx-2 sm:mx-6 my-4 sm:my-6 lg:my-8 py-3 sm:py-4 text-xl sm:text-2xl lg:text-3xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-4 touch-manipulation">Promo</a>
          <a href="wahana" class="block mx-2 sm:mx-6 my-4 sm:my-6 lg:my-8 py-3 sm:py-4 text-xl sm:text-2xl lg:text-3xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-4 touch-manipulation">Wahana</a>
        </div>
      </div>

      <!-- Navbar Extra -->
      <div class="flex items-center gap-1 sm:gap-2">
        <a href="#" class="text-black hover:text-primary transition-all duration-500 p-2 touch-manipulation"><i data-feather="search" class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6"></i></a>
        <a href="#" class="text-black hover:text-primary transition-all duration-500 p-2 touch-manipulation"><i data-feather="shopping-cart" class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6"></i></a>
        <a href="#" id="menu-icon" class="text-black hover:text-primary transition-all duration-500 md:hidden cursor-pointer p-2 touch-manipulation">
          <i data-feather="menu" class="w-5 h-5 sm:w-6 sm:h-6"></i>
        </a>
      </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero min-h-screen flex items-center hero-bg relative px-4 sm:px-6 lg:px-7 text-white pt-16">
      <main class="max-w-4xl w-full">
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl text-white leading-tight mb-4 sm:mb-6">
          {{ $settings['hero_title'] ?? 'Berlibur Dengan' }}<span class="text-primary"> {{ $settings['hero_subtitle'] ?? 'Wahana' }}</span>
        </h1>
        <p class="text-base sm:text-lg md:text-xl lg:text-2xl mt-3 sm:mt-4 leading-relaxed font-medium text-white max-w-3xl">
          {{ $settings['hero_description'] ?? 'Mari Berlibur dan Nikmati Berbagai Wahana Seru di Agrowisata Gunung Mas Bersama Keluarga Tercinta Dengan Harga Tiket Masuk yang Terjangkau dan Dapatkan Berbagai Promo Menarik Setiap Bulannya' }}
        </p>
        <a href="#menu" class="cta inline-block mt-6 sm:mt-8 px-6 sm:px-8 lg:px-12 py-3 sm:py-4 text-base sm:text-lg lg:text-xl text-black font-semibold bg-primary rounded-lg hover:bg-yellow-500 transition-colors duration-300 touch-manipulation">
          {{ $settings['hero_cta_text'] ?? 'Dapatkan Promo' }}
        </a>
      </main>
    </section>

    <!-- About Section -->
    <section id="about" class="py-12 sm:py-16 md:py-24 lg:py-32 px-4 sm:px-6 lg:px-7">
      <h2 class="text-center text-2xl sm:text-3xl lg:text-4xl mb-6 sm:mb-8 lg:mb-12 text-text-dark">
        <span class="text-primary">{{ $settings['about_title'] ?? 'Tentang' }}</span> {{ $settings['about_subtitle'] ?? 'Kami' }}
      </h2>
      <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 max-w-6xl mx-auto">
        <!-- Facility Carousel -->
        <div class="flex-1 w-full lg:min-w-96">
          @if($facilities->count() > 0)
          <div class="facility-carousel" id="facilityCarousel">
            <div class="facility-images" id="facilityImages">
              @foreach($facilities as $facility)
              <div class="facility-slide">
                <img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $facility->name }}" class="facility-image" />
                <div class="facility-overlay">
                  <div class="facility-title">{{ $facility->name }}</div>
                  <div class="facility-description">{{ Str::limit($facility->description, 120) }}</div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          
          <!-- Indicators di luar gambar -->
          <div class="carousel-indicators" id="carouselIndicators"></div>
          @else
          <div class="bg-gray-100 rounded-xl p-12 text-center">
            <i data-feather="image" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
            <p class="text-gray-500">Belum ada wahana tersedia</p>
          </div>
          @endif
          
          <!-- Tombol Lihat Lebih Banyak -->
          <div class="text-center mt-6">
            <button onclick="window.location='wahana'"
                    class="inline-flex items-center px-6 py-3 bg-primary text-black font-semibold rounded-lg hover:bg-yellow-500 transition-colors duration-300 group">
              Lihat Lebih Banyak
              <i data-feather="chevron-down"
                class="w-5 h-5 ml-2 group-hover:transform group-hover:translate-y-1 transition-transform duration-300"></i>
            </button>
          </div>
        </div>
        
        <div class="flex-1 w-full lg:min-w-96 px-0 lg:px-8">
          <h3 class="text-xl sm:text-2xl lg:text-3xl mb-3 sm:mb-4 text-text-dark">
            {{ $settings['about_question'] ?? 'Kenapa memilih Wahana kami?' }}
          </h3>
          <p class="mb-3 sm:mb-4 text-sm sm:text-base lg:text-lg font-medium leading-relaxed text-text-dark">
            {{ $settings['about_content_1'] ?? 'MestaKara adalah penyedia wahana yang didirikan dengan cinta dan dedikasi untuk menghadirkan pengalaman wahana terbaik. Kami percaya bahwa setiap tawa dapat menciptakan kenangan indah yang akan diingat selamanya.' }}
          </p>
          <p class="mb-3 sm:mb-4 text-sm sm:text-base lg:text-lg font-medium leading-relaxed text-text-dark">
            {{ $settings['about_content_2'] ?? 'Wahana kami didirikan langsung di tengah perkebunan terbaik dan ditata dengan presisi yang sempurna. Setiap wahana yang kami sediakan adalah hasil dari perpaduan tradisi dan kualitas premium.' }}
          </p>
          <p class="text-sm sm:text-base lg:text-lg font-medium leading-relaxed text-text-dark">
            {{ $settings['about_content_3'] ?? 'Dengan lebih dari 20 wahana menarik, fasilitas lengkap, dan staff berpengalaman, kami siap memberikan pengalaman liburan yang tak terlupakan untuk seluruh keluarga.' }}
          </p>
        </div>
      </div>
    </section>

    <!-- Promo Section -->
    <section id="menu" class="py-6">
      <h2 class="text-center text-3xl sm:text-4xl mb-4 text-text-dark">
        <span class="text-primary">Promo</span> Kami
      </h2>
      <p class="text-center max-w-lg mx-auto font-medium leading-relaxed text-text-dark mb-12 sm:mb-20 text-base sm:text-lg">
        Nikmati berbagai pilihan promo menarik untuk pengalaman liburan yang tak terlupakan
      </p>
      
      @if($promos->count() > 0)
        <div class="relative promo-slider">
          <!-- Navigation Buttons -->
          <button class="nav-button prev" id="prevBtn">
            <i data-feather="chevron-left" class="w-6 h-6"></i>
          </button>
          
          <button class="nav-button next" id="nextBtn">
            <i data-feather="chevron-right" class="w-6 h-6"></i>
          </button>

          <!-- Slider Container -->
          <div class="promo-container" id="promoContainer">
            @foreach($promos as $promo)
              @php
                $isClickable = $promo->is_clickable;
                $buttonStatus = $promo->button_status;
              @endphp
              
              <div class="promo-card block hover:no-underline {{ $isClickable ? 'clickable' : 'non-clickable promo-disabled' }}" 
                   @if($isClickable) onclick="window.location.href='{{ route('promo.show', $promo->id) }}'" @endif>
                   
                @if($promo->featured && $isClickable)
                  <span class="featured-badge">Unggulan</span>
                @endif
                
                @if($promo->status_display === 'coming_soon')
                  <span class="badge-coming-soon status-badge">Segera Hadir</span>
                @elseif($promo->status_display === 'expired')
                  <span class="badge-expired status-badge">Kadaluarsa</span>
                @elseif($promo->status_display === 'sold_out')
                  <span class="badge-sold-out status-badge">Habis</span>
                @elseif($promo->status === 'active' && $promo->discount_percent > 0)
                  <span class="badge-discount status-badge">Diskon {{ $promo->discount_percent }}%</span>
                @endif
                
                <div class="promo-image">
                  <img src="{{ $promo->image_url }}" alt="{{ $promo->name }}" loading="lazy">
                  
                  @if(!$isClickable)
                    <div class="promo-overlay-disabled">
                      <div class="overlay-content">
                        @if($promo->status_display === 'coming_soon')
                          <i data-feather="clock" class="w-8 h-8 mb-2 mx-auto"></i>
                          <span class="text-sm font-medium">Segera Hadir</span>
                          <p class="text-xs mt-1">Mulai {{ $promo->start_date->format('d M Y') }}</p>
                        @elseif($promo->status_display === 'expired')
                          <i data-feather="x-circle" class="w-8 h-8 mb-2 mx-auto"></i>
                          <span class="text-sm font-medium">Promo Berakhir</span>
                        @elseif($promo->status_display === 'sold_out')
                          <i data-feather="package" class="w-8 h-8 mb-2 mx-auto"></i>
                          <span class="text-sm font-medium">Kuota Habis</span>
                        @endif
                      </div>
                    </div>
                  @endif
                </div>
                
                <div class="p-6">
                  <h3 class="text-xl font-bold mb-2 text-text-dark">{{ $promo->name }}</h3>
                  <p class="text-gray-600 mb-4">{{ Str::limit($promo->description, 100) }}</p>
                  
                  <div class="flex items-center justify-between mb-4">
                    <div>
                      @if($promo->discount_percent > 0)
                        <span class="text-gray-400 line-through text-sm">Rp {{ number_format($promo->original_price, 0, ',', '.') }}</span>
                      @endif
                      <span class="text-primary font-bold text-xl block">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</span>
                    </div>
                    <span class="bg-gray-100 text-gray-700 text-sm font-semibold px-3 py-1 rounded-full capitalize">
                      {{ $promo->category }}
                    </span>
                  </div>
                  
                  <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                    <span>
                      @if($promo->status_display === 'expired')
                        Berakhir: {{ $promo->end_date ? $promo->end_date->format('d M Y') : '-' }}
                      @elseif($promo->status_display === 'coming_soon')
                        Mulai: {{ $promo->start_date->format('d M Y') }}
                      @else
                        @if($promo->end_date)
                          Sampai: {{ $promo->end_date->format('d M Y') }}
                        @else
                          Tidak terbatas
                        @endif
                      @endif
                    </span>
                    @if($promo->quota && $promo->status === 'active')
                      <span>Tersisa: {{ $promo->quota - $promo->actual_sold_count }}</span>
                    @endif
                  </div>
                  
                  <div class="w-full text-center font-semibold py-3 rounded-lg transition-colors duration-300 {{ $buttonStatus['class'] }}"
                       @if(!$buttonStatus['clickable']) style="cursor: not-allowed;" @endif>
                    {{ $buttonStatus['text'] }}
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <!-- Dots Indicator -->
          <div class="dots-container" id="dotsContainer"></div>
        </div>
      @else
        <div class="text-center py-12">
          <div class="inline-block p-4 bg-gray-100 rounded-full mb-4">
            <i data-feather="tag" class="w-12 h-12 text-gray-400"></i>
          </div>
          <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada promo saat ini</h3>
          <p class="text-gray-500">Silakan kembali lagi nanti untuk melihat promo terbaru</p>
        </div>
      @endif
    </section>

    <!-- Footer -->
    <footer class="bg-black text-white pt-8 sm:pt-10 lg:pt-12 pb-6 sm:pb-8">
      <div class="container mx-auto px-4 sm:px-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 sm:gap-8 mb-8 sm:mb-10 lg:mb-12">
          <div class="text-center md:text-left">
            <h3 class="text-2xl sm:text-3xl font-bold italic mb-3 sm:mb-4">
              {{ $settings['site_name'] ?? 'MestaKara' }}<span class="text-white">.</span>
            </h3>
            <p class="max-w-xs text-sm sm:text-base lg:text-lg opacity-90">
              {{ $settings['website_description'] ?? 'Menyajikan wahana menyenangkan dengan keseruan yang tak terlupakan.' }}
            </p>
          </div>
          
          <div>
            <h4 class="text-base sm:text-lg lg:text-xl font-semibold mb-4 sm:mb-5 lg:mb-6 text-center md:text-left">Tautan Cepat</h4>
            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-6 lg:space-x-8">
              <a href="#home" class="hover:text-gray-300 transition-colors duration-300 text-sm sm:text-base lg:text-lg touch-manipulation text-center md:text-left">Home</a>
              <a href="#about" class="hover:text-gray-300 transition-colors duration-300 text-sm sm:text-base lg:text-lg touch-manipulation text-center md:text-left">Tentang Kami</a>
              <a href="#menu" class="hover:text-gray-300 transition-colors duration-300 text-sm sm:text-base lg:text-lg touch-manipulation text-center md:text-left">Promo</a>
            </div>
          </div>
          
          <div>
            <h4 class="text-base sm:text-lg lg:text-xl font-semibold mb-4 sm:mb-5 lg:mb-6 text-center md:text-left">Ikuti Kami</h4>
            <div class="flex justify-center md:justify-start space-x-3 sm:space-x-4 lg:space-x-6">
              <a href="https://www.instagram.com/wisataagro8/?hl=id" class="bg-white bg-opacity-20 p-2 sm:p-2.5 lg:p-3 rounded-full hover:bg-opacity-30 transition-all duration-300 touch-manipulation">
                <i data-feather="instagram" class="w-5 h-5 sm:w-5 sm:h-5 lg:w-6 lg:h-6"></i>
              </a>
              <a href="https://twitter.com/agrowisata_n8" class="bg-white bg-opacity-20 p-2 sm:p-2.5 lg:p-3 rounded-full hover:bg-opacity-30 transition-all duration-300 touch-manipulation">
                <i data-feather="twitter" class="w-5 h-5 sm:w-5 sm:h-5 lg:w-6 lg:h-6"></i>
              </a>
              <a href="https://www.facebook.com/AgrowisataN8/" class="bg-white bg-opacity-20 p-2 sm:p-2.5 lg:p-3 rounded-full hover:bg-opacity-30 transition-all duration-300 touch-manipulation">
                <i data-feather="facebook" class="w-5 h-5 sm:w-5 sm:h-5 lg:w-6 lg:h-6"></i>
              </a>
            </div>
          </div>
        </div>
        
        <div class="border-t border-white border-opacity-30 my-6 sm:my-7 lg:my-8"></div>
        
        <div class="flex flex-col md:flex-row justify-between items-center gap-3 text-center md:text-left">
          <p class="text-xs sm:text-sm lg:text-base opacity-80">
            {{ $settings['footer_text'] ?? '© 2025 Tiketmas. All rights reserved.' }}
          </p>
          <p class="text-xs sm:text-sm lg:text-base opacity-80">
            Created by <a href="#" class="font-bold hover:underline">{{ $settings['site_name'] ?? 'Mestakara' }}</a>
          </p>
        </div>
      </div>
    </footer>

    <!-- ==================== JAVASCRIPT ==================== -->
    <script>
      // Initialize Feather icons
      feather.replace();

      // ==================== MOBILE MENU TOGGLE ====================
      const navbarNav = document.getElementById('mobile-nav');
      const menuIcon = document.getElementById('menu-icon');
      const closeMenu = document.getElementById('close-menu');
      const overlay = document.getElementById('overlay');
      let isMenuOpen = false;

      function openMobileMenu() {
        if (!isMenuOpen) {
          navbarNav.style.right = '0';
          overlay.classList.remove('hidden');
          document.body.style.overflow = 'hidden';
          isMenuOpen = true;
        }
      }

      function closeMobileMenu() {
        if (isMenuOpen) {
          navbarNav.style.right = '-100%';
          overlay.classList.add('hidden');
          document.body.style.overflow = '';
          isMenuOpen = false;
        }
      }

      menuIcon.addEventListener('click', (e) => {
        e.stopPropagation();
        openMobileMenu();
      });

      closeMenu.addEventListener('click', (e) => {
        e.stopPropagation();
        closeMobileMenu();
      });

      closeMenu.addEventListener('touchend', (e) => {
        e.preventDefault();
        e.stopPropagation();
        closeMobileMenu();
      });

      overlay.addEventListener('click', closeMobileMenu);

      document.addEventListener('click', (e) => {
        const isClickInsideNav = e.target.closest('#mobile-nav') !== null;
        const isClickOnMenuIcon = e.target.closest('#menu-icon') !== null;
        if (!isClickInsideNav && !isClickOnMenuIcon && isMenuOpen) {
          closeMobileMenu();
        }
      });

      // ==================== SMOOTH SCROLLING ====================
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
          closeMobileMenu();
        });

        anchor.addEventListener('touchend', function (e) {
          this.click();
        });
      });

      // ==================== FACILITY CAROUSEL ====================
      class FacilityCarousel {
        constructor() {
          this.container = document.getElementById('facilityImages');
          if (!this.container) return;
          this.slides = this.container.querySelectorAll('.facility-slide');
          this.totalSlides = this.slides.length;
          this.indicators = document.getElementById('carouselIndicators');
          this.currentIndex = 0;
          this.init();
        }

        init() {
          this.createIndicators();
          this.startAutoPlay();
          this.bindEvents();
          this.updateSlide();
        }

        createIndicators() {
          if (!this.indicators) return;
          this.indicators.innerHTML = '';
          for (let i = 0; i < this.totalSlides; i++) {
            const indicator = document.createElement('div');
            indicator.className = `indicator ${i === 0 ? 'active' : ''}`;
            indicator.addEventListener('click', () => this.goToSlide(i));
            this.indicators.appendChild(indicator);
          }
          this.indicatorElements = this.indicators.querySelectorAll('.indicator');
        }

        bindEvents() {
          const carousel = document.getElementById('facilityCarousel');
          if (!carousel) return;
          carousel.addEventListener('mouseenter', () => this.pauseAutoPlay());
          carousel.addEventListener('mouseleave', () => this.resumeAutoPlay());

          let startX = 0;
          let currentX = 0;
          let isDragging = false;

          this.container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
            this.pauseAutoPlay();
          });

          this.container.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
          });

          this.container.addEventListener('touchend', () => {
            if (!isDragging) return;
            isDragging = false;
            const diffX = startX - currentX;
            if (Math.abs(diffX) > 50) {
              if (diffX > 0) {
                this.nextSlide();
              } else {
                this.prevSlide();
              }
            }
            this.resumeAutoPlay();
          });
        }

        updateSlide() {
          if (!this.container) return;
          const translateX = -this.currentIndex * (100 / this.totalSlides);
          this.container.style.transform = `translateX(${translateX}%)`;
          if (this.indicatorElements) {
            this.indicatorElements.forEach((indicator, index) => {
              indicator.classList.toggle('active', index === this.currentIndex);
            });
          }
        }

        nextSlide() {
          this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
          this.updateSlide();
        }

        prevSlide() {
          this.currentIndex = this.currentIndex === 0 ? this.totalSlides - 1 : this.currentIndex - 1;
          this.updateSlide();
        }

        goToSlide(index) {
          this.currentIndex = index;
          this.updateSlide();
        }

        startAutoPlay() {
          this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
          }, 4000);
        }

        pauseAutoPlay() {
          clearInterval(this.autoPlayInterval);
        }

        resumeAutoPlay() {
          this.pauseAutoPlay();
          this.startAutoPlay();
        }
      }

      // ==================== PROMO SLIDER ====================
      class PromoSlider {
        constructor() {
          this.container = document.getElementById('promoContainer');
          this.cards = this.container ? this.container.querySelectorAll('.promo-card') : [];
          this.totalCards = this.cards.length;
          this.dotsContainer = document.getElementById('dotsContainer');
          this.currentIndex = 0;
          this.prevBtn = document.getElementById('prevBtn');
          this.nextBtn = document.getElementById('nextBtn');
          this.init();
        }

        init() {
          if (!this.container || this.totalCards === 0) return;
          this.createDots();
          this.bindEvents();
          this.updateSlider();
          this.autoPlay();
        }

        createDots() {
          if (!this.dotsContainer) return;
          this.dotsContainer.innerHTML = '';
          for (let i = 0; i < this.totalCards; i++) {
            const dot = document.createElement('div');
            dot.className = `dot ${i === 0 ? 'active' : ''}`;
            dot.addEventListener('click', () => this.goToSlide(i));
            this.dotsContainer.appendChild(dot);
          }
          this.dotElements = this.dotsContainer.querySelectorAll('.dot');
        }

        bindEvents() {
          if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prevSlide());
          }
          if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.nextSlide());
          }

          if (this.container) {
            this.container.addEventListener('mouseenter', () => this.pauseAutoPlay());
            this.container.addEventListener('mouseleave', () => this.resumeAutoPlay());
          }

          window.addEventListener('resize', () => this.handleResize());
          
          let startX = 0;
          let currentX = 0;
          let isDragging = false;

          this.container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
            this.pauseAutoPlay();
          });

          this.container.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
          });

          this.container.addEventListener('touchend', () => {
            if (!isDragging) return;
            isDragging = false;
            const diffX = startX - currentX;
            if (Math.abs(diffX) > 50) {
              if (diffX > 0) {
                this.nextSlide();
              } else {
                this.prevSlide();
              }
            }
            this.resumeAutoPlay();
          });
        }

        handleResize() {
          this.updateSlider();
        }

        updateSlider() {
          if (!this.container) return;
          
          this.cards.forEach((card, idx) => {
            card.classList.toggle('active', idx === this.currentIndex);
          });

          if (this.dotElements) {
            this.dotElements.forEach((dot, idx) => {
              dot.classList.toggle('active', idx === this.currentIndex);
            });
          }

          const cardWidth = this.cards[0]?.offsetWidth || 350;
          const gap = window.innerWidth < 768 ? 16 : 32;
          const offset = -this.currentIndex * (cardWidth + gap);
          this.container.style.transform = `translateX(${offset}px)`;
        }

        nextSlide() {
          this.currentIndex = (this.currentIndex + 1) % this.totalCards;
          this.updateSlider();
        }

        prevSlide() {
          this.currentIndex = this.currentIndex === 0 ? this.totalCards - 1 : this.currentIndex - 1;
          this.updateSlider();
        }

        goToSlide(index) {
          this.currentIndex = index;
          this.updateSlider();
        }

        autoPlay() {
          this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
          }, 5000);
        }

        pauseAutoPlay() {
          clearInterval(this.autoPlayInterval);
        }

        resumeAutoPlay() {
          this.pauseAutoPlay();
          this.autoPlay();
        }
      }

      // ==================== INITIALIZE ALL ====================
      document.addEventListener('DOMContentLoaded', () => {
        new FacilityCarousel();
        new PromoSlider();
        feather.replace(); 

        setTimeout(() => {
          feather.replace();
        }, 100);
      });
    </script>
  </body>
</html>