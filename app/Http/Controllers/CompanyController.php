<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Services\Contracts\CompanyServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyServiceInterface $companyService
    ) {}

    public function index(Request $request): View
    {
        $search = $request->get('search');

        $companies = $this->companyService->paginate(
            perPage: 15,
            search: $search
        );

        return view('companies.index', compact('companies', 'search'));
    }

    public function create(): View
    {
        return view('companies.create');
    }

    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $this->companyService->create($request->validated());

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company created successfully.');
    }

    public function show(string $uuid, \Illuminate\Http\Request $request): View
    {
            $company = $this->companyService->findByUuid($uuid);

            abort_if(! $company, 404);

            $activities = \App\Models\Activity::where('module', 'Company')
                ->where('module_uuid', $company->uuid)
                ->latest()
                ->take(10)
                ->get();

            $notes = \App\Models\CompanyNote::where('company_uuid', $company->uuid)
                ->latest()
                ->get();

            $contacts = \App\Models\Contact::where('company_uuid', $company->uuid)->latest()->get();

            $activeTab = $request->get('tab', 'overview');

            $tasks = $company->tasks;

            $documents = $company->documents;

            $meetings = $company->meetings;

            $calls = $company->callLogs;

            $aiProfile = $company->aiProfile;

            $websiteAnalysis = $company->websiteAnalysis;

            $aiSalesConsultant = $company->aiSalesConsultant;

            $recommendations = $company->aiRecommendations()
                ->orderByDesc('priority_score')
                ->orderByDesc('buying_probability')
                ->get();

            $company->setRelation(
                'aiRecommendations',
                $recommendations
            );

            $aiGeneratedContents = $company->aiGeneratedContents()
                ->orderBy('content_type')
                ->orderByDesc('version')
                ->get()
                ->groupBy('content_type')
                ->map(fn ($items) => $items->first());

            
            $aiProposals = $company->aiProposals()
                ->latest('version')
                ->get();
            
            return view('companies.show', compact(
                        'company',
                        'activities',
                        'notes',
                        'contacts',
                        'tasks',
                        'documents',
                        'meetings',
                        'activeTab',
                        'calls',
                        'aiProfile',
                        'websiteAnalysis',
                        'recommendations',
                        'aiSalesConsultant',
                        'aiGeneratedContents',
                        'aiProposals',
                    )
            );
    }

    public function edit(string $uuid): View
    {
        $company = $this->companyService->findByUuid($uuid);

        abort_if(! $company, 404);

        return view('companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, string $uuid): RedirectResponse
    {
        $company = $this->companyService->findByUuid($uuid);

        abort_if(! $company, 404);

        $this->companyService->update($company, $request->validated());

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company updated successfully.');
    }

    public function destroy(string $uuid): RedirectResponse
    {
        $company = $this->companyService->findByUuid($uuid);

        abort_if(! $company, 404);

        $this->companyService->delete($company);

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }
}