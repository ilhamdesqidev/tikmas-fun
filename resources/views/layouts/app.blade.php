<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - MestaKara</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#CFD916',
                        'text-dark': '#333333',
                        sidebar: '#1f2937',
                        'sidebar-hover': '#374151',
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        /* Sidebar transitions */
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
        
        .content-transition {
            transition: margin-left 0.3s ease-in-out;
        }
        
        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
            }
            .sidebar-mobile.active {
                transform: translateX(0);
            }
        }
        
        /* Card styles */
        .settings-card, .card {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .settings-card:hover, .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        /* Toast notification */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            z-index: 1000;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s ease;
        }
        
        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .toast.success {
            background-color: #10B981;
        }
        
        .toast.error {
            background-color: #EF4444;
        }

        /* Promo card styles */
        .promo-card {
            background: white;
            color: #333333;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
                
                .promo-card-alt {
            background: white;
            color: #333333;
            border: 1px solid #e2e8f0;
        }

        .promo-card-premium {
            background: white;
            color: #333333;
            border: 1px solid #e2e8f0;
        }

        /* Drop-up menu styles */
        .dropup-menu {
            position: absolute;
            bottom: 100%;
            left: 0;
            right: 0;
            margin-bottom: 0.5rem;
            opacity: 0;
            transform: translateY(10px);
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 50;
            background: #374151;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .dropup-menu.show {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }
        
        .dropup-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: #e5e7eb;
            text-decoration: none;
            transition: all 0.2s ease;
            border-bottom: 1px solid #4b5563;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
        }
        
        .dropup-item:hover {
            background-color: #4b5563;
            color: white;
        }
        
        .dropup-item:last-child {
            border-bottom: none;
        }
        
        .dropup-item svg {
            margin-right: 12px;
            width: 18px;
            height: 18px;
        }

        /* Additional custom styles */
        @yield('extra-css')
    </style>
</head>
<body class="font-poppins bg-gray-50">
    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

    <!-- Toast Notification -->
    <div id="toast" class="toast hidden">
        <span id="toast-message"></span>
    </div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed left-0 top-0 w-64 h-screen bg-sidebar text-white sidebar-transition sidebar-mobile md:translate-x-0 z-50">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-600">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold">MestaKara</span>
            </div>
            <button id="close-sidebar" class="md:hidden text-white hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="mt-6">
            <!-- Main Menu -->
            <div class="px-4 pb-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Main</h3>
            </div>
            <ul class="space-y-1 px-2">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-sidebar-hover hover:text-white transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'active bg-sidebar-hover text-white' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.promo.index') }}" class="nav-link flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-sidebar-hover hover:text-white transition-all duration-200 {{ request()->routeIs('admin.promo.*') ? 'active bg-sidebar-hover text-white' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>
                        Paket Promo
                        <span class="ml-auto bg-primary text-black text-xs px-2 py-1 rounded-full font-medium">New</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tickets.index') }}" class="nav-link flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-sidebar-hover hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        Tiket
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.facilities.index') }}" class="nav-link flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-sidebar-hover hover:text-white transition-all duration-200 {{ request()->routeIs('admin.facilities.*') ? 'active bg-sidebar-hover text-white' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-9 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Fasilitas
                    </a>
                </li>
            </ul>

            <!-- Reports Menu -->
            <div class="px-4 pt-6 pb-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Reports</h3>
            </div>
            <ul class="space-y-1 px-2">
                <li>
                    <a href="#" class="nav-link flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-sidebar-hover hover:text-white transition-all duration-200">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Reports
                         <span class="ml-auto bg-primary text-black text-xs px-2 py-1 rounded-full font-medium">Coming soon</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Settings & Logout Drop-up Menu -->
        <div class="absolute bottom-4 left-0 right-0 px-4">
            <div class="relative">
                <button id="settings-dropup-toggle" class="w-full flex items-center justify-between px-4 py-3 text-gray-300 rounded-lg hover:bg-sidebar-hover hover:text-white transition-all duration-200 border border-gray-600">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Settings & Account</span>
                    </div>
                    <svg class="w-5 h-5 transition-transform duration-200" id="dropup-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                </button>
                
                <!-- Drop-up Menu -->
                
                <div id="settings-dropup" class="dropup-menu">

                    <a href="{{ route('admin.staff.verification.index') }}" class="dropup-item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Verifikasi Staff
                    </a>
                    
                    <a href="{{ route('admin.settings.general') }}" class="dropup-item">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropup-item w-full text-left">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="content-transition ml-0 md:ml-64">
        <!-- Top Navigation -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex items-center justify-between px-4 py-3">
                <div class="flex items-center space-x-4">
                    <button id="sidebar-toggle" class="md:hidden text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-sm text-gray-500">@yield('page-description', 'Welcome to admin panel')</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button class="text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 relative">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V12H5a4 4 0 110-8h10v5z"></path>
                            </svg>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                        </button>
                    </div>
                    
                    <!-- User Info -->
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span>Admin</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-4 md:p-6">
            @yield('content')
        </main>
    </div>

    <!-- Base JavaScript -->
    <script>
        // Sidebar functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const closeSidebar = document.getElementById('close-sidebar');
        const overlay = document.getElementById('mobile-overlay');

        function openSidebar() {
            sidebar.classList.add('active');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebarFunc() {
            sidebar.classList.remove('active');
            overlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
        if (closeSidebar) closeSidebar.addEventListener('click', closeSidebarFunc);
        if (overlay) overlay.addEventListener('click', closeSidebarFunc);

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                closeSidebarFunc();
            }
        });

        // Settings drop-up menu functionality
        const settingsDropupToggle = document.getElementById('settings-dropup-toggle');
        const settingsDropup = document.getElementById('settings-dropup');
        const dropupChevron = document.getElementById('dropup-chevron');

        if (settingsDropupToggle) {
            settingsDropupToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                settingsDropup.classList.toggle('show');
                
                // Rotate chevron icon
                if (settingsDropup.classList.contains('show')) {
                    dropupChevron.style.transform = 'rotate(180deg)';
                } else {
                    dropupChevron.style.transform = 'rotate(0deg)';
                }
            });
        }

        // Close drop-up when clicking outside
        document.addEventListener('click', (e) => {
            if (settingsDropup && !e.target.closest('#settings-dropup-toggle') && !e.target.closest('#settings-dropup')) {
                settingsDropup.classList.remove('show');
                if (dropupChevron) dropupChevron.style.transform = 'rotate(0deg)';
            }
        });

        // Toast functionality
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        function showToast(message, type = 'success') {
            if (toast && toastMessage) {
                toastMessage.textContent = message;
                toast.className = `toast ${type} show`;
                
                setTimeout(() => {
                    toast.className = 'toast hidden';
                }, 3000);
            }
        }

        // Make showToast globally available
        window.showToast = showToast;

        // Initialize page
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Admin panel initialized');
        });

        // Yield section for additional JavaScript
        @yield('extra-js')
    </script>

    @stack('scripts')  <!-- TAMBAHKAN INI -->
</body>
</html>