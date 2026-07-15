<?php

namespace App\Models;
use App\Models\Task;
use App\Models\Document;
use App\Models\Meeting;
use App\Models\CallLog;
use App\Models\Contact;
use App\Models\CompanyAiGeneratedContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'company_name',
        'legal_name',
        'website',
        'domain',
        'email',
        'phone',
        'industry',
        'company_size',
        'country',
        'state',
        'city',
        'address',
        'linkedin_url',
        'facebook_url',
        'twitter_url',
        'source',
        'status',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function booted(): void
    {
        static::creating(function ($company) {
            if (empty($company->uuid)) {
                $company->uuid = (string) Str::uuid();
            }

            if (empty($company->created_by) && auth()->check()) {
                $company->created_by = auth()->id();
            }
        });

        static::updating(function ($company) {
            if (auth()->check()) {
                $company->updated_by = auth()->id();
            }
        });
    }

    public function contacts()
    {
       return $this->hasMany(
        Contact::class,
        'company_uuid',
        'uuid'
     );
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'company_uuid', 'uuid')
            ->latest();
    }

    public function documents()
    {
        return $this->hasMany(Document::class,'company_uuid','uuid')
            ->latest();
    }

    public function meetings()
    {
        return $this->hasMany(
            Meeting::class,
            'company_uuid',
            'uuid'
        )->latest('meeting_date');
    }


    public function callLogs()
    {
        return $this->hasMany(CallLog::class, 'company_uuid', 'uuid')
            ->latest('call_date');
    }


        /*
    |--------------------------------------------------------------------------
    | AI Profile
    |--------------------------------------------------------------------------
    */

    public function aiProfile()
    {
        return $this->hasOne(
            CompanyAiProfile::class,
            'company_uuid',
            'uuid'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Website Analysis
    |--------------------------------------------------------------------------
    */

    public function websiteAnalysis()
    {
        return $this->hasOne(
            CompanyWebsiteAnalysis::class,
            'company_uuid',
            'uuid'
        );
    }


    public function aiRecommendations()
    {
    return $this->hasMany(
        CompanyAiRecommendation::class,
        'company_uuid',
        'uuid'
    );
    }

    public function aiSalesConsultant()
    {
    return $this->hasOne(
        CompanyAiSalesConsultant::class,
        'company_uuid',
        'uuid'
    );
    }

    public function aiGeneratedContents()
    {
        return $this->hasMany(

            CompanyAiGeneratedContent::class,

            'company_uuid',

            'uuid'

        );
    }

    
}

