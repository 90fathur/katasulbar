<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::pluck('value', 'key')->all();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $fields = [
            'site_name', 'site_tagline', 'site_description', 'site_keywords',
            'contact_email', 'contact_phone', 'contact_address',
            'location_label', 'weather_label',
            'facebook_url', 'instagram_url', 'youtube_url', 'tiktok_url', 'twitter_url', 'telegram_url', 'whatsapp_channel',
            'footer_about',
            'enable_dark_mode', 'enable_comments', 'auto_approve_comments',
        ];

        foreach ($fields as $field) {
            $val = $request->input($field);
            if (in_array($field, ['enable_dark_mode', 'enable_comments', 'auto_approve_comments'])) {
                $val = $request->boolean($field) ? '1' : '0';
            }
            Setting::set($field, $val ?? '');
        }

        // Handle Site Logo Upload (Light / Main)
        if ($request->hasFile('site_logo')) {
            $request->validate([
                'site_logo' => 'image|mimes:png,jpg,jpeg,webp,svg|max:10240',
            ]);

            $path = $request->file('site_logo')->store('branding', 'public');
            $fullUrl = asset('storage/'.$path).'?v='.time();
            Setting::set('site_logo', $fullUrl);

            // Sync to public assets for backward compatibility
            $absSource = storage_path('app/public/'.$path);
            self::syncFileToPublicAssets($absSource, ['logo.png', 'logo-transparent.png']);

            // If no dark logo exists yet, initialize it with the main logo
            if (empty(Setting::get('site_logo_dark'))) {
                Setting::set('site_logo_dark', $fullUrl);
                self::syncFileToPublicAssets($absSource, ['logo-dark-bg.png']);
            }
        }

        // Handle Site Logo Dark (Optional separate logo for dark mode / dark headers)
        if ($request->hasFile('site_logo_dark')) {
            $request->validate([
                'site_logo_dark' => 'image|mimes:png,jpg,jpeg,webp,svg|max:10240',
            ]);

            $darkPath = $request->file('site_logo_dark')->store('branding', 'public');
            $darkUrl = asset('storage/'.$darkPath).'?v='.time();
            Setting::set('site_logo_dark', $darkUrl);

            $absDarkSource = storage_path('app/public/'.$darkPath);
            self::syncFileToPublicAssets($absDarkSource, ['logo-dark-bg.png']);
        }

        // Handle Site Favicon Upload
        if ($request->hasFile('site_favicon')) {
            $request->validate([
                'site_favicon' => 'file|mimes:png,ico,svg|max:2048',
            ]);

            $favPath = $request->file('site_favicon')->store('branding', 'public');
            $favUrl = asset('storage/'.$favPath).'?v='.time();
            Setting::set('site_favicon', $favUrl);

            $absFavSource = storage_path('app/public/'.$favPath);
            self::syncFileToPublicAssets($absFavSource, ['favicon.png', 'favicon.ico']);
        }

        ActivityLog::log('Ubah Pengaturan', 'Memperbarui konfigurasi website, identitas logo, dan SEO');

        return back()->with('success', 'Pengaturan website dan logo berhasil diperbarui.');
    }

    /**
     * Sinkronisasi file logo/favicon ke folder assets di public lokal dan public_html cPanel
     */
    public static function syncFileToPublicAssets(string $srcPath, array $filenames): void
    {
        if (! file_exists($srcPath)) {
            return;
        }

        $directories = [
            public_path('assets/images'),
            base_path('../public_html/assets/images'),
            public_path(''),
            base_path('../public_html'),
        ];

        foreach ($filenames as $name) {
            foreach ($directories as $dir) {
                // Jangan copy logo ke root public/ kecuali untuk file favicon
                if (in_array($dir, [public_path(''), base_path('../public_html')]) && ! in_array($name, ['favicon.ico', 'favicon.png'])) {
                    continue;
                }

                if (! is_dir($dir)) {
                    @mkdir($dir, 0755, true);
                }

                if (is_dir($dir)) {
                    @copy($srcPath, rtrim($dir, '/\\').'/'.$name);
                }
            }
        }
    }
}
