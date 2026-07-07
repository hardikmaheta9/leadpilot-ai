<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Database\Eloquent\Collection;

class CompanyService
{
    public function __construct(
        protected CompanyRepository $companyRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->companyRepository->all();
    }

    public function getByUuid(string $uuid): ?Company
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