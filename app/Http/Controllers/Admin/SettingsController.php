<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Show general settings page.
     */
    public function general()
    {
        // In a real application, you would fetch settings from database
        $settings = [
            'site_name' => 'MestaKara',
            'site_tagline' => 'Berlibur Dengan Wahana',
            'default_language' => 'id',
            'timezone' => 'Asia/Jakarta',
            'website_description' => 'Mari Berlibur dan Nikmati Berbagai Wahana Seru di Agrowisata Gunung Mas',
            'primary_color' => '#CFD916',
            'footer_text' => '© 2025 Tiketmas. All rights reserved.',
            'maintenance_mode' => false,
        ];

        // Get user profile data
        $user = [
            'full_name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'phone' => '+62 812 3456 7890',
            'role' => 'Super Administrator',
        ];

        return view('admin.settings.index', compact('settings', 'user'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'required|string|max:255',
            'default_language' => 'required|string|in:id,en',
            'timezone' => 'required|string|in:Asia/Jakarta,Asia/Makassar,Asia/Jayapura',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // In a real application, you would save to database
            // Setting::updateOrCreate(['key' => 'site_name'], ['value' => $request->site_name]);
            // Setting::updateOrCreate(['key' => 'site_tagline'], ['value' => $request->site_tagline]);
            // Setting::updateOrCreate(['key' => 'default_language'], ['value' => $request->default_language]);
            // Setting::updateOrCreate(['key' => 'timezone'], ['value' => $request->timezone]);

            return response()->json([
                'success' => true,
                'message' => 'General settings updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving settings'
            ], 500);
        }
    }

    /**
     * Update profile settings.
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // In a real application, you would update user profile
            // $user = Auth::user();
            // $user->update([
            //     'name' => $request->full_name,
            //     'email' => $request->email,
            //     'phone' => $request->phone,
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating profile'
            ], 500);
        }
    }

    /**
     * Update security settings.
     */
    public function updateSecurity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // In a real application, you would verify current password and update
            // $user = Auth::user();
            // 
            // if (!Hash::check($request->current_password, $user->password)) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Current password is incorrect'
            //     ], 422);
            // }
            //
            // $user->update([
            //     'password' => Hash::make($request->new_password)
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating password'
            ], 500);
        }
    }

    /**
     * Update website settings.
     */
    public function updateWebsite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'website_description' => 'required|string|max:500',
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'primary_color_text' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'footer_text' => 'required|string|max:255',
            'maintenance_mode' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // In a real application, you would save to database
            // Setting::updateOrCreate(['key' => 'website_description'], ['value' => $request->website_description]);
            // Setting::updateOrCreate(['key' => 'primary_color'], ['value' => $request->primary_color]);
            // Setting::updateOrCreate(['key' => 'footer_text'], ['value' => $request->footer_text]);
            // Setting::updateOrCreate(['key' => 'maintenance_mode'], ['value' => $request->has('maintenance_mode')]);

            return response()->json([
                'success' => true,
                'message' => 'Website settings updated successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving website settings'
            ], 500);
        }
    }
}