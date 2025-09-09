<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiketmas - Selamat Datang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Tambahkan di bagian <head> website Anda -->
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
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(207, 217, 22, 0.2);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        
        .start-button {
            background: linear-gradient(135deg, #CFD916 0%, #a8b818 100%);
            box-shadow: 0 4px 15px rgba(207, 217, 22, 0.3);
            transition: all 0.3s ease;
        }
        
        .start-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(207, 217, 22, 0.4);
        }
        
        .feature-card {
            background: white;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-color: #CFD916;
        }
        
        .tea-leaf {
            width: 30px;
            height: 30px;
            background: #78b65b;
            border-radius: 0 100% 0 100%;
            position: absolute;
            opacity: 0.6;
        }

        /* Fallback untuk ikon jika Feather Icons gagal dimuat */
        .icon-fallback {
            display: none;
            font-style: normal;
            font-weight: bold;
        }
        
        .feather[data-feather].icon-failed {
            display: none;
        }
        
        .feather[data-feather].icon-failed + .icon-fallback {
            display: block;
        }
    </style>
</head>
<body class="font-poppins">
    
    <!-- Welcome Section -->
    <section class="welcome-bg min-h-screen flex items-center justify-center relative px-4 sm:px-6 lg:px-8">
        
        <!-- Decorative tea leaves -->
        <div class="tea-leaf absolute top-20 left-16 leaf-float"></div>
        <div class="tea-leaf absolute top-1/3 right-20 leaf-float" style="animation-delay: -1.5s;"></div>
        <div class="tea-leaf absolute bottom-1/4 left-1/4 leaf-float" style="animation-delay: -3s;"></div>
        <div class="tea-leaf absolute bottom-32 right-1/3 leaf-float" style="animation-delay: -0.5s;"></div>
        
        <div class="max-w-6xl w-full text-center relative z-10">
            
            <!-- Brand Logo -->
            <div class="mb-8 sm:mb-12 fade-in-up">
                <h1 class="pt-10 text-5xl sm:text-6xl lg:text-7xl font-bold text-text-dark mb-4">
                    Mesta<span class="text-primary italic">Kara</span>.
                </h1>
                <p class="text-lg sm:text-xl text-green-dark opacity-80">
                    Agrowisata Gunung Mas - Pengalaman Teh Premium
                </p>
            </div>
            
            <!-- Welcome Card -->
            <div class="welcome-card rounded-3xl p-8 sm:p-12 lg:p-16 mb-12 max-w-4xl mx-auto fade-in-up" style="animation-delay: 0.2s;">
                
                <div class="text-6xl mb-6">🍃</div>
                
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold text-text-dark mb-6 sm:mb-8">
                    Selamat Datang di
                    <span class="text-primary">Surga Wisata Teh</span>
                </h2>
                
                <p class="text-lg sm:text-xl text-green-dark leading-relaxed mb-8 sm:mb-10 max-w-3xl mx-auto">
                    Jelajahi keindahan perkebunan teh, nikmati udara segar pegunungan, dan rasakan 
                    cita rasa teh premium langsung dari kebun. Pengalaman wisata yang tak terlupakan 
                    menanti Anda bersama keluarga.
                </p>
                
                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <div class="feature-card p-6 rounded-xl">
                        <div class="w-16 h-16 bg-primary bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-mountain-sun" class="w-8 h-8 text-green-dark"></i>
                            <span class="icon-fallback">⛰️</span>
                        </div>
                        <h3 class="text-lg font-semibold text-text-dark mb-2">Pemandangan Indah</h3>
                        <p class="text-green-dark text-sm">Nikmati panorama kebun teh yang hijau dan udara pegunungan yang segar</p>
                    </div>
                    
                    <div class="feature-card p-6 rounded-xl">
                        <div class="w-16 h-16 bg-primary bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="users" class="w-8 h-8 text-green-dark"></i>
                            <span class="icon-fallback">👥</span>
                        </div>
                        <h3 class="text-lg font-semibold text-text-dark mb-2">Wisata Keluarga</h3>
                        <p class="text-green-dark text-sm">Berbagai wahana seru untuk semua anggota keluarga dari anak hingga dewasa</p>
                    </div>
                    
                    <div class="feature-card p-6 rounded-xl">
                        <div class="w-16 h-16 bg-primary bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-feather="coffee" class="w-8 h-8 text-green-dark"></i>
                            <span class="icon-fallback">🍵</span>
                        </div>
                        <h3 class="text-lg font-semibold text-text-dark mb-2">Teh Premium</h3>
                        <p class="text-green-dark text-sm">Cicipi berbagai varian teh berkualitas tinggi langsung dari sumbernya</p>
                    </div>
                </div>
                
                <!-- Start Button -->
                <a href="/dashboard" class="start-button inline-flex items-center px-10 sm:px-12 py-4 sm:py-5 text-lg sm:text-xl font-semibold text-black rounded-full hover:scale-105 transform transition-all duration-300 group">
                    <span class="mr-3">Mulai Jelajahi</span>
                    <i data-feather="arrow-right" class="w-6 h-6 group-hover:translate-x-1 transition-transform duration-300"></i>
                    <span class="icon-fallback">→</span>
                </a>
            </div>
            
            <!-- Info Cards -->
            <div class="pb-10 mt-24 grid grid-cols-2 lg:grid-cols-4 gap-4 max-w-4xl mx-auto fade-in-up" style="animation-delay: 0.4s;">
                <div class="bg-white bg-opacity-80 rounded-xl p-4 border border-primary border-opacity-20">
                    <div class="text-2xl font-bold text-primary mb-1">15+</div>
                    <div class="text-green-dark text-sm">Wahana Menarik</div>
                </div>
                <div class="bg-white bg-opacity-80 rounded-xl p-4 border border-primary border-opacity-20">
                    <div class="text-2xl font-bold text-primary mb-1">10k+</div>
                    <div class="text-green-dark text-sm">Pengunjung Puas</div>
                </div>
                <div class="bg-white bg-opacity-80 rounded-xl p-4 border border-primary border-opacity-20">
                    <div class="text-2xl font-bold text-primary mb-1">25</div>
                    <div class="text-green-dark text-sm">Varian Teh</div>
                </div>
                <div class="bg-white bg-opacity-80 rounded-xl p-4 border border-primary border-opacity-20">
                    <div class="text-2xl font-bold text-primary mb-1">4.8⭐</div>
                    <div class="text-green-dark text-sm">Rating</div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 sm:bottom-12 left-1/2 transform -translate-x-1/2">
            <div class="mb-24 flex flex-col items-center text-green-dark opacity-60">
                <span class="text-xs sm:text-sm mb-2 hidden sm:block">Klik untuk melihat lebih</span>
                <div class="w-5 h-8 sm:w-6 sm:h-10 border-2 border-green-dark rounded-full flex justify-center">
                    <div class="w-1 h-2 sm:h-3 bg-primary rounded-full mt-2 animate-bounce"></div>
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
        
        // Smooth hover effects for feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 15px 30px rgba(0, 0, 0, 0.1)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
            });
        });
        
        // Add subtle parallax effect
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const leaves = document.querySelectorAll('.tea-leaf');
            
            leaves.forEach((leaf, index) => {
                const speed = (index + 1) * 0.3;
                leaf.style.transform = `translateY(${scrolled * speed}px) rotate(${scrolled * 0.1}deg)`;
            });
        });
    </script>
</body>
</html>