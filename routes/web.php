<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BukuWisudaController;
use App\Http\Controllers\Admin\WisudawanController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\AdminManagementController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/cari-alumni', [PublicController::class, 'search'])->name('cari.alumni');
Route::get('/buku/{book}', [PublicController::class, 'showBook'])->name('buku.show');
Route::get('/buku/{book}/flipbook', [PublicController::class, 'flipbook'])->name('buku.flipbook');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Kelola Buku Wisuda
    Route::resource('buku-wisuda', BukuWisudaController::class);
    
    // Kelola Wisudawan
    Route::resource('wisudawan', WisudawanController::class);
    Route::post('/wisudawan/import', [WisudawanController::class, 'import'])->name('wisudawan.import');
    
    // Kelola Template
    Route::resource('template', TemplateController::class)->except(['index', 'show'])->middleware('system.admin');
    Route::get('template', [TemplateController::class, 'index'])->name('template.index');
    
    // Kelola Arsip
    Route::get('/arsip', [ArsipController::class, 'index'])->name('admin.arsip.index');
    Route::get('/arsip/preview/{id}', [ArsipController::class, 'printPreview'])->name('admin.arsip.preview');
    Route::post('/arsip/generate/{id}', [ArsipController::class, 'generatePdf'])->name('admin.arsip.generate');

    // Kelola Admin
    Route::resource('kelola-admin', AdminManagementController::class)->except(['show'])->middleware('system.admin');

    // Pengaturan Akun & API
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/account', [App\Http\Controllers\Admin\SettingController::class, 'updateAccount'])->name('settings.account.update');

    // API Sources
    Route::post('/api-sources', [App\Http\Controllers\Admin\ApiSourceController::class, 'store'])->name('api-sources.store');
    Route::delete('/api-sources/{apiSource}', [App\Http\Controllers\Admin\ApiSourceController::class, 'destroy'])->name('api-sources.destroy');
});
