<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class BlogController extends Controller {
    public function index() {
        $blogs = Blog::latest()->get();
        return view('admin.blog.index', compact('blogs'));
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'photo' => 'image|max:2048'
        ]);

        $path = $request->file('photo') ? $request->file('photo')->store('blogs', 'public') : null;

        // Poin 6: Handling checkbox sync instagram
        $syncInsta = $request->has('sync_insta') ? true : false;

        Blog::create($request->all() + [
            'photo' => $path,
            'sync_insta' => $syncInsta,
            'publish_date' => now()
        ]);

        ActivityLog::record('Buat Blog', 'Menulis konten blog baru: ' . $request->title);

        return back()->with('success', 'Konten blog berhasil dipublish!');
    }

    public function destroy(Blog $blog) {
        $blog->delete();
        ActivityLog::record('Hapus Blog', 'Menghapus konten blog: ' . $blog->title);
        return back();
    }
}