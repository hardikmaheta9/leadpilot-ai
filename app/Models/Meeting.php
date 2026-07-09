<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Meeting extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'uuid',

        'company_uuid',

        'contact_uuid',

        'title',

        'meeting_type',

        'meeting_date',

        'start_time',

        'end_time',

        'location',

        'agenda',

        'notes',

        'outcome',

        'status',

        'created_by'

    ];

    protected $casts = [

        'meeting_date'=>'date',

    ];

    protected static function booted()
    {
        static::creating(function ($meeting){

            if(!$meeting->uuid){

                $meeting->uuid=(string)Str::uuid();

            }

            if(!$meeting->created_by && auth()->check()){

                $meeting->created_by=auth()->id();

            }

        });
    }
}