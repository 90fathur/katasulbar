<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->get('type'); // 'video' or 'shorts'
        $search = $request->get('search');

        $query = Video::with(['category', 'author']);

        if ($type === 'shorts') {
            $query->where('is_short', true);
        } elseif ($type === 'video') {
            $query->where('is_short', false);
        }

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $videos = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = VideoCategory::orderBy('name')->get();

        return view('admin.videos.index', compact('videos', 'categories', 'type', 'search'));
    }

    public function create(): View
    {
        $categories = VideoCategory::orderBy('name')->get();

        return view('admin.videos.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:videos,slug',
            'youtube_url' => 'required|url|max:255',
            'category_id' => 'nullable|exists:video_categories,id',
            'description' => 'nullable|string',
            'video_type' => 'required|in:video,shorts',
            'duration' => 'nullable|string|max:20',
            'status' => 'required|in:draft,published,archived',
            'thumbnail' => 'nullable|string',
            'thumbnail_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'is_featured' => 'nullable|boolean',
            'is_short' => 'nullable|boolean',
            'show_homepage' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
        ]);

        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('videos', 'public');
            $validated['thumbnail'] = asset('storage/'.$path);
        }

        $validated['author_id'] = Auth::id();
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_short'] = $request->boolean('is_short') || $validated['video_type'] === 'shorts';
        $validated['show_homepage'] = $request->boolean('show_homepage', true);

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $video = Video::create($validated);

        ActivityLog::log('Tambah Video', "Menambahkan video '{$video->title}'");

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil ditambahkan.');
    }

    public function edit(Video $video): View
    {
        $categories = VideoCategory::orderBy('name')->get();

        return view('admin.videos.edit', compact('video', 'categories'));
    }

    public function update(Request $request, Video $video): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:videos,slug,'.$video->id,
            'youtube_url' => 'required|url|max:255',
            'category_id' => 'nullable|exists:video_categories,id',
            'description' => 'nullable|string',
            'video_type' => 'required|in:video,shorts',
            'duration' => 'nullable|string|max:20',
            'status' => 'required|in:draft,published,archived',
            'thumbnail' => 'nullable|string',
            'thumbnail_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'is_featured' => 'nullable|boolean',
            'is_short' => 'nullable|boolean',
            'show_homepage' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
        ]);

        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('videos', 'public');
            $validated['thumbnail'] = asset('storage/'.$path);
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_short'] = $request->boolean('is_short') || $validated['video_type'] === 'shorts';
        $validated['show_homepage'] = $request->boolean('show_homepage');

        if ($validated['status'] === 'published' && empty($video->published_at)) {
            $validated['published_at'] = now();
        }

        $video->update($validated);

        ActivityLog::log('Ubah Video', "Memperbarui video '{$video->title}'");

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil diperbarui.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        $title = $video->title;
        $video->delete();

        ActivityLog::log('Hapus Video', "Menghapus video '{$title}'");

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil dihapus.');
    }
}
