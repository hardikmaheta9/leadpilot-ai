<?php

namespace App\Services\AI;

use App\Models\Company;
use App\Models\CompanyWebsiteAnalysis;

class WebsiteAnalysisStorageService
{
    public function store(
        Company $company,
        array $crawlData,
        array $analysis,
        array $technologyData
    ): CompanyWebsiteAnalysis {
        return CompanyWebsiteAnalysis::updateOrCreate(
            [
                'company_uuid' => $company->uuid,
            ],
            [
                'website_url' =>
                    $crawlData['final_url']
                    ?? $crawlData['url']
                    ?? $company->website,

                'website_title' =>
                    $crawlData['title']
                    ?? $analysis['title']
                    ?? null,

                'meta_description' =>
                    $crawlData['meta_description']
                    ?? $analysis['meta_description']
                    ?? null,

                'cms' =>
                    $technologyData['cms']
                    ?? null,

                'framework' =>
                    $this->firstFramework(
                        $technologyData['frameworks']
                            ?? []
                    ),

                'programming_language' =>
                    $technologyData['programming_language']
                    ?? null,

                'server' =>
                    $technologyData['server']
                    ?? null,

                'technologies' =>
                    $technologyData['technologies']
                    ?? [],

                'ssl_enabled' =>
                    (bool) (
                        $analysis['has_ssl']
                        ?? false
                    ),

                'mobile_friendly' =>
                    (bool) (
                        $analysis['has_viewport_meta']
                        ?? false
                    ),

                'has_blog' =>
                    (bool) (
                        $analysis['has_blog']
                        ?? false
                    ),

                'has_contact_page' =>
                    (bool) (
                        $analysis['has_contact_page']
                        ?? false
                    ),

                'has_about_page' =>
                    (bool) (
                        $analysis['has_about_page']
                        ?? false
                    ),

                'has_services_page' =>
                    (bool) (
                        $analysis['has_services_page']
                        ?? false
                    ),

                'images' =>
                    (int) (
                        $analysis['images']
                        ?? 0
                    ),

                'forms' =>
                    (int) (
                        $analysis['forms']
                        ?? 0
                    ),

                'scripts' =>
                    (int) (
                        $analysis['scripts']
                        ?? 0
                    ),

                'stylesheets' =>
                    (int) (
                        $analysis['stylesheets']
                        ?? 0
                    ),

                'word_count' =>
                    (int) (
                        $analysis['word_count']
                        ?? 0
                    ),

                'seo_score' =>
                    $this->calculateSeoScore(
                        $analysis
                    ),

                'performance_score' =>
                    $this->calculatePerformanceScore(
                        $crawlData,
                        $analysis
                    ),

                'website_score' =>
                    $this->calculateWebsiteScore(
                        $analysis
                    ),

                'analyzed_at' => now(),
            ]
        );
    }

    private function firstFramework(
        array $frameworks
    ): ?string {
        return $frameworks[0] ?? null;
    }

    private function calculateSeoScore(
        array $analysis
    ): int {
        $score = 0;

        if (!empty($analysis['title'])) {
            $score += 20;
        }

        if (!empty($analysis['meta_description'])) {
            $score += 20;
        }

        if (!empty($analysis['headings']['h1'] ?? [])) {
            $score += 20;
        }

        if (!empty($analysis['has_blog'])) {
            $score += 10;
        }

        if (!empty($analysis['has_services_page'])) {
            $score += 10;
        }

        if (!empty($analysis['has_about_page'])) {
            $score += 10;
        }

        if (!empty($analysis['has_contact_page'])) {
            $score += 10;
        }

        return min($score, 100);
    }

    private function calculatePerformanceScore(
        array $crawlData,
        array $analysis
    ): int {
        $score = 100;

        $htmlLength = (int) (
            $crawlData['html_length']
            ?? 0
        );

        if ($htmlLength > 1000000) {
            $score -= 25;
        } elseif ($htmlLength > 500000) {
            $score -= 15;
        }

        if (
            (int) ($analysis['images'] ?? 0)
            > 50
        ) {
            $score -= 15;
        }

        if (
            (int) ($analysis['scripts'] ?? 0)
            > 30
        ) {
            $score -= 15;
        }

        if (
            (int) ($analysis['stylesheets'] ?? 0)
            > 20
        ) {
            $score -= 10;
        }

        return max($score, 0);
    }

    private function calculateWebsiteScore(
        array $analysis
    ): int {
        $score = 0;

        if (!empty($analysis['has_ssl'])) {
            $score += 20;
        }

        if (!empty($analysis['has_viewport_meta'])) {
            $score += 20;
        }

        if (!empty($analysis['meta_description'])) {
            $score += 15;
        }

        if (!empty($analysis['headings']['h1'] ?? [])) {
            $score += 15;
        }

        if (!empty($analysis['has_contact_page'])) {
            $score += 10;
        }

        if (!empty($analysis['has_services_page'])) {
            $score += 10;
        }

        if (!empty($analysis['has_about_page'])) {
            $score += 5;
        }

        if (!empty($analysis['has_blog'])) {
            $score += 5;
        }

        return min($score, 100);
    }
}