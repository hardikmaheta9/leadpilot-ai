<section class="ep-page">
    <div class="ep-page-content">
        <header class="ep-section-header">
            <div>
                <span class="ep-section-eyebrow">AI Opportunity Analysis</span>
                <h2>Priority Growth Opportunities</h2>
                <p>Commercial opportunities ranked by priority, buying probability and estimated value.</p>
            </div>
            <span class="ep-section-badge">05</span>
        </header>

        <div class="ep-opportunity-grid">
            @forelse(($recommendations ?? collect())->sortByDesc('priority_score')->take(6) as $recommendation)
                @php
                    $priority = strtolower((string) ($recommendation->priority ?? 'low'));
                    $priorityClass = match($priority) {
                        'high' => 'ep-status-high',
                        'medium' => 'ep-status-medium',
                        default => 'ep-status-low',
                    };
                @endphp

                <article class="ep-opportunity-card">
                    <div class="ep-opportunity-top">
                        <h3>{{ $recommendation->recommended_service ?: $recommendation->title }}</h3>
                        <span class="ep-status-pill {{ $priorityClass }}">
                            {{ ucfirst($priority) }}
                        </span>
                    </div>

                    <p>{{ $recommendation->reason ?: $recommendation->description ?: 'Recommended opportunity based on the latest analysis.' }}</p>

                    <div class="ep-opportunity-meta">
                        <div>
                            <small>Priority</small>
                            <strong>{{ (int) ($recommendation->priority_score ?? 0) }}/100</strong>
                        </div>
                        <div>
                            <small>Probability</small>
                            <strong>{{ (int) ($recommendation->buying_probability ?? 0) }}%</strong>
                        </div>
                        <div>
                            <small>Estimated Value</small>
                            <strong>
                                ₹{{ number_format((int) ($recommendation->estimated_value_min ?? 0)) }}
                                – ₹{{ number_format((int) ($recommendation->estimated_value_max ?? 0)) }}
                            </strong>
                        </div>
                    </div>
                </article>
            @empty
                <article class="ep-opportunity-card">
                    <h3>Discovery & Digital Strategy</h3>
                    <p>A structured discovery phase is recommended to define priorities, scope, timeline and investment.</p>
                </article>
            @endforelse
        </div>
    </div>

    <footer class="ep-footer">
        <span>{{ $brand_name ?? 'WebApp Infoway' }} · AI Opportunity Analysis</span>
        <strong>05</strong>
    </footer>
</section>
