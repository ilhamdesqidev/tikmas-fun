<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\FacilityController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', [DashboardController::class, 'index'])->name('home');

Route::get('/promo/{id}', [PromoController::class, 'show'])->name('promo.show');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Public routes (bisa diakses tanpa login)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])
        ->name('login')
        ->middleware('redirect.admin');
    
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::prefix('facilities')->name('facilities.')->group(function () {
        Route::get('/', [FacilityController::class, 'index'])->name('index');
        Route::get('/create', [FacilityController::class, 'create'])->name('create');
        Route::post('/', [FacilityController::class, 'store'])->name('store');
        Route::get('/{facility}/edit', [FacilityController::class, 'edit'])->name('edit');
        Route::put('/{facility}', [FacilityController::class, 'update'])->name('update');
        Route::delete('/{facility}', [FacilityController::class, 'destroy'])->name('destroy');
    });

    // Protected routes (harus login sebagai admin)
    Route::middleware('admin')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Profile
        Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AdminDashboardController::class, 'updateProfile'])->name('profile.update');
        
        // Promo Management Routes
        Route::prefix('promo')->name('promo.')->group(function () {
            Route::get('/', [AdminPromoController::class, 'index'])->name('index');
            Route::get('/create', [AdminPromoController::class, 'create'])->name('create');
            Route::post('/store', [AdminPromoController::class, 'store'])->name('store');
            Route::get('/{id}', [AdminPromoController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AdminPromoController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminPromoController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminPromoController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [AdminPromoController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        // Settings Routes
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/general', [SettingsController::class, 'general'])->name('general');
            Route::post('/general', [SettingsController::class, 'updateGeneral'])->name('general.update');
            Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
            Route::post('/security', [SettingsController::class, 'updateSecurity'])->name('security.update');
            Route::post('/website', [SettingsController::class, 'updateWebsite'])->name('website.update');
        });
        
        // Tickets Routes (placeholder untuk pengembangan selanjutnya)
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::get('/', function () {
                return view('admin.tickets.index');
            })->name('index');
            
            Route::get('/create', function () {
                return view('admin.tickets.create');
            })->name('create');
            
            Route::get('/{id}', function ($id) {
                return view('admin.tickets.show', compact('id'));
            })->name('show');
        });
        
        // Customers Routes (placeholder untuk pengembangan selanjutnya)
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', function () {
                return view('admin.customers.index');
            })->name('index');
            
            Route::get('/{id}', function ($id) {
                return view('admin.customers.show', compact('id'));
            })->name('show');
        });
        
        // Reports Routes (placeholder untuk pengembangan selanjutnya)
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', function () {
                return view('admin.reports.index');
            })->name('index');
            
            Route::get('/sales', function () {
                return view('admin.reports.sales');
            })->name('sales');
            
            Route::get('/customers', function () {
                return view('admin.reports.customers');
            })->name('customers');
        });
        
        // Logout
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});

// API Routes untuk AJAX requests (jika diperlukan)
Route::prefix('api/admin')->name('api.admin.')->middleware('admin')->group(function () {
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'getDashboardStats']);
    Route::get('/promo/quick-stats', [AdminPromoController::class, 'getQuickStats']);
    Route::post('/promo/{id}/duplicate', [AdminPromoController::class, 'duplicate'])->name('promo.duplicate');
});

// Payment routes
Route::post('/payment/notification', [PaymentController::class, 'notificationHandler'])->name('payment.notification');
Route::get('/payment/finish', [PaymentController::class, 'paymentFinish'])->name('payment.finish');
Route::get('/payment/unfinish', [PaymentController::class, 'paymentUnfinish'])->name('payment.unfinish');
Route::get('/payment/error', [PaymentController::class, 'paymentError'])->name('payment.error');
Route::get('/payment/check-status', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
Route::post('/checkout/{id}', [PaymentController::class, 'processCheckout'])->name('checkout.process');
Route::get('/payment/checkout/{order_id}', [PaymentController::class, 'showCheckout'])->name('payment.checkout');
Route::get('/payment/invoice/{order_id}', [PaymentController::class, 'showInvoice'])->name('payment.invoice');