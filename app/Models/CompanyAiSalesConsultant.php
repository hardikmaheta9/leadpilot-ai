<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAiSalesConsultant extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_uuid',

        'executive_summary',
        'business_overview',
        'digital_maturity',
        'pain_points',
        'opportunities',
        'recommended_services',
        'recommended_package',
        'decision_makers',
        'sales_strategy',
        'objection_handling',
        'next_best_action',

        'opportunity_score',
        'buying_probability',
        'estimated_deal_value',

        'service_bundle',

        'generated_at',
    ];

    protected $casts = [
        'service_bundle' => 'array',
        'generated_at' => 'datetime',
        'opportunity_score' => 'integer',
        'buying_probability' => 'integer',
        'estimated_deal_value' => 'integer',
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