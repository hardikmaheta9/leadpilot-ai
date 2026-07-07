<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'legal_name'   => ['nullable', 'string', 'max:255'],
            'website'      => ['nullable', 'url', 'max:255'],
            'domain'       => ['nullable', 'string', 'max:255'],
            'email'        => ['nullable', 'email', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:50'],
            'industry'     => ['nullable', 'string', 'max:100'],
            'company_size' => ['nullable', 'string', 'max:100'],
            'country'      => ['nullable', 'string', 'max:100'],
            'state'        => ['nullable', 'string', 'max:100'],
            'city'         => ['nullable', 'string', 'max:100'],
            'address'      => ['nullable', 'string'],
            'linkedin_url' => ['nullable', 'url'],
            'facebook_url' => ['nullable', 'url'],
            'twitter_url'  => ['nullable', 'url'],
            'source'       => ['nullable', 'string', 'max:100'],
            'notes'        => ['nullable', 'string'],
            'status'       => ['required', 'in:prospect,qualified,customer,inactive,blacklisted'],
        ];
    }
}