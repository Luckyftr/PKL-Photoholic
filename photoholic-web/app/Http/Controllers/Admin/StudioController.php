<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudioController extends Controller
{
    public function index()
    {
        $studios = Studio::latest()->get();
        return view('admin.studios.index', compact('studios'));
    }

    public function store(Request $request)
        {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'max_people_per_session' => 'required|integer',
                'session_duration' => 'required|integer',
                'photo_strips' => 'required|integer',
                'paper_type' => 'required|in:negative_film,photo_paper',
                'price' => 'required|numeric',
            ]);

            // generate studio_code (unik)
            do {
                $code = 'STD-' . strtoupper(substr(uniqid(), -6));
            } while (Studio::where('studio_code', $code)->exists());

            $data['studio_code'] = $code;

            // upload foto
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('studios', 'public');
            }

            $studio = Studio::create($data);

            ActivityLog::record('Tambah Studio', 'Menambahkan studio baru: ' . $studio->name);

            return back()->with('success', 'Studio berhasil ditambahkan!');
        }

    public function update(Request $request, Studio $studio)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'max_people_per_session' => 'required|integer',
            'session_duration' => 'required|integer',
            'photo_strips' => 'required|integer',
            'paper_type' => 'required|in:negative_film,photo_paper',
            'price' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        // update foto kalau ada
        if ($request->hasFile('photo')) {
            if ($studio->photo) {
                Storage::disk('public')->delete($studio->photo);
            }
            $data['photo'] = $request->file('photo')->store('studios', 'public');
        }

        $studio->update($data);

        ActivityLog::record('Update Studio', 'Mengubah data studio: ' . $studio->name);

        return back()->with('success', 'Data studio diperbarui!');
    }

    public function toggleStatus(Studio $studio)
    {
        $studio->update([
            'is_active' => !$studio->is_active
        ]);

        $status = $studio->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLog::record('Toggle Studio', "Studio {$studio->name} {$status}");

        return back()->with('success', "Studio berhasil {$status}");
    }

    public function destroy(Studio $studio)
    {
        $studio->delete(); 

        ActivityLog::record('Hapus Studio', 'Menghapus studio: ' . $studio->name);

        return back()->with('success', 'Studio berhasil dihapus!');
    }
}