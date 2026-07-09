<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'company_uuid',
        'first_name',
        'last_name',
        'email',
        'phone',
        'designation',
        'department',
        'is_primary',
        'status',
        'notes',
        'created_by',
    ];

    protected static function booted(): void
    {
        static::creating(function ($contact) {
            if (empty($contact->uuid)) {
                $contact->uuid = (string) Str::uuid();
            }

            if (empty($contact->created_by) && auth()->check()) {
                $contact->created_by = auth()->id();
            }
        });
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}