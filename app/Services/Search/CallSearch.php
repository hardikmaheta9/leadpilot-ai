<?php

namespace App\Services\Search;

use App\Models\CallLog;

class CallSearch
{
    public function search(string $query): array
    {
        return CallLog::query()
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('subject', 'like', "%{$query}%")
                    ->orWhere('outcome', 'like', "%{$query}%")
                    ->orWhere('notes', 'like', "%{$query}%")
                    ->orWhere('call_type', 'like', "%{$query}%")
                    ->orWhere('status', 'like', "%{$query}%");
            })
            ->latest('call_date')
            ->limit(6)
            ->get()
            ->map(function (CallLog $call) {
                return [
                    'type' => 'call',
                    'title' => $call->subject,
                    'subtitle' => collect([
                        $call->call_type
                            ? ucfirst($call->call_type)
                            : null,
                        $call->status
                            ? ucfirst($call->status)
                            : null,
                        ($call->duration ?? 0) . ' min',
                    ])->filter()->join(' · '),
                    'url' => route('companies.show', [
                        $call->company_uuid,
                        'tab' => 'calls',
                    ]),
                ];
            })
            ->values()
            ->all();
    }
}