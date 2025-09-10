<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateGeneral(Request $request)
    {
        // Logic untuk update general settings
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'required|string|max:255',
            'default_language' => 'required|string',
            'timezone' => 'required|string',
        ]);

        // Update settings logic here
        // Anda bisa menyimpan ke database atau file config
        
        return redirect()->back()->with('success', 'General settings updated successfully!');
    }

    public function updateProfile(Request $request)
    {
        // Logic untuk update profile
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
        
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updateSecurity(Request $request)
    {
        // Logic untuk update security settings
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    public function updateWebsite(Request $request)
    {
        // Logic untuk update website settings
        $request->validate([
            'website_description' => 'required|string|max:500',
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'footer_text' => 'required|string|max:255',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        // Update website settings logic here
        // Anda bisa menyimpan ke database atau file config
        
        return redirect()->back()->with('success', 'Website settings updated successfully!');
    }
}