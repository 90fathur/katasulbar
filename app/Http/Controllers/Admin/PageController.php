<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display a listing of institutional and static pages.
     */
    public function index(): View
    {
        $pages = Page::orderBy('id')->get();

        // Highlight core pages requested by the user
        $corePages = [
            'redaksi' => $pages->firstWhere('slug', 'redaksi'),
            'tentang-kami' => $pages->firstWhere('slug', 'tentang-kami'),
            'kontak' => $pages->firstWhere('slug', 'kontak'),
        ];

        return view('admin.pages.index', compact('pages', 'corePages'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function create(): View
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created page in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:pages,slug',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = ! empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['title']);

        $validated['is_active'] = $request->boolean('is_active', true);

        $page = Page::create($validated);

        ActivityLog::log('Tambah Halaman', "Menambahkan halaman statis baru: {$page->title}");

        return redirect()->route('admin.pages.index')
            ->with('success', "Halaman '{$page->title}' berhasil dibuat.");
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page in storage.
     */
    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:200|unique:pages,slug,'.$page->id,
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['is_active'] = $request->boolean('is_active');

        $page->update($validated);

        ActivityLog::log('Ubah Halaman', "Memperbarui isi halaman: {$page->title}");

        return redirect()->route('admin.pages.index')
            ->with('success', "Halaman '{$page->title}' berhasil diperbarui.");
    }

    /**
     * Remove the specified page from storage.
     */
    public function destroy(Page $page): RedirectResponse
    {
        // Protect system core pages
        if (in_array($page->slug, ['tentang-kami', 'redaksi', 'kontak'])) {
            return back()->with('error', "Halaman sistem '{$page->title}' tidak dapat dihapus, namun isi dan statusnya dapat Anda edit.");
        }

        $title = $page->title;
        $page->delete();

        ActivityLog::log('Hapus Halaman', "Menghapus halaman: {$title}");

        return redirect()->route('admin.pages.index')
            ->with('success', "Halaman '{$title}' berhasil dihapus.");
    }

    /**
     * Quick toggle status via AJAX.
     */
    public function toggle(Page $page): JsonResponse
    {
        $page->is_active = ! $page->is_active;
        $page->save();

        ActivityLog::log('Toggle Halaman', "Mengubah status halaman {$page->title} menjadi ".($page->is_active ? 'Aktif' : 'Nonaktif'));

        return response()->json([
            'success' => true,
            'is_active' => $page->is_active,
            'message' => "Status halaman {$page->title} berhasil diperbarui.",
        ]);
    }
}
