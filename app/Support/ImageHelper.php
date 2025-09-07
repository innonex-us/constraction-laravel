<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageHelper
{
    /**
     * Normalize an original image to avoid storing excessively large files.
     * Scales down if image exceeds max width/height and re-encodes in its
     * original format (JPEG quality ~85, WEBP ~80, PNG default compression).
     */
    public static function normalizeOriginal(string $relativePath, int $maxW = 2400, int $maxH = 2400): void
    {
        $relativePath = ltrim($relativePath, '/');
        if (! Storage::disk('public')->exists($relativePath)) return;

        try {
            $ext = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));
            $raw = Storage::disk('public')->get($relativePath);
            $manager = ImageManager::gd();
            $image = $manager->read($raw);

            $w = $image->width();
            $h = $image->height();
            if ($w > $maxW || $h > $maxH) {
                $image = $image->scaleDown(width: $maxW, height: $maxH);
            }

            $encoded = match ($ext) {
                'jpg', 'jpeg' => $image->toJpeg(85),
                'webp' => $image->toWebp(80),
                default => $image->toPng(),
            };

            Storage::disk('public')->put($relativePath, (string) $encoded);
        } catch (\Throwable $e) {
            // Ignore normalization errors to avoid breaking uploads
        }
    }
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

        // First, normalize the original to a reasonable size.
        static::normalizeOriginal($relativePath);

        $ext = strtolower(pathinfo($relativePath, PATHINFO_EXTENSION));
        $name = pathinfo($relativePath, PATHINFO_FILENAME);
        $dir = trim(pathinfo($relativePath, PATHINFO_DIRNAME), '.');
        $dir = $dir === '' ? '' : ($dir . '/');

        $manager = ImageManager::gd();
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
