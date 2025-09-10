<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Support\ImageHelper;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name','logo_path','primary_color','secondary_color','address','phone','email','headline','subheadline','hero_video_url','hero_image','social_links','theme','stat_years','stat_projects','stat_emr','cta_heading','cta_text','cta_button_text','cta_button_url',
        'show_services_section','show_projects_section','show_testimonials_section','show_clients_section','show_news_section','show_badges_section',
        'services_section_heading','projects_section_heading','testimonials_section_heading','clients_section_heading','news_section_heading','badges_section_heading',
        'services_limit','projects_limit','testimonials_limit','news_limit',
    ];

    protected $casts = [
        'social_links' => 'array',
        'show_services_section' => 'boolean',
        'show_projects_section' => 'boolean',
        'show_testimonials_section' => 'boolean',
        'show_clients_section' => 'boolean',
        'show_news_section' => 'boolean',
        'show_badges_section' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (SiteSetting $setting) {
            if ($setting->hero_image && !str_starts_with($setting->hero_image, 'http')) {
                ImageHelper::generateVariants($setting->hero_image);
            }
        });
    }

    // Normalize logo path to be relative to the public disk, for Filament previews.
    public function getLogoPathAttribute($value): ?string
    {
        if (empty($value)) return null;
        $v = (string) $value;
        // If full URL, keep only the path part
        if (str_starts_with($v, 'http')) {
            $path = parse_url($v, PHP_URL_PATH);
            $v = $path ?: $v;
        }
        // Strip leading slash and `storage/` prefix if present
        $v = ltrim($v, '/');
        if (str_starts_with($v, 'storage/')) {
            $v = substr($v, strlen('storage/'));
        }
        return $v;
    }

    public function setLogoPathAttribute($value): void
    {
        if (empty($value)) { $this->attributes['logo_path'] = null; return; }
        $v = (string) $value;
        if (str_starts_with($v, 'http')) {
            $path = parse_url($v, PHP_URL_PATH);
            $v = $path ?: $v;
        }
        $v = ltrim($v, '/');
        if (str_starts_with($v, 'storage/')) {
            $v = substr($v, strlen('storage/'));
        }
        $this->attributes['logo_path'] = $v;
    }

    public function getHeroImageUrlAttribute(): ?string
    {
        if (!$this->hero_image) return null;
        return str_starts_with($this->hero_image, 'http')
            ? $this->hero_image
            : asset('storage/' . ltrim($this->hero_image, '/'));
    }

    public function getHeroImageSrcsetAttribute(): ?string
    {
        if (!$this->hero_image || str_starts_with($this->hero_image, 'http')) return null;
        $variants = ImageHelper::variantsFor($this->hero_image, 'jpg');
        if (empty($variants)) return null;
        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }

    public function getHeroImageSrcsetWebpAttribute(): ?string
    {
        if (!$this->hero_image || str_starts_with($this->hero_image, 'http')) return null;
        $variants = ImageHelper::variantsFor($this->hero_image, 'webp');
        if (empty($variants)) return null;
        $parts = [];
        foreach ($variants as $w => $path) {
            $parts[] = asset('storage/' . $path) . ' ' . $w . 'w';
        }
        return implode(', ', $parts);
    }

    public function getHeroImageFallbackUrlAttribute(): ?string
    {
        if (!$this->hero_image || str_starts_with($this->hero_image, 'http')) return $this->hero_image_url;
        $variants = ImageHelper::variantsFor($this->hero_image, 'jpg');
        if (!empty($variants)) {
            $last = end($variants);
            if ($last) return asset('storage/' . $last);
        }
        return $this->hero_image_url;
    }

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) return null;
        return str_starts_with($this->logo_path, 'http')
            ? $this->logo_path
            : asset('storage/' . ltrim($this->logo_path, '/'));
    }
}
