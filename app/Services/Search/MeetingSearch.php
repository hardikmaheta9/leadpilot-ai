<?php

namespace App\Services\Search;

use App\Models\Meeting;
use Carbon\Carbon;

class MeetingSearch
{
    public function search(string $query): array
    {
        return Meeting::query()
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('title', 'like', "%{$query}%")
                    ->orWhere('agenda', 'like', "%{$query}%")
                    ->orWhere('notes', 'like', "%{$query}%")
                    ->orWhere('outcome', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%")
                    ->orWhere('meeting_type', 'like', "%{$query}%")
                    ->orWhere('status', 'like', "%{$query}%");
            })
            ->latest('meeting_date')
            ->limit(6)
            ->get()
            ->map(function (Meeting $meeting) {
                return [
                    'type' => 'meeting',
                    'title' => $meeting->title,
                    'subtitle' => collect([
                        $meeting->meeting_date
                            ? $meeting->meeting_date->format('d M Y')
                            : null,
                        $meeting->start_time
                            ? Carbon::parse($meeting->start_time)->format('h:i A')
                            : null,
                        $meeting->meeting_type
                            ? ucwords(str_replace('_', ' ', $meeting->meeting_type))
                            : null,
                    ])->filter()->join(' · '),
                    'url' => route('companies.show', [
                        $meeting->company_uuid,
                        'tab' => 'meetings',
                    ]),
                ];
            })
            ->values()
            ->all();
    }
}