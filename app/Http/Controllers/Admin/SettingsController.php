<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WahanaImage;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = Setting::getAllGrouped();
        $wahanaImages = WahanaImage::orderBy('order')->get();
        $allSettings = Setting::all()->pluck('value', 'key')->toArray();
        $getSetting = function($key, $default = '') use ($allSettings) {
            return $allSettings[$key] ?? $default;
        };

        return view('admin.settings.index', compact('settings', 'wahanaImages', 'getSetting'));
    }

    /**
     * Update General Settings
     */
    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'required|string|max:255',
            'default_language' => 'required|string',
            'timezone' => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'text', 'general');
        }

        return response()->json([
            'success' => true,
            'message' => 'General settings updated successfully!'
        ]);
    }

    /**
     * Update Hero Section
     */
    public function updateHero(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'required|string|max:500',
            'hero_subtitle' => 'required|string|max:255',
            'hero_description' => 'required|string',
            'hero_cta_text' => 'required|string|max:100',
            'hero_background' => 'nullable|image|mimes:jpeg,png,jpg|max:20048',
        ]);

        // Save text settings
        foreach ($validated as $key => $value) {
            if ($key !== 'hero_background') {
                $type = $key === 'hero_description' ? 'textarea' : 'text';
                Setting::set($key, $value, $type, 'hero');
            }
        }

        // Handle background image upload
        if ($request->hasFile('hero_background')) {
            $oldImage = Setting::get('hero_background_path');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            $path = $request->file('hero_background')->store('hero', 'public');
            Setting::set('hero_background_path', $path, 'image', 'hero');
        }

        return response()->json([
            'success' => true,
            'message' => 'Hero section updated successfully!'
        ]);
    }

    /**
     * Update About Section
     */
    public function updateAbout(Request $request)
    {
        $validated = $request->validate([
            'about_title' => 'required|string|max:255',
            'about_subtitle' => 'required|string|max:255',
            'about_question' => 'required|string|max:500',
            'about_content_1' => 'required|string',
            'about_content_2' => 'required|string',
            'about_content_3' => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            $type = in_array($key, ['about_content_1', 'about_content_2', 'about_content_3']) ? 'textarea' : 'text';
            Setting::set($key, $value, $type, 'about');
        }

        return response()->json([
            'success' => true,
            'message' => 'About section updated successfully!'
        ]);
    }

    /**
     * Update Website Settings
     */
    public function updateWebsite(Request $request)
    {
        $validated = $request->validate([
            'website_description' => 'required|string',
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'footer_text' => 'required|string|max:255',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        Setting::set('website_description', $validated['website_description'], 'textarea', 'website');
        Setting::set('primary_color', $validated['primary_color'], 'color', 'website');
        Setting::set('footer_text', $validated['footer_text'], 'text', 'website');
        Setting::set('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0', 'boolean', 'website');

        return response()->json([
            'success' => true,
            'message' => 'Website settings updated successfully!'
        ]);
    }

    /**
     * Update Login Page Customization
     */
    public function updateLoginCustomization(Request $request)
    {
        $validated = $request->validate([
            'login_logo_text' => 'required|string|max:255',
            'login_tagline' => 'required|string|max:255',
            'login_welcome_title' => 'required|string|max:255',
            'login_welcome_subtitle' => 'required|string|max:255',
            'login_footer_text' => 'required|string|max:255',
            'login_background' => 'nullable|image|mimes:jpeg,png,jpg|max:20048',
            'login_primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        // Save text settings
        foreach ($validated as $key => $value) {
            if ($key !== 'login_background') {
                $type = $key === 'login_primary_color' ? 'color' : 'text';
                Setting::set($key, $value, $type, 'login');
            }
        }

        // Handle background image upload
        if ($request->hasFile('login_background')) {
            $oldImage = Setting::get('login_background_path');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            $path = $request->file('login_background')->store('login', 'public');
            Setting::set('login_background_path', $path, 'image', 'login');
        }

        return response()->json([
            'success' => true,
            'message' => 'Login page customization updated successfully!'
        ]);
    }

    /**
     * Update Admin Credentials
     */
    public function updateAdminCredentials(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins,username,' . $admin->id,
            'email' => 'nullable|email|unique:admins,email,' . $admin->id,
            'current_password' => 'required',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        // Verify current password
        if (!Hash::check($validated['current_password'], $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect!'
            ], 422);
        }

        // Update admin data
        $admin->name = $validated['name'];
        $admin->username = $validated['username'];
        
        if (!empty($validated['email'])) {
            $admin->email = $validated['email'];
        }

        // Update password if provided
        if (!empty($validated['new_password'])) {
            $admin->password = Hash::make($validated['new_password']);
        }

        $admin->save();

        return response()->json([
            'success' => true,
            'message' => 'Admin credentials updated successfully!'
        ]);
    }

    /**
     * Get current admin data
     */
    public function getAdminData()
    {
        $admin = Auth::guard('admin')->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'name' => $admin->name,
                'username' => $admin->username,
                'email' => $admin->email ?? '',
            ]
        ]);
    }

    /**
     * Get all wahana images
     */
    public function getWahanaImages()
    {
        $images = WahanaImage::orderBy('order')->get();
        return response()->json($images);
    }

    /**
     * Store new wahana image
     */
    public function storeWahanaImage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:20048',
            'order' => 'nullable|integer',
        ]);

        $path = $request->file('image')->store('wahana', 'public');

        $wahana = WahanaImage::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image_path' => $path,
            'order' => $validated['order'] ?? (WahanaImage::max('order') + 1),
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Wahana image added successfully!',
            'data' => $wahana
        ]);
    }

    /**
     * Update wahana image
     */
    public function updateWahanaImage(Request $request, $id)
    {
        $wahana = WahanaImage::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:20048',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'order' => $validated['order'] ?? $wahana->order,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($wahana->image_path)) {
                Storage::disk('public')->delete($wahana->image_path);
            }
            $data['image_path'] = $request->file('image')->store('wahana', 'public');
        }

        $wahana->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Wahana image updated successfully!',
            'data' => $wahana
        ]);
    }

    /**
     * Delete wahana image
     */
    public function deleteWahanaImage($id)
    {
        $wahana = WahanaImage::findOrFail($id);
        
        // Delete image file from storage
        if (Storage::disk('public')->exists($wahana->image_path)) {
            Storage::disk('public')->delete($wahana->image_path);
        }
        
        $wahana->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wahana image deleted successfully!'
        ]);
    }

    /**
     * Reorder wahana images
     */
    public function reorderWahanaImages(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:wahana_images,id',
            'orders.*.order' => 'required|integer',
        ]);

        foreach ($validated['orders'] as $item) {
            WahanaImage::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Wahana images reordered successfully!'
        ]);
    }
}