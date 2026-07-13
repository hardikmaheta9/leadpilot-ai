<?php

namespace App\Services\AI;

use App\Models\Company;
use App\Models\CompanyAiProfile;

class CompanyProfiler
{
    public function createOrUpdateProfile(
        Company $company,
        array $data = []
    ): CompanyAiProfile {

        return CompanyAiProfile::updateOrCreate(

            [
                'company_uuid' => $company->uuid,
            ],

            [

                'company_summary'      => $data['company_summary'] ?? null,

                'business_description' => $data['business_description'] ?? null,

                'industry'             => $data['industry'] ?? $company->industry,

                'business_type'        => $data['business_type'] ?? null,

                'employee_estimate'    => $data['employee_estimate'] ?? null,

                'founded_year'         => $data['founded_year'] ?? null,

                'headquarters'         => $data['headquarters']
                                            ?? collect([
                                                $company->city,
                                                $company->country,
                                            ])->filter()->join(', '),

                'lead_score'           => $data['lead_score'] ?? 0,

                'lead_grade'           => $data['lead_grade'] ?? null,

                'confidence_score'     => $data['confidence_score'] ?? 0,

                'last_analyzed_at'     => now(),

            ]
        );
    }
}