<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyWebsiteAnalysis extends Model
{
    use HasFactory;

    protected $table = 'company_website_analysis';

    protected $fillable = [

        'company_uuid',

        'website_url',
        'website_title',
        'meta_description',

        'cms',
        'framework',
        'programming_language',
        'server',

        'technologies',

        'ssl_enabled',
        'mobile_friendly',

        'has_blog',
        'has_contact_page',
        'has_about_page',
        'has_services_page',

        'images',
        'forms',
        'scripts',
        'stylesheets',
        'word_count',

        'seo_score',
        'performance_score',
        'website_score',

        'analyzed_at',

    ];

    protected $casts = [

        'technologies' => 'array',

        'ssl_enabled' => 'boolean',
        'mobile_friendly' => 'boolean',

        'has_blog' => 'boolean',
        'has_contact_page' => 'boolean',
        'has_about_page' => 'boolean',
        'has_services_page' => 'boolean',

        'analyzed_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function company()
    {
        return $this->belongsTo(
            Company::class,
            'company_uuid',
            'uuid'
        );
    }
}