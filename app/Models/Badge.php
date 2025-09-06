<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    }
}
