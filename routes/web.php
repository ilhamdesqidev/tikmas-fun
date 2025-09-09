<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Public routes (bisa diakses tanpa login)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])
        ->name('login')
        ->middleware('redirect.admin'); // Middleware untuk redirect jika sudah login
    
    Route::post('/login', [AdminAuthController::class, 'login']);

    // Protected routes (harus login sebagai admin)
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('profile');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        // Tambahkan routes admin lainnya di sini
    });
});