<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotifikasiController;

// --- Rute Beranda ---
Route::redirect('/', '/beranda');
Route::view('/beranda', 'beranda')->name('beranda')->middleware('auth');

// --- Rute Publik ---
Route::get('/reports/photo/{path}', [ReportController::class, 'photo'])
    ->where('path', '.*')
    ->name('reports.photo');

// --- Rute Autentikasi (Tamu / Belum Login) ---
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// --- Rute yang Membutuhkan Login (Grup Middleware Auth) ---
Route::middleware('auth')->group(function () {
    // Autentikasi
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Manajemen Laporan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports/preview', [ReportController::class, 'preview'])->name('reports.preview');
    Route::post('/reports/store', [ReportController::class, 'store'])->name('reports.store');

    // Manajemen Notifikasi (PBI 15)
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/buat-dummy', [NotifikasiController::class, 'buatDummy'])->name('notifikasi.dummy');
});