<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\User;
use Tests\TestCase;

class DynamicMenuTest extends TestCase
{
    /**
     * Unauthenticated user cannot access menu management.
     */
    public function test_admin_menu_index_requires_authentication(): void
    {
        $response = $this->get(route('admin.menus.index'));
        $response->assertRedirect('/login');
    }

    /**
     * Admin can view menu management dashboard.
     */
    public function test_admin_can_view_menu_management_page(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.menus.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Menu Navigasi & Dropdown');
        $response->assertSee('Tambah Menu Navigasi');
    }

    /**
     * Admin can create a parent menu and a nested dropdown child.
     */
    public function test_admin_can_create_parent_and_dropdown_submenu(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        // 1. Create parent menu
        $parentResponse = $this->actingAs($admin)->post(route('admin.menus.store'), [
            'title' => 'Menu Uji Induk',
            'type' => 'custom',
            'url' => '#',
            'parent_id' => null,
            'order' => 99,
            'target' => '_self',
            'is_active' => '1',
        ]);

        $parentResponse->assertRedirect(route('admin.menus.index'));
        $parent = MenuItem::where('title', 'Menu Uji Induk')->first();
        $this->assertNotNull($parent);

        // 2. Create child sub-menu under parent
        $childResponse = $this->actingAs($admin)->post(route('admin.menus.store'), [
            'title' => 'Sub Menu Dropdown Uji',
            'type' => 'custom',
            'url' => '/uji-dropdown',
            'parent_id' => $parent->id,
            'order' => 1,
            'target' => '_blank',
            'badge' => 'TEST',
            'is_active' => '1',
        ]);

        $childResponse->assertRedirect(route('admin.menus.index'));
        $child = MenuItem::where('title', 'Sub Menu Dropdown Uji')->first();
        $this->assertNotNull($child);
        $this->assertEquals($parent->id, $child->parent_id);
        $this->assertEquals('TEST', $child->badge);

        // Clean up
        $child->delete();
        $parent->delete();
    }

    /**
     * Admin can update a menu item.
     */
    public function test_admin_can_update_menu_item(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $menu = MenuItem::create([
            'title' => 'Menu Sebelum Edit',
            'type' => 'custom',
            'url' => '/sebelum',
            'order' => 50,
            'target' => '_self',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.menus.update', $menu->id), [
            'title' => 'Menu Setelah Edit',
            'type' => 'custom',
            'url' => '/setelah',
            'parent_id' => null,
            'order' => 55,
            'target' => '_blank',
            'badge' => 'UPDATED',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.menus.index'));
        $menu->refresh();
        $this->assertEquals('Menu Setelah Edit', $menu->title);
        $this->assertEquals('/setelah', $menu->url);
        $this->assertEquals('UPDATED', $menu->badge);

        $menu->delete();
    }

    /**
     * Admin can toggle menu active status via AJAX.
     */
    public function test_admin_can_toggle_menu_item_status(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $menu = MenuItem::create([
            'title' => 'Menu Toggle Test',
            'type' => 'custom',
            'url' => '/toggle-test',
            'order' => 90,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.menus.toggle', $menu->id));
        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'is_active' => false]);

        $menu->refresh();
        $this->assertFalse($menu->is_active);

        $menu->delete();
    }

    /**
     * Admin can delete a menu item and its cascaded submenus.
     */
    public function test_admin_can_delete_menu_item(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

        $parent = MenuItem::create([
            'title' => 'Menu Hapus Test',
            'type' => 'custom',
            'url' => '#',
            'order' => 91,
            'is_active' => true,
        ]);

        $child = MenuItem::create([
            'title' => 'Sub Hapus Test',
            'type' => 'custom',
            'url' => '/sub-hapus',
            'parent_id' => $parent->id,
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.menus.destroy', $parent->id));
        $response->assertRedirect(route('admin.menus.index'));

        $this->assertDatabaseMissing('menu_items', ['id' => $parent->id]);
        $this->assertDatabaseMissing('menu_items', ['id' => $child->id]);
    }

    /**
     * Frontend displays dynamic menu items with dropdowns.
     */
    public function test_frontend_displays_dynamic_menu_and_dropdowns(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('nav-menu-list', false);
        $response->assertSee('Sulbar');
        $response->assertSee('Mamuju');
        $response->assertSee('Majene');
        $response->assertSee('Polewali Mandar');
    }
}
