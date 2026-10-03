<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use Tests\TestCase;

class HaloSulbarPortalTest extends TestCase
{
    /**
     * Test homepage loads with branding and key sections.
     */
    public function test_homepage_loads_successfully_with_branding(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('HALO');
        $response->assertSee('SULBAR');
        $response->assertSee('Suara Sulbar, Untuk Semua');
        $response->assertSee('Berita Terkini');
        $response->assertSee('Video Singkat');
        $response->assertSee('Video Terkini');
    }

    /**
     * Test single news article page loads and view is tracked.
     */
    public function test_news_detail_loads_and_displays_content(): void
    {
        $article = Article::where('status', 'published')->first();

        $this->assertNotNull($article, 'Seed data should have at least one published article.');

        $initialViews = $article->views;
        $response = $this->get(route('news.show', $article->slug));

        $response->assertStatus(200);
        $response->assertSee($article->title);
        $response->assertSee('Komentar');

        $article->refresh();
        $this->assertEquals($initialViews + 1, $article->views);
    }

    /**
     * Test category news page loads.
     */
    public function test_category_page_loads_articles(): void
    {
        $category = Category::first();
        $this->assertNotNull($category);

        $response = $this->get(route('category.show', $category->slug));
        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    /**
     * Test dedicated video portal and shorts page loads.
     */
    public function test_video_portal_and_shorts_filter(): void
    {
        $response = $this->get(route('video.index'));
        $response->assertStatus(200);
        $response->assertSee('Galeri Video Halo Sulbar');

        $responseShorts = $this->get(route('video.index', ['tipe' => 'shorts']));
        $responseShorts->assertStatus(200);
    }

    /**
     * Test video detail page loads.
     */
    public function test_video_detail_page_loads(): void
    {
        $video = Video::where('status', 'published')->first();
        $this->assertNotNull($video);

        $response = $this->get(route('video.show', $video->slug));
        $response->assertStatus(200);
        $response->assertSee($video->title);
        $response->assertSee('embed');
    }

    /**
     * Test search and realtime ajax live search API.
     */
    public function test_search_and_live_search_api(): void
    {
        $response = $this->get(route('news.search', ['q' => 'sulbar']));
        $response->assertStatus(200);
        $response->assertSee('Hasil Pencarian');

        $apiResponse = $this->getJson(route('news.live_search', ['q' => 'sulbar']));
        $apiResponse->assertStatus(200);
        $apiResponse->assertJsonStructure([
            'results' => [
                '*' => ['id', 'title', 'category', 'category_color', 'url', 'thumbnail', 'date'],
            ],
        ]);
    }

    /**
     * Test sitemap and RSS feed endpoints.
     */
    public function test_sitemap_and_rss_feed(): void
    {
        $sitemapResponse = $this->get(route('sitemap'));
        $sitemapResponse->assertStatus(200);

        $rssResponse = $this->get(route('feed'));
        $rssResponse->assertStatus(200);
    }

    /**
     * Test newsletter subscription.
     */
    public function test_newsletter_subscription(): void
    {
        $email = 'test_subscriber_'.time().'@example.com';
        $response = $this->postJson(route('newsletter.subscribe'), [
            'email' => $email,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => $email,
        ]);
    }

    /**
     * Test admin panel authentication and dashboard access.
     */
    public function test_admin_requires_authentication(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test authenticated superadmin can access dashboard and management pages.
     */
    public function test_authenticated_admin_can_access_dashboard_and_modules(): void
    {
        $admin = User::where('email', 'admin@halosulbar.com')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Redaksi');
        $response->assertSee('Total Berita');

        // Test articles management page
        $articlesResponse = $this->actingAs($admin)->get(route('admin.articles.index'));
        $articlesResponse->assertStatus(200);
        $articlesResponse->assertSee('Manajemen Berita');

        // Test videos management page
        $videosResponse = $this->actingAs($admin)->get(route('admin.videos.index'));
        $videosResponse->assertStatus(200);
        $videosResponse->assertSee('Manajemen Video');

        // Test ads management page
        $adsResponse = $this->actingAs($admin)->get(route('admin.advertisements.index'));
        $adsResponse->assertStatus(200);
        $adsResponse->assertSee('Manajemen Iklan');

        // Test settings page
        $settingsResponse = $this->actingAs($admin)->get(route('admin.settings.index'));
        $settingsResponse->assertStatus(200);
        $settingsResponse->assertSee('Pengaturan Website');
    }
}
