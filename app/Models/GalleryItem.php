<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Support\ImageHelper;

class GalleryItem extends Model
{
    protected $fillable = [
        'title','slug','caption','image','category','order','is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (GalleryItem $item) {
            if (empty($item->slug) && ! empty($item->title)) {
                $item->slug = Str::slug($item->title);
            }
        });

        static::saved(function (GalleryItem $item) {
            if ($item->image && ! str_starts_with($item->image, 'http')) {
                ImageHelper::generateVariants($item->image);
            }
        });
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && ! str_starts_with($this->image, 'http')) {
            return asset('storage/' . ltrim($this->image, '/'));
        }
        return (string) $this->image;
    }

    public function getImageSrcsetAttribute(): ?string
    {
        if (! $this->image || str_starts_with($this->image, 'http')) {
            return null;
        }

        $variants = ImageHelper::variantsFor($this->image);
        if (empty($variants)) return null;

        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }
}
