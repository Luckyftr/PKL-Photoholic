<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $logs = ActivityLog::with('user')->latest()->take(10)->get();
        return view('admin.dashboard', compact('logs'));
    }
}
