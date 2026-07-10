<?php

namespace App\Services\Search;

use App\Models\CompanyNote;
use Illuminate\Support\Str;

class NoteSearch
{
    public function search(string $query): array
    {
        return CompanyNote::query()
            ->where('note', 'like', "%{$query}%")
            ->latest()
            ->limit(6)
            ->get()
            ->map(function (CompanyNote $note) {
                return [
                    'type' => 'note',
                    'title' => 'Company Note',
                    'subtitle' => Str::limit(
                        trim($note->note),
                        100
                    ),
                    'url' => route('companies.show', [
                        $note->company_uuid,
                        'tab' => 'notes',
                    ]),
                ];
            })
            ->values()
            ->all();
    }
}