<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Petugas - MestaKara Scanner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-4">
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">MestaKara Scanner</h1>
            <p class="text-white text-opacity-90">Sistem Scanner Tiket</p>
        </div>

        <!-- Verification Card -->
        <div class="bg-white rounded-xl shadow-2xl p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Verifikasi Petugas</h2>
                <p class="text-gray-600 text-sm">Masukkan kode petugas untuk mengakses dashboard scanner</p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Verification Form -->
            <form action="{{ route('scanner.verify') }}" method="POST" id="verificationForm">
                @csrf
                <div class="mb-6">
                    <label for="staff_code" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Petugas
                    </label>
                    <input 
                        type="text" 
                        id="staff_code" 
                        name="staff_code"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center text-lg font-mono tracking-widest uppercase"
                        placeholder="Masukkan kode petugas"
                        value="{{ old('staff_code') }}"
                        autocomplete="off"
                        maxlength="20"
                        required
                        autofocus
                    >
                </div>

                <button 
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105"
                    id="submitBtn"
                >
                    <span id="submitText">Verifikasi</span>
                    <span id="loadingText" class="hidden">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memverifikasi...
                    </span>
                </button>
            </form>

            <!-- Info Section -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="text-center">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Petunjuk Penggunaan</h3>
                    <ul class="text-xs text-gray-600 text-left space-y-1">
                        <li>• Masukkan kode petugas yang telah diberikan</li>
                        <li>• Pastikan kode diketik dengan benar</li>
                        <li>• Hubungi admin jika mengalami kendala</li>
                    </ul>
                </div>
                
                <div class="mt-4 text-center">
                    <p class="text-xs text-gray-500">
                        Sistem Scanner Tiket MestaKara v1.0
                    </p>
                </div>
            </div>
        </div>

        <!-- Status Indicator -->
        <div class="mt-6 text-center">
            <div class="inline-flex items-center space-x-2 text-white text-opacity-90">
                <div class="w-2 h-2 bg-green-400 rounded-full pulse-animation"></div>
                <span class="text-sm">Sistem Online</span>
            </div>
        </div>
    </div>

    <script>
        // Auto-format input to uppercase
        document.getElementById('staff_code').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });

        // Handle form submission
        document.getElementById('verificationForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingText = document.getElementById('loadingText');
            
            // Show loading state
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            loadingText.classList.remove('hidden');
        });

        // Auto-focus input
        document.getElementById('staff_code').focus();

        // Handle Enter key
        document.getElementById('staff_code').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('verificationForm').submit();
            }
        });

        // Clear any previous session storage
        sessionStorage.clear();
    </script>
</body>
</html>