<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ActivityLog; // ⬅️ jangan lupa ini

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
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

        // ✅ Tambahan activity log
        ActivityLog::record(
            'Update User',
            'Mengubah data pengguna: ' . $user->name
        );

        return back()->with('success', 'Data pengguna berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();

        // (opsional tapi disarankan 🔥)
        ActivityLog::record(
            'Delete User',
            'Menghapus pengguna: ' . $user->name
        );

        return back()->with('success', 'Pengguna berhasil dihapus');
    }
}