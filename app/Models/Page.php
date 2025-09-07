<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Support\ImageHelper;

class Page extends Model
{
    protected $fillable = [
        'title','slug','content','hero_image','meta_title','meta_description','is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Page $page) {
            if (empty($page->slug) && ! empty($page->title)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::saved(function (Page $page) {
            if ($page->hero_image && ! str_starts_with($page->hero_image, 'http')) {
                ImageHelper::generateVariants($page->hero_image);
            }
        });
    }

    public function getHeroImageUrlAttribute(): ?string
    {
        if (! $this->hero_image) return null;
        return str_starts_with($this->hero_image, 'http')
            ? $this->hero_image
            : asset('storage/' . ltrim($this->hero_image, '/'));
    }

    public function getHeroImageSrcsetAttribute(): ?string
    {
        if (! $this->hero_image || str_starts_with($this->hero_image, 'http')) return null;
        $variants = ImageHelper::variantsFor($this->hero_image, 'jpg');
        if (empty($variants)) return null;
        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }

    public function getHeroImageSrcsetWebpAttribute(): ?string
    {
        if (! $this->hero_image || str_starts_with($this->hero_image, 'http')) return null;
        $variants = ImageHelper::variantsFor($this->hero_image, 'webp');
        if (empty($variants)) return null;
        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }

    public function getHeroImageFallbackUrlAttribute(): ?string
    {
        if (! $this->hero_image) return null;
        if (str_starts_with($this->hero_image, 'http')) return $this->hero_image_url;
        $variants = ImageHelper::variantsFor($this->hero_image, 'jpg');
        if (! empty($variants)) {
            $last = end($variants);
            if ($last) return asset('storage/' . $last);
        }
        return $this->hero_image_url;
    }
}
