<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Studio;
use App\Models\ActivityLog;
use App\Models\User; 
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;
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
        // Validasi
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'studio_id' => 'required|exists:studios,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'payment_method' => 'required|in:cash,qris,voucher',
        ]);

        // Cek studio aktif
        $studio = Studio::where('id', $request->studio_id)
            ->where('is_active', true)
            ->first();

        if (!$studio) {
            return back()->with('error', 'Studio tidak aktif atau tidak ditemukan!');
        }

        // Cek bentrok jadwal
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
            return back()->with('error', 'Jadwal sudah dibooking, silakan pilih waktu lain!');
        }

        // Hitung jumlah sesi
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $durasiMenit = $start->diffInMinutes($end);
        $jumlahSesi = $durasiMenit / 5;

        // Tentukan status berdasarkan payment
        $status = 'pending';

        if ($request->payment_method == 'cash') {
            $status = 'confirmed';
        }

        if ($request->payment_method == 'voucher') {
            $status = 'confirmed';
        }

        // Simpan booking
        $booking = Booking::create([
            'booking_code' => 'INV-' . strtoupper(uniqid()),
            'user_id' => $request->user_id,
            'studio_id' => $request->studio_id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'payment_method' => $request->payment_method,
            'status' => $status,
            'notes' => ($request->notes ?? '') . " (Total Sesi: $jumlahSesi)",
        ]);

        // Kirim email invoice
        Mail::to($booking->user->email)->send(new InvoiceMail($booking));

        // Activity log
        ActivityLog::record(
            'Tambah Booking',
            'Admin menambahkan booking offline untuk ' . $booking->user->name
        );

        return back()->with('success', 'Booking berhasil diproses!');
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

        // cek bentrok (exclude dirinya sendiri)
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

        // status ulang berdasarkan payment
        $status = 'pending';

        if ($request->payment_method == 'cash' || $request->payment_method == 'voucher') {
            $status = 'confirmed';
        }

        $booking->update([
            'studio_id' => $request->studio_id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'payment_method' => $request->payment_method,
            'status' => $status,
            'notes' => $request->notes,
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
        $users = User::all(); 

        // <-- TAMBAHAN 3: Menyisipkan 'users' ke dalam compact() agar bisa dibaca oleh file Blade
        return view('admin.bookings.create', compact('slots', 'unavailable', 'studios', 'date', 'studio_id', 'users'));
    }
}