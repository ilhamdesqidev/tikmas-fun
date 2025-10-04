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
use App\Http\Controllers\WahanaController;
use App\Http\Controllers\Admin\StaffVerificationController;

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
Route::get('/promos', [PromoController::class, 'index'])->name('promo.index');
// routes/web.php
Route::get('/promo/{id}', [PromoController::class, 'show'])->name('promo.show');

Route::controller(WahanaController::class)->group(function () {
    Route::get('/wahana', 'index')->name('wahana.index');
    Route::get('/wahana/{id}', 'show')->name('wahana.show');
});

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

        Route::prefix('staff/verification')->name('staff.verification.')->group(function () {
            Route::get('/', [StaffVerificationController::class, 'index'])->name('index');
            Route::post('/', [StaffVerificationController::class, 'store'])->name('store');
            
            // Generate routes
            Route::get('/generate', [StaffVerificationController::class, 'generateCode'])->name('generate');
            Route::post('/generate-custom', [StaffVerificationController::class, 'generateCustomCode'])->name('generate.custom');
            Route::post('/check-code', [StaffVerificationController::class, 'checkCode'])->name('check');
            Route::get('/suggestions', [StaffVerificationController::class, 'getCodeSuggestions'])->name('suggestions');
            
            Route::put('/{id}', [StaffVerificationController::class, 'update'])->name('update');
            Route::post('/{id}/toggle', [StaffVerificationController::class, 'toggleStatus'])->name('toggle');
            Route::delete('/{id}', [StaffVerificationController::class, 'destroy'])->name('destroy');
            Route::post('/bulk', [StaffVerificationController::class, 'bulkAction'])->name('bulk');
        });
        
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
         // Tambahkan route lainnya
    Route::get('/promo', function () { return 'Promo Page'; })->name('promo.index');
    Route::get('/tickets', function () { return 'Tickets Page'; })->name('tickets.index');
    Route::get('/customers', function () { return 'Customers Page'; })->name('customers.index');
    Route::get('/reports', function () { return 'Reports Page'; })->name('reports.index');
        
        // Profile
        Route::get('/profile', [AdminDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [AdminDashboardController::class, 'updateProfile'])->name('profile.update');
        
        // Promo Management Routes - Simplified Version
        Route::prefix('promo')->name('promo.')->group(function () {
            // Resource Routes (CRUD)
            Route::get('/', [AdminPromoController::class, 'index'])->name('index');
            Route::get('/create', [AdminPromoController::class, 'create'])->name('create');
            Route::post('/', [AdminPromoController::class, 'store'])->name('store');
            Route::get('/{id}', [AdminPromoController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [AdminPromoController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminPromoController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminPromoController::class, 'destroy'])->name('destroy');
            
            // Additional Functionality
            Route::post('/{id}/toggle-status', [AdminPromoController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/bulk-action', [AdminPromoController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/stats/overview', [AdminPromoController::class, 'getPromoStats'])->name('stats');
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
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            
            // Update settings
            Route::post('/general', [SettingsController::class, 'updateGeneral'])->name('general.update');
            Route::post('/hero', [SettingsController::class, 'updateHero'])->name('hero.update');
            Route::post('/about', [SettingsController::class, 'updateAbout'])->name('about.update');
            Route::post('/website', [SettingsController::class, 'updateWebsite'])->name('website.update');
            
            // Wahana images CRUD
            Route::get('/wahana-images', [SettingsController::class, 'getWahanaImages'])->name('wahana.index');
            Route::post('/wahana-images', [SettingsController::class, 'storeWahanaImage'])->name('wahana.store');
            Route::post('/wahana-images/{id}', [SettingsController::class, 'updateWahanaImage'])->name('wahana.update');
            Route::delete('/wahana-images/{id}', [SettingsController::class, 'deleteWahanaImage'])->name('wahana.delete');
            Route::post('/wahana-images/reorder', [SettingsController::class, 'reorderWahanaImages'])->name('wahana.reorder');
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
    
    // Route untuk mencetak tiket gelang
    Route::get('/print/bracelet/{order_id}', [ScannerController::class, 'printBracelet'])->name('print.bracelet');
    
    // API untuk auto print setelah use ticket
    Route::post('/auto-print', [ScannerController::class, 'autoPrintBracelet'])->name('auto.print');
    
    // Logout petugas
    Route::post('/logout', [ScannerController::class, 'logout'])->name('logout');
    Route::get('/logout', [ScannerController::class, 'logout']);
    
    // API untuk mobile app (opsional)
    Route::post('/api/check', [ScannerController::class, 'checkTicket'])->name('api.check');
});