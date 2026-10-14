<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use Tests\TestCase;

class PageManagementTest extends TestCase
{
    protected function getAdmin(): User
    {
        return User::where('role', 'super_admin')->first()
            ?? User::where('role', 'admin')->first()
            ?? User::factory()->create(['role' => 'super_admin']);
    }

    /**
     * Unauthenticated user cannot access page management.
     */
    public function test_guest_cannot_access_page_management(): void
    {
        $response = $this->get(route('admin.pages.index'));
        $response->assertRedirect('/login');
    }

    /**
     * Admin can view pages management dashboard with core pages highlighted.
     */
    public function test_admin_can_view_page_management_dashboard(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get(route('admin.pages.index'));

        $response->assertStatus(200);
        $response->assertSee('Halaman Redaksi, Tentang Kami', false);
        $response->assertSee('Struktur Redaksi');
        $response->assertSee('Tentang Kami');
        $response->assertSee('Kontak & Kerja Sama', false);
    }

    /**
     * Admin can dynamically edit Struktur Redaksi and changes appear on /redaksi.
     */
    public function test_admin_can_dynamically_edit_redaksi(): void
    {
        $admin = $this->getAdmin();
        $redaksi = Page::firstOrCreate(
            ['slug' => 'redaksi'],
            ['title' => 'Struktur Redaksi', 'content' => '<p>Konten awal redaksi</p>', 'is_active' => true]
        );

        $newContent = '<h2>Susunan Redaksi Terbaru 2026</h2><p>Pemimpin Redaksi: Tim Jurnalis Sulbar Mandiri</p>';

        $response = $this->actingAs($admin)->put(route('admin.pages.update', $redaksi->id), [
            'title' => 'Struktur Redaksi Terkini',
            'slug' => 'redaksi',
            'content' => $newContent,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.pages.index'));

        // Verify database updated
        $this->assertDatabaseHas('pages', [
            'id' => $redaksi->id,
            'title' => 'Struktur Redaksi Terkini',
            'slug' => 'redaksi',
        ]);

        // Verify frontend /redaksi reflects new dynamic content
        $frontendResponse = $this->get(route('page.redaksi'));
        $frontendResponse->assertStatus(200);
        $frontendResponse->assertSee('Struktur Redaksi Terkini');
        $frontendResponse->assertSee('Pemimpin Redaksi: Tim Jurnalis Sulbar Mandiri');
    }

    /**
     * Admin can dynamically edit Tentang Kami and changes appear on /tentang-kami.
     */
    public function test_admin_can_dynamically_edit_tentang_kami(): void
    {
        $admin = $this->getAdmin();
        $tentang = Page::firstOrCreate(
            ['slug' => 'tentang-kami'],
            ['title' => 'Tentang Kami', 'content' => '<p>Konten awal tentang kami</p>', 'is_active' => true]
        );

        $newContent = '<h2>Visi & Misi Kata Sulbar</h2><p>Portal berita terpercaya untuk seluruh masyarakat Sulawesi Barat.</p>';

        $response = $this->actingAs($admin)->put(route('admin.pages.update', $tentang->id), [
            'title' => 'Tentang Kami - Kata Sulbar',
            'slug' => 'tentang-kami',
            'content' => $newContent,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.pages.index'));

        // Verify frontend /tentang-kami reflects new content
        $frontendResponse = $this->get(route('page.tentang'));
        $frontendResponse->assertStatus(200);
        $frontendResponse->assertSee('Tentang Kami - Kata Sulbar');
        $frontendResponse->assertSee('Portal berita terpercaya untuk seluruh masyarakat Sulawesi Barat');
    }

    /**
     * Admin can dynamically edit Kontak & Iklan and changes appear on /kontak.
     */
    public function test_admin_can_dynamically_edit_kontak(): void
    {
        $admin = $this->getAdmin();
        $kontak = Page::firstOrCreate(
            ['slug' => 'kontak'],
            ['title' => 'Kontak & Kerja Sama Iklan', 'content' => '<p>Konten awal kontak</p>', 'is_active' => true]
        );

        $newContent = '<h2>Alamat Redaksi Utama</h2><p>WhatsApp Hotline: 0852-9999-8888, Email: halo@katasulbar.com</p>';

        $response = $this->actingAs($admin)->put(route('admin.pages.update', $kontak->id), [
            'title' => 'Hubungi Redaksi & Pasang Iklan',
            'slug' => 'kontak',
            'content' => $newContent,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.pages.index'));

        // Verify frontend /kontak reflects new content
        $frontendResponse = $this->get(route('page.kontak'));
        $frontendResponse->assertStatus(200);
        $frontendResponse->assertSee('Hubungi Redaksi & Pasang Iklan');
        $frontendResponse->assertSee('WhatsApp Hotline: 0852-9999-8888');
    }

    /**
     * Core system pages cannot be deleted.
     */
    public function test_core_system_pages_cannot_be_deleted(): void
    {
        $admin = $this->getAdmin();
        $redaksi = Page::firstOrCreate(
            ['slug' => 'redaksi'],
            ['title' => 'Struktur Redaksi', 'content' => '<p>Konten</p>', 'is_active' => true]
        );

        $response = $this->actingAs($admin)->delete(route('admin.pages.destroy', $redaksi->id));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('pages', ['id' => $redaksi->id]);
    }

    /**
     * Admin can toggle page status via AJAX.
     */
    public function test_admin_can_toggle_page_status(): void
    {
        $admin = $this->getAdmin();
        $page = Page::firstOrCreate(
            ['slug' => 'kebijakan-privasi'],
            ['title' => 'Kebijakan Privasi', 'content' => '<p>Privasi</p>', 'is_active' => true]
        );

        $initialStatus = $page->is_active;

        $response = $this->actingAs($admin)->postJson(route('admin.pages.toggle', $page->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'is_active' => ! $initialStatus]);

        $this->assertEquals(! $initialStatus, $page->fresh()->is_active);
    }
}
