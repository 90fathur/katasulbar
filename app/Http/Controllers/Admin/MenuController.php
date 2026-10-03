<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display a listing of navigation menus and dropdown submenus.
     */
    public function index(): View
    {
        $menus = MenuItem::with(['children.category', 'category'])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $parentMenus = MenuItem::whereNull('parent_id')->orderBy('order')->get();

        return view('admin.menus.index', compact('menus', 'categories', 'parentMenus'));
    }

    /**
     * Store a newly created navigation menu item.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'type' => 'required|in:category,custom',
            'category_id' => 'nullable|exists:categories,id',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'nullable|integer',
            'target' => 'required|in:_self,_blank',
            'badge' => 'nullable|string|max:20',
            'badge_color' => 'nullable|string|max:30',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validated['type'] === 'category' && ! empty($validated['category_id'])) {
            $cat = Category::find($validated['category_id']);
            if ($cat) {
                $validated['url'] = route('category.show', $cat->slug, false);
            }
        }

        if (empty($validated['url'])) {
            $validated['url'] = '#';
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? (MenuItem::max('order') + 1);

        $menu = MenuItem::create($validated);

        ActivityLog::log('Tambah Menu', "Menambahkan menu navigasi: {$menu->title}");

        return redirect()->route('admin.menus.index')->with('success', "Menu '{$menu->title}' berhasil ditambahkan.");
    }

    /**
     * Update the specified navigation menu item.
     */
    public function update(Request $request, MenuItem $menu): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'type' => 'required|in:category,custom',
            'category_id' => 'nullable|exists:categories,id',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'order' => 'nullable|integer',
            'target' => 'required|in:_self,_blank',
            'badge' => 'nullable|string|max:20',
            'badge_color' => 'nullable|string|max:30',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        // Prevent setting self as parent
        if (! empty($validated['parent_id']) && (int) $validated['parent_id'] === $menu->id) {
            $validated['parent_id'] = null;
        }

        if ($validated['type'] === 'category' && ! empty($validated['category_id'])) {
            $cat = Category::find($validated['category_id']);
            if ($cat) {
                $validated['url'] = route('category.show', $cat->slug, false);
            }
        }

        if (empty($validated['url'])) {
            $validated['url'] = '#';
        }

        $validated['is_active'] = $request->boolean('is_active');
        $menu->update($validated);

        ActivityLog::log('Ubah Menu', "Memperbarui menu navigasi: {$menu->title}");

        return redirect()->route('admin.menus.index')->with('success', "Menu '{$menu->title}' berhasil diperbarui.");
    }

    /**
     * Remove the specified navigation menu item.
     */
    public function destroy(MenuItem $menu): RedirectResponse
    {
        $title = $menu->title;
        $menu->delete();

        ActivityLog::log('Hapus Menu', "Menghapus menu navigasi: {$title}");

        return redirect()->route('admin.menus.index')->with('success', "Menu '{$title}' dan seluruh submenunya berhasil dihapus.");
    }

    /**
     * Quick toggle active status for navigation menu item via AJAX.
     */
    public function toggle(MenuItem $menu): JsonResponse
    {
        $menu->is_active = ! $menu->is_active;
        $menu->save();

        ActivityLog::log('Toggle Status Menu', "Mengubah status aktif menu {$menu->title} menjadi ".($menu->is_active ? 'Aktif' : 'Nonaktif'));

        return response()->json([
            'success' => true,
            'is_active' => $menu->is_active,
        ]);
    }
}
