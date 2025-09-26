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
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ScannerController;

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

// Payment routes (public)
Route::post('/payment/notification', [PaymentController::class, 'notificationHandler'])->name('payment.notification');
Route::get('/payment/finish', [PaymentController::class, 'paymentFinish'])->name('payment.finish');
Route::get('/payment/unfinish', [PaymentController::class, 'paymentUnfinish'])->name('payment.unfinish');
Route::get('/payment/error', [PaymentController::class, 'paymentError'])->name('payment.error');

// PERBAIKAN: Route check-status yang benar
Route::get('/payment/check-status/{order_id}', [PaymentController::class, 'checkStatus'])->name('payment.check-status');

// PERBAIKAN: Pisahkan route checkout form dan proses
Route::get('/checkout/{id}', [PaymentController::class, 'showCheckoutForm'])->name('checkout.form'); // Form checkout
Route::post('/checkout/{id}', [PaymentController::class, 'processCheckout'])->name('checkout.process'); // Proses checkout

Route::get('/payment/checkout/{order_id}', [PaymentController::class, 'showCheckout'])->name('payment.checkout');

// Invoice routes
Route::get('/invoice/{order_id}', [PaymentController::class, 'showInvoice'])->name('payment.invoice');
Route::get('/invoice/{order_id}/download', [PaymentController::class, 'showInvoice'])->name('payment.invoice.download');
Route::get('/invoice/{order_id}/autodownload', [PaymentController::class, 'autoDownloadInvoice'])->name('payment.invoice.autodownload');
// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Public routes (bisa diakses tanpa login)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])
        ->name('login')
        ->middleware('redirect.admin');
    
    Route::post('/login', [AdminAuthController::class, 'login']);

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
        
        // Facility Management Routes
        Route::prefix('facilities')->name('facilities.')->group(function () {
            Route::get('/', [FacilityController::class, 'index'])->name('index');
            Route::get('/create', [FacilityController::class, 'create'])->name('create');
            Route::post('/', [FacilityController::class, 'store'])->name('store');
            Route::get('/{facility}/edit', [FacilityController::class, 'edit'])->name('edit');
            Route::put('/{facility}', [FacilityController::class, 'update'])->name('update');
            Route::delete('/{facility}', [FacilityController::class, 'destroy'])->name('destroy');
        });

        // Tickets Management Routes (FIXED - menggunakan controller yang benar)
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::get('/', [TicketController::class, 'index'])->name('index');
            Route::get('/{order_id}', [TicketController::class, 'show'])->name('show');
            Route::post('/{order_id}/status', [TicketController::class, 'updateStatus'])->name('update-status');
            Route::delete('/{order_id}', [TicketController::class, 'destroy'])->name('destroy');
            Route::get('/export/export', [TicketController::class, 'export'])->name('export');
        });
        
        // Settings Routes
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/general', [SettingsController::class, 'general'])->name('general');
            Route::post('/general', [SettingsController::class, 'updateGeneral'])->name('general.update');
            Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
            Route::post('/security', [SettingsController::class, 'updateSecurity'])->name('security.update');
            Route::post('/website', [SettingsController::class, 'updateWebsite'])->name('website.update');
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
    
    // API untuk tickets
    Route::get('/tickets/stats', [TicketController::class, 'getStats']);
});

// Routes untuk Scanner System (Staff Only)
Route::prefix('scanner')->name('scanner.')->group(function () {
    
    // Halaman verifikasi petugas
    Route::get('/verification', [ScannerController::class, 'showVerificationForm'])->name('verification');
    Route::post('/verify', [ScannerController::class, 'verifyStaff'])->name('verify');
    
    // Dashboard scanner (memerlukan verifikasi)
    Route::get('/dashboard', [ScannerController::class, 'dashboard'])->name('dashboard');
    
    // API untuk scan barcode
    Route::post('/scan', [ScannerController::class, 'scanBarcode'])->name('scan');
    
    // API untuk menggunakan tiket
    Route::post('/use', [ScannerController::class, 'useTicket'])->name('use');
    
    // Logout petugas
    Route::post('/logout', [ScannerController::class, 'logout'])->name('logout');
    Route::get('/logout', [ScannerController::class, 'logout']);
    
    // API untuk mobile app (opsional)
    Route::post('/api/check', [ScannerController::class, 'checkTicket'])->name('api.check');
});