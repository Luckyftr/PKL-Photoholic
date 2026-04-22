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
        // 1. Validasi data yang dikirim dari Javascript
        $request->validate([
            'studio_id' => 'required|exists:studios,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // 2. Cek bentrok jadwal (Penting untuk pelanggan!)
        $isBooked = Booking::where('studio_id', $request->studio_id)
            ->where('booking_date', $request->booking_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($isBooked) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, jadwal ini baru saja di-booking orang lain.'
            ], 422); // 422 Unprocessable Entity
        }

        // Ambil data studio untuk mendapatkan harga per sesinya
        $studio = Studio::findOrFail($request->studio_id);

        // ==========================================
        // PERBAIKAN: MEMECAH SESI (Sama seperti Admin)
        // ==========================================
        $waktuMulai = Carbon::parse($request->booking_date . ' ' . $request->start_time);
        $waktuSelesai = Carbon::parse($request->booking_date . ' ' . $request->end_time);
        
        // Hitung durasi dan jumlah sesi (per 5 menit)
        $durasiMenit = $waktuMulai->diffInMinutes($waktuSelesai);
        $jumlahSesi = max(1, intval($durasiMenit / 5));

        // Generate 1 Kode Invoice untuk semua sesi yang dipesan
        $bookingCode = 'INV-' . strtoupper(uniqid());
        
        $currentStartTime = $waktuMulai->copy();

        // 3. Simpan ke database menggunakan Looping
        for ($i = 0; $i < $jumlahSesi; $i++) {
            $currentEndTime = $currentStartTime->copy()->addMinutes(5);

            Booking::create([
                'booking_code' => $bookingCode,
                'user_id' => Auth::id(), 
                'studio_id' => $request->studio_id,
                'booking_date' => $request->booking_date,
                'start_time' => $currentStartTime->format('H:i'), // Waktu mulai per sesi
                'end_time' => $currentEndTime->format('H:i'),     // Waktu selesai per sesi
                'payment_method' => 'qris',
                'status' => 'confirmed', 
                'notes' => $request->notes,
                'total_price' => $studio->price, // Menambahkan harga per sesi
            ]);

            // Majukan waktu untuk sesi berikutnya
            $currentStartTime->addMinutes(5);
        }
        // ==========================================

        ActivityLog::record(
            'Pemesanan Baru', 
            'Pelanggan ' . Auth::user()->name . " membuat pesanan $bookingCode ($jumlahSesi sesi)"
        );

        // 4. Kembalikan nomor invoice agar bisa dicetak di frontend
        return response()->json([
            'success' => true, 
            'booking_code' => $bookingCode,
            'jumlah_sesi' => $jumlahSesi // Opsional, jika mau ditampilkan di frontend
        ]);
    }
}