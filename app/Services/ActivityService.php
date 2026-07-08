<?php

namespace App\Services;

use App\Models\Activity;
use App\Repositories\Contracts\ActivityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ActivityService
{
    public function __construct(
        protected ActivityRepositoryInterface $activityRepository
    ) {}

    public function log(string $module, string $moduleUuid, string $action, ?string $description = null): Activity
    {
        return $this->activityRepository->create([
            'module' => $module,
            'module_uuid' => $moduleUuid,
            'action' => $action,
            'description' => $description,
        ]);
    }

    public function recent(int $limit = 10): Collection
    {
        return $this->activityRepository->recent($limit);
    }

    public function byModule(string $module, string $moduleUuid): Collection
    {
        return $this->activityRepository->byModule($module, $moduleUuid);
    }
}