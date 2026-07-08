<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'module',
        'module_uuid',
        'action',
        'description',
        'performed_by',
    ];

    protected static function booted(): void
    {
        static::creating(function ($activity) {

            if (empty($activity->uuid)) {
                $activity->uuid = (string) Str::uuid();
            }

            if (empty($activity->performed_by) && auth()->check()) {
                $activity->performed_by = auth()->id();
            }

        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}