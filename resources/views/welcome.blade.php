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
          Menu
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
          <a href="#menu" class="block mx-2 sm:mx-6 my-6 sm:my-8 py-4 text-2xl sm:text-3xl text-white border-b border-gray-700 transition-all duration-300 hover:text-primary hover:pl-4 touch-manipulation">Menu</a>
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
        <a href="#" class="cta inline-block mt-6 sm:mt-8 px-8 sm:px-12 py-3 sm:py-4 text-lg sm:text-xl text-white bg-primary rounded-lg hover:bg-yellow-500 transition-colors duration-300 touch-manipulation">
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
        <div class="flex-1 lg:min-w-96 mb-6 lg:mb-0">
          <img src="img/tentangteh.jpg" alt="Tentang Kami" class="w-full h-60 sm:h-80 object-cover rounded-xl" />
        </div>
        <div class="flex-1 lg:min-w-96 px-0 lg:px-8">
          <h3 class="text-2xl sm:text-3xl mb-4 text-text-dark">Kenapa memilih teh kami?</h3>
          <p class="mb-4 text-base sm:text-lg md:text-xl font-medium leading-relaxed text-text-dark">
            MestaKara adalah kedai teh yang didirikan dengan cinta dan
            dedikasi untuk menghadirkan pengalaman teh terbaik. Kami percaya
            bahwa setiap tegukan teh dapat menciptakan kenangan indah yang akan
            diingat selamanya.
          </p>
          <p class="mb-4 text-base sm:text-lg md:text-xl font-medium leading-relaxed text-text-dark">
            Daun teh kami dipilih langsung dari perkebunan terbaik dan diolah
            dengan teknik tradisional yang sempurna. Setiap cangkir teh yang kami
            sajikan adalah hasil dari perpaduan tradisi dan kualitas premium.
          </p>
        </div>
      </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="py-16 sm:py-24 md:py-32 px-4 sm:px-7">
      <h2 class="text-center text-3xl sm:text-4xl mb-4 text-text-dark">
        <span class="text-primary">Menu</span> Kami
      </h2>
      <p class="text-center max-w-lg mx-auto font-medium leading-relaxed text-text-dark mb-12 sm:mb-20 text-base sm:text-lg">
        Nikmati berbagai pilihan teh dan makanan pendamping yang lezat
      </p>
      
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6 max-w-6xl mx-auto">
        <div class="text-center pb-8 sm:pb-16">
          <img src="img/espresso.jpg" alt="Teh Earl Grey" class="rounded-full w-24 h-24 sm:w-32 sm:h-32 md:w-36 md:h-36 object-cover mx-auto mb-3 sm:mb-4 menu-card-img" />
          <h3 class="mt-2 sm:mt-4 mb-1 sm:mb-2 text-text-dark text-sm sm:text-base">- Earl Grey -</h3>
          <p class="text-primary font-bold text-sm sm:text-base">IDR 15K</p>
        </div>
        <div class="text-center pb-8 sm:pb-16">
          <img src="img/cappuccino.jpg" alt="Teh Jasmine" class="rounded-full w-24 h-24 sm:w-32 sm:h-32 md:w-36 md:h-36 object-cover mx-auto mb-3 sm:mb-4 menu-card-img" />
          <h3 class="mt-2 sm:mt-4 mb-1 sm:mb-2 text-text-dark text-sm sm:text-base">- Jasmine -</h3>
          <p class="text-primary font-bold text-sm sm:text-base">IDR 25K</p>
        </div>
        <div class="text-center pb-8 sm:pb-16">
          <img src="img/latte.jpg" alt="Green Tea Latte" class="rounded-full w-24 h-24 sm:w-32 sm:h-32 md:w-36 md:h-36 object-cover mx-auto mb-3 sm:mb-4 menu-card-img" />
          <h3 class="mt-2 sm:mt-4 mb-1 sm:mb-2 text-text-dark text-sm sm:text-base">- Green Tea Latte -</h3>
          <p class="text-primary font-bold text-sm sm:text-base">IDR 22K</p>
        </div>
        <div class="text-center pb-8 sm:pb-16">
          <img src="img/americano.jpg" alt="Oolong" class="rounded-full w-24 h-24 sm:w-32 sm:h-32 md:w-36 md:h-36 object-cover mx-auto mb-3 sm:mb-4 menu-card-img" />
          <h3 class="mt-2 sm:mt-4 mb-1 sm:mb-2 text-text-dark text-sm sm:text-base">- Oolong -</h3>
          <p class="text-primary font-bold text-sm sm:text-base">IDR 18K</p>
        </div>
        <div class="text-center pb-8 sm:pb-16">
          <img src="img/mocha.jpg" alt="Thai Tea" class="rounded-full w-24 h-24 sm:w-32 sm:h-32 md:w-36 md:h-36 object-cover mx-auto mb-3 sm:mb-4 menu-card-img" />
          <h3 class="mt-2 sm:mt-4 mb-1 sm:mb-2 text-text-dark text-sm sm:text-base">- Thai Tea -</h3>
          <p class="text-primary font-bold text-sm sm:text-base">IDR 28K</p>
        </div>
        <div class="text-center pb-8 sm:pb-16">
          <img src="img/crossaint.jpg" alt="Scone" class="rounded-full w-24 h-24 sm:w-32 sm:h-32 md:w-36 md:h-36 object-cover mx-auto mb-3 sm:mb-4 menu-card-img" />
          <h3 class="mt-2 sm:mt-4 mb-1 sm:mb-2 text-text-dark text-sm sm:text-base">- Tea Scone -</h3>
          <p class="text-primary font-bold text-sm sm:text-base">IDR 12K</p>
        </div>
      </div>
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
              Menyajikan teh berkualitas tinggi dengan cita rasa yang tak terlupakan.
            </p>
          </div>
          
          <!-- Quick Links -->
          <div class="mb-6 sm:mb-8 md:mb-0">
            <h4 class="text-lg sm:text-xl font-semibold mb-4 sm:mb-6 text-center md:text-left">Tautan Cepat</h4>
            <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-8">
              <a href="#home" class="hover:text-gray-200 transition-colors duration-300 text-base sm:text-lg touch-manipulation text-center md:text-left">Home</a>
              <a href="#about" class="hover:text-gray-200 transition-colors duration-300 text-base sm:text-lg touch-manipulation text-center md:text-left">Tentang Kami</a>
              <a href="#menu" class="hover:text-gray-200 transition-colors duration-300 text-base sm:text-lg touch-manipulation text-center md:text-left">Menu</a>
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
          navbarNav.classList.remove('-right-full');
          navbarNav.classList.add('right-0');
          overlay.classList.remove('hidden');
          overlay.classList.add('block');
          document.body.style.overflow = 'hidden';
          isMenuOpen = true;
        }
      }

      function closeMobileMenu() {
        if (isMenuOpen) {
          navbarNav.classList.add('-right-full');
          navbarNav.classList.remove('right-0');
          overlay.classList.add('hidden');
          overlay.classList.remove('block');
          document.body.style.overflow = 'auto';
          isMenuOpen = false;
        }
      }

      menuIcon.addEventListener('click', (e) => {
        e.stopPropagation();
        openMobileMenu();
      });

      // Touch-friendly close button
      closeMenu.addEventListener('click', (e) => {
        e.stopPropagation();
        closeMobileMenu();
      });

      // Touch event for close button
      closeMenu.addEventListener('touchend', (e) => {
        e.preventDefault();
        e.stopPropagation();
        closeMobileMenu();
      });

      overlay.addEventListener('click', closeMobileMenu);

      // Close mobile menu when clicking outside
      document.addEventListener('click', (e) => {
        const isClickInsideNav = e.target.closest('#mobile-nav') !== null;
        const isClickOnMenuIcon = e.target.closest('#menu-icon') !== null;

        if (!isClickInsideNav && !isClickOnMenuIcon && isMenuOpen) {
          closeMobileMenu();
        }
      });

      // Improved smooth scrolling for navigation links with offset
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const targetId = this.getAttribute('href');
          const target = document.querySelector(targetId);

          if (target) {
            // Close mobile menu
            closeMobileMenu();

            // Calculate offset based on navbar height
            const navbarHeight = document.querySelector('nav').offsetHeight;
            const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;

            // Smooth scroll to target position with offset
            window.scrollTo({
              top: targetPosition,
              behavior: 'smooth'
            });
          }
        });

        // Touch events for better mobile experience
        anchor.addEventListener('touchend', function (e) {
          // Prevent double-tap zoom on mobile
          e.preventDefault();
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
        // Close menu on orientation change to prevent layout issues
        setTimeout(() => {
          if (isMenuOpen) {
            closeMobileMenu();
          }
          // Re-initialize icons after orientation change
          feather.replace();
        }, 100);
      });

      // Re-initialize Feather icons after DOM manipulation
      feather.replace();

      // Optimize scroll performance
      let ticking = false;
      function updateNavbar() {
        const navbar = document.querySelector('nav');
        const scrollTop = window.pageYOffset;
        
        if (scrollTop > 100) {
          navbar.style.backdropFilter = 'blur(10px)';
          navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
        } else {
          navbar.style.backdropFilter = 'none';
          navbar.style.backgroundColor = 'rgba(255, 255, 255, 1)';
        }
        
        ticking = false;
      }

      function requestTick() {
        if (!ticking) {
          requestAnimationFrame(updateNavbar);
          ticking = true;
        }
      }

      window.addEventListener('scroll', requestTick);
    </script>
  </body>
</html>