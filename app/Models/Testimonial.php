<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\ImageHelper;

class Testimonial extends Model
{
    protected $fillable = [
        'author_name','author_title','company','content','rating','avatar_image','is_featured','order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (Testimonial $t) {
            if ($t->avatar_image && ! str_starts_with($t->avatar_image, 'http')) {
                ImageHelper::generateVariants($t->avatar_image);
            }
        });
    }

    public function getAvatarImageUrlAttribute(): ?string
    {
        if (! $this->avatar_image) return null;
        return str_starts_with($this->avatar_image, 'http')
            ? $this->avatar_image
            : asset('storage/' . ltrim($this->avatar_image, '/'));
    }
}
