<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name','logo_path','primary_color','secondary_color','address','phone','email','headline','subheadline','hero_video_url','social_links','theme','stat_years','stat_projects','stat_emr','cta_heading','cta_text','cta_button_text','cta_button_url',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

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
}
