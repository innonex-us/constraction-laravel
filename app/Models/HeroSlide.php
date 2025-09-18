<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\ImageHelper;

class HeroSlide extends Model
{
    protected $fillable = [
        'title',
        'subtitle', 
        'description',
        'image',
        'video_url',
        'button_text',
        'button_url',
        'button_style',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (HeroSlide $slide) {
            if ($slide->image && ! str_starts_with($slide->image, 'http')) {
                ImageHelper::generateVariants($slide->image);
            }
        });
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) return null;
        return str_starts_with($this->image, 'http')
            ? $this->image
            : asset('storage/' . ltrim($this->image, '/'));
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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }
}