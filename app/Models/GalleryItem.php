<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    }
}
