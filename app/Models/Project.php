<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected static function booted(): void
    {
        static::saving(function (Project $project) {
            if (empty($project->slug) && ! empty($project->title)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }
}
