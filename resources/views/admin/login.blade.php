@php
    use App\Models\Setting;
    
    // Get login customization settings
    $loginLogoText = Setting::get('login_logo_text', 'MestaKara');
    $loginTagline = Setting::get('login_tagline', 'Admin Panel');
    $loginWelcomeTitle = Setting::get('login_welcome_title', 'Welcome Back!');
    $loginWelcomeSubtitle = Setting::get('login_welcome_subtitle', 'Please login to your account');
    $loginFooterText = Setting::get('login_footer_text', '© 2025 MestaKara. All rights reserved.');
    $loginBackgroundPath = Setting::get('login_background_path');
    $loginPrimaryColor = Setting::get('login_primary_color', '#CFD916');
    
    $backgroundImage = $loginBackgroundPath 
        ? asset('storage/' . $loginBackgroundPath) 
        : 'https://images.unsplash.com/photo-1557683316-973673baf926?w=1200';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - {{ $loginLogoText }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 
                        primary: '{{ $loginPrimaryColor }}' 
                    },
                    fontFamily: { 
                        'poppins': ['Poppins', 'sans-serif'] 
                    }
                }
            }
        }
    </script>
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
        }
        
        .password-wrapper { 
            position: relative; 
        }
        
        .password-toggle {
            position: absolute; 
            right: 12px; 
            top: 50%;
            transform: translateY(-50%); 
            cursor: pointer;
            color: #6b7280; 
            transition: color 0.2s;
        }
        
        .password-toggle:hover { 
            color: {{ $loginPrimaryColor }}; 
        }
        
        .bg-image {
            background-image: url('{{ $backgroundImage }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        
        @media (max-width: 768px) {
            .login-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4 bg-image">
    
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    
    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-md login-container">
        
        <!-- Card -->
        <div class="glass-effect rounded-2xl shadow-2xl overflow-hidden">
            
            <!-- Header -->
            <div class="bg-primary p-6 text-center">
                <div class="w-16 h-16 bg-white rounded-full mx-auto mb-3 flex items-center justify-center shadow-lg">
                    <i data-feather="user" class="w-8 h-8 text-gray-800"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $loginLogoText }}</h1>
                <p class="text-sm text-gray-700 mt-1">{{ $loginTagline }}</p>
            </div>

            <!-- Welcome Section -->
            <div class="px-8 pt-6 pb-4 text-center border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">{{ $loginWelcomeTitle }}</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $loginWelcomeSubtitle }}</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                
                <!-- Success Message (after password reset) -->
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg animate-fade-in">
                        <div class="flex items-start">
                            <i data-feather="check-circle" class="w-5 h-5 text-green-600 mr-3 flex-shrink-0 mt-0.5"></i>
                            <p class="text-sm text-green-800">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg animate-fade-in">
                        <div class="flex items-start">
                            <i data-feather="alert-circle" class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5"></i>
                            <div class="flex-1">
                                @foreach ($errors->all() as $error)
                                    <p class="text-sm text-red-800">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('admin.login') }}" id="loginForm">
                    @csrf
                    
                    <div class="space-y-6">
                        
                        <!-- Username Field -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="user" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input 
                                    id="username" 
                                    type="text" 
                                    name="username" 
                                    value="{{ old('username') }}" 
                                    required 
                                    autofocus
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                                    placeholder="Enter your username">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="password-wrapper">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="lock" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input 
                                    id="password" 
                                    type="password" 
                                    name="password" 
                                    required
                                    class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                                    placeholder="Enter your password">
                                <span class="password-toggle" onclick="togglePassword('password')">
                                    <i data-feather="eye" class="w-5 h-5"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input 
                                    id="remember" 
                                    name="remember" 
                                    type="checkbox" 
                                    {{ old('remember') ? 'checked' : '' }}
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded cursor-pointer">
                                <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer select-none">
                                    Remember me
                                </label>
                            </div>
                            <a href="{{ route('admin.password.request') }}" class="text-sm text-primary hover:text-yellow-600 font-medium transition duration-200">
                                Forgot password?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="w-full bg-primary hover:bg-yellow-500 text-black font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i data-feather="log-in" class="w-5 h-5 mr-2"></i>
                            <span id="btnText">Login</span>
                            <svg id="btnLoader" class="hidden animate-spin h-5 w-5 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                        
                    </div>
                </form>

                <!-- Security Notice -->
                <div class="mt-6 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start text-xs text-blue-800">
                        <i data-feather="shield" class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5"></i>
                        <p>Your credentials are encrypted and secure. For security reasons, please do not share your login information.</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-sm text-white drop-shadow-lg">{{ $loginFooterText }}</p>
        </div>

        <!-- Help Link -->
        <div class="mt-3 text-center">
            <a href="mailto:support@mestakara.com" class="text-sm text-white hover:text-primary transition duration-200 drop-shadow-lg flex items-center justify-center">
                <i data-feather="help-circle" class="w-4 h-4 mr-1"></i>
                Need help? Contact support
            </a>
        </div>
        
    </div>

    <!-- Scripts -->
    <script>
        // Initialize Feather Icons
        feather.replace();

        // Toggle Password Visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-feather', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-feather', 'eye');
            }
            feather.replace();
        }

        // Form Submit Handler with Loading State
        document.getElementById('loginForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            btnText.textContent = 'Logging in...';
            btnLoader.classList.remove('hidden');
            
            // Re-enable Feather icons
            feather.replace();
        });

        // Auto-hide success message after 5 seconds
        @if (session('status'))
            setTimeout(function() {
                const successMsg = document.querySelector('.bg-green-50');
                if (successMsg) {
                    successMsg.style.transition = 'opacity 0.5s ease-out';
                    successMsg.style.opacity = '0';
                    setTimeout(() => successMsg.remove(), 500);
                }
            }, 5000);
        @endif

        // Add fade-in animation
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.glass-effect');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + F for forgot password
            if (e.altKey && e.key === 'f') {
                e.preventDefault();
                window.location.href = '{{ route("admin.password.request") }}';
            }
        });

        // Focus management
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        
        usernameInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                passwordInput.focus();
            }
        });
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        /* Loading animation */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Smooth transitions */
        input:focus {
            outline: none;
        }

        /* Button hover effect */
        button[type="submit"]:hover:not(:disabled) {
            transform: translateY(-2px);
        }

        button[type="submit"]:active:not(:disabled) {
            transform: translateY(0);
        }

        /* Custom scrollbar for mobile */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: {{ $loginPrimaryColor }};
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #b8b814;
        }
    </style>

</body>
</html>