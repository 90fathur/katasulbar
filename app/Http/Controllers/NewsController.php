<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\ArticleView;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $article = Article::with(['category', 'subcategory', 'author', 'editor', 'tags', 'comments'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Track view
        $ip = $request->ip();
        $today = now()->startOfDay();

        $alreadyViewedToday = ArticleView::where('article_id', $article->id)
            ->where('ip_address', $ip)
            ->where('viewed_at', '>=', $today)
            ->exists();

        if (! $alreadyViewedToday) {
            ArticleView::create([
                'article_id' => $article->id,
                'ip_address' => $ip,
                'user_agent' => $request->userAgent(),
                'viewed_at' => now(),
            ]);
            $article->increment('views_count');
            $article->increment('unique_views_count');
        } else {
            $article->increment('views_count');
        }

        // In-article ad injection
        $inArticleAd = Advertisement::forPosition('in_article')->first();
        $formattedContent = $this->injectAdIntoContent($article->content, $inArticleAd);

        // Related articles: same category first, fallback to latest published
        $relatedArticles = Article::with('category')
            ->published()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

        if ($relatedArticles->count() < 4) {
            $excludeIds = $relatedArticles->pluck('id')->push($article->id)->all();
            $moreRelated = Article::with('category')
                ->published()
                ->whereNotIn('id', $excludeIds)
                ->orderByDesc('published_at')
                ->take(4 - $relatedArticles->count())
                ->get();
            $relatedArticles = $relatedArticles->merge($moreRelated);
        }

        // Prev and Next articles
        $prevArticle = Article::published()
            ->where('id', '<', $article->id)
            ->orderByDesc('id')
            ->first();

        $nextArticle = Article::published()
            ->where('id', '>', $article->id)
            ->orderBy('id')
            ->first();

        // Sidebar popular with guaranteed fallback
        $popularArticles = Article::getPopularArticles(5, 30, $article->id);

        // Sidebar latest
        $latestArticles = Article::with('category')
            ->published()
            ->where('id', '!=', $article->id)
            ->orderByDesc('published_at')
            ->take(5)
            ->get();

        if ($latestArticles->count() < 5) {
            $excludeIds = $latestArticles->pluck('id')->push($article->id)->all();
            $moreLatest = Article::with('category')
                ->published()
                ->whereNotIn('id', $excludeIds)
                ->orderByDesc('id')
                ->take(5 - $latestArticles->count())
                ->get();
            $latestArticles = $latestArticles->merge($moreLatest);
        }

        return view('frontend.news-detail', compact(
            'article',
            'formattedContent',
            'relatedArticles',
            'prevArticle',
            'nextArticle',
            'popularArticles',
            'latestArticles'
        ));
    }

    public function category(Request $request, string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $sort = $request->get('sort', 'terbaru');
        $query = Article::with(['category', 'author'])
            ->published()
            ->where('category_id', $category->id);

        if ($sort === 'terpopuler') {
            $query->orderByDesc('views_count');
        } elseif ($sort === 'terlama') {
            $query->orderBy('published_at');
        } else {
            $query->orderByDesc('published_at');
        }

        $articles = $query->paginate(9)->withQueryString();

        $popularArticles = Article::getPopularArticles(5, 30);

        return view('frontend.category', compact('category', 'articles', 'popularArticles', 'sort'));
    }

    public function tag(Request $request, string $slug): View
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $articles = $tag->articles()
            ->with(['category', 'author'])
            ->published()
            ->orderByDesc('published_at')
            ->paginate(9);

        $popularArticles = Article::getPopularArticles(5, 30);

        return view('frontend.tag', compact('tag', 'articles', 'popularArticles'));
    }

    public function search(Request $request): View
    {
        $query = trim((string) $request->input('q'));

        $articles = Article::with(['category', 'author'])
            ->published()
            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('title', 'like', "%{$query}%")
                        ->orWhere('excerpt', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%")
                        ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$query}%"))
                        ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', "%{$query}%"))
                        ->orWhereHas('author', fn ($a) => $a->where('name', 'like', "%{$query}%"));
                });
            })
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        $popularArticles = Article::getPopularArticles(5, 30);

        return view('frontend.search', compact('query', 'articles', 'popularArticles'));
    }

    public function liveSearch(Request $request): JsonResponse
    {
        $q = trim((string) $request->input('q'));
        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $results = Article::with('category')
            ->published()
            ->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%")
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$q}%"));
            })
            ->orderByDesc('published_at')
            ->take(6)
            ->get(['id', 'title', 'slug', 'thumbnail', 'category_id', 'published_at', 'views_count'])
            ->map(function ($article) {
                return [
                    'id' => $article->id,
                    'title' => $article->title,
                    'category' => $article->category ? $article->category->name : 'Sulbar',
                    'category_color' => $article->category ? $article->category->color : '#062B56',
                    'url' => route('news.show', $article->slug),
                    'thumbnail' => $article->thumbnail ?: asset('assets/images/sulbar_panorama.jpg'),
                    'date' => $article->published_at ? $article->published_at->translatedFormat('d M Y') : '',
                ];
            });

        return response()->json(['results' => $results]);
    }

    public function adClick(Advertisement $ad): RedirectResponse
    {
        $ad->recordClick();

        return redirect()->away($ad->target_url ?: route('home'));
    }

    private function injectAdIntoContent(string $content, ?Advertisement $ad): string
    {
        if (! $ad || ! $ad->is_active) {
            return $content;
        }

        $targetParagraph = $ad->paragraph_position ?: 3;
        $paragraphs = explode('</p>', $content);
        $pCount = count($paragraphs);

        if ($pCount <= 1) {
            $adHtml = view('frontend.partials.ad-slot', ['ad' => $ad, 'slotClass' => 'ad-in-article my-4'])->render();

            return $content.$adHtml;
        }

        // Target insertion index (0-indexed)
        $insertIndex = min(max(0, $pCount - 2), max(0, $targetParagraph - 1));

        $adHtml = view('frontend.partials.ad-slot', ['ad' => $ad, 'slotClass' => 'ad-in-article my-4'])->render();
        $paragraphs[$insertIndex] .= '</p>'.$adHtml;

        return implode('</p>', array_slice($paragraphs, 0, -1)).end($paragraphs);
    }
}
