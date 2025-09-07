<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Support\ImageHelper;

class Badge extends Model
{
    protected $fillable = [
        'name','slug','image','url','order','is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Badge $badge) {
            if (empty($badge->slug) && ! empty($badge->name)) {
                $badge->slug = Str::slug($badge->name);
            }
        });

        static::saved(function (Badge $badge) {
            if ($badge->image && ! str_starts_with($badge->image, 'http')) {
                ImageHelper::generateVariants($badge->image);
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
}
