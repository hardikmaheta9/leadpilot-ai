<?php

namespace App\Observers;

use App\Models\Company;
use App\Services\ActivityService;

class CompanyObserver
{
    public function __construct(
        protected ActivityService $activityService
    ) {}

    /**
     * Handle the Company "created" event.
     */
    public function created(Company $company): void
    {
        $this->activityService->log(
            module: 'Company',
            moduleUuid: $company->uuid,
            action: 'created',
            description: "Company '{$company->company_name}' was created."
        );
    }

    /**
     * Handle the Company "updated" event.
     */
    public function updated(Company $company): void
    {
        $this->activityService->log(
            module: 'Company',
            moduleUuid: $company->uuid,
            action: 'updated',
            description: "Company '{$company->company_name}' was updated."
        );
    }

    /**
     * Handle the Company "deleted" event.
     */
    public function deleted(Company $company): void
    {
        $this->activityService->log(
            module: 'Company',
            moduleUuid: $company->uuid,
            action: 'deleted',
            description: "Company '{$company->company_name}' was deleted."
        );
    }

    public function restored(Company $company): void
    {
        //
    }

    public function forceDeleted(Company $company): void
    {
        //
    }
}