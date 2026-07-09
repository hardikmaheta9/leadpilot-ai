<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'uuid',

        'company_uuid',

        'contact_uuid',

        'title',

        'category',

        'original_filename',

        'stored_filename',

        'mime_type',

        'file_size',

        'disk',

        'path',

        'uploaded_by',

    ];

    protected static function booted()
    {
        static::creating(function ($document) {

            if (!$document->uuid) {
                $document->uuid = (string) Str::uuid();
            }

            if (!$document->uploaded_by && auth()->check()) {
                $document->uploaded_by = auth()->id();
            }

        });
    }
}