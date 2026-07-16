<?php

namespace App\Services\Documents;

use App\Models\Company;

class ProposalTemplateBuilder
{
    public function build(Company $company): array
    {
        $company->loadMissing([
            'aiProfile',
            'websiteAnalysis',
            'aiRecommendations',
            'aiSalesConsultant',
            'contacts',
        ]);

        $consultant = $company->aiSalesConsultant;
        $website = $company->websiteAnalysis;
        $profile = $company->aiProfile;

        return [
            'company' => $company,
            'website' => $website,
            'aiProfile' => $profile,

            'proposal_title' => 'Enterprise Digital Transformation Proposal',
            'prepared_for' => $company->company_name,
            'generated_date' => now()->format('d M Y'),

            'brand_name' => 'WebApp Infoway',
            'brand_website' => 'https://webappinfoway.com',
            'brand_email' => 'info@webappinfoway.com',
            'prepared_by_name' => 'Hardik Maheta',
            'prepared_by_title' => 'Founder',

            'executive_summary' => $consultant?->executive_summary,
            'business_overview' => $consultant?->business_overview,
            'digital_maturity' => $consultant?->digital_maturity,
            'pain_points' => $consultant?->pain_points,
            'opportunities' => $consultant?->opportunities,
            'recommended_services' => $consultant?->recommended_services,
            'recommended_package' => $consultant?->recommended_package,
            'decision_makers' => $consultant?->decision_makers,
            'sales_strategy' => $consultant?->sales_strategy,
            'next_best_action' => $consultant?->next_best_action,

            'opportunity_score' => (int) ($consultant?->opportunity_score ?? 0),
            'buying_probability' => (int) ($consultant?->buying_probability ?? 0),
            'estimated_deal_value' => (int) ($consultant?->estimated_deal_value ?? 0),
            'confidence_score' => (int) ($profile?->confidence_score ?? 0),
            'website_score' => (int) ($website?->website_score ?? 0),
            'seo_score' => (int) ($website?->seo_score ?? 0),
            'performance_score' => (int) ($website?->performance_score ?? 0),

            'service_bundle' => $consultant?->service_bundle ?? [],
            'recommendations' => $company->aiRecommendations ?? collect(),
        ];
    }
}
