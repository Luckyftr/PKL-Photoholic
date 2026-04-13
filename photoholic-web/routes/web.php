<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Auth\AuthController;

// Route Autentikasi
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Google Login
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::get('/', function () {
    return view('welcome');
});

// Route Register di sini!
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Route Lupa Password (Custom)
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request.custom');
Route::post('/forgot-password', [AuthController::class, 'updatePasswordCustom'])->name('password.update.custom');

// Route khusus halaman profil admin
Route::view('/profile', 'admin.profile')->name('admin.profile');

// Grup Route Admin
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/bookings/history', [BookingController::class, 'history'])->name('bookings.history');

    // Resource Route untuk CRUD otomatis
    Route::resource('users', UserController::class);
    Route::resource('studios', StudioController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('blogs', BlogController::class);
    
    // Route tambahan untuk toggle status studio
    Route::post('studios/{studio}/toggle', [StudioController::class, 'toggleStatus'])->name('studios.toggle');
    Route::post('users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
});