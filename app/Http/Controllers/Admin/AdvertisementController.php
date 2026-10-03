<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    public function index(): View
    {
        $ads = Advertisement::orderBy('position')->get();

        return view('admin.advertisements.index', compact('ads'));
    }

    public function create(): View
    {
        return view('admin.advertisements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'required|in:horizontal_top,sidebar_right,article_middle,home_bottom,in_article',
            'ad_type' => 'required|in:image,code',
            'image_url' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:3072',
            'target_url' => 'nullable|url|max:255',
            'ad_code' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'paragraph_position' => 'nullable|integer|in:3,5,7',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('ads', 'public');
            $validated['image_url'] = asset('storage/'.$path);
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $ad = Advertisement::create($validated);

        ActivityLog::log('Tambah Iklan', "Menambahkan slot iklan '{$ad->title}' ({$ad->position})");

        return redirect()->route('admin.advertisements.index')->with('success', 'Iklan berhasil ditambahkan.');
    }

    public function edit(Advertisement $advertisement): View
    {
        return view('admin.advertisements.edit', ['ad' => $advertisement]);
    }

    public function update(Request $request, Advertisement $advertisement): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'required|in:horizontal_top,sidebar_right,article_middle,home_bottom,in_article',
            'ad_type' => 'required|in:image,code',
            'image_url' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:3072',
            'target_url' => 'nullable|url|max:255',
            'ad_code' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'paragraph_position' => 'nullable|integer|in:3,5,7',
        ]);

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('ads', 'public');
            $validated['image_url'] = asset('storage/'.$path);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $advertisement->update($validated);

        ActivityLog::log('Ubah Iklan', "Memperbarui iklan '{$advertisement->title}'");

        return redirect()->route('admin.advertisements.index')->with('success', 'Iklan berhasil diperbarui.');
    }

    public function destroy(Advertisement $advertisement): RedirectResponse
    {
        $title = $advertisement->title;
        $advertisement->delete();

        ActivityLog::log('Hapus Iklan', "Menghapus iklan '{$title}'");

        return redirect()->route('admin.advertisements.index')->with('success', 'Iklan berhasil dihapus.');
    }
}
