<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $settings['site_name'] ?? 'MestaKara' }} - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet" />
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
      /* ==================== GLOBAL & RESETS ==================== */
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }
      
      html {
        scroll-behavior: smooth;
      }
      
      body {
        overflow-x: hidden;
        font-family: 'Poppins', sans-serif;
        background: #fafafa;
        color: #333;
        line-height: 1.6;
      }

      /* ==================== UTILITY CLASSES ==================== */
      .container-custom {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1rem;
      }

      @media (min-width: 640px) {
        .container-custom { padding: 0 1.5rem; }
      }

      @media (min-width: 1024px) {
        .container-custom { padding: 0 2rem; }
      }

      /* ==================== ANIMATIONS ==================== */
      @keyframes fadeInUp {
        from {
          opacity: 0;
          transform: translateY(40px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }

      @keyframes pulseBadge {
        0%, 100% {
          box-shadow: 0 0 0 0 rgba(255, 71, 87, 0.7);
        }
        50% {
          box-shadow: 0 0 0 15px rgba(255, 71, 87, 0);
        }
      }

      .fade-in-section {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 1s ease-out, transform 1s ease-out;
      }

      .fade-in-section.visible {
        opacity: 1;
        transform: translateY(0);
      }

      /* ==================== SCROLL PROGRESS BAR ==================== */
      .scroll-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: linear-gradient(90deg, {{ $settings['primary_color'] ?? '#CFD916' }} 0%, #a8b012 100%);
        z-index: 9999;
        transition: width 0.1s ease;
        box-shadow: 0 0 20px rgba(207, 217, 22, 0.6);
      }

      /* ==================== NAVBAR ==================== */
      nav {
        backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.98);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      }

      nav.scrolled {
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        background: rgba(255, 255, 255, 0.95);
      }

      .nav-link {
        position: relative;
        transition: color 0.3s;
      }

      .nav-link::after {
        content: '';
        position: absolute;
        left: 1rem;
        right: 1rem;
        bottom: 0;
        height: 2px;
        background: {{ $settings['primary_color'] ?? '#CFD916' }};
        transform: scaleX(0);
        transition: transform 0.3s;
      }

      .nav-link:hover::after {
        transform: scaleX(1);
      }
      
      /* ==================== HERO SECTION ==================== */
      .hero-bg {
        background-image: linear-gradient(135deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4)), 
          url("{{ isset($settings['hero_background_path']) ? asset('storage/' . $settings['hero_background_path']) : '/assets/img/mainimg.jpg' }}");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        position: relative;
        overflow: hidden;
      }

      .hero-bg::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(207, 217, 22, 0.15) 0%, transparent 70%);
        animation: rotate 30s linear infinite;
        pointer-events: none;
      }

      @media (max-width: 768px) {
        .hero-bg {
          background-attachment: scroll;
        }
      }

      .hero-content h1 {
        text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.5);
        animation: fadeInUp 1s ease-out;
        font-weight: 700;
        letter-spacing: -0.02em;
      }
      
      .hero-content p {
        text-shadow: 1px 2px 4px rgba(0, 0, 0, 0.4);
        animation: fadeInUp 1.3s ease-out;
      }
      
      .hero-cta {
        box-shadow: 0 10px 40px rgba(207, 217, 22, 0.5);
        animation: fadeInUp 1.6s ease-out;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      }

      .hero-cta::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
      }

      .hero-cta:hover::before {
        left: 100%;
      }

      .hero-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 50px rgba(207, 217, 22, 0.6);
      }

      /* ==================== STATS SECTION ==================== */
      .stats-section {
        background: linear-gradient(135deg, {{ $settings['primary_color'] ?? '#CFD916' }} 0%, #a8b012 100%);
        position: relative;
        overflow: hidden;
      }

      .stats-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.5;
        pointer-events: none;
      }

      .stat-box {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        padding: 2.5rem 2rem;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
      }

      .stat-box::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.4s;
      }

      .stat-box:hover::before {
        opacity: 1;
      }

      .stat-box:hover {
        transform: translateY(-10px) scale(1.05);
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
      }

      .stat-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        border: 3px solid rgba(255, 255, 255, 0.3);
      }

      .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        display: block;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
      }

      .stat-label {
        font-size: 1.1rem;
        color: white;
        font-weight: 600;
        opacity: 0.95;
        text-transform: uppercase;
        letter-spacing: 1px;
      }

      /* ==================== FACILITY CAROUSEL ==================== */
      .facility-carousel {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.2);
      }

      .facility-images {
        display: flex;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
      }

      .facility-slide {
        flex: 0 0 100%;
        position: relative;
      }

      .facility-image {
        width: 100%;
        height: 500px;
        object-fit: cover;
        display: block;
      }

      .facility-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.9) 0%, transparent 100%);
        padding: 3rem 2.5rem 2rem;
        color: white;
      }

      .carousel-indicators {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin-top: 2rem;
      }

      .indicator {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #d1d5db;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
      }

      .indicator.active {
        background: {{ $settings['primary_color'] ?? '#CFD916' }};
        transform: scale(1.5);
        box-shadow: 0 0 20px rgba(207, 217, 22, 0.6);
        border-color: {{ $settings['primary_color'] ?? '#CFD916' }};
      }

      /* ==================== PROMO SLIDER ==================== */
      .promo-slider {
        overflow: hidden;
        position: relative;
        padding: 4rem 0;
      }

      .promo-container {
        display: flex;
        transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        gap: 2.5rem;
        padding: 0 calc(50% - 185px);
      }

      .promo-card {
        min-width: 370px;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        transform: scale(0.88);
        opacity: 0.5;
        flex-shrink: 0;
      }

      .promo-card.active {
        transform: scale(1);
        opacity: 1;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.25);
        z-index: 10;
      }

      .promo-card.clickable:hover {
        transform: scale(1.03) translateY(-8px);
        cursor: pointer;
        box-shadow: 0 35px 90px rgba(0, 0, 0, 0.3);
      }

      .promo-image {
        height: 260px;
        overflow: hidden;
        position: relative;
      }

      .promo-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
      }

      .promo-card.clickable:hover .promo-image img {
        transform: scale(1.1);
      }

      .badge {
        position: absolute;
        top: 20px;
        padding: 10px 18px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13px;
        z-index: 10;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      }

      .badge-featured {
        left: 20px;
        background: linear-gradient(135deg, #ff4757 0%, #ff6348 100%);
        color: white;
        animation: pulseBadge 2.5s ease-in-out infinite;
      }

      .badge-discount {
        right: 20px;
        background: linear-gradient(135deg, {{ $settings['primary_color'] ?? '#CFD916' }} 0%, #a8b012 100%);
        color: #000;
      }

      .nav-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: white;
        border: none;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 20;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
      }

      .nav-button:hover:not(:disabled) {
        background: {{ $settings['primary_color'] ?? '#CFD916' }};
        color: #000;
        transform: translateY(-50%) scale(1.15);
        box-shadow: 0 15px 50px rgba(207, 217, 22, 0.5);
      }

      .nav-button.prev { left: 30px; }
      .nav-button.next { right: 30px; }

      .nav-button:disabled {
        opacity: 0.4;
        cursor: not-allowed;
      }

      /* ==================== SEARCH BOX ==================== */
      .search-container {
        max-width: 600px;
        margin: 0 auto 3rem;
        position: relative;
      }

      .search-input {
        width: 100%;
        padding: 1.2rem 1.5rem 1.2rem 3.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        font-size: 1.05rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      }

      .search-input:focus {
        outline: none;
        border-color: {{ $settings['primary_color'] ?? '#CFD916' }};
        box-shadow: 0 8px 30px rgba(207, 217, 22, 0.2);
        transform: translateY(-2px);
      }

      .search-icon {
        position: absolute;
        left: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
      }

      /* ==================== BACK TO TOP ==================== */
      .back-to-top {
        position: fixed;
        bottom: 40px;
        right: 40px;
        width: 60px;
        height: 60px;
        background: {{ $settings['primary_color'] ?? '#CFD916' }};
        color: #000;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
        box-shadow: 0 10px 40px rgba(207, 217, 22, 0.5);
      }

      .back-to-top.visible {
        opacity: 1;
        visibility: visible;
      }

      .back-to-top:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 50px rgba(207, 217, 22, 0.6);
      }

      /* ==================== RESPONSIVE ==================== */
      @media (max-width: 768px) {
        .stat-number { font-size: 2.5rem; }
        .facility-image { height: 350px; }
        .promo-card { min-width: 320px; }
        .nav-button { width: 50px; height: 50px; }
        .nav-button.prev { left: 15px; }
        .nav-button.next { right: 15px; }
        .promo-container { padding: 0 calc(50% - 160px); gap: 2rem; }
        .back-to-top { width: 55px; height: 55px; bottom: 30px; right: 30px; }
      }
    </style>
  </head>
  <body>
    <!-- Scroll Progress Bar -->
    <div class="scroll-progress" id="scrollProgress"></div>

    <!-- Overlay for Mobile Menu -->
    <div id="overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300"></div>

    <!-- Navbar -->
    <nav id="navbar" class="w-full py-4 sm:py-5 lg:py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between fixed top-0 left-0 right-0 z-50">
      <a href="#" class="text-xl sm:text-2xl lg:text-3xl font-bold text-black italic">
        {{ $settings['site_name'] ?? 'MestaKara' }}<span class="text-primary">.</span>
      </a>
      
      <!-- Desktop Navigation -->
      <div class="hidden md:flex items-center gap-1">
        <a href="#home" class="nav-link text-black inline-block text-base lg:text-lg px-4 lg:px-5 py-2 hover:text-primary font-medium">Home</a>
        <a href="#about" class="nav-link text-black inline-block text-base lg:text-lg px-4 lg:px-5 py-2 hover:text-primary font-medium">Tentang Kami</a>
        <a href="#menu" class="nav-link text-black inline-block text-base lg:text-lg px-4 lg:px-5 py-2 hover:text-primary font-medium">Promo</a>
        <a href="wahana" class="nav-link text-black inline-block text-base lg:text-lg px-4 lg:px-5 py-2 hover:text-primary font-medium">Wahana</a>
      </div>

      <!-- Mobile Menu Button -->
      <button id="menuIcon" class="md:hidden text-black hover:text-primary p-2 transition-colors">
        <i data-feather="menu" class="w-6 h-6"></i>
      </button>
    </nav>

    <!-- Mobile Navigation -->
    <div id="mobileNav" class="fixed top-0 -right-full w-full sm:w-80 h-screen bg-gradient-to-b from-gray-900 to-black transition-all duration-300 z-50 pt-16" style="box-shadow: -5px 0 20px rgba(0, 0, 0, 0.5);">
      <button id="closeMenu" class="absolute top-6 right-6 text-white text-3xl hover:text-primary transition-colors">
        <i data-feather="x"></i>
      </button>
      
      <div class="flex flex-col px-4">
        <a href="#home" class="mobile-link block mx-6 my-6 py-4 text-2xl text-white border-b border-gray-700 hover:text-primary hover:pl-4 transition-all">Home</a>
        <a href="#about" class="mobile-link block mx-6 my-6 py-4 text-2xl text-white border-b border-gray-700 hover:text-primary hover:pl-4 transition-all">Tentang Kami</a>
        <a href="#menu" class="mobile-link block mx-6 my-6 py-4 text-2xl text-white border-b border-gray-700 hover:text-primary hover:pl-4 transition-all">Promo</a>
        <a href="wahana" class="mobile-link block mx-6 my-6 py-4 text-2xl text-white border-b border-gray-700 hover:text-primary hover:pl-4 transition-all">Wahana</a>
      </div>
    </div>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center justify-center hero-bg px-4 sm:px-6 lg:px-8 text-white pt-20">
      <div class="hero-content max-w-5xl w-full text-center relative z-10">
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl leading-tight mb-6">
          {{ $settings['hero_title'] ?? 'Berlibur Dengan' }}<span class="text-primary"> {{ $settings['hero_subtitle'] ?? 'Wahana' }}</span>
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl lg:text-3xl mt-4 leading-relaxed font-medium max-w-4xl mx-auto">
          {{ $settings['hero_description'] ?? 'Mari Berlibur dan Nikmati Berbagai Wahana Seru di Agrowisata Gunung Mas Bersama Keluarga Tercinta' }}
        </p>
        <a href="#menu" class="hero-cta inline-block mt-8 px-10 py-5 text-lg lg:text-xl text-black font-bold bg-primary rounded-full">
          {{ $settings['hero_cta_text'] ?? 'Dapatkan Promo' }}
        </a>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-16 sm:py-20 lg:py-24 px-4 sm:px-6 lg:px-8 relative">
      <div class="container-custom">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 max-w-4xl mx-auto">
          <div class="stat-box">
            <div class="stat-icon">
              <i data-feather="map" class="w-10 h-10 text-white"></i>
            </div>
            <span class="stat-number" data-count="{{ $facilities->count() ?? 0 }}">0</span>
            <span class="stat-label">Wahana Menarik</span>
          </div>
          
          <div class="stat-box">
            <div class="stat-icon">
              <i data-feather="tag" class="w-10 h-10 text-white"></i>
            </div>
            <span class="stat-number" data-count="{{ $promos->count() ?? 0 }}">0</span>
            <span class="stat-label">Promo Aktif</span>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 sm:py-20 md:py-28 lg:py-32 px-4 sm:px-6 lg:px-8 bg-white fade-in-section">
      <h2 class="text-center text-3xl sm:text-4xl lg:text-5xl mb-8 lg:mb-12 text-text-dark font-bold">
        <span class="text-primary">{{ $settings['about_title'] ?? 'Tentang' }}</span> {{ $settings['about_subtitle'] ?? 'Kami' }}
      </h2>
      <div class="container-custom">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
          <!-- Facility Carousel -->
          <div class="flex-1">
            @if($facilities->count() > 0)
            <div class="facility-carousel" id="facilityCarousel">
              <div class="facility-images" id="facilityImages">
                @foreach($facilities as $facility)
                <div class="facility-slide">
                  <img src="{{ asset('storage/' . $facility->image) }}" alt="{{ $facility->name }}" class="facility-image" />
                  <div class="facility-overlay">
                    <h3 class="text-2xl font-bold mb-3">{{ $facility->name }}</h3>
                    <p class="text-lg opacity-95">{{ Str::limit($facility->description, 120) }}</p>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            
            <div class="carousel-indicators" id="carouselIndicators"></div>
            @else
            <div class="bg-gray-100 rounded-3xl p-16 text-center">
              <i data-feather="image" class="w-20 h-20 mx-auto text-gray-400 mb-4"></i>
              <p class="text-gray-500 text-lg">Belum ada wahana tersedia</p>
            </div>
            @endif
            
            <div class="text-center mt-8">
              <button onclick="window.location='wahana'" class="inline-flex items-center px-8 py-4 bg-primary text-black font-bold rounded-full hover:bg-yellow-500 transition-all group hover:shadow-xl text-lg">
                Lihat Semua Wahana
                <i data-feather="arrow-right" class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform"></i>
              </button>
            </div>
          </div>
          
          <div class="flex-1 flex flex-col justify-center">
            <h3 class="text-2xl sm:text-3xl lg:text-4xl mb-4 sm:mb-6 text-text-dark font-bold">
              {{ $settings['about_question'] ?? 'Kenapa memilih Wahana kami?' }}
            </h3>
            <p class="mb-5 text-base sm:text-lg lg:text-xl leading-relaxed text-gray-700">
              {{ $settings['about_content_1'] ?? 'MestaKara adalah penyedia wahana yang didirikan dengan cinta dan dedikasi untuk menghadirkan pengalaman wahana terbaik.' }}
            </p>
            <p class="mb-5 text-base sm:text-lg lg:text-xl leading-relaxed text-gray-700">
              {{ $settings['about_content_2'] ?? 'Wahana kami didirikan langsung di tengah perkebunan terbaik dan ditata dengan presisi yang sempurna.' }}
            </p>
            <p class="text-base sm:text-lg lg:text-xl leading-relaxed text-gray-700">
              {{ $settings['about_content_3'] ?? 'Dengan lebih dari 20 wahana menarik, fasilitas lengkap, dan staff berpengalaman, kami siap memberikan pengalaman liburan yang tak terlupakan untuk seluruh keluarga.' }}
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Promo Section -->
    <section id="menu" class="py-16 sm:py-20 lg:py-24 bg-gradient-to-b from-gray-50 to-white fade-in-section">
      <h2 class="text-center text-3xl sm:text-4xl lg:text-5xl mb-6 text-text-dark font-bold">
        <span class="text-primary">Promo</span> Kami
      </h2>
      <p class="text-center max-w-2xl mx-auto leading-relaxed text-gray-600 mb-10 text-lg sm:text-xl px-4">
        Nikmati berbagai pilihan promo menarik untuk pengalaman liburan yang tak terlupakan
      </p>

      @if($promos->count() > 0)
        <!-- Search Box -->
        <div class="search-container px-4">
          <i data-feather="search" class="search-icon w-5 h-5"></i>
          <input type="text" id="searchInput" class="search-input" placeholder="Cari promo yang kamu inginkan...">
        </div>

        <div class="relative promo-slider">
          <!-- Navigation Buttons -->
          <button class="nav-button prev" id="prevBtn" aria-label="Previous">
            <i data-feather="chevron-left" class="w-7 h-7"></i>
          </button>
          
          <button class="nav-button next" id="nextBtn" aria-label="Next">
            <i data-feather="chevron-right" class="w-7 h-7"></i>
          </button>

          <!-- Slider Container -->
          <div class="promo-container" id="promoContainer">
            @foreach($promos as $promo)
              @php
                $isClickable = $promo->is_clickable;
                $buttonStatus = $promo->button_status;
              @endphp
              
              <div class="promo-card {{ $isClickable ? 'clickable' : 'non-clickable' }}" 
                   data-name="{{ strtolower($promo->name) }}"
                   @if($isClickable) onclick="window.location.href='{{ route('promo.show', $promo->id) }}'" @endif>
                   
                @if($promo->featured && $isClickable)
                  <span class="badge badge-featured">Unggulan</span>
                @endif
                
                @if($promo->discount_percent > 0 && $promo->status === 'active')
                  <span class="badge badge-discount" style="right: 20px;">Diskon {{ $promo->discount_percent }}%</span>
                @endif
                
                <div class="promo-image">
                  <img src="{{ $promo->image_url }}" alt="{{ $promo->name }}" loading="lazy">
                  
                  @if(!$isClickable)
                    <div class="absolute inset-0 bg-black bg-opacity-75 flex items-center justify-center backdrop-blur-sm z-10">
                      <div class="text-center text-white">
                        @if($promo->status_display === 'coming_soon')
                          <i data-feather="clock" class="w-12 h-12 mb-3 mx-auto"></i>
                          <span class="text-base font-semibold block">Segera Hadir</span>
                          <p class="text-sm mt-2 opacity-90">Mulai {{ $promo->start_date->format('d M Y') }}</p>
                        @elseif($promo->status_display === 'expired')
                          <i data-feather="x-circle" class="w-12 h-12 mb-3 mx-auto"></i>
                          <span class="text-base font-semibold block">Promo Berakhir</span>
                        @elseif($promo->status_display === 'sold_out')
                          <i data-feather="package" class="w-12 h-12 mb-3 mx-auto"></i>
                          <span class="text-base font-semibold block">Kuota Habis</span>
                        @endif
                      </div>
                    </div>
                  @endif
                </div>
                
                <div class="p-6">
                  <h3 class="text-xl font-bold mb-3 text-text-dark">{{ $promo->name }}</h3>
                  <p class="text-gray-600 mb-5 leading-relaxed">{{ Str::limit($promo->description, 100) }}</p>
                  
                  <div class="flex items-center justify-between mb-5">
                    <div>
                      @if($promo->discount_percent > 0)
                        <span class="text-gray-400 line-through text-sm block mb-1">Rp {{ number_format($promo->original_price, 0, ',', '.') }}</span>
                      @endif
                      <span class="text-primary font-bold text-2xl">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</span>
                    </div>
                    <span class="bg-gray-100 text-gray-700 text-sm font-bold px-4 py-2 rounded-full capitalize">
                      {{ $promo->category }}
                    </span>
                  </div>
                  
                  <div class="flex items-center justify-between text-sm text-gray-500 mb-5 pb-5 border-b border-gray-100">
                    <span class="flex items-center">
                      <i data-feather="calendar" class="w-4 h-4 mr-2"></i>
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
                      <span class="flex items-center">
                        <i data-feather="users" class="w-4 h-4 mr-2"></i>
                        Tersisa: {{ $promo->quota - $promo->actual_sold_count }}
                      </span>
                    @endif
                  </div>
                  
                  <div class="w-full text-center font-bold py-3.5 rounded-full transition-all duration-300 {{ $buttonStatus['class'] }}">
                    {{ $buttonStatus['text'] }}
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <!-- Dots Indicator -->
          <div class="flex justify-center gap-3 mt-12 flex-wrap" id="dotsContainer"></div>
        </div>
      @else
        <div class="text-center py-16 px-4">
          <div class="inline-block p-6 bg-gray-100 rounded-full mb-6">
            <i data-feather="tag" class="w-16 h-16 text-gray-400"></i>
          </div>
          <h3 class="text-2xl font-bold text-gray-600 mb-3">Tidak ada promo saat ini</h3>
          <p class="text-gray-500 text-lg">Silakan kembali lagi nanti untuk melihat promo terbaru</p>
        </div>
      @endif
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-b from-gray-900 to-black text-white pt-16 pb-8">
      <div class="container-custom">
        <div class="flex flex-col md:flex-row justify-between items-center gap-10 mb-12">
          <div class="text-center md:text-left">
            <h3 class="text-3xl sm:text-4xl font-bold italic mb-4">
              {{ $settings['site_name'] ?? 'MestaKara' }}<span class="text-primary">.</span>
            </h3>
            <p class="max-w-sm text-base lg:text-lg opacity-90 leading-relaxed">
              {{ $settings['website_description'] ?? 'Menyajikan wahana menyenangkan dengan keseruan yang tak terlupakan.' }}
            </p>
          </div>
          
          <div>
            <h4 class="text-xl font-bold mb-6 text-center md:text-left">Tautan Cepat</h4>
            <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-8">
              <a href="#home" class="hover:text-primary transition-colors text-base lg:text-lg text-center md:text-left font-medium">Home</a>
              <a href="#about" class="hover:text-primary transition-colors text-base lg:text-lg text-center md:text-left font-medium">Tentang Kami</a>
              <a href="#menu" class="hover:text-primary transition-colors text-base lg:text-lg text-center md:text-left font-medium">Promo</a>
            </div>
          </div>
          
          <div>
            <h4 class="text-xl font-bold mb-6 text-center md:text-left">Ikuti Kami</h4>
            <div class="flex justify-center md:justify-start space-x-4">
              <a href="https://www.instagram.com/wisataagro8/?hl=id" class="bg-white bg-opacity-10 p-3 rounded-full hover:bg-primary hover:text-black transition-all" aria-label="Instagram">
                <i data-feather="instagram" class="w-6 h-6"></i>
              </a>
              <a href="https://twitter.com/agrowisata_n8" class="bg-white bg-opacity-10 p-3 rounded-full hover:bg-primary hover:text-black transition-all" aria-label="Twitter">
                <i data-feather="twitter" class="w-6 h-6"></i>
              </a>
              <a href="https://www.facebook.com/AgrowisataN8/" class="bg-white bg-opacity-10 p-3 rounded-full hover:bg-primary hover:text-black transition-all" aria-label="Facebook">
                <i data-feather="facebook" class="w-6 h-6"></i>
              </a>
            </div>
          </div>
        </div>
        
        <div class="border-t border-white border-opacity-20 my-8"></div>
        
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-center md:text-left">
          <p class="text-sm lg:text-base opacity-80">
            {{ $settings['footer_text'] ?? '© 2025 Tiketmas. All rights reserved.' }}
          </p>
          <p class="text-sm lg:text-base opacity-80">
            Created by <a href="#" class="font-bold hover:text-primary transition-colors">{{ $settings['site_name'] ?? 'Mestakara' }}</a>
          </p>
        </div>
      </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" aria-label="Back to top">
      <i data-feather="arrow-up" class="w-7 h-7"></i>
    </button>

    <!-- ==================== JAVASCRIPT ==================== -->
    <script>
      // Initialize Feather icons
      feather.replace();

      // ==================== UTILITY FUNCTIONS ====================
      const $ = (selector) => document.querySelector(selector);
      const $ = (selector) => document.querySelectorAll(selector);

      // ==================== SCROLL PROGRESS BAR ====================
      const updateScrollProgress = () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        $('#scrollProgress').style.width = scrolled + '%';
      };

      window.addEventListener('scroll', updateScrollProgress, { passive: true });

      // ==================== NAVBAR SCROLL EFFECT ====================
      let lastScroll = 0;
      const navbar = $('#navbar');
      
      window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        navbar.classList.toggle('scrolled', currentScroll > 100);
        lastScroll = currentScroll;
      }, { passive: true });

      // ==================== BACK TO TOP BUTTON ====================
      const backToTop = $('#backToTop');
      
      window.addEventListener('scroll', () => {
        backToTop.classList.toggle('visible', window.pageYOffset > 400);
      }, { passive: true });

      backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });

      // ==================== MOBILE MENU ====================
      class MobileMenu {
        constructor() {
          this.menu = $('#mobileNav');
          this.menuIcon = $('#menuIcon');
          this.closeBtn = $('#closeMenu');
          this.overlay = $('#overlay');
          this.isOpen = false;
          this.init();
        }

        init() {
          this.menuIcon.addEventListener('click', (e) => {
            e.stopPropagation();
            this.open();
          });

          this.closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.close();
          });

          this.overlay.addEventListener('click', () => this.close());

          document.addEventListener('click', (e) => {
            const isClickInside = e.target.closest('#mobileNav') || e.target.closest('#menuIcon');
            if (!isClickInside && this.isOpen) this.close();
          });

          $('.mobile-link').forEach(link => {
            link.addEventListener('click', () => this.close());
          });
        }

        open() {
          this.menu.style.right = '0';
          this.overlay.classList.remove('hidden');
          document.body.style.overflow = 'hidden';
          this.isOpen = true;
        }

        close() {
          this.menu.style.right = '-100%';
          this.overlay.classList.add('hidden');
          document.body.style.overflow = '';
          this.isOpen = false;
        }
      }

      // ==================== SMOOTH SCROLLING ====================
      $('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          const href = this.getAttribute('href');
          if (href === '#') return;
          
          e.preventDefault();
          const target = $(href);
          if (!target) return;
          
          const navbarHeight = $('nav').offsetHeight;
          const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
          
          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
        });
      });

      // ==================== ANIMATED COUNTER ====================
      const animateCounter = (element, target, duration = 2000) => {
        let start = 0;
        const increment = target / (duration / 16);
        
        const timer = setInterval(() => {
          start += increment;
          if (start >= target) {
            element.textContent = target;
            clearInterval(timer);
          } else {
            element.textContent = Math.floor(start);
          }
        }, 16);
      };

      // ==================== STATS COUNTER ====================
      const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            $('.stat-number').forEach(stat => {
              const target = parseInt(stat.getAttribute('data-count'));
              animateCounter(stat, target);
            });
            statsObserver.unobserve(entry.target);
          }
        });
      }, { threshold: 0.5 });

      const statsSection = $('.stats-section');
      if (statsSection) statsObserver.observe(statsSection);

      // ==================== SECTION FADE IN ANIMATION ====================
      const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            sectionObserver.unobserve(entry.target);
          }
        });
      }, { threshold: 0.15, rootMargin: '0px 0px -100px 0px' });

      $('.fade-in-section').forEach(section => sectionObserver.observe(section));

      // ==================== FACILITY CAROUSEL ====================
      class FacilityCarousel {
        constructor() {
          this.container = $('#facilityImages');
          if (!this.container) return;
          
          this.slides = $('.facility-slide');
          this.totalSlides = this.slides.length;
          this.indicatorsContainer = $('#carouselIndicators');
          this.currentIndex = 0;
          this.autoPlayInterval = null;
          
          if (this.totalSlides > 0) this.init();
        }

        init() {
          this.createIndicators();
          this.updateSlide();
          this.startAutoPlay();
          this.bindEvents();
        }

        createIndicators() {
          this.indicatorsContainer.innerHTML = '';
          for (let i = 0; i < this.totalSlides; i++) {
            const indicator = document.createElement('div');
            indicator.className = `indicator ${i === 0 ? 'active' : ''}`;
            indicator.addEventListener('click', () => this.goToSlide(i));
            this.indicatorsContainer.appendChild(indicator);
          }
        }

        bindEvents() {
          const carousel = $('#facilityCarousel');
          
          carousel.addEventListener('mouseenter', () => this.pauseAutoPlay());
          carousel.addEventListener('mouseleave', () => this.startAutoPlay());

          let startX = 0, isDragging = false;

          this.container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
            this.pauseAutoPlay();
          }, { passive: true });

          this.container.addEventListener('touchend', (e) => {
            if (!isDragging) return;
            const endX = e.changedTouches[0].clientX;
            const diffX = startX - endX;
            
            if (Math.abs(diffX) > 50) {
              diffX > 0 ? this.nextSlide() : this.prevSlide();
            }
            
            isDragging = false;
            this.startAutoPlay();
          }, { passive: true });
        }

        updateSlide() {
          this.container.style.transform = `translateX(-${this.currentIndex * 100}%)`;
          
          $('.indicator').forEach((ind, idx) => {
            ind.classList.toggle('active', idx === this.currentIndex);
          });
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
          this.pauseAutoPlay();
          this.autoPlayInterval = setInterval(() => this.nextSlide(), 5000);
        }

        pauseAutoPlay() {
          if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
          }
        }
      }

      // ==================== PROMO SLIDER ====================
      class PromoSlider {
        constructor() {
          this.container = $('#promoContainer');
          if (!this.container) return;
          
          this.cards = Array.from($('.promo-card'));
          this.totalCards = this.cards.length;
          this.currentIndex = 0;
          this.prevBtn = $('#prevBtn');
          this.nextBtn = $('#nextBtn');
          this.dotsContainer = $('#dotsContainer');
          this.searchInput = $('#searchInput');
          
          if (this.totalCards > 0) this.init();
        }

        init() {
          this.createDots();
          this.updateSlider();
          this.bindEvents();
          if (this.searchInput) this.initSearch();
        }

        createDots() {
          this.dotsContainer.innerHTML = '';
          for (let i = 0; i < this.totalCards; i++) {
            const dot = document.createElement('div');
            dot.className = `w-4 h-4 rounded-full bg-gray-300 cursor-pointer transition-all ${i === 0 ? 'bg-primary scale-125' : ''}`;
            dot.addEventListener('click', () => this.goToSlide(i));
            this.dotsContainer.appendChild(dot);
          }
        }

        bindEvents() {
          this.prevBtn.addEventListener('click', () => this.prevSlide());
          this.nextBtn.addEventListener('click', () => this.nextSlide());

          let startX = 0, isDragging = false;

          this.container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            isDragging = true;
          }, { passive: true });

          this.container.addEventListener('touchend', (e) => {
            if (!isDragging) return;
            const endX = e.changedTouches[0].clientX;
            const diffX = startX - endX;
            
            if (Math.abs(diffX) > 50) {
              diffX > 0 ? this.nextSlide() : this.prevSlide();
            }
            
            isDragging = false;
          }, { passive: true });

          window.addEventListener('resize', () => this.updateSlider(), { passive: true });
        }

        initSearch() {
          let searchTimeout;
          this.searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
              this.filterCards(e.target.value.toLowerCase());
            }, 300);
          });
        }

        filterCards(searchTerm) {
          let visibleCount = 0;
          
          this.cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const isVisible = name.includes(searchTerm);
            card.style.display = isVisible ? 'block' : 'none';
            if (isVisible) visibleCount++;
          });
          
          if (visibleCount === 0) {
            this.showNoResults();
          } else {
            this.hideNoResults();
            this.currentIndex = 0;
            this.updateSlider();
          }
        }

        showNoResults() {
          let noResults = $('#noResults');
          if (!noResults) {
            noResults = document.createElement('div');
            noResults.id = 'noResults';
            noResults.className = 'text-center py-16 px-4';
            noResults.innerHTML = `
              <div class="inline-block p-6 bg-gray-100 rounded-full mb-6">
                <i data-feather="search" class="w-16 h-16 text-gray-400"></i>
              </div>
              <h3 class="text-2xl font-bold text-gray-600 mb-3">Promo tidak ditemukan</h3>
              <p class="text-gray-500 text-lg">Coba kata kunci lain</p>
            `;
            this.container.parentElement.appendChild(noResults);
            feather.replace();
          }
          
          this.container.style.display = 'none';
          this.dotsContainer.style.display = 'none';
          this.prevBtn.style.display = 'none';
          this.nextBtn.style.display = 'none';
        }

        hideNoResults() {
          const noResults = $('#noResults');
          if (noResults) noResults.remove();
          
          this.container.style.display = 'flex';
          this.dotsContainer.style.display = 'flex';
          this.prevBtn.style.display = 'flex';
          this.nextBtn.style.display = 'flex';
        }

        updateSlider() {
          this.cards.forEach((card, idx) => {
            card.classList.toggle('active', idx === this.currentIndex);
          });

          const dots = this.dotsContainer.children;
          Array.from(dots).forEach((dot, idx) => {
            if (idx === this.currentIndex) {
              dot.className = 'w-4 h-4 rounded-full bg-primary scale-125 cursor-pointer transition-all';
            } else {
              dot.className = 'w-4 h-4 rounded-full bg-gray-300 cursor-pointer transition-all';
            }
          });

          const cardWidth = this.cards[0]?.offsetWidth || 370;
          const gap = window.innerWidth < 768 ? 32 : 40;
          const offset = -this.currentIndex * (cardWidth + gap);
          this.container.style.transform = `translateX(${offset}px)`;

          this.prevBtn.disabled = this.currentIndex === 0;
          this.nextBtn.disabled = this.currentIndex === this.totalCards - 1;
        }

        nextSlide() {
          if (this.currentIndex < this.totalCards - 1) {
            this.currentIndex++;
            this.updateSlider();
          }
        }

        prevSlide() {
          if (this.currentIndex > 0) {
            this.currentIndex--;
            this.updateSlider();
          }
        }

        goToSlide(index) {
          this.currentIndex = index;
          this.updateSlider();
        }
      }

      // ==================== INITIALIZE ALL ====================
      document.addEventListener('DOMContentLoaded', () => {
        new MobileMenu();
        new FacilityCarousel();
        new PromoSlider();
        
        // Re-initialize Feather icons after dynamic content
        setTimeout(() => feather.replace(), 100);
      });
    </script>
  </body>
</html>