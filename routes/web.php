<?php

// Import class yang dibutuhkan untuk routing dan controller
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;

/**
 * Routing utama aplikasi
 * Mengatur route frontend dan admin dengan middleware dan prefix yang sesuai
 */

// Route untuk halaman utama frontend
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route untuk halaman kebijakan privasi dan syarat ketentuan frontend
Route::view('/kebijakan-privasi', 'frontend.privacy')->name('privacy');
Route::view('/syarat-ketentuan', 'frontend.terms')->name('terms');

// Group route dengan prefix 'admin' untuk halaman admin
Route::prefix('admin')->group(function () {
    // Route untuk menampilkan form login admin
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    // Route untuk proses login admin
    Route::post('login', [AuthController::class, 'login'])->name('admin.login.submit');
    // Route untuk logout admin
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Group route yang membutuhkan autentikasi admin (middleware auth:admin)
    Route::middleware(['auth:admin'])->name('admin.')->group(function () {
        // Route untuk halaman dashboard admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Resource route untuk manajemen kartu, kecuali show (CRUD kecuali tampil detail)
        Route::resource('cards', CardController::class)->except(['show']);
        // Resource route untuk manajemen pengguna admin, kecuali show
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Route untuk registrasi admin baru (tanpa autentikasi)
    Route::get('register', [AuthController::class, 'showAddAdminForm'])->name('admin.register');
    Route::post('register', [AuthController::class, 'storeAdmin'])->name('admin.storeAdmin');

    // Route untuk form lupa password admin
    Route::get('lupa-password', [AuthController::class, 'showForgotPasswordForm'])->name('admin.lupaPassword');
    // Route untuk mengirim email reset password
    Route::post('lupa-password', [AuthController::class, 'sendResetLinkEmail'])->name('admin.kirimLinkResetPassword');

    // Route untuk form reset password dengan token
    Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('admin.resetPasswordForm');
    // Route untuk proses reset password
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('admin.resetPassword');


    Route::post('cards/{card}/toggle-status', [CardController::class, 'toggleStatus'])
        ->name('admin.cards.toggle-status');
});
