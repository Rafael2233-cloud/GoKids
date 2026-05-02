<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('admin')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.artikel.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.artikel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:Nutrisi,Vaksinasi,Tumbuh Kembang,Kesehatan',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'content' => 'required|string',
            'status' => 'required|in:published,draft',
        ]);

        $data = $request->only(['title', 'category', 'content', 'status']);
        $data['admin_id'] = Auth::id();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('articles', 'public');
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        Article::create($data);

        return redirect('/admin/artikel')->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function edit(Article $artikel)
    {
        return view('admin.artikel.edit', ['article' => $artikel]);
    }

    public function update(Request $request, Article $artikel)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:Nutrisi,Vaksinasi,Tumbuh Kembang,Kesehatan',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'content' => 'required|string',
            'status' => 'required|in:published,draft',
        ]);

        $data = $request->only(['title', 'category', 'content', 'status']);

        if ($request->hasFile('thumbnail')) {
            if ($artikel->thumbnail) {
                Storage::disk('public')->delete($artikel->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('articles', 'public');
        }

        if ($data['status'] === 'published' && !$artikel->published_at) {
            $data['published_at'] = now();
        }

        $artikel->update($data);

        return redirect('/admin/artikel')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(Article $artikel)
    {
        if ($artikel->thumbnail) {
            Storage::disk('public')->delete($artikel->thumbnail);
        }

        $artikel->delete();

        return redirect('/admin/artikel')->with('success', 'Artikel berhasil dihapus!');
    }
}
