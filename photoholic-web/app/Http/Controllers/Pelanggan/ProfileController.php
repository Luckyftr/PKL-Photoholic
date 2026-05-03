<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    // Menampilkan halaman profil
    public function index()
    {
        $user = Auth::user(); 
        return view('pelanggan.profile.index', compact('user'));
    }

    // Menyimpan perubahan data profil
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input (Username sudah dihapus)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address']);

        // Jika user upload foto baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            // Simpan foto baru
            $data['photo'] = $request->file('photo')->store('users', 'public');
        }

        // Update data di database
        User::where('id', $user->id)->update($data);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}