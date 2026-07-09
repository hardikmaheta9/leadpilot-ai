<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'company_uuid',
        'contact_uuid',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'assigned_to',
        'created_by',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($task) {
            if (empty($task->uuid)) {
                $task->uuid = (string) Str::uuid();
            }

            if (empty($task->created_by) && auth()->check()) {
                $task->created_by = auth()->id();
            }

            if (empty($task->assigned_to) && auth()->check()) {
                $task->assigned_to = auth()->id();
            }
        });
    }
}