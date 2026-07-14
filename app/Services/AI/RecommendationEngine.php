<?php

namespace App\Services\AI;

use App\Models\Company;
use App\Models\CompanyAiRecommendation;
use App\Models\CompanyWebsiteAnalysis;
use App\Services\AI\Engines\AutomationRecommendationEngine;
use App\Services\AI\Engines\ChatbotRecommendationEngine;
use App\Services\AI\Engines\CrmRecommendationEngine;
use App\Services\AI\Engines\DigitalMarketingRecommendationEngine;
use App\Services\AI\Engines\EcommerceRecommendationEngine;
use App\Services\AI\Engines\ErpRecommendationEngine;
use App\Services\AI\Engines\MobileAppRecommendationEngine;
use App\Services\AI\Engines\SeoRecommendationEngine;
use App\Services\AI\Engines\WebsiteRecommendationEngine;
use Illuminate\Support\Collection;

class RecommendationEngine
{
    public function __construct(
        private WebsiteRecommendationEngine $websiteEngine,
        private SeoRecommendationEngine $seoEngine,
        private CrmRecommendationEngine $crmEngine,
        private ErpRecommendationEngine $erpEngine,
        private MobileAppRecommendationEngine $mobileAppEngine,
        private EcommerceRecommendationEngine $ecommerceEngine,
        private ChatbotRecommendationEngine $chatbotEngine,
        private AutomationRecommendationEngine $automationEngine,
        private DigitalMarketingRecommendationEngine $digitalMarketingEngine,
    ) {
    }

    public function generate(
        Company $company,
        CompanyWebsiteAnalysis $websiteAnalysis
    ): Collection {
        $recommendations = collect();

        $recommendations = $recommendations->merge(
            $this->websiteEngine->analyze(
                $company,
                $websiteAnalysis
            )
        );

        $recommendations = $recommendations->merge(
            $this->seoEngine->analyze(
                $company,
                $websiteAnalysis
            )
        );

        $recommendations = $recommendations->merge(
            $this->crmEngine->analyze(
                $company,
                $websiteAnalysis
            )
        );

        $recommendations = $recommendations->merge(
            $this->erpEngine->analyze(
                $company,
                $websiteAnalysis
            )
        );

        $recommendations = $recommendations->merge(
            $this->mobileAppEngine->analyze(
                $company,
                $websiteAnalysis
            )
        );

        $recommendations = $recommendations->merge(
            $this->ecommerceEngine->analyze(
                $company,
                $websiteAnalysis
            )
        );

        $recommendations = $recommendations->merge(
            $this->chatbotEngine->analyze(
                $company,
                $websiteAnalysis
            )
        );

        $recommendations = $recommendations->merge(
            $this->automationEngine->analyze(
                $company,
                $websiteAnalysis
            )
        );

        $recommendations = $recommendations->merge(
            $this->digitalMarketingEngine->analyze(
                $company,
                $websiteAnalysis
            )
        );

        $recommendations = $recommendations
            ->filter(
                fn ($recommendation) =>
                    is_array($recommendation)
            )
            ->unique('type')
            ->sortByDesc('priority_score')
            ->values();

        return $this->saveRecommendations(
            $company,
            $recommendations
        );
    }

    private function saveRecommendations(
        Company $company,
        Collection $recommendations
    ): Collection {
        CompanyAiRecommendation::where(
            'company_uuid',
            $company->uuid
        )->delete();

        return $recommendations
            ->sortByDesc('priority_score')
            ->map(
                function (
                    array $recommendation
                ) use ($company) {
                    return CompanyAiRecommendation::create([
                        'company_uuid' => $company->uuid,
                        ...$recommendation,
                        'status' => 'new',
                        'generated_at' => now(),
                    ]);
                }
            )
            ->values();
    }
}