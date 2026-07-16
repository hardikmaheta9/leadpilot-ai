<section class="ep-page">
    <div class="ep-page-content">
        <header class="ep-section-header">
            <div>
                <span class="ep-section-eyebrow">Commercial Terms</span>
                <h2>Terms & Conditions</h2>
                <p>Key assumptions and commercial conditions for proposal approval.</p>
            </div>
            <span class="ep-section-badge">12</span>
        </header>

        @php
            $terms = [
                ['Scope Confirmation', 'Final scope and deliverables will be confirmed after discovery.'],
                ['Taxes', 'Applicable taxes, including GST, will be charged as required by law.'],
                ['Change Requests', 'Work outside the approved scope will be estimated and approved separately.'],
                ['Client Responsibilities', 'Timely feedback, content, access and approvals are required to maintain the schedule.'],
                ['Support', 'Post-launch support and maintenance terms will be defined in the final agreement.'],
                ['Confidentiality', 'Both parties will protect confidential business and technical information.'],
            ];
        @endphp

        <div class="ep-term-grid">
            @foreach($terms as [$title, $description])
                <article class="ep-term-card">
                    <h3>{{ $title }}</h3>
                    <p>{{ $description }}</p>
                </article>
            @endforeach
        </div>

        <div class="ep-grid-3 ep-mt-24">
            <div class="ep-card">
                <div class="ep-card-body">
                    <div class="ep-label-value">
                        <span class="ep-label">Proposal Validity</span>
                        <span class="ep-value">30 Days</span>
                    </div>
                </div>
            </div>

            <div class="ep-card">
                <div class="ep-card-body">
                    <div class="ep-label-value">
                        <span class="ep-label">Implementation Start</span>
                        <span class="ep-value">Subject to availability</span>
                    </div>
                </div>
            </div>

            <div class="ep-card">
                <div class="ep-card-body">
                    <div class="ep-label-value">
                        <span class="ep-label">Commercial Terms</span>
                        <span class="ep-value">Finalized after discovery</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="ep-footer">
        <span>{{ $brand_name ?? 'WebApp Infoway' }} · Commercial Terms</span>
        <strong>12</strong>
    </footer>
</section>
