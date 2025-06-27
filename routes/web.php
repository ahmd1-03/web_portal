<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Admin\AuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/kebijakan-privasi', [HomeController::class, 'showPage'])->name('privacy')->defaults('slug', 'kebijakan-privasi');
Route::get('/syarat-ketentuan', [HomeController::class, 'showPage'])->name('terms')->defaults('slug', 'syarat-ketentuan');

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('cards', CardController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
});


Route::prefix('admin')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });
});

