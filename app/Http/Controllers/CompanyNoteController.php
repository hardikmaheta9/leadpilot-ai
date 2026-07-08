<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CompanyNoteController extends Controller
{
    public function store(Request $request, string $companyUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $request->validate([
            'note' => ['required', 'string', 'max:5000'],
        ]);

        CompanyNote::create([
            'company_uuid' => $company->uuid,
            'note' => $request->note,
        ]);

        return redirect()
            ->route('companies.show', $company->uuid)
            ->with('success', 'Note added successfully.');
    }
}
