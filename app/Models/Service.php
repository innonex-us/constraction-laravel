<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name', 'slug', 'excerpt', 'content', 'icon', 'image', 'order', 'is_active', 'meta_title', 'meta_description',
    ];
}
