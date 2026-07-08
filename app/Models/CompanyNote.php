<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CompanyNote extends Model
{
    protected $fillable = [
        'uuid',
        'company_uuid',
        'note',
        'created_by',
    ];

    protected static function booted(): void
    {
        static::creating(function ($note) {
            if (empty($note->uuid)) {
                $note->uuid = (string) Str::uuid();
            }

            if (empty($note->created_by) && auth()->check()) {
                $note->created_by = auth()->id();
            }
        });
    }
}