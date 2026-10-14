<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BreakingNewsController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\VideoController;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes - KATA SULBAR ("Suara Sulbar, Untuk Semua")
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// News & Articles
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::post('/berita/{id}/komentar', [InteractionController::class, 'storeComment'])->name('news.comment');
Route::get('/kategori/{slug}', [NewsController::class, 'category'])->name('category.show');
Route::get('/tag/{slug}', [NewsController::class, 'tag'])->name('tag.show');
Route::get('/cari', [NewsController::class, 'search'])->name('news.search');
Route::get('/api/live-search', [NewsController::class, 'liveSearch'])->name('news.live_search');
Route::get('/api/weather', fn () => response()->json(WeatherService::getSulbarWeather()))->name('api.weather');
Route::get('/iklan/click/{ad}', [NewsController::class, 'adClick'])->name('ad.click');

// Videos & Shorts (Sections 10, 31, 32, 36)
Route::get('/video', [VideoController::class, 'index'])->name('video.index');
Route::get('/video/{slug}', [VideoController::class, 'show'])->name('video.show');

// Interactions (Newsletter & Bookmarks)
Route::post('/newsletter', [InteractionController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');
Route::get('/bookmark', [InteractionController::class, 'bookmarks'])->name('bookmarks');

// Static Pages (Tentang, Redaksi, Kontak, Privasi, Syarat, dll)
Route::get('/tentang-kami', fn () => app(PageController::class)->show('tentang-kami'))->name('page.tentang');
Route::get('/redaksi', fn () => app(PageController::class)->show('redaksi'))->name('page.redaksi');
Route::get('/pedoman-media-siber', fn () => app(PageController::class)->show('pedoman-media-siber'))->name('page.pedoman');
Route::get('/kontak', fn () => app(PageController::class)->show('kontak'))->name('page.kontak');
Route::get('/kebijakan-privasi', fn () => app(PageController::class)->show('kebijakan-privasi'))->name('page.privasi');
Route::get('/syarat-ketentuan', fn () => app(PageController::class)->show('syarat-ketentuan'))->name('page.syarat');
Route::get('/halaman/{slug}', [PageController::class, 'show'])->name('page.show');

// SEO & Syndication
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');
Route::get('/feed', [PageController::class, 'feed'])->name('feed');

/*
|--------------------------------------------------------------------------
| Admin Panel Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

/*
|--------------------------------------------------------------------------
| Admin Panel Protected Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->as('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Articles Management
    Route::resource('articles', AdminArticleController::class);
    Route::post('/articles/{article}/toggle', [AdminArticleController::class, 'quickToggle'])->name('articles.toggle');

    // Categories & Tags
    Route::resource('categories', AdminCategoryController::class)->except(['show', 'create', 'edit']);
    Route::resource('tags', AdminTagController::class)->except(['show', 'create', 'edit']);

    // Videos & Shorts Management
    Route::resource('videos', AdminVideoController::class);

    // Advertisements & Banners
    Route::resource('advertisements', AdvertisementController::class);

    // Breaking News Ticker
    Route::resource('breaking-news', BreakingNewsController::class)->except(['show', 'create', 'edit']);

    // Navigation Menus & Dropdown
    Route::resource('menus', MenuController::class)->except(['show', 'create', 'edit']);
    Route::post('/menus/{menu}/toggle', [MenuController::class, 'toggle'])->name('menus.toggle');

    // Institutional Pages (Redaksi, Tentang Kami, Kontak, dll)
    Route::resource('pages', AdminPageController::class);
    Route::post('/pages/{page}/toggle', [AdminPageController::class, 'toggle'])->name('pages.toggle');

    // Comment Moderation
    Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::put('/comments/{comment}/status/{status}', [AdminCommentController::class, 'updateStatus'])->name('comments.status');
    Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

    // Media Library
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media/upload', [MediaController::class, 'upload'])->name('media.upload');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

    // Newsletter Subscribers
    Route::get('/newsletters', [AdminNewsletterController::class, 'index'])->name('newsletters.index');
    Route::delete('/newsletters/{subscriber}', [AdminNewsletterController::class, 'destroy'])->name('newsletters.destroy');
    Route::get('/newsletters/export', [AdminNewsletterController::class, 'export'])->name('newsletters.export');

    // User & Team Management
    Route::resource('users', UserController::class);

    // Website & SEO Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Activity Log
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
});

// Helper setup untuk cPanel shared hosting tanpa terminal
Route::get('/cpanel-setup', function (\Illuminate\Http\Request $request) {
    if ($request->query('key') !== 'katasulbar2026') {
        abort(403, 'Akses ditolak. Parameter key salah.');
    }

    $results = [];

    // 1. Hubungkan storage link
    try {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $results['storage_link'] = 'Berhasil dihubungkan';
    } catch (\Throwable $e) {
        $results['storage_link'] = 'Catatan: ' . $e->getMessage();
    }

    // 2. Clear & optimize cache
    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $results['cache_cleared'] = 'Cache berhasil dibersihkan';
    } catch (\Throwable $e) {
        $results['cache_cleared'] = $e->getMessage();
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Konfigurasi cPanel Kata Sulbar selesai!',
        'details' => $results,
        'app_env' => config('app.env'),
        'app_url' => config('app.url'),
    ]);
});
