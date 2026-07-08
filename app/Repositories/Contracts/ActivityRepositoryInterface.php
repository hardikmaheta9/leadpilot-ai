<?php

namespace App\Repositories\Contracts;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Collection;

interface ActivityRepositoryInterface
{
    public function create(array $data): Activity;

    public function recent(int $limit = 10): Collection;

    public function byModule(string $module, string $moduleUuid): Collection;
}