<?php

namespace App\Http\Controllers;

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

    public function index(): View
    {
        $companies = $this->companyService->paginate();

        return view('companies.index', compact('companies'));
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

    public function show(string $uuid): View
    {
        $company = $this->companyService->findByUuid($uuid);

        abort_if(! $company, 404);

        return view('companies.show', compact('company'));
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