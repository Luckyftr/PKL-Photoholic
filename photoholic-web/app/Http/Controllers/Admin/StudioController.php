<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudioController extends Controller {
    public function index() {
        $studios = Studio::all();
        return view('admin.studio.index', compact('studios'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'price' => 'required|numeric'
        ]);

        $path = $request->file('photo') ? $request->file('photo')->store('studios', 'public') : null;

        Studio::create($request->all() + ['photo' => $path]);
        
        ActivityLog::record('Tambah Studio', 'Menambahkan studio baru: ' . $request->name);

        return back()->with('success', 'Studio berhasil ditambahkan!');
    }

    public function update(Request $request, Studio $studio) {
        $data = $request->all();

        if ($request->hasFile('photo')) {
            if ($studio->photo) Storage::disk('public')->delete($studio->photo);
            $data['photo'] = $request->file('photo')->store('studios', 'public');
        }

        $studio->update($data);
        ActivityLog::record('Update Studio', 'Mengubah data studio: ' . $studio->name);

        return back()->with('success', 'Data studio diperbarui!');
    }

    public function toggleStatus(Studio $studio) {
        $studio->update(['is_active' => !$studio->is_active]);
        $status = $studio->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        ActivityLog::record('Toggle Studio', "Studio {$studio->name} {$status}");
        return back();
    }
}
