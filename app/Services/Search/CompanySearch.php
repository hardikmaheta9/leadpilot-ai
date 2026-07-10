<?php

namespace App\Services\Search;

use App\Models\Company;

class CompanySearch
{
    public function search(string $query): array
    {
        return Company::query()
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('company_name', 'like', "%{$query}%")
                    ->orWhere('legal_name', 'like', "%{$query}%")
                    ->orWhere('website', 'like', "%{$query}%")
                    ->orWhere('domain', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%")
                    ->orWhere('industry', 'like', "%{$query}%")
                    ->orWhere('city', 'like', "%{$query}%")
                    ->orWhere('state', 'like', "%{$query}%")
                    ->orWhere('country', 'like', "%{$query}%");
            })
            ->orderBy('company_name')
            ->limit(6)
            ->get()
            ->map(function (Company $company) {
                $location = collect([
                    $company->city,
                    $company->state,
                    $company->country,
                ])->filter()->join(', ');

                return [
                    'type' => 'company',
                    'title' => $company->company_name,
                    'subtitle' => collect([
                        $company->industry,
                        $location,
                    ])->filter()->join(' · '),
                    'url' => route('companies.show', $company->uuid),
                ];
            })
            ->values()
            ->all();
    }
}