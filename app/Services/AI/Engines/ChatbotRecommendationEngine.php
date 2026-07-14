<?php

namespace App\Services\AI\Engines;

use App\Models\Company;
use App\Models\CompanyWebsiteAnalysis;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ChatbotRecommendationEngine
{
    public function analyze(
        Company $company,
        CompanyWebsiteAnalysis $websiteAnalysis
    ): Collection {
        $recommendations = collect();

        $industry = Str::lower((string) $company->industry);

        $chatbotIndustries = [
            'healthcare',
            'hospital',
            'clinic',
            'education',
            'school',
            'college',
            'university',
            'real estate',
            'travel',
            'hotel',
            'hospitality',
            'ecommerce',
            'e-commerce',
            'retail',
            'finance',
            'insurance',
            'consulting',
            'software',
            'technology',
            'services',
        ];

        $industrySignal = collect($chatbotIndustries)
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

        $chatbotDetected = $technologyList->contains(
            fn ($technology) =>
                Str::contains(
                    $technology,
                    [
                        'intercom',
                        'drift',
                        'tawk',
                        'tidio',
                        'zendesk',
                        'freshchat',
                        'hubspot chat',
                        'chatbot',
                        'livechat',
                    ]
                )
        );

        if ($chatbotDetected) {
            return $recommendations;
        }

        $hasCustomerInteractionSignal =
            $industrySignal ||
            (int) $websiteAnalysis->forms > 0 ||
            (bool) $websiteAnalysis->has_contact_page;

        if (!$hasCustomerInteractionSignal) {
            return $recommendations;
        }

        $priorityScore = 70;
        $buyingProbability = 62;

        if ($industrySignal) {
            $priorityScore += 8;
            $buyingProbability += 7;
        }

        if ((int) $websiteAnalysis->forms === 0) {
            $priorityScore += 5;
        }

        if ($websiteAnalysis->website_score < 60) {
            $priorityScore += 4;
        }

        $recommendations->push(
            $this->makeRecommendation(
                type: 'ai_chatbot',
                title: 'AI Chatbot Opportunity',
                description: 'The website may benefit from an AI-powered assistant for enquiries, qualification and customer support.',
                reason: 'A chatbot can answer common questions, capture leads outside business hours and reduce manual response time.',
                recommendedService: 'AI Chatbot Development & Integration',
                priorityScore: min($priorityScore, 100),
                buyingProbability: min($buyingProbability, 100),
                estimatedValueMin: 75000,
                estimatedValueMax: 500000,
                evidence: [
                    'industry' =>
                        $company->industry ?: 'Not specified',

                    'customer_interaction_signal' =>
                        $hasCustomerInteractionSignal,

                    'chatbot_detected' => false,

                    'forms' =>
                        (int) $websiteAnalysis->forms,

                    'has_contact_page' =>
                        (bool) $websiteAnalysis->has_contact_page,

                    'website_score' =>
                        (int) $websiteAnalysis->website_score,
                ],
                suggestedActions: [
                    'Identify common customer questions',
                    'Offer lead qualification and enquiry capture',
                    'Connect the chatbot with CRM',
                    'Add appointment or demo booking',
                    'Propose multilingual and after-hours support',
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