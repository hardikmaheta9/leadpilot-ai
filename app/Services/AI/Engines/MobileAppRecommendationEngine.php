<?php

namespace App\Services\AI\Engines;

use App\Models\Company;
use App\Models\CompanyWebsiteAnalysis;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MobileAppRecommendationEngine
{
    public function analyze(
        Company $company,
        CompanyWebsiteAnalysis $websiteAnalysis
    ): Collection {
        $recommendations = collect();

        $industry = Str::lower((string) $company->industry);

        $mobileAppKeywords = [
            'restaurant',
            'food',
            'hospital',
            'healthcare',
            'clinic',
            'school',
            'education',
            'college',
            'university',
            'logistics',
            'transport',
            'travel',
            'hotel',
            'hospitality',
            'retail',
            'ecommerce',
            'e-commerce',
            'real estate',
            'fitness',
            'gym',
            'salon',
            'delivery',
        ];

        $industrySignal = collect($mobileAppKeywords)
            ->contains(
                fn (string $keyword) =>
                    str_contains($industry, $keyword)
            );

        if (!$industrySignal) {
            return $recommendations;
        }

        $priorityScore = 72;
        $buyingProbability = 64;

        if (!$websiteAnalysis->mobile_friendly) {
            $priorityScore += 12;
            $buyingProbability += 10;
        }

        if ((int) $websiteAnalysis->forms >= 1) {
            $priorityScore += 4;
        }

        $recommendations->push(
            $this->makeRecommendation(
                type: 'mobile_app',
                title: 'Mobile App Development Opportunity',
                description: 'The company operates in an industry where customers may benefit from mobile access, booking, ordering, tracking or self-service features.',
                reason: 'Mobile apps can improve repeat engagement, customer convenience and operational efficiency in service-driven and transaction-based industries.',
                recommendedService: 'Mobile App Design & Development',
                priorityScore: min($priorityScore, 100),
                buyingProbability: min($buyingProbability, 100),
                estimatedValueMin: 200000,
                estimatedValueMax: 1200000,
                evidence: [
                    'industry' => $company->industry ?: 'Not specified',
                    'mobile_app_industry_signal' => true,
                    'mobile_friendly' => (bool) $websiteAnalysis->mobile_friendly,
                    'forms' => (int) $websiteAnalysis->forms,
                ],
                suggestedActions: [
                    'Identify customer actions suitable for a mobile app',
                    'Offer booking, ordering, tracking or account features',
                    'Prepare a phased Android and iOS proposal',
                    'Recommend push notifications and CRM integration',
                    'Demonstrate operational and customer-service benefits',
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