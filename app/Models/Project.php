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
            if ($project->featured_image && ! str_starts_with($project->featured_image, 'http')) {
                ImageHelper::generateVariants($project->featured_image);
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
