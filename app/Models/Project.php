<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Support\ImageHelper;

class Project extends Model
{
    protected $fillable = [
        'title','slug','excerpt','content','location','client','status','category','featured_image','gallery','started_at','completed_at','is_featured','meta_title','meta_description',
    ];

    protected $casts = [
        'gallery' => 'array',
        'started_at' => 'date',
        'completed_at' => 'date',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Project $project) {
            if (empty($project->slug) && ! empty($project->title)) {
                $project->slug = Str::slug($project->title);
            }
        });

        static::saved(function (Project $project) {
            // Generate variants for featured image
            if ($project->featured_image && ! str_starts_with($project->featured_image, 'http')) {
                ImageHelper::generateVariants($project->featured_image);
            }
            
            // Generate variants for gallery images
            if ($project->gallery && is_array($project->gallery)) {
                foreach ($project->gallery as $galleryImage) {
                    if ($galleryImage && ! str_starts_with($galleryImage, 'http')) {
                        ImageHelper::generateVariants($galleryImage);
                    }
                }
            }
        });
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (! $this->featured_image) return null;
        return str_starts_with($this->featured_image, 'http')
            ? $this->featured_image
            : asset('storage/' . ltrim($this->featured_image, '/'));
    }

    public function getFeaturedImageSrcsetAttribute(): ?string
    {
        if (! $this->featured_image || str_starts_with($this->featured_image, 'http')) return null;
        $variants = ImageHelper::variantsFor($this->featured_image, 'jpg');
        if (empty($variants)) return null;
        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }

    public function getFeaturedImageSrcsetWebpAttribute(): ?string
    {
        if (! $this->featured_image || str_starts_with($this->featured_image, 'http')) return null;
        $variants = ImageHelper::variantsFor($this->featured_image, 'webp');
        if (empty($variants)) return null;
        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }

    public function getFeaturedImageFallbackUrlAttribute(): ?string
    {
        if (! $this->featured_image) return null;
        if (str_starts_with($this->featured_image, 'http')) return $this->featured_image_url;
        $variants = ImageHelper::variantsFor($this->featured_image, 'jpg');
        if (! empty($variants)) {
            $last = end($variants);
            if ($last) return asset('storage/' . $last);
        }
        return $this->featured_image_url;
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
