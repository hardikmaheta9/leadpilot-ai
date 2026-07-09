<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CompanyMeetingController extends Controller
{
    public function store(Request $request, string $companyUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'meeting_type' => ['required'],
            'meeting_date' => ['required','date'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'location' => ['nullable','string','max:255'],
            'agenda' => ['nullable','string'],
        ]);

        Meeting::create([
            ...$validated,
            'company_uuid' => $company->uuid,
            'status' => 'scheduled',
        ]);

        return redirect()
            ->route('companies.show', [$company->uuid,'tab'=>'meetings'])
            ->with('success','Meeting scheduled successfully.');
    }

    public function update(Request $request,string $companyUuid,string $meetingUuid): RedirectResponse
    {
        $company = Company::where('uuid',$companyUuid)->firstOrFail();

        $meeting = Meeting::where('uuid',$meetingUuid)
            ->where('company_uuid',$company->uuid)
            ->firstOrFail();

        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'meeting_type' => ['required'],
            'meeting_date' => ['required','date'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'location' => ['nullable','string','max:255'],
            'agenda' => ['nullable','string'],
        ]);

        $meeting->update($validated);

        return redirect()
            ->route('companies.show', [$company->uuid,'tab'=>'meetings'])
            ->with('success','Meeting updated successfully.');
    }

    public function complete(string $companyUuid,string $meetingUuid): RedirectResponse
    {
        $company = Company::where('uuid',$companyUuid)->firstOrFail();

        $meeting = Meeting::where('uuid',$meetingUuid)
            ->where('company_uuid',$company->uuid)
            ->firstOrFail();

        $meeting->update([
            'status'=>'completed'
        ]);

        return redirect()
            ->route('companies.show', [$company->uuid,'tab'=>'meetings'])
            ->with('success','Meeting marked as completed.');
    }

    public function destroy(string $companyUuid,string $meetingUuid): RedirectResponse
    {
        $company = Company::where('uuid',$companyUuid)->firstOrFail();

        $meeting = Meeting::where('uuid',$meetingUuid)
            ->where('company_uuid',$company->uuid)
            ->firstOrFail();

        $meeting->delete();

        return redirect()
            ->route('companies.show', [$company->uuid,'tab'=>'meetings'])
            ->with('success','Meeting deleted successfully.');
    }
}