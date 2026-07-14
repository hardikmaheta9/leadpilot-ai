<?php

namespace App\Services\AI\Engines;

use App\Models\Company;
use App\Models\CompanyWebsiteAnalysis;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ErpRecommendationEngine
{
    public function analyze(
        Company $company,
        CompanyWebsiteAnalysis $websiteAnalysis
    ): Collection {
        $recommendations = collect();

        $industry = Str::lower((string) $company->industry);

        $erpKeywords = [
            'manufacturing',
            'manufacturer',
            'factory',
            'industrial',
            'engineering',
            'supplier',
            'distribution',
            'distributor',
            'warehouse',
            'wholesale',
            'logistics',
            'construction',
            'textile',
            'pharma',
            'chemical',
            'automobile',
            'auto parts',
            'food processing',
        ];

        $erpIndustrySignal = collect($erpKeywords)
            ->contains(
                fn (string $keyword) =>
                    str_contains($industry, $keyword)
            );

        if (!$erpIndustrySignal) {
            return $recommendations;
        }

        $priorityScore = 86;
        $buyingProbability = 74;

        if (
            $websiteAnalysis->website_score < 60 ||
            !$websiteAnalysis->has_contact_page
        ) {
            $priorityScore += 4;
            $buyingProbability += 4;
        }

        $recommendations->push(
            $this->makeRecommendation(
                type: 'erp',
                title: 'ERP Implementation Opportunity',
                description: 'The company appears to operate in an industry where inventory, production, purchasing, sales and operations may benefit from centralized ERP software.',
                reason: 'Manufacturing, distribution and operational businesses often depend on disconnected spreadsheets or separate systems that reduce visibility and efficiency.',
                recommendedService: 'Custom ERP Development & Implementation',
                priorityScore: min($priorityScore, 100),
                buyingProbability: min($buyingProbability, 100),
                estimatedValueMin: 300000,
                estimatedValueMax: 2500000,
                evidence: [
                    'industry' =>
                        $company->industry ?: 'Not specified',

                    'erp_industry_signal' => true,

                    'website_score' =>
                        (int) $websiteAnalysis->website_score,

                    'has_contact_page' =>
                        (bool) $websiteAnalysis->has_contact_page,
                ],
                suggestedActions: [
                    'Schedule an ERP process discovery call',
                    'Map inventory, purchase, sales and production workflows',
                    'Identify spreadsheet-based or disconnected processes',
                    'Prepare a phased ERP implementation proposal',
                    'Offer dashboards, reporting and approval workflows',
                ]
            )
        );

        $recommendations->push(
            $this->makeRecommendation(
                type: 'operations_automation',
                title: 'Operations Automation Opportunity',
                description: 'The company may benefit from automating repetitive operational workflows and approvals.',
                reason: 'Operational businesses often lose time through manual data entry, duplicate records and delayed internal approvals.',
                recommendedService: 'Business Process Automation',
                priorityScore: 76,
                buyingProbability: 68,
                estimatedValueMin: 100000,
                estimatedValueMax: 700000,
                evidence: [
                    'industry' =>
                        $company->industry ?: 'Not specified',

                    'operational_business_signal' => true,
                ],
                suggestedActions: [
                    'Identify repetitive manual workflows',
                    'Offer automated approval systems',
                    'Connect sales, inventory and finance processes',
                    'Propose management dashboards and alerts',
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