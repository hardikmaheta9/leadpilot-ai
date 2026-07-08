<div class="col-xl-3 col-lg-6 mb-4">

    <div class="lp-stat-card">

        <div class="d-flex justify-content-between align-items-start">

            <div>

                <div class="lp-stat-title">
                    {{ $title }}
                </div>

                <div class="lp-stat-number">
                    {{ $value }}
                </div>

                <div class="lp-stat-footer">

                    <span class="lp-stat-growth">

                        <i class="fa-solid fa-arrow-trend-up me-1"></i>

                        {{ $growth ?? '+0' }}

                    </span>

                </div>

            </div>

            <div class="lp-stat-icon">

                <i class="{{ $icon }}"></i>

            </div>

        </div>

        @isset($route)

            <div class="mt-4">

                <a href="{{ $route }}" class="lp-card-link">

                    View Details

                    <i class="fa-solid fa-arrow-right ms-1"></i>

                </a>

            </div>

        @endisset

    </div>

</div>