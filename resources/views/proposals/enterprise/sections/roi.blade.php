<section class="ep-page">
    <div class="ep-page-content">
        <header class="ep-section-header">
            <div>
                <span class="ep-section-eyebrow">Expected ROI</span>
                <h2>Business Impact</h2>
                <p>Expected outcomes from the proposed transformation program.</p>
            </div>
            <span class="ep-section-badge">09</span>
        </header>

        @php
            $benefits = [
                ['More Qualified Leads', 'Improved digital visibility and lead capture.'],
                ['Higher Conversion', 'Better customer experience and systematic follow-up.'],
                ['Less Manual Work', 'Automation of repetitive sales and operational tasks.'],
                ['Better Visibility', 'Improved reporting and management decisions.'],
                ['Customer Experience', 'Faster and more consistent communication.'],
                ['Scalable Growth', 'Technology foundations that support expansion.'],
            ];
        @endphp

        <div class="ep-benefit-grid">
            @foreach($benefits as [$title, $description])
                <article class="ep-benefit-card">
                    <div class="ep-numbered-row">
                        <span class="ep-why-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h3>{{ $title }}</h3>
                            <p>{{ $description }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="ep-accent-box ep-mt-24">
            <h3>ROI Summary</h3>
            <p>{{ $roi ?: 'Expected benefits will be validated during discovery and measured against agreed success indicators.' }}</p>
        </div>
    </div>

    <footer class="ep-footer">
        <span>{{ $brand_name ?? 'WebApp Infoway' }} · Expected ROI</span>
        <strong>09</strong>
    </footer>
</section>
