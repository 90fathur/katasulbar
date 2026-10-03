<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Article;
use App\Models\BreakingNews;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BreakingNewsController extends Controller
{
    public function index(): View
    {
        $breakingNews = BreakingNews::with('article')->orderBy('order')->get();
        $articles = Article::published()->orderByDesc('published_at')->take(30)->get();

        return view('admin.breaking_news.index', compact('breakingNews', 'articles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'article_id' => 'nullable|exists:articles,id',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $bn = BreakingNews::create($validated);

        ActivityLog::log('Tambah Breaking News', "Menambahkan breaking news '{$bn->title}'");

        return redirect()->route('admin.breaking-news.index')->with('success', 'Breaking news berhasil ditambahkan.');
    }

    public function update(Request $request, BreakingNews $breakingNews): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'article_id' => 'nullable|exists:articles,id',
            'order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $breakingNews->update($validated);

        ActivityLog::log('Ubah Breaking News', "Memperbarui breaking news '{$breakingNews->title}'");

        return redirect()->route('admin.breaking-news.index')->with('success', 'Breaking news berhasil diperbarui.');
    }

    public function destroy(BreakingNews $breakingNews): RedirectResponse
    {
        $title = $breakingNews->title;
        $breakingNews->delete();

        ActivityLog::log('Hapus Breaking News', "Menghapus breaking news '{$title}'");

        return redirect()->route('admin.breaking-news.index')->with('success', 'Breaking news berhasil dihapus.');
    }
}
