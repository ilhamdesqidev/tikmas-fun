@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Settings - MestaKara</title>
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
        .toast {
            position: fixed; top: 20px; right: 20px; padding: 16px 24px;
            border-radius: 8px; color: white; font-weight: 500;
            transform: translateX(400px); transition: transform 0.3s;
            z-index: 1000; min-width: 300px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .toast.show { transform: translateX(0); }
        .toast.success { background-color: #10b981; }
        .toast.error { background-color: #ef4444; }
        .section-card {
            background: white; border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-radius: 12px; margin-bottom: 24px;
        }
        .nav-tabs { border-bottom: 2px solid #e5e7eb; }
        .nav-tab {
            padding: 12px 24px; cursor: pointer; border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        .nav-tab:hover { background-color: #f9fafb; }
        .nav-tab.active { border-bottom-color: #CFD916; color: #000; font-weight: 600; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .image-preview {
            width: 150px; height: 150px; border-radius: 8px;
            object-fit: cover; border: 2px solid #e5e7eb;
        }
        .wahana-card {
            position: relative; border: 2px solid #e5e7eb; border-radius: 12px;
            padding: 16px; margin-bottom: 16px; transition: all 0.3s; cursor: move;
            background: white;
        }
        .wahana-card:hover { border-color: #CFD916; box-shadow: 0 4px 12px rgba(207, 217, 22, 0.2); }
        .wahana-card.dragging { opacity: 0.5; cursor: grabbing; }
        .wahana-card.drag-over { border-color: #CFD916; background-color: #fefce8; }
        .modal {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 999; align-items: center; justify-content: center;
        }
        .modal.show { display: flex; }
        .modal-content {
            background: white; border-radius: 12px; max-width: 600px; width: 90%;
            max-height: 90vh; overflow-y: auto; padding: 24px;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin { animation: spin 1s linear infinite; }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b py-6 px-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold">Dashboard Settings</h1>
            <p class="mt-1 text-sm text-gray-600">Configure all aspects of your dashboard display</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="max-w-7xl mx-auto px-6 mt-6">
        <div class="bg-white rounded-xl shadow-sm">
            <div class="nav-tabs flex overflow-x-auto">
                <div class="nav-tab active" data-tab="general">
                    <i data-feather="sliders" class="w-4 h-4 inline mr-2"></i>General
                </div>
                <div class="nav-tab" data-tab="hero">
                    <i data-feather="image" class="w-4 h-4 inline mr-2"></i>Hero Section
                </div>
                <div class="nav-tab" data-tab="about">
                    <i data-feather="info" class="w-4 h-4 inline mr-2"></i>About Us
                </div>
                <div class="nav-tab" data-tab="website">
                    <i data-feather="globe" class="w-4 h-4 inline mr-2"></i>Website
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Contents -->
    <div class="max-w-7xl mx-auto py-6 px-6">
        
        <!-- GENERAL SETTINGS -->
        <div class="tab-content active" id="general">
            <form id="general-form">
                @csrf
                <div class="section-card p-6">
                    <h2 class="text-xl font-semibold mb-4">General Settings</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Site Name</label>
                            <input type="text" name="site_name" 
                                   value="{{ $getSetting('site_name', 'MestaKara') }}" 
                                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Site Tagline</label>
                            <input type="text" name="site_tagline" 
                                   value="{{ $getSetting('site_tagline', 'Berlibur Dengan Wahana') }}" 
                                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Language</label>
                            <select name="default_language" class="w-full px-3 py-2 border rounded-lg">
                                <option value="id" {{ $getSetting('default_language', 'id') == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                <option value="en" {{ $getSetting('default_language', 'id') == 'en' ? 'selected' : '' }}>English</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Timezone</label>
                            <select name="timezone" class="w-full px-3 py-2 border rounded-lg">
                                <option value="Asia/Jakarta" {{ $getSetting('timezone', 'Asia/Jakarta') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta</option>
                                <option value="Asia/Makassar" {{ $getSetting('timezone', 'Asia/Jakarta') == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 font-medium">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- HERO SECTION -->
        <div class="tab-content" id="hero">
            <form id="hero-form" enctype="multipart/form-data">
                @csrf
                <div class="section-card p-6">
                    <h2 class="text-xl font-semibold mb-4">Hero Section</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Hero Title</label>
                            <input type="text" name="hero_title" 
                                   value="{{ $getSetting('hero_title', 'Berlibur Dengan') }}" 
                                   class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Hero Subtitle</label>
                            <input type="text" name="hero_subtitle" 
                                   value="{{ $getSetting('hero_subtitle', 'Wahana') }}" 
                                   class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Description</label>
                            <textarea name="hero_description" rows="4" class="w-full px-3 py-2 border rounded-lg">{{ $getSetting('hero_description', 'Mari Berlibur dan Nikmati Berbagai Wahana Seru...') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">CTA Button Text</label>
                            <input type="text" name="hero_cta_text" 
                                   value="{{ $getSetting('hero_cta_text', 'Dapatkan Promo') }}" 
                                   class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Background Image</label>
                            <input type="file" name="hero_background" accept="image/*" class="w-full px-3 py-2 border rounded-lg">
                            @if($getSetting('hero_background_path'))
                                <p class="text-xs text-gray-500 mt-1">Current: {{ basename($getSetting('hero_background_path')) }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 font-medium">
                            Save Hero Section
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ABOUT SECTION -->
        <div class="tab-content" id="about">
            <form id="about-form">
                @csrf
                <div class="section-card p-6">
                    <h2 class="text-xl font-semibold mb-4">About Us Section</h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Section Title</label>
                                <input type="text" name="about_title" 
                                       value="{{ $getSetting('about_title', 'Tentang') }}" 
                                       class="w-full px-3 py-2 border rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Subtitle</label>
                                <input type="text" name="about_subtitle" 
                                       value="{{ $getSetting('about_subtitle', 'Kami') }}" 
                                       class="w-full px-3 py-2 border rounded-lg">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Question</label>
                            <input type="text" name="about_question" 
                                   value="{{ $getSetting('about_question', 'Kenapa memilih Wahana kami?') }}" 
                                   class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Paragraph 1</label>
                            <textarea name="about_content_1" rows="3" class="w-full px-3 py-2 border rounded-lg">{{ $getSetting('about_content_1', 'MestaKara adalah penyedia wahana...') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Paragraph 2</label>
                            <textarea name="about_content_2" rows="3" class="w-full px-3 py-2 border rounded-lg">{{ $getSetting('about_content_2', 'Wahana kami didirikan...') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Paragraph 3</label>
                            <textarea name="about_content_3" rows="3" class="w-full px-3 py-2 border rounded-lg">{{ $getSetting('about_content_3', 'Dengan lebih dari 20 wahana...') }}</textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 font-medium">
                            Save About Section
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- WAHANA IMAGES TAB -->
        <div class="tab-content" id="wahana">
            <div class="section-card p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-semibold">Wahana Carousel Images</h2>
                        <p class="text-sm text-gray-600 mt-1">Manage images shown in the About section carousel</p>
                    </div>
                    <button onclick="openAddWahanaModal()" class="px-4 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 font-medium flex items-center">
                        <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                        Add Image
                    </button>
                </div>

                <div id="wahana-list" class="space-y-4">
                    <!-- Wahana cards will be loaded here via JavaScript -->
                    <div class="text-center py-8 text-gray-500">
                        <i data-feather="loader" class="w-8 h-8 mx-auto animate-spin mb-2"></i>
                        <p>Loading wahana images...</p>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <i data-feather="info" class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0 mt-0.5"></i>
                        <div class="text-sm text-blue-800">
                            <strong>Tips:</strong> Drag cards to reorder them. Changes are saved automatically. Only active images will appear in the carousel.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- WEBSITE SETTINGS -->
        <div class="tab-content" id="website">
            <form id="website-form">
                @csrf
                <div class="section-card p-6">
                    <h2 class="text-xl font-semibold mb-4">Website Appearance</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Website Description</label>
                            <textarea name="website_description" rows="3" class="w-full px-3 py-2 border rounded-lg">{{ $getSetting('website_description', 'Mari Berlibur...') }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Primary Color</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" id="primary-color" name="primary_color" 
                                           value="{{ $getSetting('primary_color', '#CFD916') }}" 
                                           class="w-12 h-10 rounded border cursor-pointer">
                                    <input type="text" id="primary-color-text" 
                                           value="{{ $getSetting('primary_color', '#CFD916') }}" 
                                           class="flex-1 px-3 py-2 border rounded-lg">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Footer Text</label>
                                <input type="text" name="footer_text" 
                                       value="{{ $getSetting('footer_text', '© 2025 Tiketmas. All rights reserved.') }}" 
                                       class="w-full px-3 py-2 border rounded-lg">
                            </div>
                        </div>
                        <div class="border-t pt-6">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <label class="font-medium">Maintenance Mode</label>
                                    <p class="text-sm text-gray-600">Disable public access</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="maintenance_mode" class="sr-only peer" 
                                           {{ $getSetting('maintenance_mode', '0') == '1' ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 font-medium">
                            Save Website Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
        feather.replace();

        // CSRF Token Setup
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Toast Notification
        function showToast(message, type = 'success') {
            const existingToast = document.querySelector('.toast');
            if (existingToast) existingToast.remove();

            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4">
                        <i data-feather="x" class="w-4 h-4"></i>
                    </button>
                </div>
            `;
            document.body.appendChild(toast);
            feather.replace();
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Tab Navigation
        document.querySelectorAll('.nav-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                tab.classList.add('active');
                const tabId = tab.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
                if (tabId === 'wahana') loadWahanaImages();
                feather.replace();
            });
        });

        // Color Picker Sync
        const colorInput = document.getElementById('primary-color');
        const colorTextInput = document.getElementById('primary-color-text');
        if (colorInput && colorTextInput) {
            colorInput.addEventListener('change', e => colorTextInput.value = e.target.value);
            colorTextInput.addEventListener('input', e => {
                if (/^#[0-9A-F]{6}$/i.test(e.target.value)) colorInput.value = e.target.value;
            });
        }

        // Form Submissions
        document.getElementById('general-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            try {
                const response = await fetch('{{ route("admin.settings.general.update") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await response.json();
                showToast(data.message, data.success ? 'success' : 'error');
            } catch (error) {
                showToast('Error saving settings', 'error');
            }
        });

        document.getElementById('hero-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            try {
                const response = await fetch('{{ route("admin.settings.hero.update") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await response.json();
                showToast(data.message, data.success ? 'success' : 'error');
            } catch (error) {
                showToast('Error saving hero section', 'error');
            }
        });

        document.getElementById('about-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            try {
                const response = await fetch('{{ route("admin.settings.about.update") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await response.json();
                showToast(data.message, data.success ? 'success' : 'error');
            } catch (error) {
                showToast('Error saving about section', 'error');
            }
        });

        document.getElementById('website-form')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            try {
                const response = await fetch('{{ route("admin.settings.website.update") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: formData
                });
                const data = await response.json();
                showToast(data.message, data.success ? 'success' : 'error');
            } catch (error) {
                showToast('Error saving website settings', 'error');
            }
        });
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            feather.replace();
        });
    </script>
</body>
</html>
@endsection