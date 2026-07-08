<div class="lp-company-header">

    <div class="row align-items-center">

        <div class="col-lg-8">

            <div class="mb-2">

                <a href="{{ route('companies.index') }}"
                   class="text-decoration-none text-muted">

                    <i class="fa-solid fa-arrow-left me-2"></i>

                    Back to Companies

                </a>

            </div>

            <div class="d-flex align-items-center mb-3">

                <div class="lp-company-avatar me-3">

                    <i class="fa-solid fa-building"></i>

                </div>

                <div>

                    <h2 class="mb-1">

                        {{ $company->company_name }}

                    </h2>

                    <p class="text-muted mb-0">

                        {{ $company->industry ?: 'Industry not specified' }}

                    </p>

                </div>

            </div>

            <div class="d-flex flex-wrap gap-2">

                @if($company->website)

                    <a href="{{ $company->website }}"
                       target="_blank"
                       class="btn btn-light btn-sm">

                        <i class="fa-solid fa-globe me-1"></i>

                        Website

                    </a>

                @endif

                @if($company->email)

                    <a href="mailto:{{ $company->email }}"
                       class="btn btn-light btn-sm">

                        <i class="fa-solid fa-envelope me-1"></i>

                        Email

                    </a>

                @endif

                @if($company->phone)

                    <a href="tel:{{ $company->phone }}"
                       class="btn btn-light btn-sm">

                        <i class="fa-solid fa-phone me-1"></i>

                        Phone

                    </a>

                @endif

                @if($company->linkedin_url)

                    <a href="{{ $company->linkedin_url }}"
                       target="_blank"
                       class="btn btn-light btn-sm">

                        <i class="fa-brands fa-linkedin me-1"></i>

                        LinkedIn

                    </a>

                @endif

            </div>

        </div>

        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

            <div class="mb-3">

                <x-feedback.status-badge :status="$company->status"/>

            </div>

            <a href="{{ route('companies.edit', $company->uuid) }}"
               class="btn btn-primary">

                <i class="fa-solid fa-pen me-1"></i>

                Edit Company

            </a>

        </div>

    </div>

</div>