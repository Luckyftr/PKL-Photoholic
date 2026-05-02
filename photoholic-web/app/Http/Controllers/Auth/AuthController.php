<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    // ==========================================
    // 1. FITUR LOGIN MANUAL
    // ==========================================
    
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login manual
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required', // Bisa berisi email atau HP
            'password' => 'required'
        ]);

        // Cek apakah inputan itu format email atau bukan (berarti HP)
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password
        ];

        // Jika berhasil login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Pengecekan Role (Pembagian Jalur Admin vs Customer)
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect('/pelanggan/dashboard'); 
        }

        // Jika gagal
        return back()->withErrors([
            'login' => 'Email/Nomor HP atau password salah.',
        ])->onlyInput('login');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // ==========================================
    // 2. FITUR REGISTER MANUAL
    // ==========================================

    // Menampilkan halaman Register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses Register
    public function register(Request $request)
    {
        // Validasi Inputan
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed', 
        ], [
            'email.unique' => 'Email ini sudah terdaftar!',
            'password.confirmed' => 'Konfirmasi password tidak cocok!',
            'password.min' => 'Password minimal harus 6 karakter!'
        ]);

        // Simpan ke Database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), 
            'role' => 'customer', 
            'status' => 'active',
        ]);

        return back()->with('success', 'Registrasi berhasil!');
    }

    // ==========================================
    // 3. FITUR LUPA PASSWORD
    // ==========================================

    // Menampilkan halaman Lupa Password
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Proses Ganti Password
    public function updatePasswordCustom(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok!',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email ini tidak terdaftar di sistem kami.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Kata sandi berhasil diubah!');
    }

    // Fungsi untuk mengirim OTP ke Email
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        // Cek apakah email ada di database
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email ini tidak terdaftar di sistem kami.']);
        }

        // Generate 4 digit OTP acak
        $otp = rand(1000, 9999);

        // Simpan OTP dan email ke Session sementara
        Session::put('reset_otp', $otp);
        Session::put('reset_email', $request->email);

        // Kirim email menggunakan Mailable yang baru kita buat
        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json(['success' => true]);
    }
    // Fungsi untuk memverifikasi kecocokan OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        // Cocokkan data dari inputan dengan data yang ada di Session
        if ($request->email == Session::get('reset_email') && $request->otp == Session::get('reset_otp')) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Kode OTP salah atau tidak valid.']);
    }

    // ==========================================
    // 4. FITUR LOGIN GOOGLE (OAUTH)
    // ==========================================
    
    // Mengarahkan user ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Menangani kembalian data dari Google setelah user login
    public function handleGoogleCallback()
    {
        try {
            // 1. Ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();
            
            // 2. Cari user di database berdasarkan google_id ATAU email
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            // 3. Jika user belum ada di database, buat akun baru
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => null, 
                    'phone' => '-', // <-- TAMBAHAN: Isi default karena Google tidak memberi nomor HP
                    'role' => 'customer',
                    'status' => 'active',
                ]);
            } 
            // 4. Jika user sudah ada (misal dulu daftar manual), update dengan google_id
            else if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId()
                ]);
            }

            // 5. Lakukan proses login
            Auth::login($user);

            // 6. Arahkan user sesuai dengan rolenya
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect('/pelanggan/dashboard');

        } catch (\Exception $e) {
            // PERBAIKAN: Tampilkan error aslinya agar kita tahu jika ada masalah lain di database
            dd('Error Google Login: ' . $e->getMessage());
        }
    }


    // ==========================================
    // 5. FITUR UBAH PASSWORD ADMIN
    // ==========================================

    // Menampilkan halaman ubah password admin
    public function showUbahPasswordAdmin()
    {
        return view('auth.ubah-password');
    }

    // Memproses perubahan password via AJAX
    public function updatePasswordAdmin(Request $request)
    {
        // Validasi format dari backend
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed'
        ]);

        $user = Auth::user();

        // Cek apakah password lama yang dimasukkan sesuai dengan di database
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false, 
                'message' => 'Kata sandi lama yang Anda masukkan salah.'
            ]);
        }

        // Jika cocok, update password baru
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Kata sandi akun admin berhasil diperbarui!'
        ]);
    }

    public function showUbahPasswordPelanggan()
    {
        // Mengarahkan ke file resources/views/pelanggan/ubah-password.blade.php
        return view('pelanggan.ubah-password'); 
    }
}