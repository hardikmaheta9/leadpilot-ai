<?php

namespace App\Services\AI\Engines;

use App\Models\Company;
use App\Models\CompanyWebsiteAnalysis;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class EcommerceRecommendationEngine
{
    public function analyze(
        Company $company,
        CompanyWebsiteAnalysis $websiteAnalysis
    ): Collection {
        $recommendations = collect();

        $industry = Str::lower((string) $company->industry);

        $ecommerceKeywords = [
            'retail',
            'ecommerce',
            'e-commerce',
            'fashion',
            'jewellery',
            'jewelry',
            'furniture',
            'food',
            'cosmetics',
            'beauty',
            'electronics',
            'consumer goods',
            'wholesale',
            'manufacturer',
            'supplier',
            'pharmacy',
            'book store',
            'gift',
        ];

        $industrySignal = collect($ecommerceKeywords)
            ->contains(
                fn (string $keyword) =>
                    str_contains($industry, $keyword)
            );

        $technologyList = collect(
            $websiteAnalysis->technologies ?? []
        )->map(
            fn ($technology) =>
                Str::lower((string) $technology)
        );

        $hasEcommerceTechnology =
            Str::contains(
                Str::lower((string) $websiteAnalysis->cms),
                ['shopify', 'magento', 'woocommerce']
            )
            || $technologyList->contains(
                fn ($technology) =>
                    Str::contains(
                        $technology,
                        ['shopify', 'magento', 'woocommerce']
                    )
            );

        if (!$industrySignal || $hasEcommerceTechnology) {
            return $recommendations;
        }

        $priorityScore = 82;
        $buyingProbability = 72;

        if ($websiteAnalysis->website_score < 60) {
            $priorityScore += 6;
            $buyingProbability += 5;
        }

        if (!$websiteAnalysis->mobile_friendly) {
            $priorityScore += 5;
            $buyingProbability += 4;
        }

        $recommendations->push(
            $this->makeRecommendation(
                type: 'ecommerce',
                title: 'E-commerce Development Opportunity',
                description: 'The company appears to sell products but no dedicated e-commerce platform was detected.',
                reason: 'An online store can expand market reach, automate order processing and create a direct digital sales channel.',
                recommendedService: 'E-commerce Website Development',
                priorityScore: min($priorityScore, 100),
                buyingProbability: min($buyingProbability, 100),
                estimatedValueMin: 150000,
                estimatedValueMax: 1000000,
                evidence: [
                    'industry' =>
                        $company->industry ?: 'Not specified',

                    'ecommerce_industry_signal' => true,

                    'detected_cms' =>
                        $websiteAnalysis->cms ?: 'Not detected',

                    'ecommerce_platform_detected' => false,

                    'website_score' =>
                        (int) $websiteAnalysis->website_score,

                    'mobile_friendly' =>
                        (bool) $websiteAnalysis->mobile_friendly,
                ],
                suggestedActions: [
                    'Confirm the company product catalogue and sales process',
                    'Offer an online catalogue and shopping cart',
                    'Propose online payment and order management',
                    'Recommend inventory and CRM integration',
                    'Include mobile-first checkout and WhatsApp support',
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