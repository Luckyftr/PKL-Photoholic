<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Studio;
use App\Models\ActivityLog;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // 🔒 Validasi (tambahin biar aman)
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'studio_id' => 'required|exists:studios,id',
            'booking_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'payment_method' => 'required',
        ]);

        // 1. Hitung jumlah sesi (1 sesi = 5 menit)
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $durasiMenit = $start->diffInMinutes($end);
        $jumlahSesi = $durasiMenit / 5;

        // 2. Simpan booking
        $booking = Booking::create([
            'booking_code' => 'INV-' . strtoupper(uniqid()),
            'user_id' => $request->user_id, // pakai dari form (admin input)
            'studio_id' => $request->studio_id,
            'booking_date' => $request->booking_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'payment_method' => $request->payment_method,
            'status' => 'confirmed',
            'notes' => $request->notes . " (Total Sesi: $jumlahSesi)",
        ]);

        // 3. Kirim email invoice
        Mail::to($booking->user->email)->send(new InvoiceMail($booking));

        // 4. Activity log
        ActivityLog::record(
            'Tambah Booking',
            'Admin menambahkan booking offline untuk ' . $booking->user->name
        );

        return back()->with('success', 'Booking berhasil dan invoice telah dikirim!');
    }
}