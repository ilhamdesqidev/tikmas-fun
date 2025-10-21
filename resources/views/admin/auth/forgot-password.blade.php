<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#CFD916' },
                    fontFamily: { 'poppins': ['Poppins', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-primary p-6 text-center">
                <div class="w-16 h-16 bg-white rounded-full mx-auto mb-3 flex items-center justify-center">
                    <i data-feather="lock" class="w-8 h-8 text-gray-800"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Forgot Password?</h1>
                <p class="text-sm text-gray-700 mt-1">Don't worry, we'll send you reset instructions</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start">
                        <i data-feather="check-circle" class="w-5 h-5 text-green-600 mr-3 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-green-800">{{ session('status') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
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

                <form method="POST" action="{{ route('admin.password.email') }}">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="mail" class="w-5 h-5 text-gray-400"></i>
                                </div>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autofocus
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                    placeholder="Enter your email address">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Enter the email associated with your admin account</p>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full bg-primary hover:bg-yellow-500 text-black font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                            <i data-feather="send" class="w-5 h-5 mr-2"></i>
                            Send Reset Link
                        </button>
                    </div>
                </form>

                <!-- Back to Login -->
                <div class="mt-6 text-center">
                    <a href="{{ route('admin.login') }}" class="text-sm text-gray-600 hover:text-primary flex items-center justify-center">
                        <i data-feather="arrow-left" class="w-4 h-4 mr-1"></i>
                        Back to Login
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start">
                <i data-feather="info" class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5"></i>
                <div class="text-sm text-blue-800">
                    <strong>Note:</strong> The reset link will expire in 60 minutes. If you don't receive the email, check your spam folder.
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html>