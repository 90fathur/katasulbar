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

        ActivityLog::log('Ubah Pengaturan', 'Memperbarui konfigurasi website dan SEO');

        return back()->with('success', 'Pengaturan website berhasil disimpan.');
    }
}
