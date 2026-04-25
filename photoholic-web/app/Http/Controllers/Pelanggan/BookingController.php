<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Studio;
use App\Models\Booking;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Penting untuk INV-XXXXXXX

class BookingController extends Controller
{
    // 1. Menampilkan halaman blade booking
    public function index()
    {
        $studios = Studio::where('is_active', true)->get();
        return view('pelanggan.booking.index', compact('studios')); 
    }

    // 2. Mengambil data slot yang sudah terisi (AJAX) - Tetap 5 menitan agar frontend bisa blokir jam
    public function getBookedSlots(Request $request)
    {
        $studio_id = $request->studio_id;
        $date = $request->date;

        $bookings = Booking::where('studio_id', $studio_id)
            ->whereDate('booking_date', $date) 
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $unavailable = [];

        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->start_time);
            $end = Carbon::parse($booking->end_time);

            // Kita kirimkan pecahan 5 menit ke frontend hanya untuk keperluan tampilan UI
            while ($start < $end) {
                $unavailable[] = $start->format('H:i');
                $start->addMinutes(5);
            }
        }

        return response()->json(['unavailable' => $unavailable]);
    }

    // 3. Menyimpan pesanan (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'studio_id' => 'required|exists:studios,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $tanggalMurniString = Carbon::parse($request->booking_date)->format('Y-m-d');

        // Cek Bentrok
        $isBooked = Booking::where('studio_id', $request->studio_id)
            ->where('booking_date', $tanggalMurniString)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })->exists();

        if ($isBooked) {
            return response()->json(['success' => false, 'message' => 'Jadwal sudah terisi!'], 422);
        }

        $studio = Studio::findOrFail($request->studio_id);

        // Hitung Harga Otomatis
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $sessionsCount = ceil($start->diffInMinutes($end) / 5);
        $totalPrice = $sessionsCount * $studio->price;

        $booking = Booking::create([
            'booking_code' => 'INV-' . strtoupper(Str::random(7)),
            'user_id' => Auth::id(),
            'studio_id' => $request->studio_id,
            'booking_date' => $tanggalMurniString,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'payment_method' => 'qris', 
            'status' => 'pending',     
            'total_price' => $totalPrice,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dikirim! Mohon tunggu konfirmasi admin.',
            'redirect_url' => route('pelanggan.pembayaran.index') 
        ]);
    }

    // 4. Menampilkan Jadwal Saya (Tanpa Grouping yang bikin error)
    public function jadwal()
    {
        $user = Auth::user();
        $bookings = Booking::with('studio')
            ->where('user_id', $user->id)
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('pelanggan.jadwal.index', compact('user', 'bookings'));
    }

    // 5. Riwayat Pembayaran (Sederhana & Lancar)
    public function riwayatPembayaran()
    {
        $user = Auth::user();
        $bookings = Booking::with('studio')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Ringkasan status
        $summary = [
            'total' => $bookings->count(),
            'berhasil' => $bookings->where('status', 'confirmed')->count(),
            'menunggu' => $bookings->where('status', 'pending')->count(),
            'gagal' => $bookings->where('status', 'canceled')->count(),
        ];

        return view('pelanggan.pembayaran.index', compact('user', 'bookings', 'summary'));
    }
}