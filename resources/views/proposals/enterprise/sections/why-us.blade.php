<section class="ep-page">
    <div class="ep-page-content">
        <header class="ep-section-header">
            <div>
                <span class="ep-section-eyebrow">Why WebApp Infoway</span>
                <h2>Your Digital Transformation Partner</h2>
                <p>A practical, transparent and outcome-focused implementation approach.</p>
            </div>
            <span class="ep-section-badge">10</span>
        </header>

        @php
            $items = [
                ['Business-First Thinking', 'Solutions aligned with measurable commercial goals.'],
                ['Custom Solutions', 'Designed around your workflows, priorities and budget.'],
                ['Phased Delivery', 'Start with the highest-impact opportunity and expand safely.'],
                ['Transparent Communication', 'Clear scope, milestones, responsibilities and reporting.'],
                ['Scalable Architecture', 'Built for future growth, automation and integration.'],
                ['Continued Support', 'Training, maintenance and ongoing optimization.'],
            ];
        @endphp

        <div class="ep-why-grid">
            @foreach($items as [$title, $description])
                <article class="ep-why-card">
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

        <div class="ep-grid-4 ep-mt-24">
            @foreach([
                ['AI-led', 'Opportunity analysis'],
                ['Phased', 'Low-risk delivery'],
                ['Scalable', 'Future-ready systems'],
                ['Support', 'Post-launch continuity'],
            ] as [$title, $text])
                <div class="ep-card">
                    <div class="ep-card-body">
                        <strong style="display:block;color:var(--ep-blue);font-size:18px;">{{ $title }}</strong>
                        <span style="display:block;margin-top:5px;color:var(--ep-muted);font-size:10px;">{{ $text }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <footer class="ep-footer">
        <span>{{ $brand_name ?? 'WebApp Infoway' }} · Why Us</span>
        <strong>10</strong>
    </footer>
</section>
