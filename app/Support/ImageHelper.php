<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageHelper
{
    /**
     * Generate responsive variants for a public disk image.
     * Creates width-based copies: 400, 800, 1200 (no upscale), suffix `_w{w}`.
     */
    public static function generateVariants(string $relativePath): void
    {
        $relativePath = ltrim($relativePath, '/');
        if (! Storage::disk('public')->exists($relativePath)) {
            return;
        }

        $ext = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));
        $name = pathinfo($relativePath, PATHINFO_FILENAME);
        $dir = trim(pathinfo($relativePath, PATHINFO_DIRNAME), '.');
        $dir = $dir === '' ? '' : ($dir . '/');

        $manager = new ImageManager(['driver' => 'gd']);
        $raw = Storage::disk('public')->get($relativePath);
        $image = $manager->read($raw);
        $origW = $image->width();

        foreach ([400, 800, 1200] as $w) {
            if ($origW <= $w) continue; // don't upscale
            $jpegPath = $dir . $name . '_w' . $w . '.jpg';
            $webpPath = $dir . $name . '_w' . $w . '.webp';

            // If both variants already exist, skip expensive work
            if (Storage::disk('public')->exists($jpegPath) && Storage::disk('public')->exists($webpPath)) continue;

            $resized = $image->scaleDown(width: $w);

            // JPEG fallback (slightly higher quality for clarity)
            if (! Storage::disk('public')->exists($jpegPath)) {
                try {
                    $jpeg = $resized->toJpeg(85);
                    Storage::disk('public')->put($jpegPath, (string) $jpeg);
                } catch (\Throwable $e) {
                    // ignore
                }
            }

            // WEBP modern format (if supported by server)
            if (! Storage::disk('public')->exists($webpPath)) {
                try {
                    $webp = $resized->toWebp(80);
                    Storage::disk('public')->put($webpPath, (string) $webp);
                } catch (\Throwable $e) {
                    // ignore if WEBP not supported
                }
            }
        }
    }

    /**
     * Return available variant paths keyed by width.
     * If none exist, attempt to generate.
     *
     * @return array<int,string>
     */
    public static function variantsFor(string $relativePath, string $format = 'jpg'): array
    {
        $relativePath = ltrim($relativePath, '/');
        if (! Storage::disk('public')->exists($relativePath)) {
            return [];
        }

        $ext = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));
        $name = pathinfo($relativePath, PATHINFO_FILENAME);
        $dir = trim(pathinfo($relativePath, PATHINFO_DIRNAME), '.');
        $dir = $dir === '' ? '' : ($dir . '/');

        $variants = [];
        foreach ([400, 800, 1200] as $w) {
            $ext = $format === 'webp' ? 'webp' : 'jpg';
            $candidate = $dir . $name . '_w' . $w . '.' . $ext;
            if (Storage::disk('public')->exists($candidate)) {
                $variants[$w] = $candidate;
            }
        }

        if (empty($variants)) {
            static::generateVariants($relativePath);
            foreach ([400, 800, 1200] as $w) {
                $ext = $format === 'webp' ? 'webp' : 'jpg';
                $candidate = $dir . $name . '_w' . $w . '.' . $ext;
                if (Storage::disk('public')->exists($candidate)) {
                    $variants[$w] = $candidate;
                }
            }
        }

        ksort($variants);
        return $variants;
    }
}
