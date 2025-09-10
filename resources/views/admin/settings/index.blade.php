@extends('layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-description', 'Manage your application settings')

@section('content')
    <!-- General Settings Section -->
    <form id="general-form" action="/admin/settings/general" method="POST">
        @csrf
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
                            <option value="id">Bahasa Indonesia</option>
                            <option value="en">English</option>
                        </select>
                    </div>  
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                        <select name="timezone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="Asia/Jakarta">Asia/Jakarta (WIB)</option>
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
        @csrf
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
                            <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center">
                                <i data-feather="user" class="w-8 h-8 text-gray-600"></i>
                            </div>
                            <div>
                                <button type="button" class="px-4 py-2 bg-primary text-black rounded-lg hover:bg-yellow-500 transition-colors duration-200 text-sm font-medium">
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
        @csrf
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
                                <p class="text-sm text-gray-600">Currently logged in from 2 devices</p>
                            </div>
                            <button type="button" class="px-4 py-2 text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition-colors duration-200 text-sm font-medium">
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
        @csrf
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
@endsection

@section('extra-js')
// Form submission with AJAX
const forms = document.querySelectorAll('form');

forms.forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(form);
        const url = form.getAttribute('action');
        const method = form.getAttribute('method');
        
        try {
            const response = await fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (response.ok) {
                showToast(result.message || 'Settings saved successfully!');
            } else {
                showToast(result.message || 'Error saving settings', 'error');
            }
        } catch (error) {
            showToast('An error occurred. Please try again.', 'error');
        }
    });
});
@endsection