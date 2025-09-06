<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $fillable = [
        'title','location','department','description','apply_url','is_open','posted_at',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'posted_at' => 'date',
    ];
}
