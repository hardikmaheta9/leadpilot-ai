<section class="ep-page">
    <div class="ep-page-content">
        <header class="ep-section-header">
            <div>
                <span class="ep-section-eyebrow">Website Audit</span>
                <h2>Digital Presence Assessment</h2>
                <p>Technical, SEO and usability signals detected from the company website.</p>
            </div>
            <span class="ep-section-badge">04</span>
        </header>

        <div class="ep-audit-grid">
            @foreach([
                ['Website Score', (int) ($website_score ?? 0), 'ep-progress'],
                ['SEO Score', (int) ($seo_score ?? 0), 'ep-progress-purple'],
                ['Performance', (int) ($performance_score ?? 0), 'ep-progress-green'],
            ] as [$label, $value, $progressClass])
                <article class="ep-audit-card">
                    <small>{{ $label }}</small>
                    <strong>{{ $value }}/100</strong>
                    <div class="ep-progress {{ $progressClass }}">
                        <span style="width: {{ min(max($value, 0), 100) }}%"></span>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="ep-status-grid ep-mt-24">
            <div class="ep-status-card">
                <span>SSL Security</span>
                <strong>{{ ($website?->ssl_enabled ?? false) ? 'Enabled' : 'Needs Attention' }}</strong>
            </div>

            <div class="ep-status-card">
                <span>Mobile Friendly</span>
                <strong>{{ ($website?->mobile_friendly ?? false) ? 'Yes' : 'Needs Improvement' }}</strong>
            </div>

            <div class="ep-status-card">
                <span>CMS</span>
                <strong>{{ $website?->cms ?: 'Not detected' }}</strong>
            </div>

            <div class="ep-status-card">
                <span>Framework</span>
                <strong>{{ $website?->framework ?: 'Not detected' }}</strong>
            </div>

            <div class="ep-status-card">
                <span>Forms</span>
                <strong>{{ (int) ($website?->forms ?? 0) }}</strong>
            </div>

            <div class="ep-status-card">
                <span>Word Count</span>
                <strong>{{ number_format((int) ($website?->word_count ?? 0)) }}</strong>
            </div>
        </div>

        <div class="ep-card ep-mt-24">
            <div class="ep-card-header">
                <h3>Detected Technology Stack</h3>
                <p>Technologies identified during website analysis</p>
            </div>
            <div class="ep-card-body">
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @forelse($website?->technologies ?? [] as $technology)
                        <span class="ep-status-pill ep-status-low">{{ $technology }}</span>
                    @empty
                        <span class="ep-status-pill ep-status-low">No technology details detected</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <footer class="ep-footer">
        <span>{{ $brand_name ?? 'WebApp Infoway' }} · Website Audit</span>
        <strong>04</strong>
    </footer>
</section>
