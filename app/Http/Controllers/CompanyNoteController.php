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

        $validated = $request->validate([
                'note' => ['required', 'string'],
            ]);

            $validated['note'] = trim($validated['note']);

        CompanyNote::create([
            'company_uuid' => $company->uuid,
            'note' => $validated['note'],
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('companies.show', [$company->uuid, 'tab' => 'notes'])
            ->with('success', 'Note added successfully.');
    }

    public function update(
        Request $request,
        string $companyUuid,
        string $noteUuid
    ): RedirectResponse {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $note = CompanyNote::where('uuid', $noteUuid)
            ->where('company_uuid', $company->uuid)
            ->firstOrFail();

        $validated = $request->validate([
        'note' => ['required', 'string'],
            ]);

            $validated['note'] = trim($validated['note']);

        $note->update([
            'note' => $validated['note'],
        ]);

        return redirect()
            ->route('companies.show', [$company->uuid, 'tab' => 'notes'])
            ->with('success', 'Note updated successfully.');
    }

    public function destroy(
        string $companyUuid,
        string $noteUuid
    ): RedirectResponse {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $note = CompanyNote::where('uuid', $noteUuid)
            ->where('company_uuid', $company->uuid)
            ->firstOrFail();

        $note->delete();

        return redirect()
            ->route('companies.show', [$company->uuid, 'tab' => 'notes'])
            ->with('success', 'Note deleted successfully.');
    }
}