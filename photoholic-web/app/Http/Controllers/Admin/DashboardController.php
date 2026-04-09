<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Studio;
use App\Models\User;
use Carbon\Carbon; // Pastikan import Carbon untuk manipulasi tanggal

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // 1. STATISTIK ATAS
        // Menghitung pendapatan hari ini (asumsi dari relasi studio->price untuk booking yang tidak dibatalkan)
        $revenueToday = Booking::whereDate('booking_date', $today)
            ->whereNotIn('status', ['dibatalkan']) // Sesuaikan dengan enum status kamu
            ->with('studio')
            ->get()
            ->sum(function ($booking) {
                return $booking->studio->price ?? 0;
            });

        $bookingsToday = Booking::whereDate('booking_date', $today)->count();
        $totalUsers = User::where('role', '!=', 'admin')->count(); // Hanya menghitung pelanggan
        
        $activeStudiosCount = Studio::where('is_active', true)->count();
        $totalStudiosCount = Studio::count();

        $bookingsThisMonth = Booking::whereMonth('booking_date', $currentMonth)
            ->whereYear('booking_date', $currentYear)
            ->count();

        $pendingPayments = Booking::where('status', 'pending')->count(); // Sesuaikan nama status

        // 2. DATA LIST & TABEL
        // Jadwal Hari Ini
        $todaySchedules = Booking::with(['user', 'studio'])
            ->whereDate('booking_date', $today)
            ->orderBy('start_time', 'asc')
            ->get();

        // Aktivitas Terbaru
        $logs = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Booking Terbaru (Untuk Tabel Bawah)
        $latestBookings = Booking::with(['user', 'studio'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'revenueToday',
            'bookingsToday',
            'totalUsers',
            'activeStudiosCount',
            'totalStudiosCount',
            'bookingsThisMonth',
            'pendingPayments',
            'todaySchedules',
            'logs',
            'latestBookings'
        ));
    }
}