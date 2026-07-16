<section class="ep-page">
    <div class="ep-page-content">
        <header class="ep-section-header">
            <div>
                <span class="ep-section-eyebrow">Implementation Roadmap</span>
                <h2>Phased Delivery Plan</h2>
                <p>A structured approach that reduces risk and delivers measurable progress.</p>
            </div>
            <span class="ep-section-badge">07</span>
        </header>

        @php
            $timelineItems = collect(preg_split('/\r\n|\r|\n/', trim((string) ($timeline ?? ''))))
                ->filter()
                ->values();

            if ($timelineItems->isEmpty()) {
                $timelineItems = collect([
                    'Discovery and Planning — Week 1',
                    'Design and Solution Architecture — Week 2',
                    'Development and Configuration — Weeks 3–6',
                    'Testing, Training and Deployment — Week 7',
                ]);
            }
        @endphp

        <div class="ep-roadmap">
            @foreach($timelineItems as $line)
                <div class="ep-roadmap-item">
                    <span class="ep-number-badge">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>

                    <div class="ep-roadmap-card">
                        <h3>{{ $line }}</h3>
                        <p>Activities, approvals and deliverables will be confirmed during project kickoff.</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="ep-accent-box ep-mt-24">
            <h3>Delivery Principle</h3>
            <p>Phased delivery protects cash flow, improves visibility and reduces implementation risk.</p>
        </div>
    </div>

    <footer class="ep-footer">
        <span>{{ $brand_name ?? 'WebApp Infoway' }} · Implementation Roadmap</span>
        <strong>07</strong>
    </footer>
</section>
