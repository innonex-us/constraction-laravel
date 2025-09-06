<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Support\ImageHelper;

class Service extends Model
{
    protected $fillable = [
        'name', 'slug', 'excerpt', 'content', 'icon', 'image', 'order', 'is_active', 'meta_title', 'meta_description',
    ];

    protected static function booted(): void
    {
        static::saving(function (Service $service) {
            if (empty($service->slug) && ! empty($service->name)) {
                $service->slug = Str::slug($service->name);
            }
        });

        static::saved(function (Service $service) {
            if ($service->image && ! str_starts_with($service->image, 'http')) {
                ImageHelper::generateVariants($service->image);
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
        $variants = ImageHelper::variantsFor($this->image);
        if (empty($variants)) return null;
        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }
}
