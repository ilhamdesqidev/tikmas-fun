<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MestaKara - Agrowisata Gunung Mas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,600;0,700;1,700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#CFD916',
                        'text-dark': '#333333',
                        'green-light': '#78b65b',
                        'green-dark': '#597336',
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    fontSize: {
                        'xs': '0.75rem',
                        'sm': '0.875rem',
                        'base': '1rem',
                        'lg': '1.125rem',
                        'xl': '1.25rem',
                        '2xl': '1.5rem',
                        '3xl': '1.875rem',
                        '4xl': '2.25rem',
                        '5xl': '3rem',
                        '6xl': '3.75rem',
                        '7xl': '4.5rem',
                    }
                }
            }
        }
    </script>
    <style>
        html {
            scroll-behavior: smooth;
        }
        
        .welcome-bg {
            background: linear-gradient(135deg, #f8fffe 0%, #f0f9ff 50%, #e0f7fa 100%);
            position: relative;
            overflow: hidden;
            min-height: 100vh;
        }
        
        .welcome-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23CFD916' fill-opacity='0.03' fill-rule='evenodd'%3E%3Cpath d='m0 40l40-40h-40v40zm40 0v-40h-40l40 40z'/%3E%3C/g%3E%3C/svg%3E");
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% { transform: translateY(0); }
            40%, 43% { transform: translateY(-10px); }
            70% { transform: translateY(-5px); }
        }
        
        @keyframes leafFloat {
            0%, 100% { transform: rotate(0deg) translateY(0px); }
            25% { transform: rotate(5deg) translateY(-5px); }
            75% { transform: rotate(-3deg) translateY(-3px); }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .leaf-float {
            animation: leafFloat 4s ease-in-out infinite;
        }
        
        .welcome-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(207, 217, 22, 0.2);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        
        .start-button {
            background: linear-gradient(135deg, #CFD916 0%, #a8b818 100%);
            box-shadow: 0 4px 15px rgba(207, 217, 22, 0.3);
            transition: all 0.3s ease;
            touch-action: manipulation;
        }
        
        .start-button:hover, .start-button:active {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(207, 217, 22, 0.4);
        }
        
        .feature-card {
            background: white;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            touch-action: manipulation;
        }
        
        .feature-card:hover, .feature-card:active {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: #CFD916;
        }
        
        .tea-leaf {
            width: 20px;
            height: 20px;
            background: #78b65b;
            border-radius: 0 100% 0 100%;
            position: absolute;
            opacity: 0.4;
        }
        
        /* Mobile optimizations */
        @media (max-width: 640px) {
            .tea-leaf {
                width: 15px;
                height: 15px;
                opacity: 0.3;
            }
            
            .welcome-card {
                margin: 1rem;
                backdrop-filter: blur(8px);
            }
            
            .start-button {
                width: 100%;
                justify-content: center;
                max-width: 280px;
                margin: 0 auto;
            }
        }
        
        @media (max-width: 480px) {
            .feature-card {
                padding: 1rem !important;
            }
            
            .welcome-card {
                padding: 1.5rem !important;
            }
            
            .tea-leaf {
                display: none;
            }
        }

        /* Fallback untuk ikon jika Feather Icons gagal dimuat */
        .icon-fallback {
            display: none;
            font-style: normal;
            font-weight: bold;
            font-size: 2rem;
        }
        
        .feather[data-feather].icon-failed {
            display: none;
        }
        
        .feather[data-feather].icon-failed + .icon-fallback {
            display: block;
        }
        
        /* Prevent zoom on input focus on iOS */
        input[type="text"], 
        input[type="email"], 
        input[type="password"], 
        textarea,
        select {
            font-size: 16px;
        }
        
        /* Better touch targets */
        .touch-target {
            min-height: 44px;
            min-width: 44px;
        }
        
        /* Smooth scroll for mobile */
        html {
            -webkit-overflow-scrolling: touch;
        }
    </style>
</head>
<body class="font-poppins bg-gray-50">
    
    <!-- Welcome Section -->
    <section class="welcome-bg min-h-screen flex items-center justify-center relative px-3 py-8">
        
        <!-- Decorative tea leaves - Hidden on very small screens -->
        <div class="tea-leaf absolute top-16 left-8 leaf-float hidden sm:block"></div>
        <div class="tea-leaf absolute top-1/4 right-12 leaf-float hidden sm:block" style="animation-delay: -1.5s;"></div>
        <div class="tea-leaf absolute bottom-1/3 left-1/5 leaf-float hidden md:block" style="animation-delay: -3s;"></div>
        <div class="tea-leaf absolute bottom-24 right-1/4 leaf-float hidden md:block" style="animation-delay: -0.5s;"></div>
        
        <div class="max-w-6xl w-full text-center relative z-10">
            
            <!-- Brand Logo -->
            <div class="mb-6 sm:mb-8 md:mb-12 fade-in-up">
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-text-dark mb-2 sm:mb-4 leading-tight">
                    Mesta<span class="text-primary italic">Kara</span>.
                </h1>
                <p class="text-sm sm:text-lg md:text-xl text-green-dark opacity-80 px-4">
                    Agrowisata Gunung Mas - Pengalaman Wahana Premium
                </p>
            </div>
            
            <!-- Welcome Card -->
            <div class="welcome-card rounded-2xl sm:rounded-3xl p-4 sm:p-8 md:p-12 lg:p-16 mb-8 sm:mb-12 mx-2 sm:mx-auto max-w-4xl fade-in-up" style="animation-delay: 0.2s;">
                
                <div class="text-4xl sm:text-5xl md:text-6xl mb-4 sm:mb-6">🍃</div>
                
                <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-semibold text-text-dark mb-4 sm:mb-6 md:mb-8 leading-tight px-2">
                    Selamat Datang di
                    <span class="text-primary block sm:inline">Surga Wisata Keluarga</span>
                </h2>
                
                <p class="text-sm sm:text-lg md:text-xl text-green-dark leading-relaxed mb-6 sm:mb-8 md:mb-10 max-w-3xl mx-auto px-2">
                    Jelajahi keindahan perkebunan teh, nikmati udara segar pegunungan, dan rasakan 
                    cita rasa teh premium langsung dari kebun. Pengalaman wisata yang tak terlupakan 
                    menanti Anda bersama keluarga.
                </p>
                
                <!-- Features Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8 md:mb-10">
                    <div class="feature-card p-4 sm:p-6 rounded-xl touch-target">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-primary bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                            <i class="fas fa-mountain text-lg sm:text-xl text-green-dark"></i>
                            <span class="icon-fallback">⛰️</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-text-dark mb-2">Pemandangan Indah</h3>
                        <p class="text-green-dark text-xs sm:text-sm leading-relaxed">Nikmati panorama kebun teh yang hijau dan udara pegunungan yang segar</p>
                    </div>
                    
                    <div class="feature-card p-4 sm:p-6 rounded-xl touch-target">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-primary bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                            <i data-feather="users" class="w-5 h-5 sm:w-8 sm:h-8 text-green-dark"></i>
                            <span class="icon-fallback">👥</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-text-dark mb-2">Wisata Keluarga</h3>
                        <p class="text-green-dark text-xs sm:text-sm leading-relaxed">Berbagai wahana seru untuk semua anggota keluarga dari anak hingga dewasa</p>
                    </div>
                    
                    <div class="feature-card p-4 sm:p-6 rounded-xl touch-target sm:col-span-2 md:col-span-1">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-primary bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                            <i data-feather="coffee" class="w-5 h-5 sm:w-8 sm:h-8 text-green-dark"></i>
                            <span class="icon-fallback">🍵</span>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-text-dark mb-2">Teh Premium</h3>
                        <p class="text-green-dark text-xs sm:text-sm leading-relaxed">Cicipi berbagai varian teh berkualitas tinggi langsung dari sumbernya</p>
                    </div>
                </div>
                
                <!-- Start Button -->
                <div class="flex justify-center">
                    <a href="/dashboard" class="start-button inline-flex items-center px-6 sm:px-8 md:px-12 py-3 sm:py-4 md:py-5 text-base sm:text-lg md:text-xl font-semibold text-black rounded-full hover:scale-105 transform transition-all duration-300 group touch-target">
                        <span class="mr-2 sm:mr-3">Mulai Jelajahi</span>
                        <i data-feather="arrow-right" class="w-5 h-5 sm:w-6 sm:h-6 group-hover:translate-x-1 transition-transform duration-300"></i>
                        <span class="icon-fallback">→</span>
                    </a>
                </div>
            </div>
            
            <!-- Info Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4 max-w-4xl mx-auto fade-in-up px-2" style="animation-delay: 0.4s;">
                <div class="bg-white bg-opacity-90 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-primary border-opacity-20">
                    <div class="text-lg sm:text-2xl font-bold text-primary mb-1">15+</div>
                    <div class="text-green-dark text-xs sm:text-sm">Wahana Menarik</div>
                </div>
                <div class="bg-white bg-opacity-90 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-primary border-opacity-20">
                    <div class="text-lg sm:text-2xl font-bold text-primary mb-1">10k+</div>
                    <div class="text-green-dark text-xs sm:text-sm">Pengunjung Puas</div>
                </div>
                <div class="bg-white bg-opacity-90 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-primary border-opacity-20">
                    <div class="text-lg sm:text-2xl font-bold text-primary mb-1">25</div>
                    <div class="text-green-dark text-xs sm:text-sm">Varian Teh</div>
                </div>
                <div class="bg-white bg-opacity-90 rounded-lg sm:rounded-xl p-3 sm:p-4 border border-primary border-opacity-20">
                    <div class="text-lg sm:text-2xl font-bold text-primary mb-1">4.8⭐</div>
                    <div class="text-green-dark text-xs sm:text-sm">Rating</div>
                </div>
            </div>
        </div>
        
       
    </section>

    <script>
        // Fungsi untuk memastikan Feather Icons dimuat dengan benar
        function initializeFeatherIcons() {
            // Cek apakah Feather Icons tersedia
            if (typeof feather !== 'undefined') {
                try {
                    feather.replace();
                    
                    // Tambahkan pengecekan untuk memastikan ikon benar-benar terrender
                    setTimeout(() => {
                        const featherIcons = document.querySelectorAll('[data-feather]');
                        featherIcons.forEach(icon => {
                            // Jika ikon tidak memiliki konten SVG, tampilkan fallback
                            if (icon.childNodes.length === 0 || icon.innerHTML === '') {
                                icon.classList.add('icon-failed');
                            }
                        });
                    }, 100);
                } catch (error) {
                    console.error('Error initializing Feather Icons:', error);
                    document.querySelectorAll('[data-feather]').forEach(icon => {
                        icon.classList.add('icon-failed');
                    });
                }
            } else {
                // Jika Feather Icons tidak terdefinisi, gunakan fallback
                document.querySelectorAll('[data-feather]').forEach(icon => {
                    icon.classList.add('icon-failed');
                });
            }
        }

        // Add entrance animations on load
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in-up');
            elements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    element.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 200 + 300);
            });
            
            // Inisialisasi Feather Icons setelah DOM selesai dimuat
            initializeFeatherIcons();
        });
        
        // Juga inisialisasi ulang Feather Icons ketika seluruh halaman selesai dimuat
        window.addEventListener('load', function() {
            // Tunggu sedikit lebih lama untuk memastikan Feather Icons sudah siap
            setTimeout(initializeFeatherIcons, 500);
        });
        
        // Enhanced touch interactions for mobile
        document.querySelectorAll('.feature-card, .start-button').forEach(element => {
            // Add touch feedback
            element.addEventListener('touchstart', function() {
                this.style.transform = 'translateY(-2px) scale(0.98)';
            });
            
            element.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
            
            element.addEventListener('touchcancel', function() {
                this.style.transform = '';
            });
        });
        
        // Optimized parallax effect for mobile
        let ticking = false;
        
        function updateParallax() {
            const scrolled = window.pageYOffset;
            const leaves = document.querySelectorAll('.tea-leaf');
            
            // Only apply parallax on larger screens
            if (window.innerWidth > 640) {
                leaves.forEach((leaf, index) => {
                    const speed = (index + 1) * 0.2;
                    leaf.style.transform = `translateY(${scrolled * speed}px) rotate(${scrolled * 0.05}deg)`;
                });
            }
            
            ticking = false;
        }
        
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
            }
        });
        
        // Prevent double-tap zoom on buttons
        document.querySelectorAll('.start-button, .feature-card').forEach(element => {
            element.addEventListener('touchend', function(e) {
                e.preventDefault();
                this.click();
            });
        });
        
        // Handle orientation change
        window.addEventListener('orientationchange', function() {
            // Recalculate layout after orientation change
            setTimeout(() => {
                window.scrollTo(0, 0);
            }, 100);
        });
    </script>
</body>
</html>