<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Studio;
use App\Models\Booking;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; 
use Barryvdh\DomPDF\Facade\Pdf;

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

    // 3. Menyimpan pesanan AWAL (Mengunci Jadwal)
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
            return response()->json(['success' => false, 'message' => 'Jadwal sudah terisi oleh orang lain!'], 422);
        }

        $studio = Studio::findOrFail($request->studio_id);
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
            'status' => 'pending', // LANGSUNG PENDING UNTUK MENGUNCI SLOT!
            'total_price' => $totalPrice,
            'notes' => $request->notes,
        ]);

        // Mengembalikan ID pesanan agar bisa digunakan oleh Javascript nanti
        return response()->json([
            'success' => true,
            'booking_id' => $booking->id,
            'booking_code' => $booking->booking_code
        ]);
    }

    // FUNGSI BARU 1: Mengunggah Bukti Bayar
    public function uploadPayment(Request $request, $id)
    {
        try {
            // 1. Validasi
            $request->validate([
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // 2. Cari pesanan
            $booking = Booking::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

            // 3. Pastikan file benar-benar ada dan bisa diupload
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
                
                $booking->update([
                    'payment_proof' => $paymentProofPath
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil dikirim! Mohon tunggu konfirmasi admin.',
                    'redirect_url' => route('pelanggan.pembayaran.index') 
                ]);
            }

            // Jika file tidak terbaca
            return response()->json(['success' => false, 'message' => 'Gagal membaca file gambar.'], 400);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangkap error jika ukuran terlalu besar atau format salah
            return response()->json([
                'success' => false, 
                'message' => $e->errors()['payment_proof'][0] // Ambil pesan error validasinya
            ], 422);
        } catch (\Exception $e) {
            // Tangkap error sistem lainnya
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // FUNGSI BARU 2: Membatalkan Pesanan Otomatis (Timeout)
    public function cancelPayment($id)
    {
        $booking = Booking::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $booking->update([
            'status' => 'canceled'
        ]);

        return response()->json(['success' => true]);
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

    // 6. Fungsi untuk mengunduh Invoice (PDF)
    public function invoice(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        $booking->load(['user', 'studio']);
        $pdf = Pdf::loadView('admin.bookings.invoice', compact('booking'));
        return $pdf->download('invoice-' . $booking->booking_code . '.pdf');
    }

    // FUNGSI BARU 3: Menampilkan halaman lanjut bayar
    public function pay($id)
    {
        $booking = Booking::with('studio')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // 1. Jika status sudah bukan pending, kembalikan ke riwayat
        if ($booking->status !== 'pending') {
            return redirect()->route('pelanggan.pembayaran.index')->with('error', 'Status pesanan sudah berubah.');
        }

        // 2. LOGIKA WAKTU YANG LEBIH AKURAT DAN AMAN
        $waktuDibuat = Carbon::parse($booking->created_at);
        
        // Garis Finish: Waktu dibuat + 10 Menit
        $batasWaktu = $waktuDibuat->copy()->addMinutes(10);
        $sekarang = Carbon::now();

        // Parameter 'false' membuat hasilnya menjadi minus (-) jika batas waktu sudah terlewati
        $sisaDetik = $sekarang->diffInSeconds($batasWaktu, false);

        // 3. Jika sisa detiknya 0 atau minus, langsung batalkan!
        if ($sisaDetik <= 0) {
            $booking->update(['status' => 'canceled']);
            return redirect()->route('pelanggan.pembayaran.index')->with('error', 'Waktu pembayaran telah habis. Pesanan dibatalkan otomatis.');
        }

        // 4. Jika waktu masih ada, tampilkan halaman dengan sisa detik yang murni
        return view('pelanggan.booking.pay', compact('booking', 'sisaDetik'));
    }
}