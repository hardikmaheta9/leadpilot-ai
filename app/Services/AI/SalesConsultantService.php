<?php

namespace App\Services\AI;

use App\Models\Company;
use App\Models\CompanyAiSalesConsultant;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SalesConsultantService
{
    public function generate(Company $company): CompanyAiSalesConsultant
    {
        $company->loadMissing([
            'aiProfile',
            'websiteAnalysis',
            'aiRecommendations',
            'contacts',
        ]);

        $profile = $company->aiProfile;
        $website = $company->websiteAnalysis;
        $recommendations = $company->aiRecommendations ?? collect();
        $contacts = $company->contacts ?? collect();

        $serviceBundle = $this->buildServiceBundle(
            $recommendations
        );

        $opportunityScore = $this->calculateOpportunityScore(
            $profile?->lead_score,
            $recommendations
        );

        $buyingProbability = $this->calculateBuyingProbability(
            $profile?->lead_score,
            $recommendations
        );

        $estimatedDealValue = $this->calculateEstimatedDealValue(
            $recommendations
        );

        return CompanyAiSalesConsultant::updateOrCreate(
            [
                'company_uuid' => $company->uuid,
            ],
            [
                'executive_summary' => $this->buildExecutiveSummary(
                    $company,
                    $profile,
                    $website,
                    $recommendations
                ),

                'business_overview' => $this->buildBusinessOverview(
                    $company,
                    $profile
                ),

                'digital_maturity' => $this->buildDigitalMaturity(
                    $website
                ),

                'pain_points' => $this->buildPainPoints(
                    $website,
                    $recommendations
                ),

                'opportunities' => $this->buildOpportunities(
                    $recommendations
                ),

                'recommended_services' => $this->buildRecommendedServices(
                    $serviceBundle
                ),

                'recommended_package' => $this->buildRecommendedPackage(
                    $company,
                    $serviceBundle,
                    $estimatedDealValue
                ),

                'decision_makers' => $this->buildDecisionMakers(
                    $company,
                    $contacts
                ),

                'sales_strategy' => $this->buildSalesStrategy(
                    $company,
                    $serviceBundle,
                    $recommendations
                ),

                'objection_handling' => $this->buildObjectionHandling(
                    $serviceBundle
                ),

                'next_best_action' => $this->buildNextBestAction(
                    $company,
                    $contacts,
                    $recommendations
                ),

                'opportunity_score' => $opportunityScore,
                'buying_probability' => $buyingProbability,
                'estimated_deal_value' => $estimatedDealValue,
                'service_bundle' => $serviceBundle,
                'generated_at' => now(),
            ]
        );
    }

    private function buildServiceBundle(
        Collection $recommendations
    ): array {
        return $recommendations
            ->sortByDesc('priority_score')
            ->pluck('recommended_service')
            ->filter()
            ->unique()
            ->take(6)
            ->values()
            ->all();
    }

    private function calculateOpportunityScore(
        ?int $leadScore,
        Collection $recommendations
    ): int {
        $averagePriority = $recommendations->count()
            ? (int) round($recommendations->avg('priority_score'))
            : 0;

        return min(
            100,
            (int) round(
                (($leadScore ?? 0) * 0.45)
                + ($averagePriority * 0.55)
            )
        );
    }

    private function calculateBuyingProbability(
        ?int $leadScore,
        Collection $recommendations
    ): int {
        $averageBuyingProbability = $recommendations->count()
            ? (int) round($recommendations->avg('buying_probability'))
            : 0;

        return min(
            100,
            (int) round(
                (($leadScore ?? 0) * 0.35)
                + ($averageBuyingProbability * 0.65)
            )
        );
    }

    private function calculateEstimatedDealValue(
        Collection $recommendations
    ): int {
        if ($recommendations->isEmpty()) {
            return 0;
        }

        return (int) round(
            $recommendations->sum(function ($recommendation) {
                $min = (int) ($recommendation->estimated_value_min ?? 0);
                $max = (int) ($recommendation->estimated_value_max ?? 0);

                return ($min + $max) / 2;
            })
        );
    }

    private function buildExecutiveSummary(
        Company $company,
        $profile,
        $website,
        Collection $recommendations
    ): string {
        $summary = $profile?->company_summary
            ?: "{$company->company_name} is a prospective company in the CRM.";

        $websiteScore = $website?->website_score ?? 0;
        $leadScore = $profile?->lead_score ?? 0;
        $opportunityCount = $recommendations->count();

        return "{$summary} LeadPilot detected {$opportunityCount} service "
            . "opportunities. The current lead score is {$leadScore}/100 "
            . "and the website score is {$websiteScore}/100.";
    }

    private function buildBusinessOverview(
        Company $company,
        $profile
    ): string {
        $industry = $profile?->industry
            ?: $company->industry
            ?: 'an unspecified industry';

        $location = collect([
            $company->city,
            $company->country,
        ])->filter()->join(', ');

        $text = "{$company->company_name} operates in {$industry}.";

        if ($location !== '') {
            $text .= " The company is located in {$location}.";
        }

        return $text;
    }

    private function buildDigitalMaturity($website): string
    {
        if (!$website) {
            return 'Digital maturity cannot be assessed until the website is analyzed.';
        }

        $level = match (true) {
            $website->website_score >= 80 => 'advanced',
            $website->website_score >= 60 => 'developing',
            $website->website_score >= 40 => 'basic',
            default => 'low',
        };

        return "The company currently has a {$level} digital maturity level. "
            . "Website score: {$website->website_score}/100, "
            . "SEO score: {$website->seo_score}/100, "
            . "performance score: {$website->performance_score}/100.";
    }

    private function buildPainPoints(
        $website,
        Collection $recommendations
    ): string {
        if ($recommendations->isEmpty()) {
            return 'No major pain points have been detected yet.';
        }

        return $recommendations
            ->sortByDesc('priority_score')
            ->pluck('reason')
            ->filter()
            ->unique()
            ->take(6)
            ->map(fn ($reason) => '• ' . $reason)
            ->implode("\n");
    }

    private function buildOpportunities(
        Collection $recommendations
    ): string {
        if ($recommendations->isEmpty()) {
            return 'No service opportunities have been generated yet.';
        }

        return $recommendations
            ->sortByDesc('priority_score')
            ->map(function ($recommendation) {
                return '• '
                    . $recommendation->title
                    . ' — '
                    . ($recommendation->recommended_service ?: 'Service opportunity');
            })
            ->take(8)
            ->implode("\n");
    }

    private function buildRecommendedServices(
        array $serviceBundle
    ): string {
        if (empty($serviceBundle)) {
            return 'No service bundle has been generated yet.';
        }

        return collect($serviceBundle)
            ->map(fn ($service) => '• ' . $service)
            ->implode("\n");
    }

    private function buildRecommendedPackage(
        Company $company,
        array $serviceBundle,
        int $estimatedDealValue
    ): string {
        if (empty($serviceBundle)) {
            return 'Analyze the company website to generate a recommended package.';
        }

        return "Recommended package for {$company->company_name}: "
            . implode(', ', $serviceBundle)
            . '. Estimated combined deal value: ₹'
            . number_format($estimatedDealValue)
            . '.';
    }

    private function buildDecisionMakers(
        Company $company,
        Collection $contacts
    ): string {
        $decisionMakers = $contacts
            ->filter(function ($contact) {
                $designation = Str::lower(
                    (string) ($contact->designation ?? '')
                );

                return Str::contains($designation, [
                    'owner',
                    'founder',
                    'director',
                    'ceo',
                    'cto',
                    'cio',
                    'manager',
                    'head',
                    'partner',
                    'president',
                ]);
            })
            ->map(function ($contact) {
                $name = trim(
                    ($contact->first_name ?? '')
                    . ' '
                    . ($contact->last_name ?? '')
                );

                return trim(
                    $name
                    . (
                        $contact->designation
                            ? " ({$contact->designation})"
                            : ''
                    )
                );
            })
            ->filter()
            ->values();

        if ($decisionMakers->isNotEmpty()) {
            return $decisionMakers
                ->map(fn ($item) => '• ' . $item)
                ->implode("\n");
        }

        return 'Target the owner, founder, director, operations head, marketing head, or technology decision-maker.';
    }

    private function buildSalesStrategy(
        Company $company,
        array $serviceBundle,
        Collection $recommendations
    ): string {
        $primaryService = $serviceBundle[0]
            ?? 'digital transformation services';

        $topReason = $recommendations
            ->sortByDesc('priority_score')
            ->first()?->reason;

        $strategy = "Lead with {$primaryService}. Position the offer around measurable business outcomes, not only technical features.";

        if ($topReason) {
            $strategy .= " Use this observed issue as the opening context: {$topReason}";
        }

        $strategy .= " Ask discovery questions before presenting the full service bundle.";

        return $strategy;
    }

    private function buildObjectionHandling(
        array $serviceBundle
    ): string {
        $package = empty($serviceBundle)
            ? 'the proposed solution'
            : implode(', ', array_slice($serviceBundle, 0, 3));

        return "If budget is the concern, offer a phased implementation starting with the highest-priority service. "
            . "If timing is the concern, propose a short discovery phase. "
            . "If the company already has vendors, position {$package} as an improvement or integration project rather than a replacement.";
    }

    private function buildNextBestAction(
        Company $company,
        Collection $contacts,
        Collection $recommendations
    ): string {
        if ($recommendations->isEmpty()) {
            return 'Run website analysis and generate recommendations.';
        }

        if ($contacts->isEmpty()) {
            return 'Find and add a decision-maker contact before outreach.';
        }

        $topRecommendation = $recommendations
            ->sortByDesc('priority_score')
            ->first();

        return 'Prepare a personalized outreach message focused on '
            . ($topRecommendation->recommended_service
                ?: $topRecommendation->title)
            . ' and schedule the first follow-up.';
    }
}