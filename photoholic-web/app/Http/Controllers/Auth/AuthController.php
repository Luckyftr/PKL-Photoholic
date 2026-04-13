<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthController extends Controller
{
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
            
            return redirect('/'); // Ganti dengan rute dashboard customer nanti
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
        return redirect('/');
    }

    // Menampilkan halaman Register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses Register
    public function register(Request $request)
    {
        // 1. Validasi Inputan
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Mencegah email kembar
            'phone' => 'required|string|max:20',
            // Aturan 'confirmed' otomatis mengecek input 'password' dengan 'password_confirmation'
            'password' => 'required|string|min:6|confirmed', 
        ], [
            // Kustomisasi pesan error
            'email.unique' => 'Email ini sudah terdaftar!',
            'password.confirmed' => 'Konfirmasi password tidak cocok!',
            'password.min' => 'Password minimal harus 6 karakter!'
        ]);

        // 2. Simpan ke Database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => \Hash::make($request->password), // Wajib di-Hash!
            'role' => 'customer', // Pendaftar baru otomatis jadi Customer
            'status' => 'active',
        ]);

        // 3. Kembali ke halaman register dengan membawa pesan Sukses
        // Ini yang akan memicu "Card Success" muncul di tampilan!
        return back()->with('success', 'Registrasi berhasil!');
    }

    // Menampilkan halaman Lupa Password
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Proses Ganti Password (Simulasi OTP)
    public function updatePasswordCustom(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok!',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Kalau email tidak ditemukan
        if (!$user) {
            return back()->withErrors(['email' => 'Email ini tidak terdaftar di sistem kami.']);
        }

        // Kalau email ada, ganti password-nya!
        $user->update([
            'password' => \Hash::make($request->password)
        ]);

        // Kembalikan dengan pesan sukses untuk memunculkan Step 4
        return back()->with('success', 'Kata sandi berhasil diubah!');
    }

    // ==========================================
    // FITUR LOGIN GOOGLE
    // ==========================================
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cari user berdasarkan email google
            $user = User::where('email', $googleUser->getEmail())->first();

            // Kalau belum pernah daftar, kita buatkan otomatis
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(uniqid()), // Password acak karena login via Google
                    'role' => 'customer', // Default role
                    'status' => 'active',
                ]);
            }

            // Lakukan Login
            Auth::login($user);

            // Arahkan sesuai role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect('/');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['login' => 'Gagal masuk dengan Google.']);
        }
    }
}