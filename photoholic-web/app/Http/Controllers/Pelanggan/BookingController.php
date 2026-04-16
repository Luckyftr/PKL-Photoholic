<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Studio;
use App\Models\Booking;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // 1. Menampilkan halaman blade booking
    public function index()
    {
        $studios = Studio::where('is_active', true)->get();
        // Sesuaikan dengan letak file bladenya, misalnya: 'pelanggan.booking.index' atau 'pelanggan.booking'
        return view('pelanggan.booking.index', compact('studios')); 
    }

    // 2. Mengambil data slot yang sudah terisi (AJAX)
    public function getBookedSlots(Request $request)
    {
        $studio_id = $request->studio_id;
        $date = $request->date;

        // Cari pesanan di studio dan tanggal tersebut yang belum dibatalkan
        $bookings = Booking::where('studio_id', $studio_id)
            ->where('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $unavailable = [];

        // Pecah durasi menjadi slot 5 menitan untuk dimatikan (di-disable) di frontend
        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->start_time);
            $end = Carbon::parse($booking->end_time);

            while ($start < $end) {
                $unavailable[] = $start->format('H:i');
                $start->addMinutes(5);
            }
        }

        return response()->json(['unavailable' => $unavailable]);
    }

    // 3. Menyimpan pesanan dari Frontend ke Database (AJAX)
    public function store(Request $request)
    {
        // Validasi data yang dikirim dari Javascript
        $request->validate([
            'studio_id' => 'required|exists:studios,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $bookingCode = 'INV-' . strtoupper(uniqid());

        // Simpan ke database
        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => Auth::id(), // Diambil dari ID pelanggan yang sedang login
            'studio_id' => $request->studio_id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'payment_method' => 'qris',
            'status' => 'pending', // Menunggu admin mengonfirmasi pembayaran
            'notes' => $request->notes,
        ]);

        ActivityLog::record('Pemesanan Baru', 'Pelanggan ' . Auth::user()->name . ' membuat pesanan ' . $bookingCode);

        // Kembalikan nomor invoice agar bisa dicetak di frontend
        return response()->json([
            'success' => true, 
            'booking_code' => $bookingCode
        ]);
    }
}