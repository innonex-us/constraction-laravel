<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title','slug','content','hero_image','meta_title','meta_description','is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
