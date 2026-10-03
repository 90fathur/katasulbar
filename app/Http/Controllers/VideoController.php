<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request): View
    {
        $categorySlug = $request->get('kategori');
        $videoType = $request->get('tipe'); // 'video' or 'shorts'

        $categories = VideoCategory::withCount('videos')->get();

        $query = Video::with(['category', 'author'])->published();

        if ($categorySlug) {
            $query->whereHas('category', fn ($c) => $c->where('slug', $categorySlug));
        }

        if ($videoType === 'shorts') {
            $query->shorts();
        } elseif ($videoType === 'video') {
            $query->standard();
        }

        // Featured video
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

        // Shorts section
        $shortsVideos = Video::with('category')
            ->shorts()
            ->orderByDesc('published_at')
            ->take(8)
            ->get();

        $videos = $query->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('frontend.videos.index', compact(
            'videos',
            'categories',
            'featuredVideo',
            'shortsVideos',
            'categorySlug',
            'videoType'
        ));
    }

    public function show(string $slug): View
    {
        $video = Video::with(['category', 'author'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment views
        $video->increment('views');

        // Related videos
        $relatedVideos = Video::with('category')
            ->published()
            ->where('id', '!=', $video->id)
            ->when($video->category_id, fn ($q) => $q->where('category_id', $video->category_id))
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

        // Latest videos
        $latestVideos = Video::with('category')
            ->published()
            ->where('id', '!=', $video->id)
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

        return view('frontend.videos.show', compact('video', 'relatedVideos', 'latestVideos'));
    }
}
