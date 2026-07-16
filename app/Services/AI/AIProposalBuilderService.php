<?php

namespace App\Services\AI;

use App\Models\Company;
use App\Models\CompanyAiProposal;
use App\Services\Documents\ProposalRenderer;
use App\Services\Documents\ProposalTemplateBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AIProposalBuilderService
{
    public function __construct(
        private ProposalTemplateBuilder $builder,
        private ProposalRenderer $renderer,
    ) {
    }

    public function generate(Company $company): CompanyAiProposal
    {
        return DB::transaction(function () use ($company): CompanyAiProposal {
            $company->loadMissing([
                'aiProfile',
                'websiteAnalysis',
                'aiRecommendations',
                'aiSalesConsultant',
                'contacts',
            ]);

            $proposals = CompanyAiProposal::query()
                ->forCompany($company->uuid)
                ->lockForUpdate()
                ->get();

            $nextVersion = ((int) $proposals->max('version')) + 1;

            CompanyAiProposal::query()
                ->forCompany($company->uuid)
                ->where('is_latest', true)
                ->update([
                    'is_latest' => false,
                    'proposal_status' => DB::raw(
                        "CASE WHEN proposal_status = 'draft' THEN 'archived' ELSE proposal_status END"
                    ),
                    'archived_at' => now(),
                ]);

            $consultant = $company->aiSalesConsultant;
            $recommendations = $company->aiRecommendations ?? collect();

            $proposalTitle = 'Enterprise Digital Transformation Proposal for '
                . $company->company_name;

            $executiveSummary = $consultant?->executive_summary
                ?: $this->fallbackExecutiveSummary($company);

            $scope = $this->buildScope($recommendations);
            $timeline = $this->buildTimeline($recommendations);
            $investment = $this->buildInvestment($recommendations);
            $roi = $this->buildRoi($company, $recommendations);

            $proposalData = array_merge(
                $this->builder->build($company),
                [
                    'proposalTitle' => $proposalTitle,
                    'proposalVersion' => $nextVersion,
                    'executiveSummary' => $executiveSummary,
                    'scope' => $scope,
                    'timeline' => $timeline,
                    'investment' => $investment,
                    'roi' => $roi,
                    'recommendations' => $recommendations,
                ]
            );

            return CompanyAiProposal::create([
                'company_uuid' => $company->uuid,
                'proposal_title' => $proposalTitle,
                'proposal_html' => $this->renderer->render($proposalData),
                'executive_summary' => $executiveSummary,
                'scope' => $scope,
                'timeline' => $timeline,
                'investment' => $investment,
                'roi' => $roi,
                'version' => $nextVersion,
                'is_latest' => true,
                'proposal_status' => 'draft',
                'public_token' => Str::random(64),
                'public_token_expires_at' => now()->addDays(
                    (int) config('proposals.public_link_days', 30)
                ),
                'view_count' => 0,
                'download_count' => 0,
                'generated_at' => now(),
            ]);
        });
    }

    private function buildScope(Collection $recommendations): string
    {
        if ($recommendations->isEmpty()) {
            return "• Discovery and requirements analysis\n"
                . "• Digital strategy recommendations\n"
                . "• Implementation planning";
        }

        return $recommendations->sortByDesc('priority_score')->take(8)
            ->map(fn ($item) => '• ' . ($item->recommended_service ?: $item->title))
            ->implode("\n");
    }

    private function buildTimeline(Collection $recommendations): string
    {
        $weeks = max(4, min($recommendations->count(), 6) * 2);

        return "Discovery and Planning — Week 1\n"
            . "Design and Solution Architecture — Week 2\n"
            . "Development and Configuration — Weeks 3–" . max(3, $weeks - 1) . "\n"
            . "Testing, Training and Deployment — Week {$weeks}";
    }

    private function buildInvestment(Collection $recommendations): string
    {
        if ($recommendations->isEmpty()) {
            return 'Discovery and Planning: To be finalized';
        }

        return $recommendations->sortByDesc('priority_score')->take(8)
            ->map(function ($item) {
                return ($item->recommended_service ?: $item->title)
                    . ': ₹' . number_format((int) ($item->estimated_value_min ?? 0))
                    . ' – ₹' . number_format((int) ($item->estimated_value_max ?? 0));
            })->implode("\n");
    }

    private function buildRoi(Company $company, Collection $recommendations): string
    {
        return "The proposed solution is expected to improve lead generation, customer engagement, "
            . "sales visibility and operational efficiency. Based on {$recommendations->count()} "
            . "identified opportunities, {$company->company_name} can benefit from a phased "
            . "implementation focused on the highest-impact services.";
    }

    private function fallbackExecutiveSummary(Company $company): string
    {
        return "{$company->company_name} has been identified as a strong digital transformation "
            . "opportunity. This proposal outlines the recommended services, delivery approach, "
            . "timeline and investment required to improve digital growth, customer engagement "
            . "and operational efficiency.";
    }
}
