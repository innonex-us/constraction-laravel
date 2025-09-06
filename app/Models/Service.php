<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    }
}
