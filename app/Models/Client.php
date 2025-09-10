<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Support\ImageHelper;

class Client extends Model
{
    protected $fillable = [
        'name', 'slug', 'logo', 'website_url', 'order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Client $client) {
            if (empty($client->slug) && !empty($client->name)) {
                $client->slug = Str::slug($client->name);
            }
        });

        static::saved(function (Client $client) {
            if ($client->logo && !str_starts_with($client->logo, 'http')) {
                ImageHelper::generateVariants($client->logo);
            }
        });
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) return null;
        return str_starts_with($this->logo, 'http')
            ? $this->logo
            : asset('storage/' . ltrim($this->logo, '/'));
    }
}
