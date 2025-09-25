<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tiketmas</title>
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
      
      /* Mobile optimization for hero background */
      @media (max-width: 768px) {
        .hero-bg {
          background-attachment: scroll;
        }
      }
      
      .hero h1 {
        text-shadow: 1px 1px 3px rgba(1, 1, 3, 0.5);
      }
      
      .hero p {
        text-shadow: 1px 1px 3px rgba(1, 1, 3, 0.5);
      }
      
      .hero .cta {
        box-shadow: 1px 1px 3px rgba(1, 1, 3, 0.5);
      }
      
      .menu-card-img {
        background: linear-gradient(45deg, #78b65b, #a8e086);
      }
      
      /* Improved mobile menu animations */
      .mobile-nav-enter {
        transform: translateX(100%);
      }
      
      .mobile-nav-enter-active {
        transform: translateX(0);
        transition: transform 300ms ease-in-out;
      }
      
      .mobile-nav-exit {
        transform: translateX(0);
      }
      
      .mobile-nav-exit-active {
        transform: translateX(100%);
        transition: transform 300ms ease-in-out;
      }

      /* WAHANA CAROUSEL STYLES */
      .wahana-carousel {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      }

      .wahana-images {
        display: flex;
        transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        width: 500%; /* 5 gambar x 100% = 500% */
      }

      .wahana-slide {
        flex: 0 0 20%; /* Setiap slide mengambil 20% dari container (100% / 5 gambar) */
        position: relative;
      }

      .wahana-image {
        width: 100%;
        height: 350px;
        object-fit: cover;
        display: block;
      }

      .wahana-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
        padding: 2rem 1.5rem 1rem;
        color: white;
      }

      .wahana-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
      }

      .wahana-description {
        font-size: 1rem;
        opacity: 0.9;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
      }

      .carousel-indicators {
        position: absolute;
        bottom: 15px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 8px;
        z-index: 10;
      }

      .indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .indicator.active {
        background: #CFD916;
        transform: scale(1.3);
      }

      @media (max-width: 768px) {
        .wahana-image {
          height: 280px;
        }
        
        .wahana-title {
          font-size: 1.25rem;
        }
        
        .wahana-description {
          font-size: 0.9rem;
        }
      }

      /* PROMO SLIDER STYLES */
      .promo-slider {
        overflow: hidden;
        position: relative;
        padding: 2rem 0;
      }

      .promo-container {
        margin-left: -15%;
        display: flex;
        transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        gap: 2rem;
        padding: 0 50%;
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
        transform: scale(0.8);
        filter: blur(3px);
        opacity: 0.6;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
      }

      .promo-card.active {
        transform: scale(1);
        filter: blur(0px);
        opacity: 1;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
      }

      .promo-card:hover {
        transform: scale(0.85);
        text-decoration: none;
        color: inherit;
      }

      .promo-card.active:hover {
        transform: scale(1.02);
      }

      .discount-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #CFD916;
        color: #000;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 14px;
        z-index: 10;
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

      .promo-card:hover .promo-image img {
        transform: scale(1.05);
      }

      .nav-button {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.9);
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
        background: rgba(255, 255, 255, 0.9);
        color: inherit;
        transform: translateY(-50%) scale(1);
      }

      /* Dots indicator */
      .dots-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 2rem;
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

      /* Mobile responsive */
      @media (max-width: 768px) {
        .promo-card {
          min-width: 280px;
          max-width: 280px;
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
          padding: 0 40%;
        }
      }
    </style>
  </head>
  <body class="font-poppins bg-white text-text-dark">
    <!-- Overlay -->
    <div id="overlay" class="hidden fixed top-0 left-0 w-full h-screen bg-black bg-opacity-50 z-40 transition-opacity duration-300"></div>

    <!-- Navbar -->
    <nav class="w-full py-3 sm:py-5 px-4 sm:px-7 flex items-center justify-between bg-white border-b border-gray-400 fixed top-0 left-0 right-0 z-50" style="border-bottom: 1px solid #597336;">
      <a href="#" class="text-2xl sm:text-3xl font-bold text-black italic">
        Mesta<span class="text-primary">Kara</span>.
      </a>
      
      <!-- Desktop Navigation -->
      <div id="navbar-nav" class="hidden md:flex">
        <a href="#home" class="text-black inline-block text-xl ml-0 px-4 hover:text-primary transition-all duration-500 relative group">
          Home
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="#about" class="text-black inline-block text-xl ml-0 px-4 hover:text-primary transition-all duration-500 relative group">
          Tentang Kami
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
        <a href="#menu" class="text-black inline-block text-xl ml-0 px-4 hover:text-primary transition-all duration-500 relative group">
          Promo
          <span class="absolute left-0 bottom-0 w-0 h-0.5 bg-primary transition-all duration-300 group-hover:w-full"></span>
        </a>
      </div>

      <!-- Mobile Navigation -->
      <div id="mobile-nav" class="fixed top-0 -right-full w-full sm:w-80 h-screen bg-black transition-all duration-300 z-50 pt-16" style="box-shadow: -5px 0 15px rgba(0, 0, 0, 0.5);">
        <!-- Close button -->
        <div id="close-menu" class="absolute top-4 sm:top-6 right-4 sm:right-6 text-white cursor-pointer text-3xl sm:text-4xl touch-manipulation">
          <i data-feather="x"></i>
        </div>
        
        <div class="flex flex-col px-4">
          <a href="#home" class="block mx-2 sm:mx-6 my-6 sm:my-8 py-4 text-2xl sm:text-3xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-4 touch-manipulation">Home</a>
          <a href="#about" class="block mx-2 sm:mx-6 my-6 sm:my-8 py-4 text-2xl sm:text-3xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-4 touch-manipulation">Tentang Kami</a>
          <a href="#menu" class="block mx-2 sm:mx-6 my-6 sm:my-8 py-4 text-2xl sm:text-3xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-4 touch-manipulation">Promo</a>
        </div>
      </div>

      <!-- Navbar Extra -->
      <div class="flex items-center">
        <a href="#" class="text-black mx-1 sm:mx-2 hover:text-primary transition-all duration-500 p-2 touch-manipulation"><i data-feather="search" class="w-5 h-5 sm:w-6 sm:h-6"></i></a>
        <a href="#" class="text-black mx-1 sm:mx-2 hover:text-primary transition-all duration-500 p-2 touch-manipulation"><i data-feather="shopping-cart" class="w-5 h-5 sm:w-6 sm:h-6"></i></a>
        <a href="#" id="menu-icon" class="text-black mx-1 sm:mx-2 hover:text-primary transition-all duration-500 md:hidden cursor-pointer text-xl p-2 touch-manipulation">
          <i data-feather="menu" class="w-5 h-5 sm:w-6 sm:h-6"></i>
        </a>
      </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero min-h-screen flex items-center hero-bg relative px-4 sm:px-7 text-white">
      <main class="max-w-4xl w-full">
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl text-white leading-tight mb-4 sm:mb-6">
           Berlibur Dengan<span class="text-primary">Wahana</span>
        </h1>
        <p class="text-lg sm:text-xl md:text-2xl mt-4 leading-relaxed font-medium text-white max-w-3xl">
          Mari Berlibur dan Nikmati Berbagai Wahana Seru di Agrowisata Gunung Mas
          Bersama Keluarga Tercinta
          Dengan Harga Tiket Masuk yang Terjangkau
          dan Dapatkan Berbagai Promo Menarik Setiap Bulannya
        </p>
        <a href="#menu" class="cta inline-block mt-6 sm:mt-8 px-8 sm:px-12 py-3 sm:py-4 text-lg sm:text-xl text-white bg-primary rounded-lg hover:bg-yellow-500 transition-colors duration-300 touch-manipulation">
          Dapatkan Promo
        </a>
      </main>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 sm:py-24 md:py-32 px-4 sm:px-7">
      <h2 class="text-center text-3xl sm:text-4xl mb-8 sm:mb-12 text-text-dark">
        <span class="text-primary">Tentang</span> Kami
      </h2>
      <div class="flex flex-col lg:flex-row max-w-6xl mx-auto">
        <!-- Wahana Carousel -->
        <div class="flex-1 lg:min-w-96 mb-6 lg:mb-0">
        <div class="wahana-carousel" id="wahanaCarousel">
          <div class="wahana-images" id="wahanaImages">
            <div class="wahana-slide">
              <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Roller Coaster" class="wahana-image" />
              <div class="wahana-overlay">
                <div class="wahana-title">Roller Coaster</div>
                <div class="wahana-description">Rasakan sensasi kecepatan tinggi dengan pemandangan menakjubkan</div>
              </div>
            </div>
            
            <div class="wahana-slide">
              <img src="https://images.unsplash.com/photo-1517457373958-b7bdd4587205?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Ferris Wheel" class="wahana-image" />
              <div class="wahana-overlay">
                <div class="wahana-title">Bianglala</div>
                <div class="wahana-description">Nikmati view 360° dari ketinggian bersama orang tercinta</div>
              </div>
            </div>
            
            <div class="wahana-slide">
              <img src="https://images.unsplash.com/photo-1544197150-b99a580bb7a8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Bumper Cars" class="wahana-image" />
              <div class="wahana-overlay">
                <div class="wahana-title">Bumper Car</div>
                <div class="wahana-description">Keseruan menabrak dan tertawa bersama keluarga</div>
              </div>
            </div>
            
            <div class="wahana-slide">
              <img src="https://images.unsplash.com/photo-1570197788417-0e82375c9371?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Carousel" class="wahana-image" />
              <div class="wahana-overlay">
                <div class="wahana-title">Komidi Putar</div>
                <div class="wahana-description">Wahana klasik yang cocok untuk segala usia</div>
              </div>
            </div>
            
            <div class="wahana-slide">
              <img src="https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Water Ride" class="wahana-image" />
              <div class="wahana-overlay">
                <div class="wahana-title">Arung Jeram Mini</div>
                <div class="wahana-description">Bermain air yang menyegarkan dan menyenangkan</div>
              </div>
            </div>
          </div>
          
          <div class="carousel-indicators" id="carouselIndicators">
            <!-- Indicators akan di-generate otomatis -->
          </div>
        </div>
          
          <!-- Tombol Lihat Lebih Banyak -->
          <div class="text-center mt-6">
            <button class="inline-flex items-center px-6 py-3 bg-primary text-black font-semibold rounded-lg hover:bg-yellow-500 transition-colors duration-300 group">
              Lihat Lebih Banyak
              <i data-feather="chevron-down" class="w-5 h-5 ml-2 group-hover:transform group-hover:translate-y-1 transition-transform duration-300"></i>
            </button>
          </div>
        </div>
        
        <div class="flex-1 lg:min-w-96 px-0 lg:px-8">
          <h3 class="text-2xl sm:text-3xl mb-4 text-text-dark">Kenapa memilih Wahana kami?</h3>
          <p class="mb-4 text-base sm:text-lg md:text-xl font-medium leading-relaxed text-text-dark">
            MestaKara adalah penyedia wahana yang didirikan dengan cinta dan
            dedikasi untuk menghadirkan pengalaman wahana terbaik. Kami percaya
            bahwa setiap tawa dapat menciptakan kenangan indah yang akan
            diingat selamanya.
          </p>
          <p class="mb-4 text-base sm:text-lg md:text-xl font-medium leading-relaxed text-text-dark">
            Wahana kami didirikan langsung di tengah perkebunan terbaik dan ditata
            dengan presisi yang sempurna. Setiap wahana yang kami
            sediakan adalah hasil dari perpaduan tradisi dan kualitas premium.
          </p>
          <p class="text-base sm:text-lg md:text-xl font-medium leading-relaxed text-text-dark">
            Dengan lebih dari 20 wahana menarik, fasilitas lengkap, dan staff
            berpengalaman, kami siap memberikan pengalaman liburan yang tak terlupakan
            untuk seluruh keluarga.
          </p>
        </div>
      </div>
    </section>

    <!-- Promo Section dengan Slider -->
<section id="menu" class="py-16 sm:py-24 md:py-32 px-4 sm:px-7">
  <h2 class="text-center text-3xl sm:text-4xl mb-4 text-text-dark">
    <span class="text-primary">Promo</span> Kami
  </h2>
  <p class="text-center max-w-lg mx-auto font-medium leading-relaxed text-text-dark mb-12 sm:mb-20 text-base sm:text-lg">
     Nikmati berbagai pilihan wahana dan rekreasi yang menyenangkan
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
          <a href="{{ route('promo.show', $promo->id) }}" class="promo-card block hover:no-underline">
            @if($promo->featured)
              <span class="featured-badge">Unggulan</span>
            @endif
            <span class="discount-badge">Diskon {{ $promo->discount_percent }}%</span>
            
            <div class="promo-image">
              <img src="{{ asset('storage/' . $promo->image) }}" alt="{{ $promo->name }}" loading="lazy">
            </div>
            
            <div class="p-6">
              <h3 class="text-xl font-bold mb-2 text-text-dark">{{ $promo->name }}</h3>
              <p class="text-gray-600 mb-4">{{ Str::limit($promo->description, 100) }}</p>
              
              <div class="flex items-center justify-between mb-4">
                <div>
                  <span class="text-gray-400 line-through text-sm">Rp {{ number_format($promo->original_price, 0, ',', '.') }}</span>
                  <span class="text-primary font-bold text-xl block">Rp {{ number_format($promo->promo_price, 0, ',', '.') }}</span>
                </div>
                <span class="bg-primary text-black text-sm font-semibold px-3 py-1 rounded-full">
                  {{ $promo->category }}
                </span>
              </div>
              
              <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                <span>Berlaku hingga: {{ \Carbon\Carbon::parse($promo->end_date)->format('d M Y') }}</span>
                @if($promo->quota)
                  <span>Kuota: {{ $promo->quota }}</span>
                @endif
              </div>
              
              <div class="w-full bg-primary text-black text-center font-semibold py-3 rounded-lg hover:bg-yellow-500 transition-colors duration-300">
                Dapatkan Promo
              </div>
            </div>
          </a>
        @endforeach
      </div>

      <!-- Dots Indicator -->
      <div class="dots-container" id="dotsContainer">
        <!-- Dots akan di-generate otomatis oleh JavaScript -->
      </div>
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
              <a href="#home" class="hover:text-gray-200 transition-colors duration-300 text-base sm:text-lg touch-manipulation text-center md:text-left">Home</a>
              <a href="#about" class="hover:text-gray-200 transition-colors duration-300 text-base sm:text-lg touch-manipulation text-center md:text-left">Tentang Kami</a>
              <a href="#menu" class="hover:text-gray-200 transition-colors duration-300 text-base sm:text-lg touch-manipulation text-center md:text-left">Promo</a>
            </div>
          </div>
          
          <!-- Social Media -->
          <div>
            <h4 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6 text-center md:text-left">Ikuti Kami</h4>
            <div class="flex justify-center md:justify-start space-x-4 sm:space-x-6">
              <a href="https://www.instagram.com/wisataagro8/?hl=id" class="bg-white bg-opacity-20 p-2 sm:p-3 rounded-full hover:bg-opacity-30 transition-all duration-300 touch-manipulation">
                <i data-feather="instagram" class="w-5 h-5 sm:w-6 sm:h-6"></i>
              </a>
              <a href="https://twitter.com/agrowisata_n8" class="bg-white bg-opacity-20 p-2 sm:p-3 rounded-full hover:bg-opacity-30 transition-all duration-300 touch-manipulation">
                <i data-feather="twitter" class="w-5 h-5 sm:w-6 sm:h-6"></i>
              </a>
              <a href="https://www.facebook.com/AgrowisataN8/" class="bg-white bg-opacity-20 p-2 sm:p-3 rounded-full hover:bg-opacity-30 transition-all duration-300 touch-manipulation">
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

// Mobile menu toggle
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

// Smooth scrolling for navigation links with offset
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

// Prevent scrolling when mobile menu is open
let touchStartY = 0;
document.addEventListener('touchstart', e => {
  touchStartY = e.touches[0].clientY;
});
document.addEventListener('touchmove', e => {
  if (isMenuOpen && !e.target.closest('#mobile-nav')) {
    e.preventDefault();
  }
}, { passive: false });

// Handle orientation change
window.addEventListener('orientationchange', function() {
  setTimeout(() => {
    feather.replace();
  }, 100);
});

// Optimize scroll performance
let ticking = false;
function updateNavbar() {
  const navbar = document.querySelector('nav');
  const scrollTop = window.pageYOffset;
  if (scrollTop > 100) {
    navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
  } else {
    navbar.style.backgroundColor = '';
  }
  ticking = false;
}
function requestTick() {
  if (!ticking) {
    window.requestAnimationFrame(updateNavbar);
    ticking = true;
  }
}
window.addEventListener('scroll', requestTick);

// WAHANA CAROUSEL FUNCTIONALITY
class WahanaCarousel {
  constructor() {
    this.container = document.getElementById('wahanaImages');
    if (!this.container) return;
    this.slides = this.container.querySelectorAll('.wahana-slide');
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
    const carousel = document.getElementById('wahanaCarousel');
    if (!carousel) return;
    carousel.addEventListener('mouseenter', () => this.pauseAutoPlay());
    carousel.addEventListener('mouseleave', () => this.resumeAutoPlay());

    // Touch events for mobile swipe
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
    }, 3000);
  }

  pauseAutoPlay() {
    clearInterval(this.autoPlayInterval);
  }

  resumeAutoPlay() {
    this.pauseAutoPlay();
    this.startAutoPlay();
  }
}

// PROMO SLIDER FUNCTIONALITY - DINAMIS BERDASARKAN DATA DATABASE
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
    window.addEventListener('resize', () => this.handleResize());
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
    const offset = -this.currentIndex * (this.cards[0]?.offsetWidth + 32 || 350);
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
    }, 1989);
  }

  pauseAutoPlay() {
    clearInterval(this.autoPlayInterval);
  }

  resumeAutoPlay() {
    this.pauseAutoPlay();
    this.autoPlay();
  }
}

// Initialize sliders when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
  new WahanaCarousel();
  new PromoSlider();
  feather.replace();
});
    </script>
  </body>
</html>