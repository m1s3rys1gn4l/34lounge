<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MenuController::class, 'index'])->name('menu.index');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('menu-items', MenuItemController::class)->except('show');
        Route::post('menu-items/{menuItem}/toggle-active', [MenuItemController::class, 'toggleActive'])->name('menu-items.toggle-active');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::post('categories/{category}/move-up', [CategoryController::class, 'moveUp'])->name('categories.move-up');
        Route::post('categories/{category}/move-down', [CategoryController::class, 'moveDown'])->name('categories.move-down');
        Route::get('hero-slides', [HeroSlideController::class, 'index'])->name('hero-slides.index');
        Route::post('hero-slides/{heroSlide}', [HeroSlideController::class, 'update'])->name('hero-slides.update');
        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('qr-code', [QrCodeController::class, 'index'])->name('qr-code.index');
        Route::get('qr-code/download', [QrCodeController::class, 'download'])->name('qr-code.download');
        Route::get('qr-code/card/download', [QrCodeController::class, 'downloadCard'])->name('qr-code.card-download');
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile/email', [ProfileController::class, 'updateEmail'])->name('profile.update-email');
        Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    });
});
