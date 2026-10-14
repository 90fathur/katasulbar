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
use App\Services\ImageOptimizer;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

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

// Fallback direct storage file server for shared hosting without symlink support
Route::get('/storage/{path}', function (string $path) {
    $filePath = storage_path('app/public/'.$path);
    if (! file_exists($filePath)) {
        abort(404);
    }

    return response()->file($filePath, [
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('storage.fallback');

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
Route::get('/cpanel-setup', function (Request $request) {
    if ($request->query('key') !== 'katasulbar2026') {
        abort(403, 'Akses ditolak. Parameter key salah.');
    }

    $results = [];
    $target = storage_path('app/public');

    // 1. Hubungkan storage link untuk public dan public_html
    $destinations = [
        'public' => public_path('storage'),
        'public_html' => base_path('../public_html/storage'),
    ];

    foreach ($destinations as $name => $dest) {
        if (! file_exists($dest)) {
            if (function_exists('symlink')) {
                @symlink($target, $dest);
                if (file_exists($dest)) {
                    $results['storage_'.$name] = 'Symlink berhasil dibuat ke '.$dest;

                    continue;
                }
            }
            if (is_dir(dirname($dest))) {
                @mkdir($dest, 0755, true);
                $results['storage_'.$name] = 'Direktori storage dibuat di '.$dest;
            }
        } else {
            $results['storage_'.$name] = 'Storage link sudah ada di '.$dest;
        }
    }

    // 2. Clear & optimize cache
    try {
        @unlink(base_path('bootstrap/cache/config.php'));
        @unlink(base_path('bootstrap/cache/routes.php'));
        @unlink(base_path('bootstrap/cache/services.php'));
        @unlink(base_path('bootstrap/cache/packages.php'));
        Artisan::call('optimize:clear');
        $results['cache_cleared'] = 'Cache dan config bootstrap berhasil dibersihkan';
    } catch (Throwable $e) {
        $results['cache_cleared'] = $e->getMessage();
    }

    // 3. Auto-fix table sessions column id length if exists
    try {
        if (Schema::hasTable('sessions')) {
            DB::statement('ALTER TABLE `sessions` MODIFY `id` VARCHAR(255) NOT NULL');
            DB::table('sessions')->truncate();
            $results['sessions_table'] = 'Tabel sessions berhasil diperbaiki menjadi VARCHAR(255) dan dibersihkan';
        }
    } catch (Throwable $e) {
        $results['sessions_table'] = 'Catatan sessions: '.$e->getMessage();
    }

    // 4. Auto-compress existing article images exceeding 300KB for WhatsApp
    try {
        $articleDir = storage_path('app/public/articles');
        $optimizedCount = 0;
        if (is_dir($articleDir)) {
            $files = glob($articleDir.'/*.{jpg,jpeg,png,webp}', GLOB_BRACE) ?: [];
            foreach ($files as $img) {
                if (filesize($img) > 300000) {
                    if (ImageOptimizer::optimizeForWeb($img)) {
                        $optimizedCount++;
                    }
                }
            }
        }
        $results['images_optimized'] = "{$optimizedCount} gambar berhasil dikompres untuk WhatsApp (< 300KB)";
    } catch (Throwable $e) {
        $results['images_optimized'] = $e->getMessage();
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Konfigurasi cPanel Kata Sulbar selesai!',
        'details' => $results,
        'session_driver' => config('session.driver'),
        'app_env' => config('app.env'),
        'app_url' => config('app.url'),
    ]);
});
