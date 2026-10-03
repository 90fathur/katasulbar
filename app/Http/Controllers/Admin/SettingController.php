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
                'site_logo' => 'image|mimes:png,jpg,jpeg,webp|max:4096',
            ]);

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

        // Find bounding box (non-white)
        $minX = $w;
        $minY = $h;
        $maxX = 0;
        $maxY = 0;
        $hasNonWhite = false;

        for ($x = 0; $x < $w; $x += 2) {
            for ($y = 0; $y < $h; $y += 2) {
                $rgb = imagecolorat($src, $x, $y);
                $c = imagecolorsforindex($src, $rgb);
                if ($c['red'] < 245 || $c['green'] < 245 || $c['blue'] < 245) {
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
                    $hasNonWhite = true;
                }
            }
        }

        if (! $hasNonWhite || $minX >= $maxX || $minY >= $maxY) {
            $minX = 0;
            $minY = 0;
            $maxX = $w - 1;
            $maxY = $h - 1;
        }

        $contentW = $maxX - $minX + 1;
        $contentH = $maxY - $minY + 1;
        $pad = 16;
        $croppedW = $contentW + ($pad * 2);
        $croppedH = $contentH + ($pad * 2);

        // 1. Cropped clean white version
        $cropped = imagecreatetruecolor($croppedW, $croppedH);
        $white = imagecolorallocate($cropped, 255, 255, 255);
        imagefilledrectangle($cropped, 0, 0, $croppedW, $croppedH, $white);
        imagecopy($cropped, $src, $pad, $pad, $minX, $minY, $contentW, $contentH);
        imagepng($cropped, public_path('assets/images/logo.png'), 9);

        // 2. Transparent version
        $transImg = imagecreatetruecolor($croppedW, $croppedH);
        imagealphablending($transImg, false);
        imagesavealpha($transImg, true);
        $transparent = imagecolorallocatealpha($transImg, 0, 0, 0, 127);
        imagefilledrectangle($transImg, 0, 0, $croppedW, $croppedH, $transparent);

        for ($x = 0; $x < $croppedW; $x++) {
            for ($y = 0; $y < $croppedH; $y++) {
                $rgb = imagecolorat($cropped, $x, $y);
                $c = imagecolorsforindex($cropped, $rgb);
                $brightness = ($c['red'] * 0.299 + $c['green'] * 0.587 + $c['blue'] * 0.114);

                if ($c['red'] > 248 && $c['green'] > 248 && $c['blue'] > 248) {
                    imagesetpixel($transImg, $x, $y, $transparent);
                } elseif ($c['red'] > 220 && $c['green'] > 220 && $c['blue'] > 220) {
                    $alpha = (int) (127 * (($brightness - 220) / 35.0));
                    $alpha = max(0, min(127, $alpha));
                    $color = imagecolorallocatealpha($transImg, $c['red'], $c['green'], $c['blue'], $alpha);
                    imagesetpixel($transImg, $x, $y, $color);
                } else {
                    $color = imagecolorallocatealpha($transImg, $c['red'], $c['green'], $c['blue'], 0);
                    imagesetpixel($transImg, $x, $y, $color);
                }
            }
        }
        imagepng($transImg, public_path('assets/images/logo-transparent.png'), 9);

        // 3. Dark background version
        $darkBgImg = imagecreatetruecolor($croppedW, $croppedH);
        imagealphablending($darkBgImg, false);
        imagesavealpha($darkBgImg, true);
        imagefilledrectangle($darkBgImg, 0, 0, $croppedW, $croppedH, $transparent);

        for ($x = 0; $x < $croppedW; $x++) {
            for ($y = 0; $y < $croppedH; $y++) {
                $rgb = imagecolorat($transImg, $x, $y);
                $c = imagecolorsforindex($transImg, $rgb);
                if ($c['alpha'] == 127) {
                    imagesetpixel($darkBgImg, $x, $y, $transparent);

                    continue;
                }
                if ($c['red'] < 60 && $c['green'] < 90 && $c['blue'] > 50) {
                    $whiteColor = imagecolorallocatealpha($darkBgImg, 255, 255, 255, $c['alpha']);
                    imagesetpixel($darkBgImg, $x, $y, $whiteColor);
                } elseif ($c['red'] < 70 && $c['green'] < 70 && $c['blue'] < 70) {
                    $tagColor = imagecolorallocatealpha($darkBgImg, 203, 213, 225, $c['alpha']);
                    imagesetpixel($darkBgImg, $x, $y, $tagColor);
                } else {
                    $keepColor = imagecolorallocatealpha($darkBgImg, $c['red'], $c['green'], $c['blue'], $c['alpha']);
                    imagesetpixel($darkBgImg, $x, $y, $keepColor);
                }
            }
        }
        imagepng($darkBgImg, public_path('assets/images/logo-dark-bg.png'), 9);
    }
}
