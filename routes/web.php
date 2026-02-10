<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BukuWisudaController;
use App\Http\Controllers\Admin\WisudawanController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\ArsipController;
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
    Route::resource('template', TemplateController::class);
    
    // Kelola Arsip
    Route::get('/arsip', [ArsipController::class, 'index'])->name('admin.arsip.index');
    Route::get('/arsip/preview/{id}', [ArsipController::class, 'printPreview'])->name('admin.arsip.preview');
    Route::post('/arsip/generate/{id}', [ArsipController::class, 'generatePdf'])->name('admin.arsip.generate');

    // Pengaturan
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});
