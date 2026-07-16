<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TiktokAccountController;
use App\Http\Controllers\LiveSessionController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StatisticsController;

// Redirect root
Route::get('/', fn() => redirect()->route('dashboard'));

// Guest routes (unauthenticated only)
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // TikTok Accounts
    Route::resource('accounts', TiktokAccountController::class);

    // Live Sessions
    Route::resource('sessions', LiveSessionController::class);

    // Schedules
    Route::resource('schedules', ScheduleController::class);

    // Statistics
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
});
