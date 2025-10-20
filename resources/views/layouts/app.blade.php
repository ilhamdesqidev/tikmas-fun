<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - MestaKara</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #CFD916;
            --text-dark: #333333;
            --sidebar: #1f2937;
            --sidebar-hover: #374151;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
        }
        
        /* Sidebar styling */
        .sidebar {
            width: 16rem;
            height: 100vh;
            background-color: var(--sidebar);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 50;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease-in-out;
        }
        
        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid #4b5563;
            flex-shrink: 0;
        }
        
        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
        }
        
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid #4b5563;
            flex-shrink: 0;
            background-color: var(--sidebar);
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .content-area {
                margin-left: 0;
            }
        }
        
        @media (min-width: 769px) {
            .content-area {
                margin-left: 16rem;
            }
        }
        
        /* Navigation items */
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #d1d5db;
            border-radius: 0.5rem;
            margin: 0 0.5rem 0.25rem;
            transition: all 0.2s;
        }
        
        .nav-item:hover {
            background-color: var(--sidebar-hover);
            color: white;
        }
        
        .nav-item.active {
            background-color: var(--sidebar-hover);
            color: white;
        }
        
        .nav-icon {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
        }
        
        /* Overlay for mobile */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
            display: none;
        }
        
        .overlay.active {
            display: block;
        }
        
        /* Toast notification */
        .toast {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: white;
            z-index: 1000;
            opacity: 0;
            transform: translateY(-1.25rem);
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
        
        /* Card styles */
        .card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            padding: 1.5rem;
        }
        
        /* Badge styles */
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 9999px;
        }
        
        .badge-new {
            background-color: var(--primary);
            color: black;
        }
        
        .badge-coming {
            background-color: var(--primary);
            color: black;
        }
        
        /* Logout button */
        .logout-btn {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.75rem 1rem;
            color: #d1d5db;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        
        .logout-btn:hover {
            background-color: #dc2626;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Mobile Overlay -->
    <div id="overlay" class="overlay"></div>

    <!-- Toast Notification -->
    <div id="toast" class="toast hidden">
        <span id="toast-message"></span>
    </div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="flex items-center justify-between">
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
        </div>

        <!-- Navigation Menu -->
        <div class="sidebar-content">
            <!-- Main Menu -->
            <div class="px-4 pb-2 pt-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Main</h3>
            </div>
            <div class="mb-4">
                <a href="#" class="nav-item active">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                    Paket Promo
                    <span class="badge badge-new ml-auto">New</span>
                </a>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    Tiket
                </a>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-9 0H3m2 0h4M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Fasilitas
                </a>
            </div>

            <!-- Settings Menu -->
            <div class="px-4 pb-2 pt-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Settings</h3>
            </div>
            <div class="mb-4">
                <a href="#" class="nav-item">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Verifikasi Staff
                </a>
                <a href="#" class="nav-item">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Settings
                </a>
            </div>

            <!-- Reports Menu -->
            <div class="px-4 pb-2 pt-2">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Reports</h3>
            </div>
            <div class="mb-4">
                <a href="#" class="nav-item">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Reports
                    <span class="badge badge-coming ml-auto">Coming soon</span>
                </a>
            </div>
        </div>

        <!-- Logout Button (Fixed at bottom) -->
        <div class="sidebar-footer">
            <form method="POST" action="#" class="w-full">
                <button type="submit" class="logout-btn">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="content-area min-h-screen">
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
                        <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
                        <p class="text-sm text-gray-500">Welcome to admin panel</p>
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="card">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Total Pengunjung</h3>
                    <p class="text-3xl font-bold text-primary">1,248</p>
                    <p class="text-sm text-gray-500 mt-2">+12% dari bulan lalu</p>
                </div>
                
                <div class="card">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Pendapatan</h3>
                    <p class="text-3xl font-bold text-primary">Rp 48.5 Jt</p>
                    <p class="text-sm text-gray-500 mt-2">+8% dari bulan lalu</p>
                </div>
                
                <div class="card">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tiket Terjual</h3>
                    <p class="text-3xl font-bold text-primary">892</p>
                    <p class="text-sm text-gray-500 mt-2">+15% dari bulan lalu</p>
                </div>
            </div>
            
            <div class="mt-8 card">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Pembayaran berhasil</p>
                            <p class="text-sm text-gray-500">Tiket #TK-2023-0012 telah dibayar</p>
                        </div>
                        <div class="ml-auto text-sm text-gray-500">2 jam yang lalu</div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Promo baru ditambahkan</p>
                            <p class="text-sm text-gray-500">Paket Keluarga telah ditambahkan</p>
                        </div>
                        <div class="ml-auto text-sm text-gray-500">5 jam yang lalu</div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Sidebar functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const closeSidebar = document.getElementById('close-sidebar');
        const overlay = document.getElementById('overlay');

        function openSidebar() {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebarFunc() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
        if (closeSidebar) closeSidebar.addEventListener('click', closeSidebarFunc);
        if (overlay) overlay.addEventListener('click', closeSidebarFunc);

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 769) {
                closeSidebarFunc();
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
            
            // Test toast notification
            setTimeout(() => {
                showToast('Admin panel berhasil dimuat', 'success');
            }, 1000);
        });
    </script>
</body>
</html>