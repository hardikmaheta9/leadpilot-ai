<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAiGeneratedContent extends Model
{
    use HasFactory;

    protected $fillable = [

        'company_uuid',

        'content_type',

        'title',

        'subject',

        'content',

        'generator',

        'language',

        'version',

        'regenerated_count',

        'generated_at',

    ];

    protected $casts = [

        'generated_at' => 'datetime',

        'version' => 'integer',

        'regenerated_count' => 'integer',

    ];

    public function company()
    {
        return $this->belongsTo(

            Company::class,

            'company_uuid',

            'uuid'

        );
    }
}