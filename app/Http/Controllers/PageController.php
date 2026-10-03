<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use App\Models\Video;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $popularArticles = Article::with('category')
            ->popular(30)
            ->take(5)
            ->get();

        return view('frontend.page', compact('page', 'popularArticles'));
    }

    public function sitemap(): Response
    {
        $articles = Article::published()->orderByDesc('published_at')->take(500)->get();
        $categories = Category::where('is_active', true)->get();
        $videos = Video::published()->orderByDesc('published_at')->take(100)->get();
        $pages = Page::where('is_active', true)->get();

        $content = view('frontend.sitemap', compact('articles', 'categories', 'videos', 'pages'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function feed(): Response
    {
        $articles = Article::with(['category', 'author'])
            ->published()
            ->orderByDesc('published_at')
            ->take(30)
            ->get();

        $content = view('frontend.rss', compact('articles'))->render();

        return response($content, 200)
            ->header('Content-Type', 'application/xml');
    }
}
