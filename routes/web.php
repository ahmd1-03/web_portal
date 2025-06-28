<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Admin\AuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/kebijakan-privasi', 'frontend.privacy')->name('privacy');
Route::view('/syarat-ketentuan', 'frontend.terms')->name('terms');

Route::prefix('admin')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth:admin'])->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('cards', CardController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);

        // Routes untuk manajemen admin
        Route::get('admins/create', [\App\Http\Controllers\Admin\AuthController::class, 'showAddAdminForm'])->name('admins.create');
        Route::post('admins', [\App\Http\Controllers\Admin\AuthController::class, 'storeAdmin'])->name('admin.storeAdmin');
    });
});
