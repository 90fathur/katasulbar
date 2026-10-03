<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Hero News (Headline articles)
        $heroArticles = Article::with(['category', 'author', 'comments'])
            ->headline()
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        // If no headline found, fallback to latest published
        if ($heroArticles->isEmpty()) {
            $heroArticles = Article::with(['category', 'author', 'comments'])
                ->published()
                ->orderByDesc('published_at')
                ->take(5)
                ->get();
        }

        $mainHero = $heroArticles->first();
        $heroIds = $heroArticles->pluck('id')->toArray();

        // 2. Berita Pendamping Hero (2-3 small cards next to main hero)
        $companionArticles = Article::with(['category', 'author'])
            ->published()
            ->whereNotIn('id', $heroIds)
            ->where('is_featured', true)
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        if ($companionArticles->count() < 3) {
            $more = Article::with(['category', 'author'])
                ->published()
                ->whereNotIn('id', array_merge($heroIds, $companionArticles->pluck('id')->toArray()))
                ->orderByDesc('published_at')
                ->take(3 - $companionArticles->count())
                ->get();
            $companionArticles = $companionArticles->merge($more);
        }

        $usedIds = array_merge($heroIds, $companionArticles->pluck('id')->toArray());

        // 3. Tab Terpopuler, Terbaru, Komentar (Sidebar)
        $popularArticles = Article::with('category')
            ->popular(30)
            ->take(5)
            ->get();

        $latestArticles = Article::with('category')
            ->published()
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        $commentedArticles = Article::with('category')
            ->withCount('comments')
            ->published()
            ->orderByDesc('comments_count')
            ->take(5)
            ->get();

        // 4. Berita Terkini (Grid 3-4 kolom)
        $recentArticles = Article::with(['category', 'author'])
            ->published()
            ->whereNotIn('id', $usedIds)
            ->orderByDesc('published_at')
            ->paginate(6);

        // 5. Video Singkat / Shorts (9:16 vertical cards)
        $shortsVideos = Video::with('category')
            ->shorts()
            ->homepage()
            ->orderByDesc('published_at')
            ->take(6)
            ->get();

        // If shorts empty, fallback to any video marked or recent
        if ($shortsVideos->isEmpty()) {
            $shortsVideos = Video::with('category')
                ->published()
                ->where('is_short', true)
                ->orderByDesc('published_at')
                ->take(6)
                ->get();
        }

        // 6. Kategori Berita Visual Cards
        $visualCategories = Category::where('is_active', true)
            ->withCount('publishedArticles')
            ->orderBy('order')
            ->take(10)
            ->get();

        // 7. Video Terkini (Section 10 & 34: 70% featured + 30% list)
        $featuredVideo = Video::with(['category', 'author'])
            ->standard()
            ->featured()
            ->orderByDesc('published_at')
            ->first();

        if (! $featuredVideo) {
            $featuredVideo = Video::with(['category', 'author'])
                ->standard()
                ->orderByDesc('published_at')
                ->first();
        }

        $recentVideos = Video::with(['category', 'author'])
            ->standard()
            ->when($featuredVideo, fn ($q) => $q->where('id', '!=', $featuredVideo->id))
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        // 8. Berita Pilihan Redaksi (Editor's pick)
        $editorPickArticles = Article::with(['category', 'author'])
            ->editorPick()
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

        return view('frontend.home', compact(
            'heroArticles',
            'mainHero',
            'companionArticles',
            'popularArticles',
            'latestArticles',
            'commentedArticles',
            'recentArticles',
            'shortsVideos',
            'visualCategories',
            'featuredVideo',
            'recentVideos',
            'editorPickArticles'
        ));
    }
}
