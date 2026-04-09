<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->get();
        return view('admin.blog.index', compact('blogs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:promo,event,pengumuman,update_studio',
            'publish_date' => 'nullable|date',
            'short_caption' => 'required|string|max:255',
            'content' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        // upload foto
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('blogs', 'public');
        }

        // checkbox IG
        $data['sync_insta'] = $request->has('sync_insta');

        // publish_date otomatis kalau kosong & status publish
        if ($data['status'] === 'published' && empty($data['publish_date'])) {
            $data['publish_date'] = now();
        }

        $blog = Blog::create($data);

        ActivityLog::record('Buat Blog', 'Menulis konten blog: ' . $blog->title);

        return back()->with('success', 'Konten blog berhasil disimpan!');
    }

    public function update(Request $request, Blog $blog)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:promo,event,pengumuman,update_studio',
            'publish_date' => 'nullable|date',
            'short_caption' => 'required|string|max:255',
            'content' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        // update foto
        if ($request->hasFile('photo')) {
            if ($blog->photo) {
                Storage::disk('public')->delete($blog->photo);
            }
            $data['photo'] = $request->file('photo')->store('blogs', 'public');
        }

        // checkbox IG
        $data['sync_insta'] = $request->has('sync_insta');

        // auto set publish_date kalau publish
        if ($data['status'] === 'published' && empty($data['publish_date'])) {
            $data['publish_date'] = now();
        }

        $blog->update($data);

        ActivityLog::record('Update Blog', 'Mengubah konten blog: ' . $blog->title);

        return back()->with('success', 'Konten blog berhasil diperbarui!');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();

        ActivityLog::record('Hapus Blog', 'Menghapus konten blog: ' . $blog->title);

        return back()->with('success', 'Konten blog berhasil dihapus!');
    }
}