<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - {{ \App\Models\Setting::get('login_logo_text', 'MestaKara') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,600;0,700;1,700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      const primaryColor = '{{ \App\Models\Setting::get("login_primary_color", "#CFD916") }}';
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: primaryColor,
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
      .login-bg {
        @php
          $bgPath = \App\Models\Setting::get('login_background_path');
          $bgUrl = $bgPath 
            ? asset('storage/' . $bgPath) 
            : 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80';
        @endphp
        background-image: url('{{ $bgUrl }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        overflow: hidden;
        min-height: 100vh;
      }
      
      .login-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(8px);
        z-index: 1;
      }
      
      .login-bg::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="250" cy="250" r="200" fill="url(%23a)"/><circle cx="750" cy="750" r="300" fill="url(%23a)"/><circle cx="800" cy="200" r="150" fill="url(%23a)"/></svg>') repeat;
        animation: float 20s ease-in-out infinite;
        opacity: 0.5;
        z-index: 1;
      }
      
      @keyframes float {
        0%, 100% { transform: translate(-20px, -20px) rotate(0deg); }
        33% { transform: translate(20px, -30px) rotate(120deg); }
        66% { transform: translate(-30px, 20px) rotate(240deg); }
      }
      
      @media (max-width: 768px) {
        .login-bg::after {
          display: none;
        }
        
        .login-bg::before {
          backdrop-filter: blur(4px);
        }
      }
      
      .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
      }
      
      .input-group {
        position: relative;
      }
      
      .floating-label {
        position: absolute;
        top: 12px;
        left: 16px;
        color: #6b7280;
        transition: all 0.3s ease;
        pointer-events: none;
        font-size: 16px;
      }
      
      .form-input:focus + .floating-label,
      .form-input:not(:placeholder-shown) + .floating-label {
        top: -8px;
        left: 12px;
        font-size: 12px;
        color: {{ \App\Models\Setting::get('login_primary_color', '#CFD916') }};
        background: white;
        padding: 0 4px;
      }
      
      .form-input {
        background: transparent;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
      }
      
      .form-input:focus {
        border-color: {{ \App\Models\Setting::get('login_primary_color', '#CFD916') }};
        box-shadow: 0 0 0 3px {{ \App\Models\Setting::get('login_primary_color', '#CFD916') }}1a;
        outline: none;
      }
      
      .login-btn {
        background: linear-gradient(135deg, {{ \App\Models\Setting::get('login_primary_color', '#CFD916') }} 0%, {{ \App\Models\Setting::get('login_primary_color', '#CFD916') }}dd 100%);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
      }
      
      .login-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
      }
      
      .login-btn:hover::before {
        left: 100%;
      }
      
      .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px {{ \App\Models\Setting::get('login_primary_color', '#CFD916') }}4d;
      }
      
      .error-alert {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left: 4px solid #ef4444;
        animation: slideDown 0.3s ease-out;
      }
      
      @keyframes slideDown {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      
      .tea-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, {{ \App\Models\Setting::get('login_primary_color', '#CFD916') }} 0%, {{ \App\Models\Setting::get('login_primary_color', '#CFD916') }}cc 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 25px {{ \App\Models\Setting::get('login_primary_color', '#CFD916') }}33;
      }
      
      .login-bg > * {
        position: relative;
        z-index: 2;
      }
      
      .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        width: 100%;
      }
      
      @media (max-width: 768px) {
        .login-container {
          padding: 16px;
          align-items: flex-start;
          padding-top: 40px;
        }
        
        .glass-card {
          margin-top: 0;
        }
      }
    </style>
</head>
<body class="font-poppins">
    <div class="login-bg">
        <div class="login-container">
            <div class="w-full max-w-md">
                <!-- Logo/Brand -->
                <div class="text-center mb-8">
                    <div class="tea-icon">
                        <i data-feather="coffee" class="w-8 h-8 text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">
                        {{ \App\Models\Setting::get('login_logo_text', 'MestaKara') }}
                    </h1>
                    <p class="text-white/90 text-lg">{{ \App\Models\Setting::get('login_tagline', 'Admin Portal') }}</p>
                </div>
                
                <!-- Login Card -->
                <div class="glass-card rounded-2xl shadow-2xl p-6 md:p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-2">
                            {{ \App\Models\Setting::get('login_welcome_title', 'Welcome Back') }}
                        </h2>
                        <p class="text-gray-600">
                            {{ \App\Models\Setting::get('login_welcome_subtitle', 'Please sign in to your admin account') }}
                        </p>
                    </div>
                    
                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="error-alert rounded-xl p-4 mb-6">
                            <div class="flex items-center">
                                <i data-feather="alert-circle" class="w-5 h-5 text-red-600 mr-2 flex-shrink-0"></i>
                                <div>
                                    <p class="font-semibold text-red-800 text-sm">Please fix the following errors:</p>
                                    <ul class="list-disc list-inside text-red-700 text-sm mt-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Username Field -->
                        <div class="input-group">
                            <input 
                                type="text" 
                                class="form-input w-full px-4 py-3 rounded-xl text-gray-700 text-base"
                                id="username" 
                                name="username" 
                                value="{{ old('username') }}" 
                                placeholder=" "
                                required 
                                autofocus
                            >
                            <label for="username" class="floating-label font-medium">
                                <i data-feather="user" class="w-4 h-4 inline mr-1"></i>
                                Username
                            </label>
                        </div>
                        
                        <!-- Password Field -->
                        <div class="input-group">
                            <input 
                                type="password" 
                                class="form-input w-full px-4 py-3 rounded-xl text-gray-700 text-base"
                                id="password" 
                                name="password" 
                                placeholder=" "
                                required
                            >
                            <label for="password" class="floating-label font-medium">
                                <i data-feather="lock" class="w-4 h-4 inline mr-1"></i>
                                Password
                            </label>
                        </div>
                        
                        <!-- Login Button -->
                        <button 
                            type="submit" 
                            class="login-btn w-full py-3 px-6 rounded-xl text-white font-semibold text-lg shadow-lg hover:shadow-xl transition-all duration-300 relative z-10"
                        >
                            <span class="flex items-center justify-center">
                                <i data-feather="log-in" class="w-5 h-5 mr-2"></i>
                                Sign In
                            </span>
                        </button>
                    </form>
                    
                    <!-- Footer -->
                    <div class="text-center mt-8 pt-6 border-t border-gray-200">
                        <p class="text-gray-500 text-sm">
                            {{ \App\Models\Setting::get('login_footer_text', 'Protected by MestaKara Security') }}
                        </p>
                    </div>
                </div>
                
                <!-- Back to Website -->
                <div class="text-center mt-6">
                    <a href="/" class="inline-flex items-center text-white/90 hover:text-white transition-colors duration-300">
                        <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i>
                        Back to Website
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Feather icons
        feather.replace();
        
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-input');
            
            inputs.forEach(input => {
                // Add focus/blur effects
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
                
                // Check if input has value on load
                if (input.value) {
                    input.classList.add('has-value');
                }
                
                input.addEventListener('input', function() {
                    if (this.value) {
                        this.classList.add('has-value');
                    } else {
                        this.classList.remove('has-value');
                    }
                });
            });
            
            // Form submission effect
            const form = document.querySelector('form');
            const submitBtn = document.querySelector('.login-btn');
            
            if (form && submitBtn) {
                form.addEventListener('submit', function() {
                    submitBtn.innerHTML = '<i data-feather="loader" class="w-5 h-5 mr-2 animate-spin"></i>Signing In...';
                    submitBtn.disabled = true;
                    feather.replace();
                });
            }
            
            // Adjust layout for mobile
            function adjustLayout() {
                const isMobile = window.innerWidth <= 768;
                const container = document.querySelector('.login-container');
                
                if (isMobile) {
                    document.body.style.minHeight = '100vh';
                    document.body.style.overflow = 'auto';
                } else {
                    document.body.style.minHeight = '100vh';
                }
            }
            
            adjustLayout();
            window.addEventListener('resize', adjustLayout);
        });
    </script>
</body>
</html>