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

    public function complete(string $companyUuid, string $taskUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $task = Task::where('uuid', $taskUuid)
            ->where('company_uuid', $company->uuid)
            ->firstOrFail();

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return redirect()
            ->route('companies.show', [$company->uuid, 'tab' => 'tasks'])
            ->with('success', 'Task completed successfully.');
    }

    public function destroy(string $companyUuid, string $taskUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $task = Task::where('uuid', $taskUuid)
            ->where('company_uuid', $company->uuid)
            ->firstOrFail();

        $task->delete();

        return redirect()
            ->route('companies.show', [$company->uuid, 'tab' => 'tasks'])
            ->with('success', 'Task deleted successfully.');
    }

    public function update(Request $request, string $companyUuid, string $taskUuid): RedirectResponse
    {
        $company = Company::where('uuid', $companyUuid)->firstOrFail();

        $task = Task::where('uuid', $taskUuid)
            ->where('company_uuid', $company->uuid)
            ->firstOrFail();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'priority' => ['required', 'in:low,medium,high'],
        ]);

        $task->update($validated);

        return redirect()
            ->route('companies.show', [$company->uuid, 'tab' => 'tasks'])
            ->with('success', 'Task updated successfully.');
    }
}