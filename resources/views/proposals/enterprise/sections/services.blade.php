<section class="ep-page">
    <div class="ep-page-content">
        <header class="ep-section-header">
            <div>
                <span class="ep-section-eyebrow">Recommended Solution</span>
                <h2>Proposed Service Package</h2>
                <p>A phased solution focused on measurable outcomes, scalability and operational efficiency.</p>
            </div>
            <span class="ep-section-badge">06</span>
        </header>

        <div class="ep-accent-box ep-mb-24">
            <h3>Recommended Package</h3>
            <p>{{ $recommended_package ?: 'A phased digital transformation package will be finalized after discovery.' }}</p>
        </div>

        <div class="ep-service-grid">
            @forelse($service_bundle ?? [] as $service)
                <article class="ep-service-card">
                    <div class="ep-service-top">
                        <span class="ep-service-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="ep-status-pill ep-status-low">Recommended</span>
                    </div>

                    <h3 style="margin-top:14px;">{{ $service }}</h3>
                    <p>Included based on the company’s current digital maturity, priorities and growth potential.</p>

                    <ul class="ep-list" style="margin-top:14px;">
                        <li>Discovery and requirements</li>
                        <li>Implementation and testing</li>
                        <li>Training and handover</li>
                    </ul>
                </article>
            @empty
                <article class="ep-service-card">
                    <span class="ep-service-index">01</span>
                    <h3 style="margin-top:14px;">Discovery & Digital Strategy</h3>
                    <p>Requirements analysis, solution planning and implementation roadmap.</p>
                </article>
            @endforelse
        </div>
    </div>

    <footer class="ep-footer">
        <span>{{ $brand_name ?? 'WebApp Infoway' }} · Recommended Solution</span>
        <strong>06</strong>
    </footer>
</section>
