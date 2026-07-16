<section class="ep-page ep-signature">
    <div class="ep-page-content">
        <span class="ep-section-eyebrow" style="color:#bfdbfe;">Proposal Acceptance</span>

        <h2>Let’s Build the Next Phase of Growth</h2>

        <p class="ep-signature-intro">
            This proposal provides a clear starting point. Final scope, timeline and
            commercial terms will be confirmed after discovery and requirement validation.
        </p>

        <div class="ep-signature-grid">
            <article class="ep-signature-card">
                <small>Prepared By</small>
                <strong>{{ $brand_name ?? 'WebApp Infoway' }}</strong>
                <span>{{ $prepared_by_name ?? 'Hardik Maheta' }} · {{ $prepared_by_title ?? 'Founder' }}</span>

                <div class="ep-sign-line"></div>
                <div class="ep-sign-label">Authorized Signature</div>
            </article>

            <article class="ep-signature-card">
                <small>Accepted By</small>
                <strong>{{ $prepared_for ?? $company->company_name }}</strong>
                <span>Client Representative</span>

                <div class="ep-sign-line"></div>
                <div class="ep-sign-label">Authorized Signature</div>
            </article>
        </div>

        <div class="ep-contact-grid">
            <div class="ep-contact-item">
                <small>Website</small>
                <strong>{{ $brand_website ?? 'https://webappinfoway.com' }}</strong>
            </div>

            <div class="ep-contact-item">
                <small>Email</small>
                <strong>{{ $brand_email ?? 'info@webappinfoway.com' }}</strong>
            </div>

            <div class="ep-contact-item">
                <small>Proposal Date</small>
                <strong>{{ $generated_date ?? now()->format('d M Y') }}</strong>
            </div>
        </div>
    </div>

    <footer class="ep-footer">
        <span>Thank you for the opportunity to present this proposal.</span>
        <strong>13</strong>
    </footer>
</section>
