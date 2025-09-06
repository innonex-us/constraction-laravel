<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prequalification extends Model
{
    protected $fillable = [
        'company_name','contact_name','email','phone','trade','license_number','years_in_business','annual_revenue','bonding_capacity','emr','trir','safety_contact','insurance_carrier','coverage','address','city','state','zip','website','notes','documents',
    ];

    protected $casts = [
        'years_in_business' => 'integer',
        'annual_revenue' => 'integer',
        'bonding_capacity' => 'integer',
        'emr' => 'decimal:2',
        'trir' => 'decimal:2',
        'documents' => 'array',
    ];
}
