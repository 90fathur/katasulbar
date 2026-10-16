<?php

namespace App\Providers;

use App\Models\Advertisement;
use App\Models\BreakingNews;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Setting;
use App\Models\Video;
use App\Services\WeatherService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer(['frontend.*', 'admin.*'], function ($view) {
            try {
                if (Schema::hasTable('settings')) {
                    $settings = Setting::pluck('value', 'key')->all();
                    $view->with('siteSettings', $settings);

                    if (str_starts_with($view->getName(), 'frontend.')) {
                        $navCategories = Category::where('is_active', true)->orderBy('order')->get();
                        $navMenus = Schema::hasTable('menu_items')
                            ? MenuItem::active()->parents()->with(['activeChildren.category', 'category'])->orderBy('order')->get()
                            : collect();
                        $breakingNews = BreakingNews::active()->get();
                        $ads = Advertisement::active()->get()->groupBy('position');
                        $sidebarVideos = Video::standard()->orderByDesc('views')->take(4)->get();
                        $currentWeather = WeatherService::getSulbarWeather();

                        $view->with([
                            'navCategories' => $navCategories,
                            'navMenus' => $navMenus,
                            'breakingNews' => $breakingNews,
                            'ads' => $ads,
                            'sidebarVideos' => $sidebarVideos,
                            'currentWeather' => $currentWeather,
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                $view->with([
                    'siteSettings' => [],
                    'navCategories' => collect(),
                    'navMenus' => collect(),
                    'breakingNews' => collect(),
                    'ads' => collect(),
                    'sidebarVideos' => collect(),
                    'currentWeather' => WeatherService::getSulbarWeather(),
                ]);
            }
        });
    }
}
