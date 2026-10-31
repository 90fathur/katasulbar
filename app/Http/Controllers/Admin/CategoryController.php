<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('parent')->withCount('articles')->orderBy('order')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $rawSlug = $request->input('slug');
        if (blank($rawSlug) && $request->filled('name')) {
            $request->merge(['slug' => Str::slug($request->input('name'))]);
        } elseif (! blank($rawSlug)) {
            $request->merge(['slug' => Str::slug($rawSlug)]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:categories,slug',
            'description' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'slug.required' => 'Slug URL kategori tidak boleh kosong.',
            'slug.unique' => 'Kategori atau slug ":input" sudah terdaftar. Silakan gunakan nama atau slug lain.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        try {
            $category = Category::create($validated);
        } catch (UniqueConstraintViolationException|QueryException $e) {
            return back()->withInput()->withErrors([
                'slug' => 'Kategori atau slug ini sudah terdaftar dalam sistem. Silakan periksa daftar kategori atau gunakan nama/slug lain.',
            ]);
        }

        ActivityLog::log('Tambah Kategori', "Menambahkan kategori '{$category->name}'");

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $rawSlug = $request->input('slug');
        if (blank($rawSlug) && $request->filled('name')) {
            $request->merge(['slug' => Str::slug($request->input('name'))]);
        } elseif (! blank($rawSlug)) {
            $request->merge(['slug' => Str::slug($rawSlug)]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:categories,slug,'.$category->id,
            'description' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'nullable|integer',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'slug.required' => 'Slug URL kategori tidak boleh kosong.',
            'slug.unique' => 'Kategori atau slug ":input" sudah digunakan oleh kategori lain.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        try {
            $category->update($validated);
        } catch (UniqueConstraintViolationException|QueryException $e) {
            return back()->withInput()->withErrors([
                'slug' => 'Kategori atau slug ini sudah digunakan oleh kategori lain.',
            ]);
        }

        ActivityLog::log('Ubah Kategori', "Memperbarui kategori '{$category->name}'");

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->articles()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki artikel terhubung.');
        }

        $name = $category->name;
        $category->delete();

        ActivityLog::log('Hapus Kategori', "Menghapus kategori '{$name}'");

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
