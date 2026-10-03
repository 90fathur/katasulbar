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

        // Handle Site Logo Upload
        if ($request->hasFile('site_logo')) {
            $request->validate([
                'site_logo' => 'image|mimes:png,jpg,jpeg,webp|max:10240',
            ]);

            @ini_set('memory_limit', '1024M');

            $file = $request->file('site_logo');
            $destPath = public_path('assets/images/logo.png');
            $file->move(public_path('assets/images'), 'logo.png');

            self::generateLogoVariants($destPath);
            Setting::set('site_logo', asset('assets/images/logo-transparent.png'));
        }

        // Handle Site Favicon Upload
        if ($request->hasFile('site_favicon')) {
            $request->validate([
                'site_favicon' => 'file|mimes:png,ico,svg|max:1024',
            ]);

            $file = $request->file('site_favicon');
            $file->move(public_path('assets/images'), 'favicon.png');
            Setting::set('site_favicon', asset('assets/images/favicon.png'));
        }

        ActivityLog::log('Ubah Pengaturan', 'Memperbarui konfigurasi website, identitas logo, dan SEO');

        return back()->with('success', 'Pengaturan website dan logo berhasil diperbarui.');
    }

    public static function generateLogoVariants(string $srcPath): void
    {
        if (! file_exists($srcPath)) {
            return;
        }

        @ini_set('memory_limit', '1024M');

        $info = @getimagesize($srcPath);
        if (! $info) {
            return;
        }

        $src = match ($info['mime'] ?? '') {
            'image/png' => @imagecreatefrompng($srcPath),
            'image/jpeg' => @imagecreatefromjpeg($srcPath),
            'image/webp' => @imagecreatefromwebp($srcPath),
            default => null,
        };

        if (! $src) {
            return;
        }

        $w = imagesx($src);
        $h = imagesy($src);

        // Find bounding box with adaptive step sampling (fast and memory friendly)
        $step = max(2, (int) round(max($w, $h) / 350));
        $minX = $w;
        $minY = $h;
        $maxX = 0;
        $maxY = 0;
        $hasContent = false;

        for ($x = 0; $x < $w; $x += $step) {
            for ($y = 0; $y < $h; $y += $step) {
                $rgb = imagecolorat($src, $x, $y);
                $c = imagecolorsforindex($src, $rgb);
                $isTransparent = ($c['alpha'] ?? 0) > 115;
                $isWhite = $c['red'] > 240 && $c['green'] > 240 && $c['blue'] > 240;

                if (! $isTransparent && ! $isWhite) {
                    if ($x < $minX) {
                        $minX = $x;
                    }
                    if ($x > $maxX) {
                        $maxX = $x;
                    }
                    if ($y < $minY) {
                        $minY = $y;
                    }
                    if ($y > $maxY) {
                        $maxY = $y;
                    }
                    $hasContent = true;
                }
            }
        }

        if (! $hasContent || $minX >= $maxX || $minY >= $maxY) {
            $minX = 0;
            $minY = 0;
            $maxX = $w - 1;
            $maxY = $h - 1;
        }

        // Add 2% breathing padding
        $pad = max(4, (int) round(($maxX - $minX) * 0.02));
        $minX = max(0, $minX - $pad);
        $minY = max(0, $minY - $pad);
        $maxX = min($w - 1, $maxX + $pad);
        $maxY = min($h - 1, $maxY + $pad);

        $cropW = $maxX - $minX + 1;
        $cropH = $maxY - $minY + 1;

        // Target optimized web logo width
        $targetW = min(800, $cropW);
        $targetH = (int) round($cropH * ($targetW / $cropW));

        // 1. Scaled White Base
        $scaledBase = imagecreatetruecolor($targetW, $targetH);
        $white = imagecolorallocate($scaledBase, 255, 255, 255);
        imagefilledrectangle($scaledBase, 0, 0, $targetW, $targetH, $white);
        imagecopyresampled($scaledBase, $src, 0, 0, $minX, $minY, $targetW, $targetH, $cropW, $cropH);
        imagepng($scaledBase, public_path('assets/images/logo.png'), 8);

        // 2. Transparent Version
        $trans = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($trans, false);
        imagesavealpha($trans, true);
        $transColor = imagecolorallocatealpha($trans, 0, 0, 0, 127);
        imagefilledrectangle($trans, 0, 0, $targetW, $targetH, $transColor);

        for ($x = 0; $x < $targetW; $x++) {
            for ($y = 0; $y < $targetH; $y++) {
                $rgb = imagecolorat($scaledBase, $x, $y);
                $c = imagecolorsforindex($scaledBase, $rgb);
                $brightness = ($c['red'] * 0.299 + $c['green'] * 0.587 + $c['blue'] * 0.114);

                if ($c['red'] > 245 && $c['green'] > 245 && $c['blue'] > 245) {
                    imagesetpixel($trans, $x, $y, $transColor);
                } elseif ($c['red'] > 215 && $c['green'] > 215 && $c['blue'] > 215) {
                    $alpha = (int) (127 * (($brightness - 215) / 35.0));
                    $alpha = max(0, min(127, $alpha));
                    $col = imagecolorallocatealpha($trans, $c['red'], $c['green'], $c['blue'], $alpha);
                    imagesetpixel($trans, $x, $y, $col);
                } else {
                    $col = imagecolorallocatealpha($trans, $c['red'], $c['green'], $c['blue'], 0);
                    imagesetpixel($trans, $x, $y, $col);
                }
            }
        }
        imagepng($trans, public_path('assets/images/logo-transparent.png'), 8);

        // 3. Dark Background Version
        $darkBg = imagecreatetruecolor($targetW, $targetH);
        imagealphablending($darkBg, false);
        imagesavealpha($darkBg, true);
        imagefilledrectangle($darkBg, 0, 0, $targetW, $targetH, $transColor);

        for ($x = 0; $x < $targetW; $x++) {
            for ($y = 0; $y < $targetH; $y++) {
                $rgb = imagecolorat($trans, $x, $y);
                $c = imagecolorsforindex($trans, $rgb);

                if (($c['alpha'] ?? 0) >= 120) {
                    imagesetpixel($darkBg, $x, $y, $transColor);

                    continue;
                }

                // If navy / blue tint (HALO), make clean white
                if ($c['blue'] > $c['red'] && $c['red'] < 110) {
                    $col = imagecolorallocatealpha($darkBg, 255, 255, 255, $c['alpha']);
                    imagesetpixel($darkBg, $x, $y, $col);
                } elseif ($c['red'] < 100 && $c['green'] < 100 && $c['blue'] < 100) {
                    $col = imagecolorallocatealpha($darkBg, 226, 232, 240, $c['alpha']);
                    imagesetpixel($darkBg, $x, $y, $col);
                } else {
                    $col = imagecolorallocatealpha($darkBg, $c['red'], $c['green'], $c['blue'], $c['alpha']);
                    imagesetpixel($darkBg, $x, $y, $col);
                }
            }
        }
        imagepng($darkBg, public_path('assets/images/logo-dark-bg.png'), 8);

        imagedestroy($src);
        imagedestroy($scaledBase);
        imagedestroy($trans);
        imagedestroy($darkBg);
    }
}
