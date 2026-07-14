<?php

namespace App\Services\AI\Engines;

use App\Models\Company;
use App\Models\CompanyWebsiteAnalysis;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AutomationRecommendationEngine
{
    public function analyze(
        Company $company,
        CompanyWebsiteAnalysis $websiteAnalysis
    ): Collection {
        $recommendations = collect();

        $industry = Str::lower((string) $company->industry);

        $automationKeywords = [
            'manufacturing',
            'supplier',
            'distributor',
            'logistics',
            'consulting',
            'software',
            'technology',
            'agency',
            'real estate',
            'healthcare',
            'education',
            'finance',
            'insurance',
            'construction',
            'services',
        ];

        $industrySignal = collect($automationKeywords)
            ->contains(
                fn (string $keyword) =>
                    str_contains($industry, $keyword)
            );

        $manualProcessSignal =
            (int) $websiteAnalysis->forms > 0 ||
            (bool) $websiteAnalysis->has_contact_page ||
            $industrySignal;

        if (!$manualProcessSignal) {
            return $recommendations;
        }

        $priorityScore = 68;
        $buyingProbability = 60;

        if ($industrySignal) {
            $priorityScore += 8;
            $buyingProbability += 6;
        }

        if ((int) $websiteAnalysis->forms >= 2) {
            $priorityScore += 6;
            $buyingProbability += 5;
        }

        if ($websiteAnalysis->website_score < 60) {
            $priorityScore += 4;
        }

        $recommendations->push(
            $this->makeRecommendation(
                type: 'business_automation',
                title: 'Business Process Automation Opportunity',
                description: 'The company may benefit from automating repetitive sales, enquiry and operational workflows.',
                reason: 'Manual follow-ups, data entry and approval processes can create delays, errors and missed opportunities.',
                recommendedService: 'Workflow Automation & System Integration',
                priorityScore: min($priorityScore, 100),
                buyingProbability: min($buyingProbability, 100),
                estimatedValueMin: 100000,
                estimatedValueMax: 800000,
                evidence: [
                    'industry' =>
                        $company->industry ?: 'Not specified',

                    'automation_industry_signal' =>
                        $industrySignal,

                    'forms' =>
                        (int) $websiteAnalysis->forms,

                    'has_contact_page' =>
                        (bool) $websiteAnalysis->has_contact_page,

                    'website_score' =>
                        (int) $websiteAnalysis->website_score,
                ],
                suggestedActions: [
                    'Identify repetitive manual workflows',
                    'Map enquiry, approval and follow-up processes',
                    'Offer email and task automation',
                    'Connect website forms with CRM or ERP',
                    'Propose dashboards, alerts and scheduled reports',
                ]
            )
        );

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