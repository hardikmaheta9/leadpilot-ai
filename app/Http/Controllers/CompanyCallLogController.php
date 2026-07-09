<?php

namespace App\Http\Controllers;

use App\Models\CallLog;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CompanyCallLogController extends Controller
{
    public function store(Request $request, string $companyUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $validated = $request->validate([
            'subject' => ['required','string','max:255'],
            'call_type' => ['required','in:incoming,outgoing'],
            'call_date' => ['required','date'],
            'call_time' => ['required'],
            'duration' => ['nullable','integer','min:0'],
            'notes' => ['nullable','string'],
            'outcome' => ['nullable','string'],
        ]);

        CallLog::create([
            ...$validated,
            'company_uuid' => $company->uuid,
            'status' => 'completed',
        ]);

        return redirect()
            ->route('companies.show', [$company->uuid,'tab'=>'calls'])
            ->with('success','Call log added successfully.');
    }

    public function update(Request $request, string $companyUuid, string $callUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $call = CallLog::where('uuid', $callUuid)
            ->where('company_uuid', $company->uuid)
            ->firstOrFail();

        $validated = $request->validate([
            'subject' => ['required','string','max:255'],
            'call_type' => ['required','in:incoming,outgoing'],
            'call_date' => ['required','date'],
            'call_time' => ['required'],
            'duration' => ['nullable','integer','min:0'],
            'notes' => ['nullable','string'],
            'outcome' => ['nullable','string'],
        ]);

        $call->update($validated);

        return redirect()
            ->route('companies.show', [$company->uuid,'tab'=>'calls'])
            ->with('success','Call updated successfully.');
    }

    public function destroy(string $companyUuid, string $callUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $call = CallLog::where('uuid', $callUuid)
            ->where('company_uuid', $company->uuid)
            ->firstOrFail();

        $call->delete();

        return redirect()
            ->route('companies.show', [$company->uuid,'tab'=>'calls'])
            ->with('success','Call deleted successfully.');
    }
}