<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAiProfile extends Model
{
    use HasFactory;

    protected $fillable = [

        'company_uuid',

        'company_summary',
        'business_description',

        'industry',
        'business_type',

        'employee_estimate',
        'founded_year',
        'headquarters',

        'lead_score',
        'lead_grade',

        'confidence_score',

        'last_analyzed_at',
    ];

    protected $casts = [

        'last_analyzed_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(
            Company::class,
            'company_uuid',
            'uuid'
        );
    }
}