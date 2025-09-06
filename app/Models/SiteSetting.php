<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name','logo_path','primary_color','secondary_color','address','phone','email','headline','hero_video_url','social_links','theme',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];
}
