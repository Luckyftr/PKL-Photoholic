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

        // 3. GRAFIK 30 HARI TERAKHIR (DIPISAH PER STUDIO)
        $startDate = Carbon::now()->subDays(29)->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');

        // Ambil semua data studio yang ada
        $studios = Studio::all();

        // Ambil data transaksi per hari & per studio selama 30 hari terakhir
        $dailyDataRaw = Booking::whereBetween('booking_date', [$startDate, $endDate])
            ->selectRaw('DATE(booking_date) as date, studio_id, COUNT(*) as count')
            ->groupBy('date', 'studio_id')
            ->get();

        // Kelompokkan data agar mudah dibaca: $groupedData['2023-10-01'][1] = jumlah
        $groupedData = [];
        foreach ($dailyDataRaw as $row) {
            $groupedData[$row->date][$row->studio_id] = $row->count;
        }

        $chartLabels = [];
        $datesArray = [];

        // Looping 30 hari ke belakang untuk sumbu X
        for ($i = 29; $i >= 0; $i--) {
            $dateObj = Carbon::now()->subDays($i);
            $chartLabels[] = $dateObj->translatedFormat('d M'); 
            $datesArray[] = $dateObj->format('Y-m-d');
        }

        // Siapkan warna untuk masing-masing garis studio
        $themeColors = [
            ['border' => '#ff4a5d', 'bg' => 'rgba(255, 74, 93, 0.1)'], // Merah Photoholic
            ['border' => '#4a90e2', 'bg' => 'rgba(74, 144, 226, 0.1)'], // Biru
            ['border' => '#50e3c2', 'bg' => 'rgba(80, 227, 194, 0.1)'], // Tosca
            ['border' => '#f5a623', 'bg' => 'rgba(245, 166, 35, 0.1)'], // Oren
            ['border' => '#bd10e0', 'bg' => 'rgba(189, 16, 224, 0.1)'], // Ungu
        ];

        $chartDatasets = [];
        $colorIndex = 0;

        // Buat dataset untuk masing-masing studio
        foreach ($studios as $studio) {
            $studioData = [];
            
            // Cocokkan data pemesanan dengan tanggalnya
            foreach ($datesArray as $dateString) {
                $studioData[] = $groupedData[$dateString][$studio->id] ?? 0;
            }

            // Pilih warna (akan berulang jika jumlah studio lebih dari 5)
            $color = $themeColors[$colorIndex % count($themeColors)];

            $chartDatasets[] = [
                'label' => $studio->name, // Nama studio akan jadi label
                'data' => $studioData,
                'borderColor' => $color['border'],
                'backgroundColor' => $color['bg'],
                'borderWidth' => 2,
                'tension' => 0.4,
                'fill' => true,
                'pointBackgroundColor' => $color['border']
            ];
            
            $colorIndex++;
        }

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
            'latestBookings',
            'chartLabels',
            'chartDatasets' // Ganti chartData menjadi chartDatasets
        ));
    }
}