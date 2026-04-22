<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Studio;
use App\Models\ActivityLog;
use App\Models\User; 
//use App\Mail\InvoiceMail;
//use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'studio'])->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'studio_id' => 'required|exists:studios,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'payment_method' => 'required|in:cash,qris,voucher',
            //'user_id' => 'required|exists:users,id', 
        ]);

        $studio = Studio::where('id', $request->studio_id)
            ->where('is_active', true)
            ->first();

        if (!$studio) {
            return back()->with('error', 'Studio tidak aktif atau tidak ditemukan!');
        }

        // 1. Pastikan tanggal diubah menjadi STRING format Y-m-d
        $tanggalMurniString = Carbon::parse($request->booking_date)->format('Y-m-d');

        // 2. Cek bentrok jadwal menggunakan tanggal yang sudah di-format
        $isBooked = Booking::where('studio_id', $request->studio_id)
            ->where('booking_date', $tanggalMurniString) // Gunakan String!
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($isBooked) {
            return back()->with('error', 'Jadwal sudah dibooking, silakan pilih waktu lain!');
        }

        // 3. Persiapan Data Waktu
        $waktuMulai = Carbon::parse($tanggalMurniString . ' ' . $request->start_time);
        $waktuSelesai = Carbon::parse($tanggalMurniString . ' ' . $request->end_time);
        
        $durasiMenit = $waktuMulai->diffInMinutes($waktuSelesai);
        $jumlahSesi = max(1, intval($durasiMenit / 5));

        // Penentuan status berdasarkan metode pembayaran
        $status = 'pending';
        if ($request->payment_method == 'cash' || $request->payment_method == 'voucher') {
            $status = 'confirmed';
        }

        $baseBookingCode = 'INV-' . strtoupper(uniqid());
        $currentStartTime = $waktuMulai->copy();

        // 4. Simpan ke database menggunakan Looping
        for ($i = 0; $i < $jumlahSesi; $i++) {
            $currentEndTime = $currentStartTime->copy()->addMinutes(5);

            Booking::create([
                'booking_code' => $baseBookingCode . '-' . ($i + 1), 
                // Jika Admin memilih pelanggan dari form, gunakan $request->user_id
                // Jika tidak, sementara biarkan auth()->id() atau ID khusus Walk-in
                'user_id' => auth()->id(), 
                'studio_id' => $request->studio_id,
                'booking_date' => $tanggalMurniString, // Gunakan String!
                'start_time' => $currentStartTime->format('H:i'), 
                'end_time' => $currentEndTime->format('H:i'),     
                'payment_method' => $request->payment_method,
                'status' => $status, 
                'notes' => ($request->notes ?? '') . " (Booking by Admin)",
                'total_price' => $studio->price, 
            ]);

            // Majukan waktu untuk sesi berikutnya
            $currentStartTime->addMinutes(5);
        }

        ActivityLog::record(
            'Tambah Booking Manual',
            "Admin membuat jadwal offline ($jumlahSesi sesi) untuk Studio " . $studio->name
        );

        return back()->with('success', "Berhasil memesan $jumlahSesi jadwal!");
    }

    public function update(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking pending yang bisa diedit!');
        }

        $request->validate([
            'studio_id' => 'required|exists:studios,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'payment_method' => 'required|in:cash,qris,voucher',
        ]);

        $isBooked = Booking::where('studio_id', $request->studio_id)
            ->where('booking_date', $request->booking_date)
            ->where('id', '!=', $booking->id)
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
            return back()->with('error', 'Jadwal bentrok!');
        }

        $studio = Studio::findOrFail($request->studio_id);

        $status = 'pending';
        if ($request->payment_method == 'cash' || $request->payment_method == 'voucher') {
            $status = 'confirmed';
        }

        // Perbaikan Update: Karena sekarang 1 baris = 1 sesi, 
        // harga selalu menggunakan harga dasar studio
        $booking->update([
            'studio_id' => $request->studio_id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'payment_method' => $request->payment_method,
            'status' => $status,
            'notes' => $request->notes,
            'total_price' => $studio->price, // Dikembalikan ke harga 1 sesi
        ]);

        ActivityLog::record('Update Booking', 'Mengubah booking: ' . $booking->booking_code);

        return back()->with('success', 'Booking berhasil diperbarui!');
    }

    public function cancel(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking pending yang bisa dibatalkan!');
        }

        $booking->update([
            'status' => 'canceled'
        ]);

        ActivityLog::record('Cancel Booking', 'Membatalkan booking: ' . $booking->booking_code);

        return back()->with('success', 'Booking berhasil dibatalkan!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        ActivityLog::record('Hapus Booking', 'Menghapus booking: ' . $booking->booking_code);

        return back()->with('success', 'Booking berhasil dihapus!');
    }

    private function generateTimeSlots($start = '10:00', $end = '22:00', $interval = 5)
    {
        $slots = [];

        $current = Carbon::parse($start);
        $endTime = Carbon::parse($end);

        while ($current < $endTime) {
            $slots[] = $current->format('H:i');
            $current->addMinutes($interval);
        }

        return $slots;
    }

    private function getUnavailableSlots($studio_id, $date)
    {
        $bookings = Booking::where('studio_id', $studio_id)
            ->where('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $unavailable = [];

        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->start_time);
            $end = Carbon::parse($booking->end_time);

            while ($start < $end) {
                $unavailable[] = $start->format('H:i');
                $start->addMinutes(5);
            }
        }

        return $unavailable;
    }

    public function create(Request $request)
    {
        $studio_id = $request->studio_id;
        $date = $request->booking_date ?? now()->toDateString();

        $slots = $this->generateTimeSlots();
        $unavailable = [];

        if ($studio_id) {
            $unavailable = $this->getUnavailableSlots($studio_id, $date);
        }

        $studios = Studio::where('is_active', true)->get();

        $recentBookings = Booking::with('studio')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('admin.bookings.create', compact('slots', 'unavailable', 'studios', 'date', 'studio_id', 'recentBookings'));
    }

    public function history()
    {
        $bookings = Booking::with(['user', 'studio'])->latest()->get();
        return view('admin.bookings.history', compact('bookings'));
    }
}