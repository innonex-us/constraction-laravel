<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafetyRecord extends Model
{
    protected $fillable = [
        'year','emr','trir','ltir','total_hours','osha_recordables','description',
    ];

    protected $casts = [
        'emr' => 'decimal:2',
        'trir' => 'decimal:2',
        'ltir' => 'decimal:2',
        'total_hours' => 'integer',
        'osha_recordables' => 'integer',
    ];
}
