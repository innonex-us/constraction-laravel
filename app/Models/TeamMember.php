<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\ImageHelper;

class TeamMember extends Model
{
    protected $fillable = [
        'name','role','bio','photo','linkedin_url','order',
    ];

    protected static function booted(): void
    {
        static::saved(function (TeamMember $member) {
            if ($member->photo && ! str_starts_with($member->photo, 'http')) {
                ImageHelper::generateVariants($member->photo);
            }
        });
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) return null;
        return str_starts_with($this->photo, 'http') ? $this->photo : asset('storage/' . ltrim($this->photo, '/'));
    }

    public function getPhotoSrcsetAttribute(): ?string
    {
        if (! $this->photo || str_starts_with($this->photo, 'http')) return null;
        $variants = ImageHelper::variantsFor($this->photo, 'jpg');
        if (empty($variants)) return null;
        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }

    public function getPhotoSrcsetWebpAttribute(): ?string
    {
        if (! $this->photo || str_starts_with($this->photo, 'http')) return null;
        $variants = ImageHelper::variantsFor($this->photo, 'webp');
        if (empty($variants)) return null;
        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }

    public function getPhotoFallbackUrlAttribute(): ?string
    {
        if (! $this->photo) return null;
        if (str_starts_with($this->photo, 'http')) return $this->photo_url;
        $variants = ImageHelper::variantsFor($this->photo, 'jpg');
        if (! empty($variants)) {
            $last = end($variants);
            if ($last) return asset('storage/' . $last);
        }
        return $this->photo_url;
    }
}
