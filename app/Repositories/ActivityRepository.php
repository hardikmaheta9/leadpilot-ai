<?php

namespace App\Repositories;

use App\Models\Activity;
use App\Repositories\Contracts\ActivityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function create(array $data): Activity
    {
        return Activity::create($data);
    }

    public function recent(int $limit = 10): Collection
    {
        return Activity::latest()->take($limit)->get();
    }

    public function byModule(string $module, string $moduleUuid): Collection
    {
        return Activity::where('module', $module)
            ->where('module_uuid', $moduleUuid)
            ->latest()
            ->get();
    }
}