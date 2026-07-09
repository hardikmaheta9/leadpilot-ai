<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Contact;

class SearchService
{
    public function search(string $query): array
{
    $companies = Company::query()
        ->where(function ($q) use ($query) {
            $q->where('company_name', 'like', "%{$query}%")
              ->orWhere('website', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%")
              ->orWhere('industry', 'like', "%{$query}%")
              ->orWhere('city', 'like', "%{$query}%")
              ->orWhere('country', 'like', "%{$query}%");
        })
        ->orderBy('company_name')
        ->limit(8)
        ->get([
            'uuid',
            'company_name',
            'industry',
            'city'
        ]);

    $contacts = Contact::query()
        ->where(function ($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
              ->orWhere('last_name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%")
              ->orWhere('phone', 'like', "%{$query}%")
              ->orWhere('designation', 'like', "%{$query}%");
        })
        ->orderBy('first_name')
        ->limit(8)
        ->get([
            'uuid',
            'company_uuid',
            'first_name',
            'last_name',
            'designation',
            'email'
        ]);

    return [
        'companies' => $companies,
        'contacts' => $contacts,
    ];
}
}