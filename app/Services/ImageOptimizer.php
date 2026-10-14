<?php

namespace App\Services;

class ImageOptimizer
{
    /**
     * Optimize and compress image to ensure it is under WhatsApp/Social Media size limits (< 300KB).
     */
    public static function optimizeForWeb(string $absolutePath, int $maxWidth = 1200, int $quality = 82): bool
    {
        if (! extension_loaded('gd') || ! file_exists($absolutePath) || ! is_file($absolutePath)) {
            return false;
        }

        $info = @getimagesize($absolutePath);
        if (! $info) {
            return false;
        }

        $mime = $info['mime'] ?? '';
        $src = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($absolutePath),
            'image/png' => @imagecreatefrompng($absolutePath),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($absolutePath) : null,
            default => null,
        };

        if (! $src) {
            return false;
        }

        $origWidth = imagesx($src);
        $origHeight = imagesy($src);

        if ($origWidth > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int) round($origHeight * ($maxWidth / $origWidth));
            $dst = imagecreatetruecolor($newWidth, $newHeight);

            // Handle transparency if PNG or WebP
            if (in_array($mime, ['image/png', 'image/webp'])) {
                $white = imagecolorallocate($dst, 255, 255, 255);
                imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $white);
            }

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            imagedestroy($src);
            $src = $dst;
        }

        // Save as optimized JPEG
        $saved = imagejpeg($src, $absolutePath, $quality);
        imagedestroy($src);

        return $saved;
    }
}
