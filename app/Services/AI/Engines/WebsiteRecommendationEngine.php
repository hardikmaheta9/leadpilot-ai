<?php

namespace App\Services\AI\Engines;

use App\Models\Company;
use App\Models\CompanyWebsiteAnalysis;
use Illuminate\Support\Collection;

class WebsiteRecommendationEngine
{
    public function analyze(
        Company $company,
        CompanyWebsiteAnalysis $websiteAnalysis
    ): Collection {
        $recommendations = collect();

        if (!$websiteAnalysis->ssl_enabled) {
            $recommendations->push(
                $this->makeRecommendation(
                    type: 'security',
                    title: 'Enable Website Security',
                    description: 'The website is not using HTTPS.',
                    reason: 'Visitors may see security warnings and trust may be reduced.',
                    recommendedService: 'SSL & Website Security',
                    priorityScore: 95,
                    buyingProbability: 80,
                    estimatedValueMin: 10000,
                    estimatedValueMax: 30000,
                    evidence: [
                        'ssl_enabled' => false,
                    ],
                    suggestedActions: [
                        'Offer SSL installation',
                        'Offer a website security audit',
                        'Explain the trust and SEO benefits of HTTPS',
                    ]
                )
            );
        }

        if (!$websiteAnalysis->mobile_friendly) {
            $recommendations->push(
                $this->makeRecommendation(
                    type: 'mobile_optimization',
                    title: 'Improve Mobile Experience',
                    description: 'The website may not be optimized for mobile devices.',
                    reason: 'A poor mobile experience can reduce enquiries and conversions.',
                    recommendedService: 'Responsive Website Redesign',
                    priorityScore: 90,
                    buyingProbability: 78,
                    estimatedValueMin: 40000,
                    estimatedValueMax: 120000,
                    evidence: [
                        'mobile_friendly' => false,
                    ],
                    suggestedActions: [
                        'Offer a responsive redesign',
                        'Show mobile usability improvements',
                        'Recommend conversion-focused mobile layouts',
                    ]
                )
            );
        }

        if ($websiteAnalysis->website_score < 55) {
            $recommendations->push(
                $this->makeRecommendation(
                    type: 'website_redesign',
                    title: 'Website Redesign Opportunity',
                    description: 'The overall website score is below the recommended level.',
                    reason: 'The website may appear outdated or underperform technically.',
                    recommendedService: 'Website Design & Development',
                    priorityScore: 92,
                    buyingProbability: 82,
                    estimatedValueMin: 75000,
                    estimatedValueMax: 250000,
                    evidence: [
                        'website_score' => $websiteAnalysis->website_score,
                    ],
                    suggestedActions: [
                        'Prepare a website redesign proposal',
                        'Show a before-and-after concept',
                        'Offer performance and conversion improvements',
                    ]
                )
            );
        }

        if (
            !$websiteAnalysis->has_contact_page ||
            $websiteAnalysis->forms === 0
        ) {
            $recommendations->push(
                $this->makeRecommendation(
                    type: 'lead_capture',
                    title: 'Improve Lead Capture',
                    description: 'The website has limited lead capture capability.',
                    reason: 'Missing forms or contact pages can reduce incoming enquiries.',
                    recommendedService: 'Lead Capture & CRM Integration',
                    priorityScore: 80,
                    buyingProbability: 72,
                    estimatedValueMin: 30000,
                    estimatedValueMax: 100000,
                    evidence: [
                        'has_contact_page' => $websiteAnalysis->has_contact_page,
                        'forms' => $websiteAnalysis->forms,
                    ],
                    suggestedActions: [
                        'Add enquiry and quotation forms',
                        'Connect forms to CRM',
                        'Add automated follow-up workflows',
                    ]
                )
            );
        }

        if ($websiteAnalysis->performance_score < 60) {
            $recommendations->push(
                $this->makeRecommendation(
                    type: 'performance',
                    title: 'Website Performance Optimization',
                    description: 'The website performance score is low.',
                    reason: 'Slow websites can reduce conversions and search visibility.',
                    recommendedService: 'Website Performance Optimization',
                    priorityScore: 75,
                    buyingProbability: 68,
                    estimatedValueMin: 20000,
                    estimatedValueMax: 80000,
                    evidence: [
                        'performance_score' => $websiteAnalysis->performance_score,
                    ],
                    suggestedActions: [
                        'Offer a speed audit',
                        'Optimize images and scripts',
                        'Improve caching and hosting setup',
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