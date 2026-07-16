<section class="ep-page">
    <div class="ep-page-content">
        <header class="ep-section-header">
            <div>
                <span class="ep-section-eyebrow">Commercial Proposal</span>
                <h2>Investment Estimate</h2>
                <p>Indicative pricing based on the identified opportunities and recommended scope.</p>
            </div>
            <span class="ep-section-badge">08</span>
        </header>

        <table class="ep-table">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Priority</th>
                    <th>Investment Range</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($recommendations ?? collect())->sortByDesc('priority_score')->take(8) as $recommendation)
                    <tr>
                        <td>{{ $recommendation->recommended_service ?: $recommendation->title }}</td>
                        <td>{{ ucfirst((string) ($recommendation->priority ?? 'medium')) }}</td>
                        <td>
                            ₹{{ number_format((int) ($recommendation->estimated_value_min ?? 0)) }}
                            – ₹{{ number_format((int) ($recommendation->estimated_value_max ?? 0)) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>Discovery & Planning</td>
                        <td>Initial</td>
                        <td>To be finalized</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="ep-investment-total">
            <span>Estimated Deal Value</span>
            <strong>₹{{ number_format((int) ($estimated_deal_value ?? 0)) }}</strong>
        </div>

        <div class="ep-payment-grid ep-mt-24">
            <article class="ep-payment-card">
                <strong>40%</strong>
                <span>Advance on approval</span>
            </article>

            <article class="ep-payment-card">
                <strong>30%</strong>
                <span>Mid-project milestone</span>
            </article>

            <article class="ep-payment-card">
                <strong>30%</strong>
                <span>Before final deployment</span>
            </article>
        </div>
    </div>

    <footer class="ep-footer">
        <span>{{ $brand_name ?? 'WebApp Infoway' }} · Commercial Proposal</span>
        <strong>08</strong>
    </footer>
</section>
