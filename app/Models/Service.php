<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Support\ImageHelper;

class Service extends Model
{
    protected $fillable = [
        'name', 'slug', 'excerpt', 'content', 'icon', 'image', 'gallery', 'order', 'is_active', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'gallery' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Service $service) {
            if (empty($service->slug) && ! empty($service->name)) {
                $service->slug = Str::slug($service->name);
            }
        });

        static::saved(function (Service $service) {
            // Generate variants for main image
            if ($service->image && ! str_starts_with($service->image, 'http')) {
                ImageHelper::generateVariants($service->image);
            }
            
            // Generate variants for gallery images
            if ($service->gallery && is_array($service->gallery)) {
                foreach ($service->gallery as $galleryImage) {
                    if ($galleryImage && ! str_starts_with($galleryImage, 'http')) {
                        ImageHelper::generateVariants($galleryImage);
                    }
                }
            }
        });
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) return null;
        return str_starts_with($this->image, 'http') ? $this->image : asset('storage/' . ltrim($this->image, '/'));
    }

    public function getImageSrcsetAttribute(): ?string
    {
        if (! $this->image || str_starts_with($this->image, 'http')) return null;
        $variants = ImageHelper::variantsFor($this->image, 'jpg');
        if (empty($variants)) return null;
        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }

    public function getImageSrcsetWebpAttribute(): ?string
    {
        if (! $this->image || str_starts_with($this->image, 'http')) return null;
        $variants = ImageHelper::variantsFor($this->image, 'webp');
        if (empty($variants)) return null;
        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }

    public function getImageFallbackUrlAttribute(): ?string
    {
        if (! $this->image) return null;
        if (str_starts_with($this->image, 'http')) return $this->image_url;
        $variants = ImageHelper::variantsFor($this->image, 'jpg');
        if (! empty($variants)) {
            $last = end($variants);
            if ($last) return asset('storage/' . $last);
        }
        return $this->image_url;
    }

    // Gallery methods
    public function getGalleryUrlsAttribute(): array
    {
        if (!$this->gallery || !is_array($this->gallery)) return [];
        
        return collect($this->gallery)
            ->filter()
            ->map(function ($image) {
                return str_starts_with($image, 'http') 
                    ? $image 
                    : asset('storage/' . ltrim($image, '/'));
            })
            ->toArray();
    }

    public function getGalleryImagesAttribute(): array
    {
        if (!$this->gallery || !is_array($this->gallery)) return [];
        
        return collect($this->gallery)
            ->filter()
            ->map(function ($image) {
                return [
                    'url' => str_starts_with($image, 'http') 
                        ? $image 
                        : asset('storage/' . ltrim($image, '/')),
                    'srcset_jpg' => $this->getGalleryImageSrcset($image, 'jpg'),
                    'srcset_webp' => $this->getGalleryImageSrcset($image, 'webp'),
                    'fallback_url' => $this->getGalleryImageFallback($image),
                ];
            })
            ->toArray();
    }

    private function getGalleryImageSrcset(string $image, string $format = 'jpg'): ?string
    {
        if (str_starts_with($image, 'http')) return null;
        
        $variants = ImageHelper::variantsFor($image, $format);
        if (empty($variants)) return null;
        
        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }

    private function getGalleryImageFallback(string $image): string
    {
        if (str_starts_with($image, 'http')) return $image;
        
        $variants = ImageHelper::variantsFor($image, 'jpg');
        if (!empty($variants)) {
            $last = end($variants);
            if ($last) return asset('storage/' . $last);
        }
        
        return asset('storage/' . ltrim($image, '/'));
    }
}
