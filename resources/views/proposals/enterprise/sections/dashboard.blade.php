<section class="ep-page">
    <div class="ep-page-content">
        <header class="ep-section-header">
            <div>
                <span class="ep-section-eyebrow">Executive Dashboard</span>
                <h2>Digital Opportunity Snapshot</h2>
                <p>Key commercial and digital indicators generated from the latest company analysis.</p>
            </div>
            <span class="ep-section-badge">02</span>
        </header>

        @php
            $kpis = [
                ['Website Score', (int) ($website_score ?? 0), '/100', 'Technical website health'],
                ['SEO Score', (int) ($seo_score ?? 0), '/100', 'Search visibility readiness'],
                ['Performance', (int) ($performance_score ?? 0), '/100', 'Front-end efficiency'],
                ['Opportunity Score', (int) ($opportunity_score ?? 0), '/100', 'Commercial attractiveness'],
                ['Buying Probability', (int) ($buying_probability ?? 0), '%', 'Likelihood of engagement'],
                ['AI Confidence', (int) ($confidence_score ?? 0), '%', 'Confidence in the assessment'],
            ];
        @endphp

        <div class="ep-kpi-grid">
            @foreach($kpis as [$label, $value, $suffix, $note])
                <article class="ep-kpi">
                    <span class="ep-kpi-label">{{ $label }}</span>
                    <strong class="ep-kpi-value">{{ $value }}{{ $suffix }}</strong>
                    <div class="ep-progress {{ $loop->iteration === 2 ? 'ep-progress-purple' : ($loop->iteration === 3 ? 'ep-progress-green' : ($loop->iteration === 4 ? 'ep-progress-orange' : '')) }}">
                        <span style="width: {{ min(max($value, 0), 100) }}%"></span>
                    </div>
                    <span class="ep-kpi-note">{{ $note }}</span>
                </article>
            @endforeach
        </div>

        <div class="ep-accent-box ep-mt-24">
            <h3>Executive Summary</h3>
            <p>
                {{ $executiveSummary ?? $executive_summary ?? 'A detailed executive summary will be generated from the available business and website signals.' }}
            </p>
        </div>
    </div>

    <footer class="ep-footer">
        <span>{{ $brand_name ?? 'WebApp Infoway' }} · Enterprise Proposal</span>
        <strong>02</strong>
    </footer>
</section>
