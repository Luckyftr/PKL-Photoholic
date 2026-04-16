<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Auth\AuthController;
use App\Models\Studio;
use App\Http\Controllers\Pelanggan\BookingController as PelangganBookingController;

/*
|--------------------------------------------------------------------------
| 1. ROUTE AUTENTIKASI (LOGIN & REGISTER)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route Google Login
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Route Register & Lupa Password
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request.custom');
Route::post('/forgot-password', [AuthController::class, 'updatePasswordCustom'])->name('password.update.custom');


/*
|--------------------------------------------------------------------------
| 2. ROUTE PELANGGAN (FRONTEND)
|--------------------------------------------------------------------------
*/
Route::get('/pelanggan/dashboard', function () {
    $studios = App\Models\Studio::latest()->get(); 
    return view('pelanggan.dashboard', compact('studios'));
})->name('home');

Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/studio', function () {
        $studios = App\Models\Studio::latest()->get();
        return view('pelanggan.studio.index', compact('studios'));
    })->name('studio.index');

    // === ROUTE YANG WAJIB LOGIN ===
    Route::middleware('auth')->group(function () {
        // Halaman Form Booking
        Route::get('/booking', [PelangganBookingController::class, 'index'])->name('booking.index');
        
        // API untuk mengambil jam yang sudah dibooking (dijadikan abu-abu)
        Route::get('/api/booked-slots', [PelangganBookingController::class, 'getBookedSlots'])->name('booking.slots');
        
        // API untuk menyimpan data ke database
        Route::post('/booking/store', [PelangganBookingController::class, 'store'])->name('booking.store');
    });
});

/*
|--------------------------------------------------------------------------
| 3. ROUTE ADMIN (BACKEND)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/bookings/history', [BookingController::class, 'history'])->name('bookings.history');

    // Route khusus halaman profil admin
    Route::view('/profile', 'admin.profile')->name('admin.profile');

    // Resource Route untuk CRUD otomatis
    Route::resource('users', UserController::class);
    Route::resource('studios', StudioController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('blogs', BlogController::class);
    
    // Route tambahan untuk toggle status
    Route::post('studios/{studio}/toggle', [StudioController::class, 'toggleStatus'])->name('studios.toggle');
    Route::post('users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
});