<section class="ep-page ep-cover">
    <div class="ep-page-content">
        <div class="ep-cover-top">
            <div class="ep-brand">
                <div class="ep-brand-mark">WI</div>
                <div class="ep-brand-text">
                    <strong>{{ $brand_name ?? 'WebApp Infoway' }}</strong>
                    <span>AI Digital Transformation Consultant</span>
                </div>
            </div>

            <div class="ep-cover-badges">
                <span class="ep-cover-badge">Confidential</span>
                <span class="ep-cover-badge">AI Generated</span>
            </div>
        </div>

        <div class="ep-cover-main">
            <span class="ep-cover-eyebrow">Strategic Business Proposal</span>

            <h1>{{ $proposalTitle ?? 'Enterprise Digital Transformation Proposal' }}</h1>

            <p class="ep-cover-subtitle">
                A tailored digital growth, technology and automation proposal prepared
                specifically for {{ $prepared_for ?? $company->company_name }}.
            </p>

            <div class="ep-cover-meta-grid">
                <div class="ep-cover-meta">
                    <small>Prepared For</small>
                    <strong>{{ $prepared_for ?? $company->company_name }}</strong>
                </div>

                <div class="ep-cover-meta">
                    <small>Prepared By</small>
                    <strong>{{ $brand_name ?? 'WebApp Infoway' }}</strong>
                </div>

                <div class="ep-cover-meta">
                    <small>Proposal Date</small>
                    <strong>{{ $generated_date ?? now()->format('d M Y') }}</strong>
                </div>

                <div class="ep-cover-meta">
                    <small>Proposal Version</small>
                    <strong>{{ $proposalVersion ?? 1 }}</strong>
                </div>
            </div>
        </div>
    </div>

    <footer class="ep-footer">
        <span>
            This document contains confidential recommendations prepared exclusively
            for {{ $prepared_for ?? $company->company_name }}.
        </span>
        <strong>01</strong>
    </footer>
</section>
