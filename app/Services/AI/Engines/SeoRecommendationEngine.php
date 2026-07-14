<?php

namespace App\Services\AI\Engines;

use App\Models\Company;
use App\Models\CompanyWebsiteAnalysis;
use Illuminate\Support\Collection;

class SeoRecommendationEngine
{
    public function analyze(
        Company $company,
        CompanyWebsiteAnalysis $websiteAnalysis
    ): Collection {
        $recommendations = collect();

        if ($websiteAnalysis->seo_score < 60) {
            $recommendations->push(
                $this->makeRecommendation(
                    type: 'seo',
                    title: 'SEO Improvement Opportunity',
                    description: 'The website has a low SEO score.',
                    reason: 'Weak on-page SEO can reduce organic visibility and inbound enquiries.',
                    recommendedService: 'SEO Optimization',
                    priorityScore: 85,
                    buyingProbability: 75,
                    estimatedValueMin: 25000,
                    estimatedValueMax: 90000,
                    evidence: [
                        'seo_score' => $websiteAnalysis->seo_score,
                        'has_blog' => $websiteAnalysis->has_blog,
                    ],
                    suggestedActions: [
                        'Offer a complete SEO audit',
                        'Improve titles, metadata and heading structure',
                        'Create a keyword and content strategy',
                    ]
                )
            );
        }

        if (!$websiteAnalysis->has_blog) {
            $recommendations->push(
                $this->makeRecommendation(
                    type: 'content_marketing',
                    title: 'Content Marketing Opportunity',
                    description: 'No blog or news section was detected.',
                    reason: 'Regular industry content can improve authority, rankings and organic lead generation.',
                    recommendedService: 'Content Marketing & SEO',
                    priorityScore: 55,
                    buyingProbability: 55,
                    estimatedValueMin: 15000,
                    estimatedValueMax: 60000,
                    evidence: [
                        'has_blog' => false,
                    ],
                    suggestedActions: [
                        'Offer blog setup and content architecture',
                        'Propose a monthly content calendar',
                        'Target industry-specific search terms',
                    ]
                )
            );
        }

        if (
            $websiteAnalysis->word_count < 300 &&
            $websiteAnalysis->seo_score < 70
        ) {
            $recommendations->push(
                $this->makeRecommendation(
                    type: 'thin_content',
                    title: 'Improve Website Content Depth',
                    description: 'The website appears to contain limited indexable content.',
                    reason: 'Thin content can weaken search relevance and reduce conversion confidence.',
                    recommendedService: 'SEO Copywriting',
                    priorityScore: 65,
                    buyingProbability: 62,
                    estimatedValueMin: 20000,
                    estimatedValueMax: 75000,
                    evidence: [
                        'word_count' => $websiteAnalysis->word_count,
                        'seo_score' => $websiteAnalysis->seo_score,
                    ],
                    suggestedActions: [
                        'Rewrite service pages',
                        'Add industry-specific landing pages',
                        'Improve calls to action and trust content',
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