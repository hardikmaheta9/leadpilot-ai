<?php

namespace App\Services\Search;

use App\Models\Document;

class DocumentSearch
{
    public function search(string $query): array
    {
        return Document::query()
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('title', 'like', "%{$query}%")
                    ->orWhere('category', 'like', "%{$query}%")
                    ->orWhere('original_filename', 'like', "%{$query}%")
                    ->orWhere('mime_type', 'like', "%{$query}%");
            })
            ->latest()
            ->limit(6)
            ->get()
            ->map(function (Document $document) {
                return [
                    'type' => 'document',
                    'title' => $document->title,
                    'subtitle' => collect([
                        $document->category,
                        $document->original_filename,
                    ])->filter()->join(' · '),
                    'url' => route('companies.show', [
                        $document->company_uuid,
                        'tab' => 'documents',
                    ]),
                ];
            })
            ->values()
            ->all();
    }
}