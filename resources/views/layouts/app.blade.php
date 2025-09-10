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
    <script src="https://unpkg.com/feather-icons"></script>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .promo-card-alt {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        
        .promo-card-premium {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
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
        
        .dropup-item i {
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
                    <i data-feather="layout" class="w-5 h-5 text-black"></i>
                </div>
                <span class="text-xl font-bold">MestaKara</span>
            </div>
            <button id="close-sidebar" class="md:hidden text-white hover:text-gray-300">
                <i data-feather="x" class="w-6 h-6"></i>
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
                        <i data-feather="home" class="w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.promo.index') }}" class="nav-link flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-sidebar-hover hover:text-white transition-all duration-200 {{ request()->routeIs('admin.promo.*') ? 'active bg-sidebar-hover text-white' : '' }}">
                        <i data-feather="gift" class="w-5 h-5 mr-3"></i>
                        Paket Promo
                        <span class="ml-auto bg-primary text-black text-xs px-2 py-1 rounded-full font-medium">New</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-sidebar-hover hover:text-white transition-all duration-200">
                        <i data-feather="ticket" class="w-5 h-5 mr-3"></i>
                        Tiket
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-sidebar-hover hover:text-white transition-all duration-200">
                        <i data-feather="users" class="w-5 h-5 mr-3"></i>
                        Customers
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
                        <i data-feather="bar-chart-2" class="w-5 h-5 mr-3"></i>
                        Reports
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Settings & Logout Drop-up Menu -->
        <div class="absolute bottom-4 left-0 right-0 px-4">
            <div class="relative">
                <button id="settings-dropup-toggle" class="w-full flex items-center justify-between px-4 py-3 text-gray-300 rounded-lg hover:bg-sidebar-hover hover:text-white transition-all duration-200 border border-gray-600">
                    <div class="flex items-center">
                        <i data-feather="settings" class="w-5 h-5 mr-3"></i>
                        <span>Settings & Account</span>
                    </div>
                    <i data-feather="chevron-up" class="w-5 h-5 transition-transform duration-200" id="dropup-chevron"></i>
                </button>
                
                <!-- Drop-up Menu -->
                <div id="settings-dropup" class="dropup-menu">
                    <a href="{{ route('admin.settings.general') }}" class="dropup-item">
                        <i data-feather="settings" class="w-4 h-4"></i>
                        Settings
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropup-item w-full text-left">
                            <i data-feather="log-out" class="w-4 h-4"></i>
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
                        <i data-feather="menu" class="w-6 h-6"></i>
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
                            <i data-feather="bell" class="w-5 h-5"></i>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                        </button>
                    </div>
                    
                    <!-- User Info -->
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                            <i data-feather="user" class="w-4 h-4 text-black"></i>
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
        // Initialize Feather icons
        feather.replace();

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

        // Initialize icons after page load
        document.addEventListener('DOMContentLoaded', () => {
            feather.replace();
            
            // Additional initialization code can go here
            console.log('Admin panel initialized');
        });

        // Yield section for additional JavaScript
        @yield('extra-js')
    </script>
</body>
</html>