<section class="ep-page">
    <div class="ep-page-content">
        <header class="ep-section-header">
            <div>
                <span class="ep-section-eyebrow">Relevant Experience</span>
                <h2>Illustrative Case Studies</h2>
                <p>Representative outcomes demonstrating the value of structured digital transformation.</p>
            </div>
            <span class="ep-section-badge">11</span>
        </header>

        @php
            $cases = [
                [
                    'Manufacturing',
                    'CRM & Workflow Automation',
                    'Centralized enquiry tracking and automated follow-up.',
                    ['Reduced manual coordination', 'Improved pipeline visibility', 'Faster response times'],
                ],
                [
                    'Professional Services',
                    'Website & Lead Generation',
                    'Modern digital presence focused on qualified enquiries.',
                    ['Improved credibility', 'Better conversion paths', 'SEO-ready architecture'],
                ],
                [
                    'SME Operations',
                    'Business Process Digitization',
                    'Connected data, reporting and operational workflows.',
                    ['Reduced repetitive work', 'Better management visibility', 'Scalable systems'],
                ],
            ];
        @endphp

        <div class="ep-case-grid">
            @foreach($cases as [$industry, $title, $description, $outcomes])
                <article class="ep-case-card">
                    <span class="ep-status-pill ep-status-low">{{ $industry }}</span>
                    <h3 style="margin-top:14px;">{{ $title }}</h3>
                    <p>{{ $description }}</p>

                    <ul class="ep-list" style="margin-top:14px;">
                        @foreach($outcomes as $outcome)
                            <li>{{ $outcome }}</li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>

        <div class="ep-accent-box ep-mt-24">
            <h3>Important Note</h3>
            <p>These examples are illustrative. Client-specific case studies can be added once approved references are available.</p>
        </div>
    </div>

    <footer class="ep-footer">
        <span>{{ $brand_name ?? 'WebApp Infoway' }} · Relevant Experience</span>
        <strong>11</strong>
    </footer>
</section>
