<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\AI\CompanyIntelligenceService;
use Illuminate\Http\RedirectResponse;
use Throwable;

class CompanyAiController extends Controller
{
    public function analyze(
        string $companyUuid,
        CompanyIntelligenceService $intelligenceService
    ): RedirectResponse {
        $company = Company::where('uuid', $companyUuid)
            ->firstOrFail();

        try {
            $intelligenceService->analyze($company);

            return redirect()
                ->route('companies.show', [
                    $company->uuid,
                    'tab' => 'ai',
                ])
                ->with(
                    'success',
                    'Company AI analysis completed successfully.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('companies.show', [
                    $company->uuid,
                    'tab' => 'ai',
                ])
                ->with(
                    'error',
                    'AI analysis failed: ' . $exception->getMessage()
                );
        }
    }
}