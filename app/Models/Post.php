<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Support\ImageHelper;

class Post extends Model
{
    protected $fillable = [
        'title','slug','excerpt','body','featured_image','published_at','is_published','meta_title','meta_description',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            if (empty($post->slug) && ! empty($post->title)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::saved(function (Post $post) {
            if ($post->featured_image && ! str_starts_with($post->featured_image, 'http')) {
                ImageHelper::generateVariants($post->featured_image);
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
        $variants = ImageHelper::variantsFor($this->featured_image);
        if (empty($variants)) return null;
        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }
}
