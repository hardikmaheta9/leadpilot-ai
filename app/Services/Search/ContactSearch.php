<?php

namespace App\Services\Search;

use App\Models\Contact;

class ContactSearch
{
    public function search(string $query): array
    {
        return Contact::query()
            ->where(function ($builder) use ($query) {
                $builder
                    ->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%")
                    ->orWhere('designation', 'like', "%{$query}%")
                    ->orWhere('department', 'like', "%{$query}%")
                    ->orWhere('notes', 'like', "%{$query}%");
            })
            ->orderBy('first_name')
            ->limit(6)
            ->get()
            ->map(function (Contact $contact) {
                $fullName = trim(
                    ($contact->first_name ?? '') . ' ' .
                    ($contact->last_name ?? '')
                );

                return [
                    'type' => 'contact',
                    'title' => $fullName ?: 'Unnamed Contact',
                    'subtitle' => collect([
                        $contact->designation,
                        $contact->department,
                        $contact->email,
                    ])->filter()->join(' · '),
                    'url' => route('companies.show', [
                        $contact->company_uuid,
                        'tab' => 'contacts',
                    ]),
                ];
            })
            ->values()
            ->all();
    }
}