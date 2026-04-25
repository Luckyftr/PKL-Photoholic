<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Studio;
use App\Models\User; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar pemesanan (untuk ACC/Konfirmasi)
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'studio'])->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Menampilkan halaman Atur Jadwal (Form Create)
     */
    public function create(Request $request)
    {
        $studios = Studio::where('is_active', true)->get();
        $date = $request->date ?? date('Y-m-d');
        $studio_id = $request->studio_id;

        // Ambil 5 riwayat input terakhir untuk list di bawah form
        $recentBookings = Booking::with('studio')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.bookings.create', compact('studios', 'date', 'studio_id', 'recentBookings'));
    }

    /**
     * Menyimpan booking offline/admin
     */
    public function store(Request $request)
    {
        $request->validate([
            'studio_id' => 'required|exists:studios,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'payment_method' => 'required|in:cash,qris,voucher',
        ]);

        $studio = Studio::findOrFail($request->studio_id);
        $tanggalMurniString = Carbon::parse($request->booking_date)->format('Y-m-d');

        // Cek Bentrok
        $isBooked = Booking::where('studio_id', $request->studio_id)
            ->where('booking_date', $tanggalMurniString)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })->exists();

        if ($isBooked) return back()->with('error', 'Jadwal bentrok!');

        // Hitung Harga menggunakan session_duration dari database
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        
        // Rumus: $Sessions = \lceil \frac{Duration}{SessionDuration} \rceil$
        $sessionsCount = ceil($start->diffInMinutes($end) / $studio->session_duration);
        $totalPrice = $sessionsCount * $studio->price;

        $status = ($request->payment_method === 'qris') ? 'pending' : 'confirmed';

        Booking::create([
            'booking_code' => 'INV-' . strtoupper(Str::random(7)),
            'user_id' => auth()->id(), // Admin yang menginput
            'studio_id' => $request->studio_id,
            'booking_date' => $tanggalMurniString,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'payment_method' => $request->payment_method,
            'status' => $status,
            'total_price' => $totalPrice,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Booking berhasil dibuat!');
    }

    public function accept(Booking $booking)
    {
        $booking->update(['status' => 'confirmed']);
        return back()->with('success', 'Pembayaran Berhasil Dikonfirmasi!');
    }

    public function update(Request $request, Booking $booking)
    {
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
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })->exists();

        if ($isBooked) return back()->with('error', 'Jadwal bentrok!');

        $studio = Studio::findOrFail($request->studio_id);
        
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $sessionsCount = ceil($start->diffInMinutes($end) / $studio->session_duration);
        $totalPrice = $sessionsCount * $studio->price;

        $booking->update([
            'studio_id' => $request->studio_id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'payment_method' => $request->payment_method,
            'total_price' => $totalPrice,
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Booking diperbarui!');
    }

    public function cancel(Booking $booking)
    {
        $booking->update(['status' => 'canceled']);
        return back()->with('success', 'Booking dibatalkan!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('success', 'Booking dihapus!');
    }

    public function history()
    {
        $bookings = Booking::with(['user', 'studio'])->latest()->get();
        return view('admin.bookings.history', compact('bookings'));
    }
}