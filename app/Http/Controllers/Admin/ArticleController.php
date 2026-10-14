<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Services\ImageOptimizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status');
        $categoryId = $request->get('category_id');
        $search = $request->get('search');

        $query = Article::with(['category', 'author', 'editor']);

        // Reporter can only view own articles unless editor/admin
        $user = Auth::user();
        if ($user->role === 'reporter' || $user->role === 'kontributor') {
            $query->where('author_id', $user->id);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $articles = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.articles.index', compact('articles', 'categories', 'status', 'categoryId', 'search'));
    }

    public function create(): View
    {
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $editors = User::whereIn('role', ['super_admin', 'admin', 'pemred', 'editor'])->get();

        return view('admin.articles.create', compact('categories', 'tags', 'editors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'thumbnail' => 'nullable|string',
            'thumbnail_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'thumbnail_caption' => 'nullable|string|max:255',
            'status' => 'required|in:draft,review,published,archived',
            'is_headline' => 'nullable|boolean',
            'is_breaking' => 'nullable|boolean',
            'is_editor_pick' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'allow_comments' => 'nullable|boolean',
            'editor_id' => 'nullable|exists:users,id',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:255',
            'tags' => 'nullable|string', // comma-separated
        ]);

        $user = Auth::user();

        // Reporter permissions check: cannot publish directly without editor role
        if (! $user->canPublish() && $validated['status'] === 'published') {
            $validated['status'] = 'review';
        }

        // Handle uploaded file
        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('articles', 'public');
            ImageOptimizer::optimizeForWeb(storage_path('app/public/'.$path));
            $validated['thumbnail'] = asset('storage/'.$path);
        }

        $validated['author_id'] = $user->id;
        $validated['is_headline'] = $request->boolean('is_headline');
        $validated['is_breaking'] = $request->boolean('is_breaking');
        $validated['is_editor_pick'] = $request->boolean('is_editor_pick');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['allow_comments'] = $request->boolean('allow_comments', true);

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $article = Article::create($validated);

        // Process tags
        if (! empty($request->input('tags'))) {
            $tagNames = explode(',', (string) $request->input('tags'));
            $tagIds = [];
            foreach ($tagNames as $name) {
                $name = trim($name);
                if (! empty($name)) {
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($name)],
                        ['name' => $name]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $article->tags()->sync($tagIds);
        }

        ActivityLog::log('Tambah Berita', "Menambahkan berita '{$article->title}'");

        return redirect()->route('admin.articles.index')->with('success', 'Berita berhasil disimpan.');
    }

    public function edit(Article $article): View
    {
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $editors = User::whereIn('role', ['super_admin', 'admin', 'pemred', 'editor'])->get();
        $selectedTags = $article->tags->pluck('name')->implode(', ');

        return view('admin.articles.edit', compact('article', 'categories', 'tags', 'editors', 'selectedTags'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,'.$article->id,
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'thumbnail' => 'nullable|string',
            'thumbnail_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'thumbnail_caption' => 'nullable|string|max:255',
            'status' => 'required|in:draft,review,published,archived',
            'is_headline' => 'nullable|boolean',
            'is_breaking' => 'nullable|boolean',
            'is_editor_pick' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'allow_comments' => 'nullable|boolean',
            'editor_id' => 'nullable|exists:users,id',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
        ]);

        $user = Auth::user();
        if (! $user->canPublish() && $validated['status'] === 'published' && $article->status !== 'published') {
            $validated['status'] = 'review';
        }

        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('articles', 'public');
            ImageOptimizer::optimizeForWeb(storage_path('app/public/'.$path));
            $validated['thumbnail'] = asset('storage/'.$path);
        }

        $validated['is_headline'] = $request->boolean('is_headline');
        $validated['is_breaking'] = $request->boolean('is_breaking');
        $validated['is_editor_pick'] = $request->boolean('is_editor_pick');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['allow_comments'] = $request->boolean('allow_comments');

        if ($validated['status'] === 'published' && empty($article->published_at) && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        if ($request->has('tags')) {
            $tagNames = explode(',', (string) $request->input('tags'));
            $tagIds = [];
            foreach ($tagNames as $name) {
                $name = trim($name);
                if (! empty($name)) {
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($name)],
                        ['name' => $name]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            $article->tags()->sync($tagIds);
        }

        ActivityLog::log('Ubah Berita', "Memperbarui berita '{$article->title}'");

        return redirect()->route('admin.articles.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $title = $article->title;
        $article->delete();

        ActivityLog::log('Hapus Berita', "Menghapus berita '{$title}'");

        return redirect()->route('admin.articles.index')->with('success', 'Berita berhasil dihapus.');
    }

    public function quickToggle(Request $request, Article $article): JsonResponse
    {
        $field = $request->input('field');
        if (! in_array($field, ['is_headline', 'is_breaking', 'is_editor_pick', 'is_featured'])) {
            return response()->json(['success' => false, 'message' => 'Kolom tidak valid'], 400);
        }

        $article->$field = ! $article->$field;
        $article->save();

        ActivityLog::log('Quick Toggle', "Mengubah status {$field} pada berita '{$article->title}'");

        return response()->json([
            'success' => true,
            'field' => $field,
            'new_value' => $article->$field,
        ]);
    }
}
