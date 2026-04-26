<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;

Route::redirect('/', '/beranda');

Route::view('/beranda', 'beranda')->name('beranda')->middleware('auth');

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
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');