<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash; // <-- INI WAJIB UNTUK ENKRIPSI PASSWORD

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    // --- FUNGSI BARU UNTUK NAMBAH USER ---
    public function store(Request $request)
    {
        // 1. Validasi inputan dari form
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Pastikan email belum dipakai
            'password' => 'required|string|min:6', // Minimal 6 karakter
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,customer',
            'status' => 'required|in:active,inactive'
        ]);

        // 2. Enkripsi password sebelum disimpan ke database
        $data['password'] = Hash::make($data['password']);

        // 3. Simpan ke database
        $user = User::create($data);

        // 4. Catat di Activity Log
        ActivityLog::record(
            'Tambah User',
            'Menambahkan pengguna baru: ' . $user->name
        );

        // 5. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,customer',
            'status' => 'required|in:active,inactive'
        ]);

        $user->update($data);

        // Tambahan activity log
        ActivityLog::record(
            'Update User',
            'Mengubah data pengguna: ' . $user->name
        );

        return back()->with('success', 'Data pengguna berhasil diperbarui');
    }

    public function toggleStatus(User $user)
    {
        // Mencegah admin menonaktifkan dirinya sendiri
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri!');
        }

        // Balikkan statusnya
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'diaktifkan' : 'dinonaktifkan';

        // Log Aktivitas
        ActivityLog::record(
            'Toggle User',
            "Pengguna {$user->name} berhasil {$statusText}"
        );

        return back()->with('success', "Akun berhasil {$statusText}!");
    }
    
    public function destroy(User $user)
    {
        $user->delete();

        // (opsional tapi disarankan)
        ActivityLog::record(
            'Delete User',
            'Menghapus pengguna: ' . $user->name
        );

        return back()->with('success', 'Pengguna berhasil dihapus');
    }
}