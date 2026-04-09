<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StudioController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BlogController;

Route::get('/', function () {
    return view('welcome');
});

// Grup Route Admin
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Resource Route untuk CRUD otomatis
    Route::resource('users', UserController::class);
    Route::resource('studios', StudioController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('blogs', BlogController::class);
    
    // Route tambahan untuk toggle status studio
    Route::post('studios/{studio}/toggle', [StudioController::class, 'toggleStatus'])->name('studios.toggle');
});