<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Services\Contracts\CompanyServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CompanyService implements CompanyServiceInterface
{
    public function __construct(
        protected CompanyRepositoryInterface $companyRepository
    ) {
    }

   public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        return $this->companyRepository->paginate($perPage, $search);
    }

    public function findByUuid(string $uuid): ?Company
    {
        return $this->companyRepository->findByUuid($uuid);
    }

    public function create(array $data): Company
    {
        return $this->companyRepository->create($data);
    }

    public function update(Company $company, array $data): bool
    {
        return $this->companyRepository->update($company, $data);
    }

    public function delete(Company $company): bool
    {
        return $this->companyRepository->delete($company);
    }
}