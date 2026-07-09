<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CompanyTaskController extends Controller
{
    public function store(Request $request, string $companyUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['required', 'in:low,medium,high'],
        ]);

        Task::create([
            ...$validated,
            'company_uuid' => $company->uuid,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('companies.show', [$company->uuid, 'tab' => 'tasks'])
            ->with('success', 'Task created successfully.');
    }
}