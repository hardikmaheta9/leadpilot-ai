<?php

namespace App\Services;

use App\Services\Search\CallSearch;
use App\Services\Search\CompanySearch;
use App\Services\Search\ContactSearch;
use App\Services\Search\DocumentSearch;
use App\Services\Search\MeetingSearch;
use App\Services\Search\NoteSearch;
use App\Services\Search\TaskSearch;

class SearchService
{
    public function search(string $query): array
    {
        $query = trim($query);

        if ($query === '') {
            return [];
        }

        return [
            'companies' => app(CompanySearch::class)->search($query),
            'contacts' => app(ContactSearch::class)->search($query),
            'tasks' => app(TaskSearch::class)->search($query),
            'meetings' => app(MeetingSearch::class)->search($query),
            'calls' => app(CallSearch::class)->search($query),
            'notes' => app(NoteSearch::class)->search($query),
            'documents' => app(DocumentSearch::class)->search($query),
        ];
    }
}