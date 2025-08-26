<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\DeletedCardController;
use App\Http\Controllers\Admin\ProfileController;

/**
 * Routing utama aplikasi
 * Mengatur route frontend dan admin dengan middleware dan prefix yang sesuai
 */

// ======================== FRONTEND ========================

// Splash screen
Route::get('/', function () {
    return view('frontend.splash');
})->name('splash');

// Halaman utama frontend
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Pencarian AJAX
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Kebijakan privasi & syarat ketentuan
Route::view('/kebijakan-privasi', 'frontend.privacy')->name('privacy');
Route::view('/syarat-ketentuan', 'frontend.terms')->name('terms');


// ======================== ADMIN ========================
Route::prefix('admin')->group(function () {

    // ---------- AUTH ----------
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Semua route di bawah ini harus login sebagai admin
    Route::middleware(['auth:admin', 'check.activity'])->name('admin.')->group(function () {

        // Heartbeat (update last_activity)
        Route::post('/heartbeat', [AuthController::class, 'heartbeat'])->name('heartbeat');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ---------- CRUD ----------
        Route::resource('cards', CardController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);

        // ---------- ACTIVITIES ----------
        Route::get('/detail', [ActivityController::class, 'detail'])->name('detail');
        Route::get('/deleted', [ActivityController::class, 'deleted'])->name('deleted');

        // AJAX - restore activity (PAKAI POST)
        Route::post('/activities/{id}/restore', [ActivityController::class, 'restore'])->name('activities.restore');

        // AJAX - permanent delete activity (PAKAI DELETE)
        Route::delete('/activities/{id}/permanent-delete', [ActivityController::class, 'permanentDelete'])->name('activities.permanentDelete');

        // ---------- CARDS ----------
        // AJAX - restore card (PAKAI POST)
        Route::post('/cards/{id}/restore', [DeletedCardController::class, 'restore'])->name('cards.restore');

        // AJAX - permanent delete card (PAKAI DELETE)
        Route::delete('/cards/{id}/permanent-delete', [DeletedCardController::class, 'permanentDelete'])->name('cards.permanentDelete');

        // ---------- BACKWARD COMPATIBILITY ----------
        // Hanya fallback kalau masih ada request lama yang pakai GET
        Route::get('/activities/{id}/restore', [ActivityController::class, 'restoreFallback'])->name('activities.restore.fallback');
        Route::get('/activities/{id}/permanent-delete', [ActivityController::class, 'permanentDeleteFallback'])->name('activities.permanentDelete.fallback');

        // ---------- PROFILE ----------
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });

    // ---------- REGISTER ADMIN ----------
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('register', [AuthController::class, 'register'])->name('admin.storeAdmin');

    // ---------- FORGOT PASSWORD ----------
    Route::get('lupa-password', [AuthController::class, 'showForgotPasswordForm'])->name('admin.lupaPassword');
    Route::post('lupa-password', [AuthController::class, 'sendResetLinkEmail'])->name('admin.kirimLinkResetPassword');

    // ---------- RESET PASSWORD ----------
    Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('admin.resetPasswordForm');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('admin.resetPassword');

    // ---------- TOGGLE CARD STATUS ----------
    Route::post('cards/{card}/toggle-status', [CardController::class, 'toggleStatus'])
        ->name('admin.cards.toggle-status');
});
