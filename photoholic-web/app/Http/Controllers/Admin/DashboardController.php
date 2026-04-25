<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Studio;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        // 1. STATISTIK ATAS
        // Menghitung pendapatan hari ini dari total_price booking yang sudah dikonfirmasi/lunas
        $revenueToday = Booking::whereDate('booking_date', $today)
            ->whereIn('status', ['confirmed', 'selesai']) // Hanya yang sudah sah
            ->sum('total_price');

        $bookingsToday = Booking::whereDate('booking_date', $today)->count();
        $totalUsers = User::where('role', '!=', 'admin')->count(); 
        
        $activeStudiosCount = Studio::where('is_active', true)->count();
        $totalStudiosCount = Studio::count();

        $bookingsThisMonth = Booking::whereMonth('booking_date', $currentMonth)
            ->whereYear('booking_date', $currentYear)
            ->count();

        $pendingPayments = Booking::where('status', 'pending')->count();

        // 2. DATA LIST & TABEL
        // Jadwal Hari Ini - Dibatasi 5 data saja sesuai permintaan
        $todaySchedules = Booking::with(['user', 'studio'])
            ->whereDate('booking_date', $today)
            ->orderBy('start_time', 'asc')
            ->take(5)
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