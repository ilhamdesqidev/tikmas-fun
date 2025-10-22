<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $settings['site_name'] ?? 'MestaKara' }} - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,600;0,700;1,700&display=swap" rel="stylesheet" />
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
        background: #fafafa;
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
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      }

      nav.scrolled {
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        background: rgba(255, 255, 255, 0.95);
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
      }

      @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
      }

      @media (max-width: 768px) {
        .hero-bg {
          background-attachment: scroll;
        }
      }
      
      .hero h1 {
        text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.5);
        animation: fadeInUp 1s ease-out;
        font-weight: 700;
        letter-spacing: -0.02em;
      }
      
      .hero p {
        text-shadow: 1px 2px 4px rgba(0, 0, 0, 0.4);
        animation: fadeInUp 1.3s ease-out;
      }
      
      .hero .cta {
        box-shadow: 0 10px 40px rgba(207, 217, 22, 0.5);
        animation: fadeInUp 1.6s ease-out;
        position: relative;
        overflow: hidden;
        transform: translateY(0);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      }

      .hero .cta::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
      }

      .hero .cta:hover::before {
        left: 100%;
      }

      .hero .cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 50px rgba(207, 217, 22, 0.6);
      }

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

      /* ==================== STATS SECTION ==================== */
      .stats-section {
        background: linear-gradient(135deg, {{ $settings['primary_color'] ?? '#CFD916' }} 0%, #a8b012 100%);
        position: relative;
        overflow: hidden;
      }

      .stats-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.5;
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

      @media (max-width: 768px) {
        .stat-number {
          font-size: 2.5rem;
        }
        .stat-label {
          font-size: 1rem;
        }
      }

      /* ==================== SECTION ANIMATIONS ==================== */
      .fade-in-section {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 1s ease-out, transform 1s ease-out;
      }

      .fade-in-section.visible {
        opacity: 1;
        transform: translateY(0);
      }

      /* ==================== FACILITY CAROUSEL ==================== */
      .facility-carousel {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.2);
        max-width: 100%;
      }

      .facility-images {
        display: flex;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        width: {{ $facilities->count() > 0 ? ($facilities->count() * 100) : 100 }}%;
      }

      .facility-slide {
        flex: 0 0 {{ $facilities->count() > 0 ? (100 / $facilities->count()) : 100 }}%;
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

      .facility-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
      }

      .facility-description {
        font-size: 1.1rem;
        opacity: 0.95;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        line-height: 1.6;
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

      @media (max-width: 1024px) {
        .facility-image {
          height: 420px;
        }
      }

      @media (max-width: 768px) {
        .facility-image {
          height: 350px;
        }
        
        .facility-title {
          font-size: 1.5rem;
        }
        
        .facility-description {
          font-size: 0.95rem;
        }
        
        .facility-overlay {
          padding: 2rem 1.5rem 1.5rem;
        }
      }

      /* ==================== PROMO SLIDER ==================== */
      .promo-slider {
        overflow: hidden;
        position: relative;
        padding: 4rem 0;
        max-width: 100%;
      }

      .promo-container {
        display: flex;
        transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        gap: 2.5rem;
        padding: 0 calc(50% - 185px);
      }

      .promo-card {
        min-width: 370px;
        max-width: 370px;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        margin-bottom: 10px;
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

      .promo-card.non-clickable {
        cursor: not-allowed;
      }

      .promo-disabled {
        opacity: 0.65;
        filter: grayscale(0.4);
      }

      .promo-overlay-disabled {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.75);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        z-index: 5;
        backdrop-filter: blur(5px);
      }

      .status-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 10px 18px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13px;
        z-index: 10;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      }

      .badge-coming-soon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
      }

      .badge-expired {
        background: rgba(107, 114, 128, 0.95);
        color: white;
      }

      .badge-sold-out {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
      }

      .badge-discount {
        background: linear-gradient(135deg, {{ $settings['primary_color'] ?? '#CFD916' }} 0%, #a8b012 100%);
        color: #000;
        box-shadow: 0 4px 20px rgba(207, 217, 22, 0.5);
      }

      .featured-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: linear-gradient(135deg, #ff4757 0%, #ff6348 100%);
        color: white;
        padding: 10px 18px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13px;
        z-index: 10;
        box-shadow: 0 4px 20px rgba(255, 71, 87, 0.4);
        animation: pulse-badge 2.5s ease-in-out infinite;
      }

      @keyframes pulse-badge {
        0%, 100% {
          box-shadow: 0 0 0 0 rgba(255, 71, 87, 0.7);
        }
        50% {
          box-shadow: 0 0 0 15px rgba(255, 71, 87, 0);
        }
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

      .nav-button:hover {
        background: {{ $settings['primary_color'] ?? '#CFD916' }};
        color: #000;
        transform: translateY(-50%) scale(1.15);
        box-shadow: 0 15px 50px rgba(207, 217, 22, 0.5);
      }

      .nav-button.prev {
        left: 30px;
      }

      .nav-button.next {
        right: 30px;
      }

      .nav-button:disabled {
        opacity: 0.4;
        cursor: not-allowed;
      }

      .nav-button:disabled:hover {
        background: white;
        color: inherit;
        transform: translateY(-50%) scale(1);
      }

      .dots-container {
        display: flex;
        justify-content: center;
        gap: 14px;
        margin-top: 3rem;
        flex-wrap: wrap;
      }

      .dot {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #ddd;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
      }

      .dot.active {
        background: {{ $settings['primary_color'] ?? '#CFD916' }};
        transform: scale(1.4);
        border-color: {{ $settings['primary_color'] ?? '#CFD916' }};
        box-shadow: 0 0 20px rgba(207, 217, 22, 0.6);
      }

      .dot:hover {
        background: #a8b012;
        transform: scale(1.2);
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

      /* ==================== BACK TO TOP BUTTON ==================== */
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

      /* ==================== RESPONSIVE ADJUSTMENTS ==================== */
      @media (max-width: 1024px) {
        .promo-container {
          padding: 0 calc(50% - 175px);
        }
        
        .promo-card {
          min-width: 350px;
          max-width: 350px;
        }
      }

      @media (max-width: 768px) {
        .promo-card {
          min-width: 320px;
          max-width: 320px;
        }

        .nav-button {
          width: 50px;
          height: 50px;
        }

        .nav-button.prev {
          left: 15px;
        }

        .nav-button.next {
          right: 15px;
        }

        .promo-container {
          padding: 0 calc(50% - 160px);
          gap: 2rem;
        }
        
        .promo-image {
          height: 220px;
        }

        .back-to-top {
          width: 55px;
          height: 55px;
          bottom: 30px;
          right: 30px;
        }
      }

      @media (max-width: 480px) {
        .promo-card {
          min-width: 300px;
          max-width: 300px;
        }
        
        .promo-container {
          padding: 0 calc(50% - 150px);
        }

        .nav-button {
          width: 45px;
          height: 45px;
        }
      }
    </style>
  </head>
  <body class="font-poppins bg-white text-text-dark">
    <!-- Scroll Progress Bar -->
    <div class="scroll-progress" id="scrollProgress"></div>

    <!-- Overlay -->
    <div id="overlay" class="hidden fixed top-0 left-0 w-full h-screen bg-black bg-opacity-50 z-40 transition-opacity duration-300"></div>

    <!-- Navbar -->
    <nav id="navbar" class="w-full py-4 sm:py-5 lg:py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between fixed top-0 left-0 right-0 z-50">
      <a href="#" class="text-xl sm:text-2xl lg:text-3xl font-bold text-black italic">
        {{ $settings['site_name'] ?? 'MestaKara' }}<span class="text-primary">.</span>
      </a>
      
      <!-- Desktop Navigation -->
      <div id="navbar-nav" class="hidden md:flex items-center gap-1">
        <a href="#home" class="text-black inline-block text-base lg:text-lg px-4 lg:px-5 py-2 hover:text-primary transition-all duration-300 relative group font-medium">
          Home
          <span class="absolute left-4 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-[calc(100%-2rem)]"></span>
        </a>
        <a href="#about" class="text-black inline-block text-base lg:text-lg px-4 lg:px-5 py-2 hover:text-primary transition-all duration-300 relative group font-medium">
          Tentang Kami
          <span class="absolute left-4 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-[calc(100%-2rem)]"></span>
        </a>
        <a href="#menu" class="text-black inline-block text-base lg:text-lg px-4 lg:px-5 py-2 hover:text-primary transition-all duration-300 relative group font-medium">
          Promo
          <span class="absolute left-4 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-[calc(100%-2rem)]"></span>
        </a>
        <a href="wahana" class="text-black inline-block text-base lg:text-lg px-4 lg:px-5 py-2 hover:text-primary transition-all duration-300 relative group font-medium">
          Wahana
          <span class="absolute left-4 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-[calc(100%-2rem)]"></span>
        </a>
      </div>

      <!-- Mobile Navigation -->
      <div id="mobile-nav" class="fixed top-0 -right-full w-full sm:w-80 h-screen bg-gradient-to-b from-gray-900 to-black transition-all duration-300 z-50 pt-16" style="box-shadow: -5px 0 20px rgba(0, 0, 0, 0.5);">
        <div id="close-menu" class="absolute top-6 right-6 text-white cursor-pointer text-3xl touch-manipulation hover:text-primary transition-colors">
          <i data-feather="x"></i>
        </div>
        
        <div class="flex flex-col px-4">
          <a href="#home" class="block mx-6 my-6 py-4 text-2xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-4 touch-manipulation">Home</a>
          <a href="#about" class="block mx-6 my-6 py-4 text-2xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-4 touch-manipulation">Tentang Kami</a>
          <a href="#menu" class="block mx-6 my-6 py-4 text-2xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-4 touch-manipulation">Promo</a>
          <a href="wahana" class="block mx-6 my-6 py-4 text-2xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-4 touch-manipulation">Wahana</a>
        </div>
      </div>

      <!-- Navbar Extra -->
      <div class="flex items-center gap-2">
        <a href="#" id="menu-icon" class="text-black hover:text-primary transition-all duration-300 md:hidden cursor-pointer p-2 touch-manipulation">
          <i data-feather="menu" class="w-6 h-6"></i>
        </a>
      </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero min-h-screen flex items-center justify-center hero-bg relative px-4 sm:px-6 lg:px-8 text-white pt-20">
      <main class="max-w-5xl w-full text-center relative z-10">
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl text-white leading-tight mb-6">
          {{ $settings['hero_title'] ?? 'Berlibur Dengan' }}<span class="text-primary"> {{ $settings['hero_subtitle'] ?? 'Wahana' }}</span>
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl lg:text-3xl mt-4 leading-relaxed font-medium text-white max-w-4xl mx-auto">
          {{ $settings['hero_description'] ?? 'Mari Berlibur dan Nikmati Berbagai Wahana Seru di Agrowisata Gunung Mas Bersama Keluarga Tercinta' }}
        </p>
        <a href="#menu" class="cta inline-block mt-8 px-10 py-5 text-lg lg:text-xl text-black font-bold bg-primary rounded-full hover:bg-yellow-500 transition-all duration-300 touch-manipulation relative z-10">
          {{ $settings['hero_cta_text'] ?? 'Dapatkan Promo' }}
        </a>
      </main>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-16 sm:py-20 lg:py-24 px-4 sm:px-6 lg:px-8 relative">
      <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
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
      <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 max-w-7xl mx-auto">
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
          
          <!-- Indicators -->
          <div class="carousel-indicators" id="carouselIndicators"></div>
          @else
          <div class="bg-gray-100 rounded-3xl p-16 text-center">
            <i data-feather="image" class="w-20 h-20 mx-auto text-gray-400 mb-4"></i>
            <p class="text-gray-500 text-lg">Belum ada wahana tersedia</p>
          </div>
          @endif
          
          <!-- Tombol Lihat Lebih Banyak -->
          <div class="text-center mt-8">
            <button onclick="window.location='wahana'"
                    class="inline-flex items-center px-8 py-4 bg-primary text-black font-bold rounded-full hover:bg-yellow-500 transition-all duration-300 group hover:shadow-xl text-lg">
              Lihat Semua Wahana
              <i data-feather="arrow-right"
                class="w-5 h-5 ml-2 group-hover:transform group-hover:translate-x-2 transition-transform duration-300"></i>
            </button>
          </div>
        </div>
        
        <div class="flex-1 w-full lg:min-w-96 px-0 lg:px-8 flex flex-col justify-center">
          <h3 class="text-2xl sm:text-3xl lg:text-4xl mb-4 sm:mb-6 text-text-dark font-bold">
            {{ $settings['about_question'] ?? 'Kenapa memilih Wahana kami?' }}
          </h3>
          <p class="mb-4 sm:mb-5 text-base sm:text-lg lg:text-xl leading-relaxed text-gray-700">
            {{ $settings['about_content_1'] ?? 'MestaKara adalah penyedia wahana yang didirikan dengan cinta dan dedikasi untuk menghadirkan pengalaman wahana terbaik. Kami percaya bahwa setiap tawa dapat menciptakan kenangan indah yang akan diingat selamanya.' }}
          </p>
          <p class="mb-4 sm:mb-5 text-base sm:text-lg lg:text-xl leading-relaxed text-gray-700">
            {{ $settings['about_content_2'] ?? 'Wahana kami didirikan langsung di tengah perkebunan terbaik dan ditata dengan presisi yang sempurna. Setiap wahana yang kami sediakan adalah hasil dari perpaduan tradisi dan kualitas premium.' }}
          </p>
          <p class="text-base sm:text-lg lg:text-xl leading-relaxed text-gray-700">
            {{ $settings['about_content_3'] ?? 'Dengan lebih dari 20 wahana menarik, fasilitas lengkap, dan staff berpengalaman, kami siap memberikan pengalaman liburan yang tak terlupakan untuk seluruh keluarga.' }}
          </p>
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
          <button class="nav-button prev" id="prevBtn">
            <i data-feather="chevron-left" class="w-7 h-7"></i>
          </button>
          
          <button class="nav-button next" id="nextBtn">
            <i data-feather="chevron-right" class="w-7 h-7"></i>
          </button>

          <!-- Slider Container -->
          <div class="promo-container" id="promoContainer">
            @foreach($promos as $promo)
              @php
                $isClickable = $promo->is_clickable;
                $buttonStatus = $promo->button_status;
              @endphp
              
              <div class="promo-card block hover:no-underline {{ $isClickable ? 'clickable' : 'non-clickable promo-disabled' }}" 
                   data-name="{{ strtolower($promo->name) }}"
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
                      <div class="overlay-content text-center">
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
                  
                  <div class="w-full text-center font-bold py-3.5 rounded-full transition-all duration-300 {{ $buttonStatus['class'] }}"
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
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
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
              <a href="#home" class="hover:text-primary transition-colors duration-300 text-base lg:text-lg touch-manipulation text-center md:text-left font-medium">Home</a>
              <a href="#about" class="hover:text-primary transition-colors duration-300 text-base lg:text-lg touch-manipulation text-center md:text-left font-medium">Tentang Kami</a>
              <a href="#menu" class="hover:text-primary transition-colors duration-300 text-base lg:text-lg touch-manipulation text-center md:text-left font-medium">Promo</a>
            </div>
          </div>
          
          <div>
            <h4 class="text-xl font-bold mb-6 text-center md:text-left">Ikuti Kami</h4>
            <div class="flex justify-center md:justify-start space-x-4">
              <a href="https://www.instagram.com/wisataagro8/?hl=id" class="bg-white bg-opacity-10 p-3 rounded-full hover:bg-primary hover:text-black transition-all duration-300 touch-manipulation">
                <i data-feather="instagram" class="w-6 h-6"></i>
              </a>
              <a href="https://twitter.com/agrowisata_n8" class="bg-white bg-opacity-10 p-3 rounded-full hover:bg-primary hover:text-black transition-all duration-300 touch-manipulation">
                <i data-feather="twitter" class="w-6 h-6"></i>
              </a>
              <a href="https://www.facebook.com/AgrowisataN8/" class="bg-white bg-opacity-10 p-3 rounded-full hover:bg-primary hover:text-black transition-all duration-300 touch-manipulation">
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
    <div class="back-to-top" id="backToTop">
      <i data-feather="arrow-up" class="w-7 h-7"></i>
    </div>

    <!-- ==================== JAVASCRIPT ==================== -->
    <script>
      // Initialize Feather icons
      feather.replace();

      // ==================== SCROLL PROGRESS BAR ====================
      window.addEventListener('scroll', () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        document.getElementById('scrollProgress').style.width = scrolled + '%';
      });

      // ==================== NAVBAR SCROLL EFFECT ====================
      const navbar = document.getElementById('navbar');
      window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }
      });

      // ==================== BACK TO TOP BUTTON ====================
      const backToTop = document.getElementById('backToTop');
      window.addEventListener('scroll', () => {
        if (window.scrollY > 400) {
          backToTop.classList.add('visible');
        } else {
          backToTop.classList.remove('visible');
        }
      });

      backToTop.addEventListener('click', () => {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });

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

      // ==================== ANIMATED COUNTER ====================
      function animateCounter(element, target, duration = 2000) {
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
      }

      // ==================== STATS COUNTER ====================
      const statNumbers = document.querySelectorAll('.stat-number');
      let countersStarted = false;

      const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting && !countersStarted) {
            statNumbers.forEach(stat => {
              const target = parseInt(stat.getAttribute('data-count'));
              animateCounter(stat, target);
            });
            countersStarted = true;
          }
        });
      }, { threshold: 0.5 });

      const statsSection = document.querySelector('.stats-section');
      if (statsSection) {
        statsObserver.observe(statsSection);
      }

      // ==================== SECTION FADE IN ANIMATION ====================
      const observerOptions = {
        threshold: 0.15,
        rootMargin: '0px 0px -100px 0px'
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
          }
        });
      }, observerOptions);

      document.querySelectorAll('.fade-in-section').forEach(section => {
        observer.observe(section);
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
          if (this.totalSlides === 0) return;
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

        initSearch() {
          if (!this.searchInput) return;
          let searchTimeout;
          this.searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
              const searchTerm = e.target.value.toLowerCase();
              this.filterCards(searchTerm);
            }, 300);
          });
        }

        filterCards(searchTerm) {
          let visibleCount = 0;
          this.cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            if (name.includes(searchTerm)) {
              card.style.display = 'block';
              visibleCount++;
            } else {
              card.style.display = 'none';
            }
          });
          
          if (visibleCount === 0) {
            this.showNoResults();
          } else {
            this.hideNoResults();
            this.updateVisibleCards();
          }
        }

        showNoResults() {
          let noResultsEl = document.getElementById('noResults');
          if (!noResultsEl) {
            noResultsEl = document.createElement('div');
            noResultsEl.id = 'noResults';
            noResultsEl.className = 'text-center py-16 px-4';
            noResultsEl.innerHTML = `
              <div class="inline-block p-6 bg-gray-100 rounded-full mb-6">
                <i data-feather="search" class="w-16 h-16 text-gray-400"></i>
              </div>
              <h3 class="text-2xl font-bold text-gray-600 mb-3">Promo tidak ditemukan</h3>
              <p class="text-gray-500 text-lg">Coba kata kunci lain</p>
            `;
            this.container.parentElement.appendChild(noResultsEl);
            feather.replace();
          }
          this.container.style.display = 'none';
          if (this.dotsContainer) this.dotsContainer.style.display = 'none';
          if (this.prevBtn) this.prevBtn.style.display = 'none';
          if (this.nextBtn) this.nextBtn.style.display = 'none';
        }

        hideNoResults() {
          const noResultsEl = document.getElementById('noResults');
          if (noResultsEl) {
            noResultsEl.remove();
          }
          this.container.style.display = 'flex';
          if (this.dotsContainer) this.dotsContainer.style.display = 'flex';
          if (this.prevBtn) this.prevBtn.style.display = 'flex';
          if (this.nextBtn) this.nextBtn.style.display = 'flex';
        }

        updateVisibleCards() {
          const visibleCards = this.cards.filter(card => card.style.display !== 'none');
          this.totalCards = visibleCards.length;
          this.currentIndex = 0;
          this.createDots();
          this.updateSlider();
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

          const cardWidth = this.cards[0]?.offsetWidth || 370;
          const gap = window.innerWidth < 768 ? 24 : 40;
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
          }, 6000);
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