<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name','email','phone','subject','message','status','resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];
}
