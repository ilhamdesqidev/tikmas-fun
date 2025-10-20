<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MestaKara - Dashboard</title>
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
          url("https://images.unsplash.com/photo-1520639888713-7851133b1ed0?w=1920");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
      }
      
      @media (max-width: 768px) {
        .hero-bg {
          background-attachment: scroll;
          min-height: 100svh;
        }
        
        .hero-content {
          padding-bottom: 3rem;
        }
      }
      
      .hero h1 {
        text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8);
        line-height: 1.2;
      }
      
      .hero p {
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
      }
      
      .hero .cta {
        box-shadow: 0 4px 15px rgba(207, 217, 22, 0.4);
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
        width: 300%;
      }

      .facility-slide {
        flex: 0 0 33.333%;
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
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.85));
        padding: 2.5rem 1.5rem 1.5rem;
        color: white;
      }

      .facility-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
      }

      .facility-description {
        font-size: 1rem;
        opacity: 0.95;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
        line-height: 1.5;
      }

      .carousel-indicators {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 1.25rem;
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
        background: #CFD916;
        transform: scale(1.4);
      }

      @media (max-width: 1024px) {
        .facility-image {
          height: 320px;
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
          font-size: 0.9rem;
        }
        
        .facility-overlay {
          padding: 2rem 1.25rem 1rem;
        }
      }

      @media (max-width: 480px) {
        .facility-image {
          height: 260px;
        }
        
        .facility-title {
          font-size: 1.125rem;
        }
        
        .facility-description {
          font-size: 0.85rem;
          line-height: 1.4;
        }
        
        .facility-overlay {
          padding: 1.75rem 1rem 0.875rem;
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
        background: #CFD916;
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
        border: 2px solid #CFD916;
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
        background: #CFD916;
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
        background: #CFD916;
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
          width: 45px;
          height: 45px;
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
          height: 220px;
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
          width: 40px;
          height: 40px;
        }
        
        .promo-image {
          height: 200px;
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
        
        .promo-image {
          height: 180px;
        }
      }
      
      /* Mobile Navbar Improvements */
      @media (max-width: 768px) {
        .navbar-mobile-optimized {
          padding: 0.75rem 1rem;
        }
        
        .navbar-mobile-optimized a {
          font-size: 1.25rem;
        }
      }
    </style>
  </head>
  <body class="font-poppins bg-white text-text-dark">
    <!-- Overlay -->
    <div id="overlay" class="hidden fixed top-0 left-0 w-full h-screen bg-black bg-opacity-50 z-40 transition-opacity duration-300"></div>

    <!-- Navbar -->
    <nav class="navbar-mobile-optimized w-full py-3 sm:py-4 lg:py-5 px-4 sm:px-6 lg:px-7 flex items-center justify-between bg-white border-b border-gray-400 fixed top-0 left-0 right-0 z-50" style="border-bottom: 1px solid #597336;">
      <a href="#" class="text-xl sm:text-2xl lg:text-3xl font-bold text-black italic">
        MestaKara<span class="text-primary">.</span>
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
        <a href="#wahana" class="text-black inline-block text-base lg:text-xl ml-0 px-3 lg:px-4 hover:text-primary transition-all duration-500 relative group">
          Wahana
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
      </div>

      <!-- Mobile Navigation -->
      <div id="mobile-nav" class="fixed top-0 -right-full w-full sm:w-80 h-screen bg-black transition-all duration-300 z-50 pt-16" style="box-shadow: -5px 0 15px rgba(0, 0, 0, 0.5);">
        <div id="close-menu" class="absolute top-4 sm:top-6 right-4 sm:right-6 text-white cursor-pointer text-3xl sm:text-4xl touch-manipulation">
          <i data-feather="x"></i>
        </div>
        
        <div class="flex flex-col px-2">
          <a href="#home" class="block mx-4 my-4 py-3 text-lg sm:text-xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-3 touch-manipulation">Home</a>
          <a href="#about" class="block mx-4 my-4 py-3 text-lg sm:text-xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-3 touch-manipulation">Tentang Kami</a>
          <a href="#menu" class="block mx-4 my-4 py-3 text-lg sm:text-xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-3 touch-manipulation">Promo</a>
          <a href="#wahana" class="block mx-4 my-4 py-3 text-lg sm:text-xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-3 touch-manipulation">Wahana</a>
        </div>
      </div>

      <!-- Navbar Extra -->
      <div class="flex items-center gap-2 sm:gap-3">
        <a href="#" class="text-black hover:text-primary transition-all duration-500 p-2 touch-manipulation"><i data-feather="search" class="w-5 h-5 sm:w-5 sm:h-5 lg:w-6 lg:h-6"></i></a>
        <a href="#" class="text-black hover:text-primary transition-all duration-500 p-2 touch-manipulation"><i data-feather="shopping-cart" class="w-5 h-5 sm:w-5 sm:h-5 lg:w-6 lg:h-6"></i></a>
        <a href="#" id="menu-icon" class="text-black hover:text-primary transition-all duration-500 md:hidden cursor-pointer p-2 touch-manipulation">
          <i data-feather="menu" class="w-6 h-6"></i>
        </a>
      </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero min-h-screen flex items-center hero-bg relative px-4 sm:px-6 lg:px-7 text-white pt-20 sm:pt-16">
      <main class="hero-content max-w-4xl w-full">
        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl text-white leading-tight mb-4 sm:mb-6">
          Berlibur Dengan<span class="text-primary"> Wahana</span>
        </h1>
        <p class="text-sm sm:text-base md:text-lg lg:text-xl mt-3 sm:mt-4 leading-relaxed font-medium text-white max-w-2xl">
          Mari Berlibur dan Nikmati Berbagai Wahana Seru di Agrowisata Gunung Mas Bersama Keluarga Tercinta Dengan Harga Tiket Masuk yang Terjangkau dan Dapatkan Berbagai Promo Menarik Setiap Bulannya
        </p>
        <a href="#menu" class="cta inline-block mt-6 sm:mt-8 px-6 sm:px-8 lg:px-12 py-3 sm:py-4 text-sm sm:text-base lg:text-lg text-black font-semibold bg-primary rounded-lg hover:bg-yellow-500 transition-colors duration-300 touch-manipulation">
          Dapatkan Promo
        </a>
      </main>
    </section>

    <!-- About Section -->
    <section id="about" class="py-12 sm:py-16 md:py-20 lg:py-24 px-4 sm:px-6 lg:px-7">
      <h2 class="text-center text-2xl sm:text-3xl lg:text-4xl mb-8 sm:mb-10 lg:mb-12 text-text-dark font-bold">
        <span class="text-primary">Tentang</span> Kami
      </h2>
      <div class="flex flex-col lg:flex-row gap-8 lg:gap-10 max-w-6xl mx-auto">
        <!-- Facility Carousel -->
        <div class="flex-1 w-full">
          <div class="facility-carousel" id="facilityCarousel">
            <div class="facility-images" id="facilityImages">
              <div class="facility-slide">
                <img src="https://images.unsplash.com/photo-1594818379496-da1e345b0ded?w=800" alt="Wahana 1" class="facility-image" />
                <div class="facility-overlay">
                  <div class="facility-title">Wahana Petualangan</div>
                  <div class="facility-description">Nikmati pengalaman petualangan yang mendebarkan dengan berbagai wahana seru</div>
                </div>
              </div>
              <div class="facility-slide">
                <img src="https://images.unsplash.com/photo-1519452575417-564c1401ecc0?w=800" alt="Wahana 2" class="facility-image" />
                <div class="facility-overlay">
                  <div class="facility-title">Taman Bermain</div>
                  <div class="facility-description">Area bermain yang aman dan menyenangkan untuk seluruh keluarga</div>
                </div>
              </div>
              <div class="facility-slide">
                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800" alt="Wahana 3" class="facility-image" />
                <div class="facility-overlay">
                  <div class="facility-title">Wisata Alam</div>
                  <div class="facility-description">Jelajahi keindahan alam dengan pemandangan yang memukau</div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Indicators -->
          <div class="carousel-indicators" id="carouselIndicators"></div>
          
          <!-- Tombol Lihat Lebih Banyak -->
          <div class="text-center mt-6">
            <button onclick="alert('Menuju halaman wahana')"
                    class="inline-flex items-center px-6 py-3 bg-primary text-black font-semibold rounded-lg hover:bg-yellow-500 transition-colors duration-300 group touch-manipulation">
              Lihat Lebih Banyak
              <i data-feather="chevron-down"
                class="w-5 h-5 ml-2 group-hover:transform group-hover:translate-y-1 transition-transform duration-300"></i>
            </button>
          </div>
        </div>
        
        <div class="flex-1 w-full lg:px-4">
          <h3 class="text-xl sm:text-2xl lg:text-3xl mb-4 text-text-dark font-semibold">
            Kenapa memilih Wahana kami?
          </h3>
          <p class="mb-4 text-sm sm:text-base lg:text-lg font-medium leading-relaxed text-text-dark">
            MestaKara adalah penyedia wahana yang didirikan dengan cinta dan dedikasi untuk menghadirkan pengalaman wahana terbaik. Kami percaya bahwa setiap tawa dapat menciptakan kenangan indah yang akan diingat selamanya.
          </p>
          <p class="mb-4 text-sm sm:text-base lg:text-lg font-medium leading-relaxed text-text-dark">
            Wahana kami didirikan langsung di tengah perkebunan terbaik dan ditata dengan presisi yang sempurna. Setiap wahana yang kami sediakan adalah hasil dari perpaduan tradisi dan kualitas premium.
          </p>
          <p class="text-sm sm:text-base lg:text-lg font-medium leading-relaxed text-text-dark">
            Dengan lebih dari 20 wahana menarik, fasilitas lengkap, dan staff berpengalaman, kami siap memberikan pengalaman liburan yang tak terlupakan untuk seluruh keluarga.
          </p>
        </div>
      </div>
    </section>

    <!-- Promo Section -->
    <section id="menu" class="py-8 sm:py-12 bg-gray-50">
      <h2 class="text-center text-2xl sm:text-3xl lg:text-4xl mb-3 sm:mb-4 text-text-dark font-bold">
        <span class="text-primary">Promo</span> Kami
      </h2>
      <p class="text-center max-w-lg mx-auto font-medium leading-relaxed text-text-dark mb-8 sm:mb-12 lg:mb-16 text-sm sm:text-base lg:text-lg px-4">
        Nikmati berbagai pilihan promo menarik untuk pengalaman liburan yang tak terlupakan
      </p>
      
      <div class="relative promo-slider">
        <!-- Navigation Buttons -->
        <button class="nav-button prev" id="prevBtn">
          <i data-feather="chevron-left" class="w-5 h-5 sm:w-6 sm:h-6"></i>
        </button>
        
        <button class="nav-button next" id="nextBtn">
          <i data-feather="chevron-right" class="w-5 h-5 sm:w-6 sm:h-6"></i>
        </button>

        <!-- Slider Container -->
        <div class="promo-container" id="promoContainer">
          <!-- Promo Card 1 -->
          <div class="promo-card clickable active" onclick="alert('Detail Promo 1')">
            <span class="featured-badge">Unggulan</span>
            <span class="badge-discount status-badge">Diskon 20%</span>
            
            <div class="promo-image">
              <img src="https://images.unsplash.com/photo-1517457373958-b7bdd4587205?w=400" alt="Promo 1" loading="lazy">
            </div>
            
            <div class="p-5 sm:p-6">
              <h3 class="text-lg sm:text-xl font-bold mb-2 text-text-dark">Paket Keluarga Hemat</h3>
              <p class="text-gray-600 mb-4 text-sm sm:text-base">Nikmati liburan bersama keluarga dengan harga spesial untuk 4 orang</p>
              
              <div class="flex items-center justify-between mb-4">
                <div>
                  <span class="text-gray-400 line-through text-xs sm:text-sm">Rp 500.000</span>
                  <span class="text-primary font-bold text-lg sm:text-xl block">Rp 400.000</span>
                </div>
                <span class="bg-gray-100 text-gray-700 text-xs sm:text-sm font-semibold px-3 py-1 rounded-full">
                  Keluarga
                </span>
              </div>
              
              <div class="flex items-center justify-between text-xs sm:text-sm text-gray-500 mb-4">
                <span>Sampai: 31 Des 2025</span>
                <span>Tersisa: 50</span>
              </div>
              
              <div class="w-full text-center font-semibold py-3 rounded-lg bg-primary text-black hover:bg-yellow-500 transition-colors cursor-pointer">
                Pesan Sekarang
              </div>
            </div>
          </div>

          <!-- Promo Card 2 -->
          <div class="promo-card clickable" onclick="alert('Detail Promo 2')">
            <span class="badge-discount status-badge">Diskon 15%</span>
            
            <div class="promo-image">
              <img src="https://images.unsplash.com/photo-1519452635265-7b1fbfd1e4e0?w=400" alt="Promo 2" loading="lazy">
            </div>
            
            <div class="p-5 sm:p-6">
              <h3 class="text-lg sm:text-xl font-bold mb-2 text-text-dark">Weekend Special</h3>
              <p class="text-gray-600 mb-4 text-sm sm:text-base">Promo spesial untuk akhir pekan bersama orang tersayang</p>
              
              <div class="flex items-center justify-between mb-4">
                <div>
                  <span class="text-gray-400 line-through text-xs sm:text-sm">Rp 300.000</span>
                  <span class="text-primary font-bold text-lg sm:text-xl block">Rp 255.000</span>
                </div>
                <span class="bg-gray-100 text-gray-700 text-xs sm:text-sm font-semibold px-3 py-1 rounded-full">
                  Couple
                </span>
              </div>
              
              <div class="flex items-center justify-between text-xs sm:text-sm text-gray-500 mb-4">
                <span>Sampai: 30 Nov 2025</span>
                <span>Tersisa: 30</span>
              </div>
              
              <div class="w-full text-center font-semibold py-3 rounded-lg bg-primary text-black hover:bg-yellow-500 transition-colors cursor-pointer">
                Pesan Sekarang
              </div>
            </div>
          </div>

          <!-- Promo Card 3 - Coming Soon -->
          <div class="promo-card non-clickable promo-disabled">
            <span class="badge-coming-soon status-badge">Segera Hadir</span>
            
            <div class="promo-image">
              <img src="https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=400" alt="Promo 3" loading="lazy">
              
              <div class="promo-overlay-disabled">
                <div class="overlay-content text-center">
                  <i data-feather="clock" class="w-8 h-8 mb-2 mx-auto"></i>
                  <span class="text-sm font-medium block">Segera Hadir</span>
                  <p class="text-xs mt-1">Mulai 1 Jan 2026</p>
                </div>
              </div>
            </div>
            
            <div class="p-5 sm:p-6">
              <h3 class="text-lg sm:text-xl font-bold mb-2 text-text-dark">Tahun Baru Meriah</h3>
              <p class="text-gray-600 mb-4 text-sm sm:text-base">Rayakan tahun baru dengan promo istimewa untuk keluarga</p>
              
              <div class="flex items-center justify-between mb-4">
                <div>
                  <span class="text-primary font-bold text-lg sm:text-xl block">Rp 600.000</span>
                </div>
                <span class="bg-gray-100 text-gray-700 text-xs sm:text-sm font-semibold px-3 py-1 rounded-full">
                  Special
                </span>
              </div>
              
              <div class="flex items-center justify-between text-xs sm:text-sm text-gray-500 mb-4">
                <span>Mulai: 1 Jan 2026</span>
              </div>
              
              <div class="w-full text-center font-semibold py-3 rounded-lg bg-gray-300 text-gray-600 cursor-not-allowed">
                Segera Hadir
              </div>
            </div>
          </div>

          <!-- Promo Card 4 -->
          <div class="promo-card clickable" onclick="alert('Detail Promo 4')">
            <span class="badge-discount status-badge">Diskon 25%</span>
            
            <div class="promo-image">
              <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400" alt="Promo 4" loading="lazy">
            </div>
            
            <div class="p-5 sm:p-6">
              <h3 class="text-lg sm:text-xl font-bold mb-2 text-text-dark">Adventure Pack</h3>
              <p class="text-gray-600 mb-4 text-sm sm:text-base">Paket lengkap untuk petualangan seru di semua wahana</p>
              
              <div class="flex items-center justify-between mb-4">
                <div>
                  <span class="text-gray-400 line-through text-xs sm:text-sm">Rp 800.000</span>
                  <span class="text-primary font-bold text-lg sm:text-xl block">Rp 600.000</span>
                </div>
                <span class="bg-gray-100 text-gray-700 text-xs sm:text-sm font-semibold px-3 py-1 rounded-full">
                  Premium
                </span>
              </div>
              
              <div class="flex items-center justify-between text-xs sm:text-sm text-gray-500 mb-4">
                <span>Tidak terbatas</span>
                <span>Tersisa: 100</span>
              </div>
              
              <div class="w-full text-center font-semibold py-3 rounded-lg bg-primary text-black hover:bg-yellow-500 transition-colors cursor-pointer">
                Pesan Sekarang
              </div>
            </div>
          </div>
        </div>

        <!-- Dots Indicator -->
        <div class="dots-container" id="dotsContainer"></div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-white pt-10 sm:pt-12 lg:pt-16 pb-6 sm:pb-8">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 sm:gap-10 mb-8 sm:mb-10 lg:mb-12">
          <div class="text-center md:text-left">
            <h3 class="text-2xl sm:text-3xl font-bold italic mb-3 sm:mb-4">
              MestaKara<span class="text-primary">.</span>
            </h3>
            <p class="max-w-xs mx-auto md:mx-0 text-sm sm:text-base opacity-90 leading-relaxed">
              Menyajikan wahana menyenangkan dengan keseruan yang tak terlupakan bersama keluarga tercinta.
            </p>
          </div>
          
          <div class="text-center">
            <h4 class="text-base sm:text-lg font-semibold mb-4 sm:mb-5">Tautan Cepat</h4>
            <div class="flex flex-col space-y-3">
              <a href="#home" class="hover:text-primary transition-colors duration-300 text-sm sm:text-base touch-manipulation">Home</a>
              <a href="#about" class="hover:text-primary transition-colors duration-300 text-sm sm:text-base touch-manipulation">Tentang Kami</a>
              <a href="#menu" class="hover:text-primary transition-colors duration-300 text-sm sm:text-base touch-manipulation">Promo</a>
              <a href="#wahana" class="hover:text-primary transition-colors duration-300 text-sm sm:text-base touch-manipulation">Wahana</a>
            </div>
          </div>
          
          <div class="text-center md:text-right">
            <h4 class="text-base sm:text-lg font-semibold mb-4 sm:mb-5">Ikuti Kami</h4>
            <div class="flex justify-center md:justify-end space-x-4">
              <a href="https://www.instagram.com/wisataagro8/?hl=id" class="bg-white bg-opacity-20 p-3 rounded-full hover:bg-opacity-30 hover:scale-110 transition-all duration-300 touch-manipulation">
                <i data-feather="instagram" class="w-5 h-5"></i>
              </a>
              <a href="https://twitter.com/agrowisata_n8" class="bg-white bg-opacity-20 p-3 rounded-full hover:bg-opacity-30 hover:scale-110 transition-all duration-300 touch-manipulation">
                <i data-feather="twitter" class="w-5 h-5"></i>
              </a>
              <a href="https://www.facebook.com/AgrowisataN8/" class="bg-white bg-opacity-20 p-3 rounded-full hover:bg-opacity-30 hover:scale-110 transition-all duration-300 touch-manipulation">
                <i data-feather="facebook" class="w-5 h-5"></i>
              </a>
            </div>
            <div class="mt-6">
              <p class="text-sm opacity-80 mb-2">Hubungi Kami</p>
              <a href="tel:+6281234567890" class="text-primary hover:text-yellow-400 transition-colors text-sm sm:text-base font-medium">
                +62 812-3456-7890
              </a>
            </div>
          </div>
        </div>
        
        <div class="border-t border-white border-opacity-30 pt-6 sm:pt-8"></div>
        
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 text-center sm:text-left">
          <p class="text-xs sm:text-sm opacity-80">
            © 2025 MestaKara. All rights reserved.
          </p>
          <p class="text-xs sm:text-sm opacity-80">
            Created with <span class="text-primary">❤</span> by <a href="#" class="font-bold hover:text-primary transition-colors">MestaKara Team</a>
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