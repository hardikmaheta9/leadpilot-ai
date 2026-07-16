<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyAiProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_uuid',
        'proposal_title',
        'proposal_html',
        'executive_summary',
        'scope',
        'timeline',
        'investment',
        'roi',
        'version',
        'generated_at',
        'is_latest',
        'proposal_status',
        'public_token',
        'public_token_expires_at',
        'pdf_path',
        'docx_path',
        'email_sent_to',
        'email_sent_by',
        'sent_at',
        'delivered_at',
        'viewed_at',
        'last_viewed_at',
        'view_count',
        'downloaded_at',
        'download_count',
        'accepted_at',
        'rejected_at',
        'change_requested_at',
        'client_response_note',
        'archived_at',
        'expires_at',
    ];

    protected $casts = [
        'version' => 'integer',
        'generated_at' => 'datetime',
        'is_latest' => 'boolean',
        'public_token_expires_at' => 'datetime',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'viewed_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'view_count' => 'integer',
        'downloaded_at' => 'datetime',
        'download_count' => 'integer',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'change_requested_at' => 'datetime',
        'archived_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_uuid', 'uuid');
    }

    public function scopeLatestVersion(Builder $query): Builder
    {
        return $query->where('is_latest', true);
    }

    public function scopeForCompany(Builder $query, string $companyUuid): Builder
    {
        return $query->where('company_uuid', $companyUuid);
    }

    public function hasValidPublicLink(): bool
    {
        return filled($this->public_token)
            && (!$this->public_token_expires_at || now()->lte($this->public_token_expires_at));
    }

    public function isTerminal(): bool
    {
        return in_array($this->proposal_status, ['accepted', 'rejected', 'expired'], true);
    }
}
