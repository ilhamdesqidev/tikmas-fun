@extends('layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-description', 'Manage your application settings')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - MestaKara</title>
    <meta name="csrf-token" content="dummy-csrf-token">
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
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .settings-card {
            background: white;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            transform: translateX(400px);
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
            min-width: 300px;
        }
        
        .toast.show {
            transform: translateX(0);
        }
        
        .toast.success {
            background-color: #10b981;
        }
        
        .toast.error {
            background-color: #ef4444;
        }
        
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }
        
        .loading button {
            position: relative;
        }
        
        .loading button::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- App Layout Simulation -->
    <div class="min-h-screen bg-gray-50">
        <!-- Page Title Section -->
        <div class="bg-white border-b border-gray-200 py-6 px-4 sm:px-6">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
                <p class="mt-1 text-sm text-gray-600">Manage your application settings</p>
            </div>
        </div>
        
        <!-- Content Section -->
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6">
            <!-- General Settings Section -->
            <form id="general-form" action="/admin/settings/general" method="POST">
                <input type="hidden" name="_token" value="dummy-csrf-token">
                <section id="general" class="settings-section">
                    <div class="settings-card rounded-xl p-6 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center mr-4">
                                <i data-feather="sliders" class="w-5 h-5 text-black"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">General Settings</h2>
                                <p class="text-sm text-gray-600">Basic application configurations</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                                <input type="text" name="site_name" value="MestaKara" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Site Tagline</label>
                                <input type="text" name="site_tagline" value="Berlibur Dengan Wahana" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Default Language</label>
                                <select name="default_language" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="id" selected>Bahasa Indonesia</option>
                                    <option value="en">English</option>
                                </select>
                            </div>  
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                                <select name="timezone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="Asia/Jakarta" selected>Asia/Jakarta (WIB)</option>
                                    <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                                    <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 font-medium">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </section>
            </form>

            <!-- Profile Settings Section -->
            <form id="profile-form" action="/admin/settings/profile" method="POST">
                <input type="hidden" name="_token" value="dummy-csrf-token">
                <section id="profile" class="settings-section">
                    <div class="settings-card rounded-xl p-6 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center mr-4">
                                <i data-feather="user" class="w-5 h-5 text-black"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Profile Settings</h2>
                                <p class="text-sm text-gray-600">Manage your personal information</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center" id="photo-display">
                                        <i data-feather="user" class="w-8 h-8 text-gray-600"></i>
                                    </div>
                                    <div>
                                        <input type="file" id="photo-input" accept="image/*" style="display: none;">
                                        <button type="button" id="change-photo-btn" class="px-4 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 text-sm font-medium">
                                            Change Photo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input type="text" name="full_name" value="Administrator" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" value="admin@gmail.com" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" name="phone" value="+62 812 3456 7890" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                                <input type="text" value="Super Administrator" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" readonly>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 font-medium">
                                Update Profile
                            </button>
                        </div>
                    </div>
                </section>
            </form>

            <!-- Security Section -->
            <form id="security-form" action="/admin/settings/security" method="POST">
                <input type="hidden" name="_token" value="dummy-csrf-token">
                <section id="security" class="settings-section">
                    <div class="settings-card rounded-xl p-6 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center mr-4">
                                <i data-feather="shield" class="w-5 h-5 text-black"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Security Settings</h2>
                                <p class="text-sm text-gray-600">Manage your account security</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                                <div class="grid grid-cols-1 gap-4 max-w-md">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                        <input type="password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                        <input type="password" name="new_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                        <input type="password" name="new_password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                </div>
                                <button type="submit" class="mt-4 px-6 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 font-medium">
                                    Update Password
                                </button>
                            </div>

                            <div class="border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Session Management</h3>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">Active Sessions</p>
                                        <p class="text-sm text-gray-600">Currently logged in from <span id="session-count">2</span> devices</p>
                                    </div>
                                    <button type="button" id="logout-all-btn" class="px-4 py-2 text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition-colors duration-200 text-sm font-medium">
                                        Logout All Devices
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </form>

            <!-- Website Settings Section -->
            <form id="website-form" action="/admin/settings/website" method="POST">
                <input type="hidden" name="_token" value="dummy-csrf-token">
                <section id="website" class="settings-section">
                    <div class="settings-card rounded-xl p-6 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center mr-4">
                                <i data-feather="globe" class="w-5 h-5 text-black"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Website Settings</h2>
                                <p class="text-sm text-gray-600">Configure your website appearance and content</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Website Description</label>
                                <textarea name="website_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">Mari Berlibur dan Nikmati Berbagai Wahana Seru di Agrowisata Gunung Mas</textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                                    <div class="flex items-center space-x-3">
                                        <input type="color" name="primary_color" value="#CFD916" class="w-12 h-10 rounded border border-gray-300">
                                        <input type="text" name="primary_color_text" value="#CFD916" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Footer Text</label>
                                    <input type="text" name="footer_text" value="© 2025 Tiketmas. All rights reserved." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="maintenance" name="maintenance_mode" class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                                <label for="maintenance" class="ml-2 text-sm font-medium text-gray-700">Enable Maintenance Mode</label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 font-medium">
                                Save Website Settings
                            </button>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>

    <script>
        // Initialize Feather icons
        feather.replace();

        // Toast notification function
        function showToast(message, type = 'success') {
            // Remove existing toast
            const existingToast = document.querySelector('.toast');
            if (existingToast) {
                existingToast.remove();
            }

            // Create new toast
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i data-feather="x" class="w-4 h-4"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            feather.replace();

            // Show toast
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        if (toast.parentNode) {
                            toast.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }

        // Simulate API call
        async function simulateAPICall(formData, url) {
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    // Simulate success most of the time
                    if (Math.random() > 0.1) {
                        resolve({
                            success: true,
                            message: 'Settings saved successfully!'
                        });
                    } else {
                        reject({
                            success: false,
                            message: 'Error saving settings. Please try again.'
                        });
                    }
                }, 1500); // Simulate network delay
            });
        }

        // Form submission with AJAX (like the original code)
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData(form);
                const url = form.getAttribute('action');
                const method = form.getAttribute('method');
                const button = form.querySelector('button[type="submit"]');
                const originalText = button.textContent;
                
                // Show loading state
                form.classList.add('loading');
                button.textContent = '';
                
                try {
                    const result = await simulateAPICall(formData, url);
                    
                    if (result.success) {
                        showToast(result.message || 'Settings saved successfully!');
                    } else {
                        showToast(result.message || 'Error saving settings', 'error');
                    }
                } catch (error) {
                    showToast(error.message || 'An error occurred. Please try again.', 'error');
                } finally {
                    // Remove loading state
                    form.classList.remove('loading');
                    button.textContent = originalText;
                }
            });
        });

        // Photo upload functionality
        const changePhotoBtn = document.getElementById('change-photo-btn');
        const photoInput = document.getElementById('photo-input');
        const photoDisplay = document.getElementById('photo-display');

        changePhotoBtn.addEventListener('click', () => {
            photoInput.click();
        });

        photoInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    photoDisplay.innerHTML = `<img src="${e.target.result}" alt="Profile Photo" class="w-16 h-16 rounded-full object-cover">`;
                    showToast('Photo updated successfully!', 'success');
                };
                reader.readAsDataURL(file);
            }
        });

        // Color picker synchronization
        const colorInput = document.querySelector('input[name="primary_color"]');
        const colorTextInput = document.querySelector('input[name="primary_color_text"]');

        colorInput.addEventListener('change', (e) => {
            colorTextInput.value = e.target.value;
        });

        colorTextInput.addEventListener('input', (e) => {
            const color = e.target.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                colorInput.value = color;
            }
        });

        // Logout all devices functionality
        document.getElementById('logout-all-btn').addEventListener('click', () => {
            if (confirm('Are you sure you want to logout from all devices?')) {
                // Simulate logout process
                showToast('Logged out from all devices successfully!', 'success');
                document.getElementById('session-count').textContent = '1';
            }
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', () => {
            feather.replace();
            console.log('Settings page initialized');
        });
    </script>
</body>
@endsection