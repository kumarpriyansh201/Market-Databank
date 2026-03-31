<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketDataController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\AdminController;

// ── Public Pages ──
Route::get('/', function () {
    // Keep the landing page available even when tables are not migrated yet (e.g., fresh test DB).
    $stats = [
        'products' => Schema::hasTable('products') ? \App\Models\Product::count() : 0,
        'data_points' => Schema::hasTable('market_data') ? \App\Models\MarketData::approved()->count() : 0,
        'locations' => Schema::hasTable('locations') ? \App\Models\Location::count() : 0,
        'users' => Schema::hasTable('users') ? \App\Models\User::count() : 0,
    ];
    return view('welcome', compact('stats'));
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

// ── Auth ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
    Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/logout', function (Request $request) {
    if (Auth::check()) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    return redirect('/');
})->middleware('web');

// ── Authenticated Routes ──
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Market Data (view/search/download for all authenticated roles)
    Route::get('/market-data', [MarketDataController::class, 'index'])->name('market-data.index');
    Route::get('/market-data/{marketData}', [MarketDataController::class, 'show'])->whereNumber('marketData')->name('market-data.show');
    Route::get('/market-data/{marketData}/download', [MarketDataController::class, 'download'])->whereNumber('marketData')->name('market-data.download');

    // User routes
    Route::middleware('isUser')->group(function () {
        Route::get('/saved-datasets', [MarketDataController::class, 'saved'])->name('saved-datasets.index');
        Route::post('/market-data/{marketData}/save', [MarketDataController::class, 'toggleSave'])->whereNumber('marketData')->name('market-data.save');
    });

    // Contributor routes
    Route::middleware('isContributor')->group(function () {
        Route::get('/my-datasets', [MarketDataController::class, 'mine'])->name('market-data.mine');

        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
        Route::get('/analytics/chart-data', [AnalyticsController::class, 'chartData'])->name('analytics.chart-data');
    });

    // Dataset management routes (admin + contributor)
    Route::middleware('role:admin,contributor')->group(function () {
        Route::get('/market-data/create', [MarketDataController::class, 'create'])->name('market-data.create');
        Route::post('/market-data', [MarketDataController::class, 'store'])->name('market-data.store');
        Route::get('/market-data/{marketData}/edit', [MarketDataController::class, 'edit'])->whereNumber('marketData')->name('market-data.edit');
        Route::put('/market-data/{marketData}', [MarketDataController::class, 'update'])->whereNumber('marketData')->name('market-data.update');
        Route::delete('/market-data/{marketData}', [MarketDataController::class, 'destroy'])->whereNumber('marketData')->name('market-data.destroy');
    });

    // Reports
    Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.export.csv');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}/view', [ReportController::class, 'view'])->name('reports.view');
    Route::get('/reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');

    Route::middleware('role:admin,contributor')->group(function () {
        Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

        // Report file uploads (new feature)
        Route::get('/reports/upload/create', [ReportController::class, 'createUpload'])->name('reports.upload.create');
        Route::post('/reports/upload', [ReportController::class, 'storeUpload'])->name('reports.upload.store');
        Route::get('/reports/my-uploads', [ReportController::class, 'myUploads'])->name('reports.myUploads');
        Route::get('/reports/upload/{report}/download', [ReportController::class, 'downloadUpload'])->name('reports.upload.download');
        Route::delete('/reports/upload/{report}', [ReportController::class, 'destroyUpload'])->name('reports.upload.destroy');
    });

    // Admin report approval routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/reports/pending', [ReportController::class, 'pending'])->name('reports.pending');
        Route::post('/reports/{report}/approve', [ReportController::class, 'approve'])->name('reports.approve');
        Route::post('/reports/{report}/reject', [ReportController::class, 'reject'])->name('reports.reject');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{notification}/read', [ProfileController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [ProfileController::class, 'markAllRead'])->name('notifications.mark-all-read');

    // Comments
    Route::post('/market-data/{marketData}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// ── Admin Routes ──
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

    Route::get('/approvals', [AdminController::class, 'approvals'])->name('approvals');
    Route::post('/approvals/{marketData}/approve', [AdminController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{marketData}/reject', [AdminController::class, 'reject'])->name('approvals.reject');

    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');

    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');

    Route::get('/locations', [AdminController::class, 'locations'])->name('locations');
    Route::post('/locations', [AdminController::class, 'storeLocation'])->name('locations.store');
    Route::delete('/locations/{location}', [AdminController::class, 'deleteLocation'])->name('locations.delete');
});
