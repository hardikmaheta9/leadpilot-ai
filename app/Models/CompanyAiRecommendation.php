<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAiRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_uuid',

        'type',
        'title',
        'description',
        'reason',

        'recommended_service',

        'priority_score',
        'priority',

        'buying_probability',

        'estimated_value_min',
        'estimated_value_max',

        'status',

        'evidence',
        'suggested_actions',

        'generated_at',
    ];

    protected $casts = [
        'evidence' => 'array',
        'suggested_actions' => 'array',
        'generated_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(
            Company::class,
            'company_uuid',
            'uuid'
        );
    }
}