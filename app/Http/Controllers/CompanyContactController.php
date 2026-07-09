<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CompanyContactController extends Controller
{
    public function store(Request $request, string $companyUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'designation' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        Contact::create([
            ...$validated,
            'company_uuid' => $company->uuid,
        ]);

        return redirect()
            ->route('companies.show', [$company->uuid, 'tab' => 'contacts'])
            ->with('success', 'Contact added successfully.');
    }

    public function update(Request $request, string $companyUuid, string $contactUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $contact = Contact::where('uuid', $contactUuid)
            ->where('company_uuid', $company->uuid)
            ->firstOrFail();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'designation' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $contact->update($validated);

        return redirect()
            ->route('companies.show', [$company->uuid, 'tab' => 'contacts'])
            ->with('success', 'Contact updated successfully.');
    }

    public function destroy(string $companyUuid, string $contactUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $contact = Contact::where('uuid', $contactUuid)
            ->where('company_uuid', $company->uuid)
            ->firstOrFail();

        $contact->delete();

        return redirect()
            ->route('companies.show', [$company->uuid, 'tab' => 'contacts'])
            ->with('success', 'Contact deleted successfully.');
    }
}