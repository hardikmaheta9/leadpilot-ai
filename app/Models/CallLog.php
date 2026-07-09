<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CallLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'company_uuid',
        'contact_uuid',
        'call_type',
        'subject',
        'call_date',
        'call_time',
        'duration',
        'notes',
        'outcome',
        'status',
        'created_by',
    ];

    protected $casts = [
        'call_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function ($callLog) {
            if (empty($callLog->uuid)) {
                $callLog->uuid = (string) Str::uuid();
            }

            if (empty($callLog->created_by) && auth()->check()) {
                $callLog->created_by = auth()->id();
            }
        });
    }
}