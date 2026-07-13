<?php

namespace App\Services\AI;

use App\Models\Company;
use App\Models\CompanyAiProfile;
use App\Services\AI\WebsiteAnalysisStorageService;
use RuntimeException;

class CompanyIntelligenceService
{
    public function __construct(
        private WebsiteCrawler $crawler,
        private WebsiteAnalyzer $analyzer,
        private TechnologyDetector $technologyDetector,
        private ContactExtractor $contactExtractor,
        private LeadScoringService $leadScoringService,
        private CompanyProfiler $companyProfiler,
        private WebsiteAnalysisStorageService $websiteStorage
    ) {
    }

    public function analyze(Company $company): array
    {
        if (empty($company->website)) {
            throw new RuntimeException(
                'The company does not have a website URL.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Step 1: Crawl Website
        |--------------------------------------------------------------------------
        */

        $crawlData = $this->crawler->crawl(
            $company->website
        );

        if (empty($crawlData['success'])) {
            throw new RuntimeException(
                $crawlData['error']
                    ?? 'Unable to crawl the company website.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Step 2: Analyze Website Structure
        |--------------------------------------------------------------------------
        */

        $analysis = $this->analyzer->analyze(
            $crawlData
        );

        if (empty($analysis['success'])) {
            throw new RuntimeException(
                $analysis['error']
                    ?? 'Unable to analyze the company website.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Step 3: Detect Technologies
        |--------------------------------------------------------------------------
        */

        $technologies = $this->technologyDetector
            ->detect($crawlData);

        /*
        |--------------------------------------------------------------------------
        | Step 4: Extract Contact Information
        |--------------------------------------------------------------------------
        */

        $contacts = $this->contactExtractor
            ->extract($crawlData);

         
        /*
        |--------------------------------------------------------------------------
        | Save Website Analysis
        |--------------------------------------------------------------------------
        */

        $this->websiteStorage->store(
            $company,
            $crawlData,
            $analysis,
            $technologies
        );    

        
        /*
        |--------------------------------------------------------------------------
        | Step 5: Calculate Lead Opportunity Score
        |--------------------------------------------------------------------------
        */

        $scoring = $this->leadScoringService
            ->calculate(
                $analysis,
                $technologies,
                $contacts
            );

        /*
        |--------------------------------------------------------------------------
        | Step 6: Build Basic Company Summary
        |--------------------------------------------------------------------------
        */

        $summary = $this->buildSummary(
            $company,
            $analysis
        );

        /*
        |--------------------------------------------------------------------------
        | Step 7: Save AI Profile
        |--------------------------------------------------------------------------
        */

        $profile = $this->companyProfiler
            ->createOrUpdateProfile(
                $company,
                [
                    'company_summary' => $summary,

                    'business_description' =>
                        $analysis['meta_description']
                            ?? null,

                    'industry' =>
                        $company->industry,

                    'headquarters' =>
                        collect([
                            $company->city,
                            $company->country,
                        ])->filter()->join(', '),

                    'lead_score' =>
                        $scoring['lead_score'],

                    'lead_grade' =>
                        $scoring['lead_grade'],

                    'confidence_score' =>
                        $this->calculateConfidence(
                            $analysis,
                            $technologies,
                            $contacts
                        ),
                ]
            );

        return [
            'success' => true,

            'company' => $company,

            'profile' => $profile,

            'crawl' => [
                'url' =>
                    $crawlData['final_url']
                        ?? $crawlData['url']
                        ?? null,

                'status_code' =>
                    $crawlData['status_code']
                        ?? null,

                'title' =>
                    $crawlData['title']
                        ?? null,

                'meta_description' =>
                    $crawlData['meta_description']
                        ?? null,
            ],

            'analysis' => $analysis,

            'technologies' => $technologies,

            'contacts' => $contacts,

            'scoring' => $scoring,
        ];
    }

    private function buildSummary(
        Company $company,
        array $analysis
    ): string {
        $parts = [];

        $parts[] =
            $company->company_name
            . ' is a company'
            . (
                $company->industry
                    ? ' operating in the '
                        . $company->industry
                        . ' industry'
                    : ''
            )
            . '.';

        if (!empty($analysis['meta_description'])) {
            $parts[] =
                trim(
                    $analysis['meta_description']
                );
        }

        if (!empty($company->city) || !empty($company->country)) {
            $location = collect([
                $company->city,
                $company->country,
            ])->filter()->join(', ');

            if ($location !== '') {
                $parts[] =
                    'The company is located in '
                    . $location
                    . '.';
            }
        }

        return implode(' ', $parts);
    }

    private function calculateConfidence(
        array $analysis,
        array $technologies,
        array $contacts
    ): int {
        $score = 0;

        if (!empty($analysis['title'])) {
            $score += 15;
        }

        if (!empty($analysis['meta_description'])) {
            $score += 15;
        }

        if (!empty($analysis['headings']['h1'] ?? [])) {
            $score += 15;
        }

        if (!empty($technologies['technologies'])) {
            $score += 20;
        }

        if (!empty($contacts['emails'])) {
            $score += 15;
        }

        if (!empty($contacts['phones'])) {
            $score += 10;
        }

        if (!empty($contacts['social_links'])) {
            $score += 10;
        }

        return min($score, 100);
    }
}