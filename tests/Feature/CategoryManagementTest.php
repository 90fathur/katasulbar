<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    private function getAdmin(): User
    {
        return User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_categories_page(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Kategori Berita');
        $response->assertSee('Tambah Kategori Baru');
    }

    public function test_admin_can_create_category_with_auto_generated_slug(): void
    {
        $admin = $this->getAdmin();

        Category::where('slug', 'hiburan-terbaru')->delete();

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Hiburan Terbaru',
            'slug' => '',
            'color' => '#123456',
            'order' => 1,
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Hiburan Terbaru',
            'slug' => 'hiburan-terbaru',
        ]);
    }

    public function test_creating_category_with_duplicate_slug_returns_validation_error_instead_of_500(): void
    {
        $admin = $this->getAdmin();

        // Ensure a category exists with slug 'entertainment'
        $existing = Category::firstOrCreate(
            ['slug' => 'entertainment'],
            ['name' => 'Entertainment', 'color' => '#062B56', 'order' => 50, 'is_active' => true]
        );

        // Attempt to create another category with empty slug but same name 'Entertainment'
        $response = $this->actingAs($admin)
            ->from(route('admin.categories.index'))
            ->post(route('admin.categories.store'), [
                'name' => 'Entertainment',
                'slug' => '',
            ]);

        // Must redirect back with validation error, NOT throw 500 error
        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHasErrors('slug');
    }

    public function test_admin_can_update_category(): void
    {
        $admin = $this->getAdmin();

        $category = Category::firstOrCreate(
            ['slug' => 'kategori-uji'],
            ['name' => 'Kategori Uji', 'color' => '#062B56', 'order' => 99, 'is_active' => true]
        );

        $response = $this->actingAs($admin)->put(route('admin.categories.update', $category), [
            'name' => 'Kategori Uji Diperbarui',
            'slug' => 'kategori-uji',
            'color' => '#FF0000',
            'order' => 10,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Kategori Uji Diperbarui',
            'color' => '#FF0000',
        ]);
    }

    public function test_cannot_delete_category_with_articles(): void
    {
        $admin = $this->getAdmin();

        $category = Category::whereHas('articles')->first();
        if (! $category) {
            $category = Category::firstOrCreate(
                ['slug' => 'kategori-berisi'],
                ['name' => 'Kategori Berisi', 'color' => '#000000']
            );
            Article::factory()->create(['category_id' => $category->id]);
        }

        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
