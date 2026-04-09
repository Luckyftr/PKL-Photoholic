<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Studio;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // 🔥 Activity terbaru
        $logs = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();
        $totalUsers = User::count();
        $totalStudios = Studio::count();
        $totalBookings = Booking::count();

        return view('admin.dashboard', compact(
            'logs',
            'totalUsers',
            'totalStudios',
            'totalBookings'
        ));
    }
}