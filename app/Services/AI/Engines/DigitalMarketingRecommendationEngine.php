<?php

namespace App\Services\AI\Engines;

use App\Models\Company;
use App\Models\CompanyWebsiteAnalysis;
use Illuminate\Support\Collection;

class DigitalMarketingRecommendationEngine
{
    public function analyze(
        Company $company,
        CompanyWebsiteAnalysis $websiteAnalysis
    ): Collection {
        $recommendations = collect();

        $poorSeo = (int) $websiteAnalysis->seo_score < 65;
        $hasWebsite = !empty($websiteAnalysis->website_url);
        $hasBasicWebsiteQuality = (int) $websiteAnalysis->website_score >= 40;

        if (!$hasWebsite || !$poorSeo) {
            return $recommendations;
        }

        $priorityScore = 66;
        $buyingProbability = 60;

        if ($hasBasicWebsiteQuality) {
            $priorityScore += 8;
            $buyingProbability += 6;
        }

        if (!$websiteAnalysis->has_blog) {
            $priorityScore += 5;
        }

        $recommendations->push(
            $this->makeRecommendation(
                type: 'digital_marketing',
                title: 'Digital Marketing Opportunity',
                description: 'The company has an online presence but may need stronger digital visibility and campaign support.',
                reason: 'A functioning website with weak SEO often indicates an opportunity for search marketing, paid campaigns and conversion optimization.',
                recommendedService: 'Digital Marketing & Lead Generation',
                priorityScore: min($priorityScore, 100),
                buyingProbability: min($buyingProbability, 100),
                estimatedValueMin: 50000,
                estimatedValueMax: 300000,
                evidence: [
                    'industry' => $company->industry ?: 'Not specified',
                    'website_score' => (int) $websiteAnalysis->website_score,
                    'seo_score' => (int) $websiteAnalysis->seo_score,
                    'has_blog' => (bool) $websiteAnalysis->has_blog,
                ],
                suggestedActions: [
                    'Offer a digital marketing audit',
                    'Propose SEO and paid advertising campaigns',
                    'Create landing pages for key services',
                    'Set up conversion tracking and analytics',
                    'Recommend monthly lead-generation reporting',
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