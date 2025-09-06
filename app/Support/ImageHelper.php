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
            $variantPath = $dir . $name . '_w' . $w . '.' . $ext;
            if (Storage::disk('public')->exists($variantPath)) continue;

            $resized = $image->scaleDown(width: $w);
            $encoded = $resized->toJpeg(80);
            // If original isn't jpeg, still save jpeg variant to reduce size
            $variantPath = $dir . $name . '_w' . $w . '.jpg';
            Storage::disk('public')->put($variantPath, (string) $encoded);
        }
    }

    /**
     * Return available variant paths keyed by width.
     * If none exist, attempt to generate.
     *
     * @return array<int,string>
     */
    public static function variantsFor(string $relativePath): array
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
            $candidate = $dir . $name . '_w' . $w . '.jpg';
            if (Storage::disk('public')->exists($candidate)) {
                $variants[$w] = $candidate;
            }
        }

        if (empty($variants)) {
            static::generateVariants($relativePath);
            foreach ([400, 800, 1200] as $w) {
                $candidate = $dir . $name . '_w' . $w . '.jpg';
                if (Storage::disk('public')->exists($candidate)) {
                    $variants[$w] = $candidate;
                }
            }
        }

        ksort($variants);
        return $variants;
    }
}

