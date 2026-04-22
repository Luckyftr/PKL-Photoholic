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

        // 2. Ubah tanggal menjadi STRING murni (Y-m-d) agar aman masuk database
        $tanggalMurniString = Carbon::parse($request->booking_date)->format('Y-m-d');

        // 3. Cek bentrok jadwal menggunakan String
        $isBooked = Booking::where('studio_id', $request->studio_id)
            ->where('booking_date', $tanggalMurniString)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($isBooked) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, jadwal ini baru saja di-booking orang lain.'
            ], 422); 
        }

        // Ambil data studio untuk mendapatkan harga per sesinya
        $studio = Studio::findOrFail($request->studio_id);

        // ==========================================
        // PERBAIKAN: MEMECAH SESI & FORMAT TANGGAL
        // ==========================================
        $waktuMulai = Carbon::parse($tanggalMurniString . ' ' . $request->start_time);
        $waktuSelesai = Carbon::parse($tanggalMurniString . ' ' . $request->end_time);
        
        $durasiMenit = $waktuMulai->diffInMinutes($waktuSelesai);
        $jumlahSesi = max(1, intval($durasiMenit / 5));

        $baseBookingCode = 'INV-' . strtoupper(uniqid());
        
        $currentStartTime = $waktuMulai->copy();

        // 4. Simpan ke database menggunakan Looping
        for ($i = 0; $i < $jumlahSesi; $i++) {
            $currentEndTime = $currentStartTime->copy()->addMinutes(5);

            Booking::create([
                'booking_code' => $baseBookingCode . '-' . ($i + 1), 
                'user_id' => Auth::id(), 
                'studio_id' => $request->studio_id,
                'booking_date' => $tanggalMurniString, // Gunakan String
                'start_time' => $currentStartTime->format('H:i'), 
                'end_time' => $currentEndTime->format('H:i'),     
                'payment_method' => 'qris',
                'status' => 'pending',
                'notes' => $request->notes,
                'total_price' => $studio->price, 
            ]);

            $currentStartTime->addMinutes(5);
        }
        // ==========================================

        ActivityLog::record(
            'Pemesanan Baru', 
            'Pelanggan ' . Auth::user()->name . " membuat pesanan $baseBookingCode ($jumlahSesi sesi)"
        );

        // 5. Kembalikan respons AJAX
        return response()->json([
            'success' => true, 
            'booking_code' => $baseBookingCode,
            'jumlah_sesi' => $jumlahSesi
        ]);
    }

    // 4. Menampilkan halaman Jadwal Saya
    public function jadwal()
    {
        $user = Auth::user();

        // Ambil semua booking milik user ini, urutkan dari yang terbaru
        $allBookings = Booking::with('studio')
            ->where('user_id', $user->id)
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();

        // Mengelompokkan booking yang punya kode (atau base kode) yang sama
        $groupedBookings = $allBookings->groupBy(function ($item) {
            // Jika kamu menggunakan format INV-XXXX-1, INV-XXXX-2, kita ambil depannya saja
            $parts = explode('-', $item->booking_code);
            if (count($parts) > 2) {
                return $parts[0] . '-' . $parts[1]; // Mengembalikan 'INV-XXXX'
            }
            return $item->booking_code; // Jika formatnya tetap sama
        });

        return view('pelanggan.jadwal.index', compact('user', 'groupedBookings'));
    }

    // 5. Menampilkan halaman Riwayat Pembayaran
    public function riwayatPembayaran()
    {
        $user = Auth::user();

        // Ambil semua booking milik user, urutkan dari waktu pembuatan (transaksi) terbaru
        $allBookings = Booking::with('studio')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kelompokkan berdasarkan base kode (INV-XXXX)
        $groupedBookings = $allBookings->groupBy(function ($item) {
            $parts = explode('-', $item->booking_code);
            if (count($parts) > 2) {
                return $parts[0] . '-' . $parts[1]; 
            }
            return $item->booking_code;
        });

        // Hitung ringkasan status pembayaran
        $summary = [
            'total' => $groupedBookings->count(),
            'berhasil' => 0,
            'menunggu' => 0,
            'gagal' => 0,
        ];

        foreach ($groupedBookings as $group) {
            $status = $group->first()->status;
            if ($status == 'confirmed') {
                $summary['berhasil']++;
            } elseif ($status == 'pending') {
                $summary['menunggu']++;
            } elseif ($status == 'canceled') {
                $summary['gagal']++;
            }
        }

        return view('pelanggan.pembayaran.index', compact('user', 'groupedBookings', 'summary'));
    }
}