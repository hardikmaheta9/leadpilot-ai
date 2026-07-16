<section class="ep-page">
    <div class="ep-page-content">
        <header class="ep-section-header">
            <div>
                <span class="ep-section-eyebrow">Company Analysis</span>
                <h2>Business Context & Digital Maturity</h2>
                <p>Core business profile, maturity level and strategic context behind the recommendations.</p>
            </div>
            <span class="ep-section-badge">03</span>
        </header>

        <div class="ep-grid-2">
            <div class="ep-card">
                <div class="ep-card-header">
                    <h3>Company Profile</h3>
                    <p>Current business details</p>
                </div>
                <div class="ep-card-body">
                    <dl>
                        <div class="ep-label-value">
                            <dt>Company</dt>
                            <dd>{{ $company->company_name }}</dd>
                        </div>
                        <div class="ep-label-value">
                            <dt>Industry</dt>
                            <dd>{{ $company->industry ?: 'Not specified' }}</dd>
                        </div>
                        <div class="ep-label-value">
                            <dt>Location</dt>
                            <dd>{{ collect([$company->city, $company->country])->filter()->implode(', ') ?: 'Not specified' }}</dd>
                        </div>
                        <div class="ep-label-value">
                            <dt>Website</dt>
                            <dd>{{ $company->website ?: 'Not available' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="ep-card">
                <div class="ep-card-header">
                    <h3>Digital Maturity</h3>
                    <p>AI assessment</p>
                </div>
                <div class="ep-card-body">
                    <p class="ep-copy">
                        {{ $digital_maturity ?: 'Digital maturity will be refined during discovery and requirements validation.' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="ep-swot ep-mt-24">
            <article class="ep-swot-item ep-swot-strength">
                <h3>Strengths</h3>
                <p>Established digital presence, industry knowledge and a foundation ready for scalable improvement.</p>
            </article>

            <article class="ep-swot-item ep-swot-weakness">
                <h3>Weaknesses</h3>
                <p>{{ $pain_points ?: 'Current gaps may reduce lead capture, follow-up efficiency and operational visibility.' }}</p>
            </article>

            <article class="ep-swot-item ep-swot-opportunity">
                <h3>Opportunities</h3>
                <p>{{ $opportunities ?: 'CRM, automation, AI-enabled engagement and digital growth opportunities have been identified.' }}</p>
            </article>

            <article class="ep-swot-item ep-swot-risk">
                <h3>Risks</h3>
                <p>Delayed modernization may increase lead leakage, response time and competitive pressure.</p>
            </article>
        </div>

        <div class="ep-card ep-mt-24">
            <div class="ep-card-header">
                <h3>Business Overview</h3>
            </div>
            <div class="ep-card-body">
                <p class="ep-copy">{{ $business_overview ?: 'A detailed business overview will be validated during discovery.' }}</p>
            </div>
        </div>
    </div>

    <footer class="ep-footer">
        <span>{{ $brand_name ?? 'WebApp Infoway' }} · Company Analysis</span>
        <strong>03</strong>
    </footer>
</section>
