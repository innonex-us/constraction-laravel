<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title','slug','excerpt','content','location','client','status','category','featured_image','gallery','started_at','completed_at','is_featured','meta_title','meta_description',
    ];

    protected $casts = [
        'gallery' => 'array',
        'started_at' => 'date',
        'completed_at' => 'date',
        'is_featured' => 'boolean',
    ];
}
