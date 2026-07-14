<?php

namespace App\Services\AI\Engines;

use App\Models\Company;
use App\Models\CompanyWebsiteAnalysis;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CrmRecommendationEngine
{
    public function analyze(
        Company $company,
        CompanyWebsiteAnalysis $websiteAnalysis
    ): Collection {
        $recommendations = collect();

        $industry = Str::lower((string) $company->industry);

        $b2bKeywords = [
            'manufacturing',
            'industrial',
            'supplier',
            'distributor',
            'wholesale',
            'software',
            'technology',
            'consulting',
            'logistics',
            'construction',
            'real estate',
            'agency',
            'services',
        ];

        $looksB2b = collect($b2bKeywords)
            ->contains(fn (string $keyword) => str_contains($industry, $keyword));

        $hasLeadCaptureGap =
            !$websiteAnalysis->has_contact_page ||
            (int) $websiteAnalysis->forms === 0;

        if ($looksB2b || $hasLeadCaptureGap) {
            $priorityScore = $looksB2b && $hasLeadCaptureGap
                ? 88
                : 72;

            $buyingProbability = $looksB2b && $hasLeadCaptureGap
                ? 80
                : 66;

            $recommendations->push(
                $this->makeRecommendation(
                    type: 'crm',
                    title: 'CRM Implementation Opportunity',
                    description: 'The company may benefit from a centralized system for enquiries, follow-ups, contacts and sales activities.',
                    reason: $looksB2b
                        ? 'The company appears to operate in a B2B environment where structured lead and relationship management can improve sales efficiency.'
                        : 'The website has limited lead capture capability, creating an opportunity for CRM-connected forms and automated follow-up.',
                    recommendedService: 'CRM Development & Integration',
                    priorityScore: $priorityScore,
                    buyingProbability: $buyingProbability,
                    estimatedValueMin: 75000,
                    estimatedValueMax: 350000,
                    evidence: [
                        'industry' => $company->industry ?: 'Not specified',
                        'b2b_industry_signal' => $looksB2b,
                        'has_contact_page' => (bool) $websiteAnalysis->has_contact_page,
                        'forms' => (int) $websiteAnalysis->forms,
                    ],
                    suggestedActions: [
                        'Offer a CRM workflow discovery session',
                        'Map the company enquiry and follow-up process',
                        'Propose website form integration with CRM',
                        'Demonstrate automated reminders and sales tracking',
                    ]
                )
            );
        }

        if ((int) $websiteAnalysis->forms >= 2) {
            $recommendations->push(
                $this->makeRecommendation(
                    type: 'crm_automation',
                    title: 'Automate Website Enquiry Management',
                    description: 'Multiple website forms were detected and may require centralized tracking.',
                    reason: 'Manual handling of enquiries from multiple forms can cause missed follow-ups and fragmented customer data.',
                    recommendedService: 'CRM Automation & Lead Routing',
                    priorityScore: 74,
                    buyingProbability: 70,
                    estimatedValueMin: 50000,
                    estimatedValueMax: 180000,
                    evidence: [
                        'forms' => (int) $websiteAnalysis->forms,
                    ],
                    suggestedActions: [
                        'Connect all forms to one CRM pipeline',
                        'Add automatic lead assignment',
                        'Configure email and task notifications',
                        'Track enquiry source and conversion status',
                    ]
                )
            );
        }

        return $recommendations;
    }

    private function makeRecommendation(
        string $type,
        string $title,
        string $description,
        string $reason,
        string $recommendedService,
        int $priorityScore,
        int $buyingProbability,
        int $estimatedValueMin,
        int $estimatedValueMax,
        array $evidence,
        array $suggestedActions
    ): array {
        return [
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'reason' => $reason,
            'recommended_service' => $recommendedService,
            'priority_score' => min($priorityScore, 100),
            'priority' => $this->priorityLabel($priorityScore),
            'buying_probability' => min($buyingProbability, 100),
            'estimated_value_min' => $estimatedValueMin,
            'estimated_value_max' => $estimatedValueMax,
            'evidence' => $evidence,
            'suggested_actions' => $suggestedActions,
        ];
    }

    private function priorityLabel(int $score): string
    {
        return match (true) {
            $score >= 85 => 'high',
            $score >= 60 => 'medium',
            default => 'low',
        };
    }
}