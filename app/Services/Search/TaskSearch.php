<?php

namespace App\Services\Search;

use App\Models\Task;

class TaskSearch
{
    public function search(string $query): array
    {
        return Task::query()
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('priority', 'like', "%{$query}%")
                    ->orWhere('status', 'like', "%{$query}%");
            })
            ->latest()
            ->limit(6)
            ->get()
            ->map(function (Task $task) {
                return [
                    'type' => 'task',
                    'title' => $task->title,
                    'subtitle' => collect([
                        ucfirst(str_replace('_', ' ', $task->status)),
                        ucfirst($task->priority ?? 'medium') . ' priority',
                        $task->due_date
                            ? 'Due ' . $task->due_date->format('d M Y')
                            : null,
                    ])->filter()->join(' · '),
                    'url' => route('companies.show', [
                        $task->company_uuid,
                        'tab' => 'tasks',
                    ]),
                ];
            })
            ->values()
            ->all();
    }
}