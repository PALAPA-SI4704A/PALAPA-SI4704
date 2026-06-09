<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\NotifikasiController; // Import controller notifikasi
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::view('/beranda', 'beranda')->name('beranda')->middleware('auth');
Route::view('/faq', 'faq')->name('faq')->middleware('auth');

Route::get('/reports/photo/{path}', [ReportController::class, 'photo'])
    ->where('path', '.*')
    ->name('reports.photo');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ReportController::class, 'profile'])->name('profile');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports/preview', [ReportController::class, 'preview'])->name('reports.preview');
    Route::post('/reports/store', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    Route::get('/reports/{report}/history', [ReportController::class, 'history'])->name('reports.history');

    // Rute Notifikasi (PBI 15)
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read'); 
    Route::put('/profile/update', [ReportController::class, 'updateProfile'])->name('profile.update');
    Route::post('/reports/{report}/preview', [ReportController::class, 'previewEdit'])->name('reports.previewEdit');

    // Petugas Routes
    Route::get('/petugas/dashboard', [PetugasController::class, 'index'])->name('petugas.dashboard');
    Route::get('/petugas/reports/{report}', [PetugasController::class, 'show'])->name('petugas.reports.show');
    Route::post('/petugas/reports/{report}/verify', [PetugasController::class, 'verify'])->name('petugas.reports.verify');
    Route::post('/petugas/reports/{report}/status', [PetugasController::class, 'updateStatus'])->name('petugas.reports.updateStatus');

    // Admin Routes
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/reports', [AdminController::class, 'reportsIndex'])->name('admin.reports.index');
    Route::get('/admin/reports/{report}', [AdminController::class, 'show'])->name('admin.reports.show');
    Route::post('/admin/reports/{report}/verify', [AdminController::class, 'verify'])->name('admin.reports.verify');
    Route::post('/admin/reports/{report}/assign/{petugas}', [AdminController::class, 'assign'])->name('admin.reports.assign');
    Route::post('/admin/reports/{report}/reassign/{petugas}', [AdminController::class, 'reassign'])->name('admin.reports.reassign');
    Route::delete('/admin/reports/{report}', [AdminController::class, 'destroy'])->name('admin.reports.destroy');
    Route::get('/admin/users', [AdminController::class, 'usersIndex'])->name('admin.users.index');
    Route::post('/admin/users/import-petugas', [AdminController::class, 'importPetugas'])->name('admin.users.import');
    Route::get('/admin/users/{user}/edit', [AdminController::class, 'usersEdit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminController::class, 'usersUpdate'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'usersDestroy'])->name('admin.users.destroy');
    Route::get('/admin/tren-distribusi', [AdminController::class, 'trenDistribusi'])->name('admin.tren-distribusi');
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');