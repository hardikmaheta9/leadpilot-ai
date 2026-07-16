<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $proposalTitle ?? $proposal_title ?? 'Digital Transformation Proposal' }}
    </title>

    <style>
        :root {
            --proposal-primary: #1D4ED8;
            --proposal-primary-dark: #1E3A8A;
            --proposal-secondary: #7C3AED;
            --proposal-accent: #0EA5E9;
            --proposal-success: #059669;
            --proposal-warning: #D97706;
            --proposal-danger: #DC2626;

            --proposal-text: #0F172A;
            --proposal-muted: #64748B;
            --proposal-border: #E2E8F0;
            --proposal-soft: #F8FAFC;
            --proposal-white: #FFFFFF;

            --proposal-shadow:
                0 24px 70px rgba(15, 23, 42, 0.14);

            --proposal-radius: 22px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            background: #E2E8F0;
        }

        body {
            margin: 0;
            padding: 32px;
            background: #E2E8F0;
            color: var(--proposal-text);
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
            line-height: 1.65;
        }

        .proposal-document {
            width: 100%;
            max-width: 980px;
            margin: 0 auto;
        }

        .proposal-page {
            position: relative;
            min-height: 1120px;
            margin: 0 0 32px;
            padding: 64px;
            overflow: hidden;
            border-radius: 28px;
            background: var(--proposal-white);
            box-shadow: var(--proposal-shadow);
        }

        .proposal-page:last-child {
            margin-bottom: 0;
        }

        .proposal-cover {
            display: flex;
            min-height: 1120px;
            flex-direction: column;
            justify-content: space-between;
            color: #FFFFFF;
            background:
                radial-gradient(
                    circle at 82% 16%,
                    rgba(255, 255, 255, 0.22),
                    transparent 24%
                ),
                radial-gradient(
                    circle at 14% 82%,
                    rgba(14, 165, 233, 0.28),
                    transparent 26%
                ),
                linear-gradient(
                    145deg,
                    #0F172A 0%,
                    #1E3A8A 48%,
                    #7C3AED 100%
                );
        }

        .proposal-cover::before {
            content: "";
            position: absolute;
            top: -160px;
            right: -140px;
            width: 420px;
            height: 420px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 50%;
        }

        .proposal-cover::after {
            content: "";
            position: absolute;
            right: 80px;
            bottom: 120px;
            width: 180px;
            height: 180px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 38px;
            transform: rotate(22deg);
        }

        .proposal-cover-top,
        .proposal-cover-main,
        .proposal-cover-bottom {
            position: relative;
            z-index: 2;
        }

        .proposal-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .proposal-brand-mark {
            display: inline-flex;
            width: 52px;
            height: 52px;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.12);
            font-size: 21px;
            font-weight: 900;
            backdrop-filter: blur(12px);
        }

        .proposal-brand-text strong {
            display: block;
            font-size: 18px;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .proposal-brand-text span {
            display: block;
            margin-top: 2px;
            color: rgba(255, 255, 255, 0.72);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .proposal-confidential {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 34px;
            padding: 9px 14px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.88);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.11em;
            text-transform: uppercase;
        }

        .proposal-cover-main {
            max-width: 760px;
            padding: 90px 0;
        }

        .proposal-cover-eyebrow {
            display: block;
            margin-bottom: 20px;
            color: #BFDBFE;
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .proposal-cover h1 {
            max-width: 760px;
            margin: 0;
            font-size: 64px;
            line-height: 1.02;
            font-weight: 950;
            letter-spacing: -0.055em;
        }

        .proposal-cover-subtitle {
            max-width: 650px;
            margin: 28px 0 0;
            color: rgba(255, 255, 255, 0.78);
            font-size: 18px;
            line-height: 1.75;
        }

        .proposal-cover-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-top: 48px;
        }

        .proposal-cover-meta {
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
        }

        .proposal-cover-meta small {
            display: block;
            color: rgba(255, 255, 255, 0.58);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .proposal-cover-meta strong {
            display: block;
            margin-top: 8px;
            color: #FFFFFF;
            font-size: 15px;
            font-weight: 850;
        }

        .proposal-cover-bottom {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            padding-top: 28px;
            border-top: 1px solid rgba(255, 255, 255, 0.14);
        }

        .proposal-cover-note {
            max-width: 520px;
            color: rgba(255, 255, 255, 0.62);
            font-size: 11px;
            line-height: 1.7;
        }

        .proposal-cover-page-number {
            color: rgba(255, 255, 255, 0.58);
            font-size: 12px;
            font-weight: 800;
        }

                .proposal-section-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 28px;
            margin-bottom: 38px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--proposal-border);
        }

        .proposal-section-eyebrow {
            display: block;
            margin-bottom: 8px;
            color: var(--proposal-primary);
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .proposal-section-header h2 {
            margin: 0;
            font-size: 34px;
            line-height: 1.15;
            font-weight: 950;
            letter-spacing: -0.035em;
        }

        .proposal-section-header p {
            max-width: 680px;
            margin: 12px 0 0;
            color: var(--proposal-muted);
            font-size: 14px;
        }

        .proposal-page-number {
            color: var(--proposal-muted);
            font-size: 12px;
            font-weight: 900;
        }

        .proposal-kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .proposal-kpi-card,
        .proposal-assessment-card,
        .proposal-content-card {
            border: 1px solid var(--proposal-border);
            border-radius: var(--proposal-radius);
            background: var(--proposal-white);
        }

        .proposal-kpi-card {
            padding: 24px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(37, 99, 235, 0.08),
                    transparent 32%
                ),
                var(--proposal-white);
        }

        .proposal-kpi-card small {
            display: block;
            color: var(--proposal-muted);
            font-size: 10px;
            font-weight: 850;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .proposal-kpi-card strong {
            display: block;
            margin-top: 10px;
            font-size: 28px;
            font-weight: 950;
            letter-spacing: -0.03em;
        }

        .proposal-kpi-card p {
            margin: 14px 0 0;
            color: var(--proposal-muted);
            font-size: 12px;
        }

        .proposal-progress {
            height: 8px;
            margin-top: 16px;
            overflow: hidden;
            border-radius: 999px;
            background: #EAF0F8;
        }

        .proposal-progress span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(
                90deg,
                var(--proposal-primary),
                var(--proposal-accent)
            );
        }

        .proposal-progress-purple span {
            background: linear-gradient(
                90deg,
                var(--proposal-secondary),
                #A855F7
            );
        }

        .proposal-progress-green span {
            background: linear-gradient(
                90deg,
                var(--proposal-success),
                #34D399
            );
        }

        .proposal-content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .proposal-content-grid-balanced {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .proposal-content-card {
            overflow: hidden;
        }

        .proposal-content-card-heading {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 20px 22px;
            border-bottom: 1px solid var(--proposal-border);
            background: var(--proposal-soft);
        }

        .proposal-content-icon {
            display: inline-flex;
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: #DBEAFE;
            color: var(--proposal-primary);
            font-size: 12px;
            font-weight: 950;
        }

        .proposal-content-icon-red {
            background: #FEE2E2;
            color: var(--proposal-danger);
        }

        .proposal-content-icon-purple {
            background: #EDE9FE;
            color: var(--proposal-secondary);
        }

        .proposal-content-card-heading h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 900;
        }

        .proposal-content-card-heading p {
            margin: 3px 0 0;
            color: var(--proposal-muted);
            font-size: 11px;
        }

        .proposal-copy {
            padding: 24px;
            color: #334155;
            font-size: 13px;
            line-height: 1.8;
        }

        .proposal-preline {
            white-space: pre-line;
        }

        .proposal-detail-list {
            margin: 0;
            padding: 22px 24px;
        }

        .proposal-detail-list div {
            display: grid;
            grid-template-columns: 110px 1fr;
            gap: 14px;
            padding: 11px 0;
            border-bottom: 1px solid var(--proposal-border);
        }

        .proposal-detail-list div:last-child {
            border-bottom: 0;
        }

        .proposal-detail-list dt {
            color: var(--proposal-muted);
            font-size: 11px;
            font-weight: 800;
        }

        .proposal-detail-list dd {
            margin: 0;
            font-size: 12px;
            font-weight: 800;
            word-break: break-word;
        }

        .proposal-assessment-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }

        .proposal-assessment-card {
            padding: 20px;
        }

        .proposal-assessment-label {
            display: block;
            color: var(--proposal-muted);
            font-size: 10px;
            font-weight: 850;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .proposal-assessment-card strong {
            display: block;
            margin-top: 8px;
            font-size: 22px;
            font-weight: 950;
        }


                .proposal-solution-banner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 28px;
            padding: 26px;
            border-radius: 24px;
            color: #FFFFFF;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(255, 255, 255, 0.18),
                    transparent 30%
                ),
                linear-gradient(
                    135deg,
                    var(--proposal-primary-dark),
                    var(--proposal-secondary)
                );
        }

        .proposal-solution-banner small {
            display: block;
            color: rgba(255, 255, 255, 0.68);
            font-size: 10px;
            font-weight: 850;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .proposal-solution-banner h3 {
            max-width: 680px;
            margin: 8px 0 0;
            font-size: 19px;
            line-height: 1.55;
            font-weight: 850;
        }

        .proposal-solution-banner > span {
            display: inline-flex;
            min-width: 96px;
            padding: 12px 16px;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.1);
            font-size: 12px;
            font-weight: 900;
            text-align: center;
        }

        .proposal-service-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .proposal-service-card {
            position: relative;
            padding: 24px;
            overflow: hidden;
            border: 1px solid var(--proposal-border);
            border-radius: 22px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(37, 99, 235, 0.06),
                    transparent 32%
                ),
                #FFFFFF;
        }

        .proposal-service-card::after {
            content: "";
            position: absolute;
            right: -46px;
            bottom: -46px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(37, 99, 235, 0.035);
        }

        .proposal-service-card-top {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .proposal-service-number {
            color: var(--proposal-primary);
            font-size: 12px;
            font-weight: 950;
            letter-spacing: 0.08em;
        }

        .proposal-priority-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 10px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.07em;
            text-transform: uppercase;
        }

        .proposal-priority-high {
            background: #FEE2E2;
            color: #B91C1C;
        }

        .proposal-priority-medium {
            background: #FEF3C7;
            color: #92400E;
        }

        .proposal-priority-low {
            background: #E2E8F0;
            color: #475569;
        }

        .proposal-service-card h3 {
            position: relative;
            z-index: 2;
            margin: 0;
            font-size: 18px;
            line-height: 1.35;
            font-weight: 900;
            letter-spacing: -0.02em;
        }

        .proposal-service-card > p {
            position: relative;
            z-index: 2;
            margin: 12px 0 0;
            color: var(--proposal-muted);
            font-size: 12px;
            line-height: 1.75;
        }

        .proposal-service-meta {
            position: relative;
            z-index: 2;
            display: grid;
            gap: 10px;
            margin: 20px 0 0;
            padding: 16px;
            border-radius: 16px;
            background: var(--proposal-soft);
        }

        .proposal-service-meta div {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }

        .proposal-service-meta dt {
            color: var(--proposal-muted);
            font-size: 10px;
            font-weight: 750;
        }

        .proposal-service-meta dd {
            margin: 0;
            color: var(--proposal-text);
            font-size: 11px;
            font-weight: 900;
            text-align: right;
        }

        .mt-24 {
            margin-top: 24px;
        }

        .proposal-timeline {
            position: relative;
            display: grid;
            gap: 18px;
        }

        .proposal-timeline::before {
            content: "";
            position: absolute;
            top: 24px;
            bottom: 24px;
            left: 27px;
            width: 2px;
            background: linear-gradient(
                180deg,
                var(--proposal-primary),
                var(--proposal-secondary)
            );
        }

        .proposal-timeline-item {
            position: relative;
            display: grid;
            grid-template-columns: 56px 1fr;
            gap: 20px;
            align-items: flex-start;
        }

        .proposal-timeline-marker {
            position: relative;
            z-index: 2;
            display: inline-flex;
            width: 56px;
            height: 56px;
            align-items: center;
            justify-content: center;
            border: 6px solid #FFFFFF;
            border-radius: 18px;
            color: #FFFFFF;
            background: linear-gradient(
                135deg,
                var(--proposal-primary),
                var(--proposal-secondary)
            );
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.22);
            font-size: 11px;
            font-weight: 950;
        }

        .proposal-timeline-content {
            padding: 20px 22px;
            border: 1px solid var(--proposal-border);
            border-radius: 20px;
            background: #FFFFFF;
        }

        .proposal-timeline-content small {
            color: var(--proposal-primary);
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .proposal-timeline-content h3 {
            margin: 6px 0 0;
            font-size: 16px;
            font-weight: 900;
        }

        .proposal-timeline-content p {
            margin: 8px 0 0;
            color: var(--proposal-muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .proposal-roadmap-note {
            margin-top: 28px;
            padding: 22px;
            border-left: 4px solid var(--proposal-primary);
            border-radius: 0 18px 18px 0;
            background: #EFF6FF;
        }

        .proposal-roadmap-note strong {
            display: block;
            font-size: 14px;
            font-weight: 900;
        }

        .proposal-roadmap-note p {
            margin: 7px 0 0;
            color: var(--proposal-muted);
            font-size: 12px;
        }

                .proposal-investment-layout {
            display: grid;
            grid-template-columns: 1.7fr 1fr;
            gap: 22px;
            margin-bottom: 26px;
        }

        .proposal-investment-card {
            overflow: hidden;
            border: 1px solid var(--proposal-border);
            border-radius: 24px;
            background: #FFFFFF;
        }

        .proposal-investment-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 22px 24px;
            color: #FFFFFF;
            background: linear-gradient(
                135deg,
                var(--proposal-primary-dark),
                var(--proposal-secondary)
            );
        }

        .proposal-investment-heading small {
            display: block;
            color: rgba(255, 255, 255, 0.68);
            font-size: 9px;
            font-weight: 850;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .proposal-investment-heading h3 {
            margin: 6px 0 0;
            font-size: 18px;
            font-weight: 900;
        }

        .proposal-investment-heading > span {
            display: inline-flex;
            padding: 8px 11px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            font-size: 10px;
            font-weight: 900;
        }

        .proposal-investment-table {
            padding: 8px 24px;
        }

        .proposal-investment-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 18px;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--proposal-border);
        }

        .proposal-investment-row:last-child {
            border-bottom: 0;
        }

        .proposal-investment-row span {
            color: var(--proposal-muted);
            font-size: 12px;
            font-weight: 700;
        }

        .proposal-investment-row strong {
            color: var(--proposal-text);
            font-size: 12px;
            font-weight: 900;
            text-align: right;
        }

        .proposal-investment-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 20px 24px;
            border-top: 1px solid var(--proposal-border);
            background: var(--proposal-soft);
        }

        .proposal-investment-total span {
            color: var(--proposal-muted);
            font-size: 11px;
            font-weight: 850;
            text-transform: uppercase;
        }

        .proposal-investment-total strong {
            font-size: 17px;
            font-weight: 950;
            text-align: right;
        }

        .proposal-investment-summary {
            display: grid;
            gap: 14px;
        }

        .proposal-value-card {
            padding: 20px;
            border: 1px solid #BFDBFE;
            border-radius: 20px;
            background: #EFF6FF;
        }

        .proposal-value-card-purple {
            border-color: #DDD6FE;
            background: #F5F3FF;
        }

        .proposal-value-card-green {
            border-color: #A7F3D0;
            background: #ECFDF5;
        }

        .proposal-value-card small {
            display: block;
            color: var(--proposal-muted);
            font-size: 9px;
            font-weight: 850;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .proposal-value-card strong {
            display: block;
            margin-top: 7px;
            font-size: 23px;
            font-weight: 950;
            letter-spacing: -0.03em;
        }

        .proposal-value-card p {
            margin: 8px 0 0;
            color: var(--proposal-muted);
            font-size: 10px;
            line-height: 1.6;
        }

        .proposal-roi-card {
            margin-bottom: 24px;
        }

        .proposal-benefit-grid,
        .proposal-why-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .proposal-benefit-card,
        .proposal-why-card {
            padding: 22px;
            border: 1px solid var(--proposal-border);
            border-radius: 20px;
            background:
                radial-gradient(
                    circle at top right,
                    rgba(37, 99, 235, 0.055),
                    transparent 32%
                ),
                #FFFFFF;
        }

        .proposal-benefit-card > span,
        .proposal-why-card > span {
            display: inline-flex;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #DBEAFE;
            color: var(--proposal-primary);
            font-size: 10px;
            font-weight: 950;
        }

        .proposal-benefit-card h3,
        .proposal-why-card h3 {
            margin: 16px 0 0;
            font-size: 15px;
            line-height: 1.35;
            font-weight: 900;
        }

        .proposal-benefit-card p,
        .proposal-why-card p {
            margin: 9px 0 0;
            color: var(--proposal-muted);
            font-size: 11px;
            line-height: 1.7;
        }
                .proposal-next-steps {
            position: relative;
            display: grid;
            gap: 18px;
        }

        .proposal-next-steps::before {
            content: "";
            position: absolute;
            top: 28px;
            bottom: 28px;
            left: 27px;
            width: 2px;
            background: linear-gradient(
                180deg,
                var(--proposal-primary),
                var(--proposal-secondary)
            );
        }

        .proposal-next-step {
            position: relative;
            display: grid;
            grid-template-columns: 56px 1fr;
            gap: 20px;
            align-items: flex-start;
        }

        .proposal-next-step > span {
            position: relative;
            z-index: 2;
            display: inline-flex;
            width: 56px;
            height: 56px;
            align-items: center;
            justify-content: center;
            border: 6px solid #FFFFFF;
            border-radius: 18px;
            color: #FFFFFF;
            background: linear-gradient(
                135deg,
                var(--proposal-primary),
                var(--proposal-secondary)
            );
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.22);
            font-size: 11px;
            font-weight: 950;
        }

        .proposal-next-step > div {
            padding: 20px 22px;
            border: 1px solid var(--proposal-border);
            border-radius: 20px;
            background: #FFFFFF;
        }

        .proposal-next-step h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 900;
        }

        .proposal-next-step p {
            margin: 8px 0 0;
            color: var(--proposal-muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .proposal-approval-box {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-top: 28px;
            padding: 22px;
            border: 1px solid var(--proposal-border);
            border-radius: 22px;
            background: var(--proposal-soft);
        }

        .proposal-approval-box small {
            display: block;
            color: var(--proposal-muted);
            font-size: 9px;
            font-weight: 850;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .proposal-approval-box strong {
            display: block;
            margin-top: 8px;
            font-size: 12px;
            font-weight: 900;
        }

        .proposal-signature-page {
            display: flex;
            min-height: 1120px;
            flex-direction: column;
            justify-content: space-between;
            color: #FFFFFF;
            background:
                radial-gradient(
                    circle at 82% 18%,
                    rgba(255, 255, 255, 0.2),
                    transparent 24%
                ),
                radial-gradient(
                    circle at 14% 82%,
                    rgba(14, 165, 233, 0.26),
                    transparent 28%
                ),
                linear-gradient(
                    145deg,
                    #0F172A 0%,
                    #1E3A8A 50%,
                    #7C3AED 100%
                );
        }

        .proposal-signature-main {
            position: relative;
            z-index: 2;
            max-width: 820px;
        }

        .proposal-signature-page .proposal-section-eyebrow {
            color: #BFDBFE;
        }

        .proposal-signature-page h2 {
            max-width: 720px;
            margin: 0;
            font-size: 48px;
            line-height: 1.08;
            font-weight: 950;
            letter-spacing: -0.045em;
        }

        .proposal-signature-main > p {
            max-width: 720px;
            margin: 24px 0 0;
            color: rgba(255, 255, 255, 0.72);
            font-size: 15px;
            line-height: 1.8;
        }

        .proposal-signature-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
            margin-top: 54px;
        }

        .proposal-signature-card {
            padding: 26px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
        }

        .proposal-signature-card small {
            display: block;
            color: rgba(255, 255, 255, 0.58);
            font-size: 9px;
            font-weight: 850;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .proposal-signature-card strong {
            display: block;
            margin-top: 10px;
            font-size: 17px;
            font-weight: 900;
        }

        .proposal-signature-card span {
            display: block;
            margin-top: 4px;
            color: rgba(255, 255, 255, 0.66);
            font-size: 11px;
        }

        .proposal-signature-line {
            height: 1px;
            margin-top: 58px;
            background: rgba(255, 255, 255, 0.4);
        }

        .proposal-signature-card p {
            margin: 8px 0 0;
            color: rgba(255, 255, 255, 0.58);
            font-size: 9px;
            text-transform: uppercase;
        }

        .proposal-contact-box {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-top: 26px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.07);
        }

        .proposal-contact-box small {
            display: block;
            color: rgba(255, 255, 255, 0.56);
            font-size: 9px;
            font-weight: 850;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .proposal-contact-box strong {
            display: block;
            margin-top: 7px;
            color: #FFFFFF;
            font-size: 11px;
            font-weight: 850;
            word-break: break-word;
        }

        .proposal-signature-footer {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.14);
        }

        .proposal-signature-footer p {
            margin: 0;
            color: rgba(255, 255, 255, 0.64);
            font-size: 11px;
        }

        .proposal-signature-footer span {
            color: rgba(255, 255, 255, 0.58);
            font-size: 12px;
            font-weight: 900;
        }

        @media (max-width: 767px) {
            body {
                padding: 14px;
            }

            .proposal-page {
                min-height: auto;
                padding: 28px;
                border-radius: 20px;
            }

            .proposal-cover {
                min-height: 780px;
            }

            .proposal-cover h1 {
                font-size: 42px;
            }

            .proposal-cover-grid {
                grid-template-columns: 1fr;
            }

            .proposal-cover-bottom {
                align-items: flex-start;
                flex-direction: column;
            }

             .proposal-section-header {
                flex-direction: column;
            }

            .proposal-kpi-grid,
            .proposal-content-grid,
            .proposal-content-grid-balanced,
            .proposal-assessment-grid {
                grid-template-columns: 1fr;
            }

                        .proposal-solution-banner {
                align-items: flex-start;
                flex-direction: column;
            }

            .proposal-service-grid {
                grid-template-columns: 1fr;
            }

             .proposal-investment-layout {
                grid-template-columns: 1fr;
            }

            .proposal-benefit-grid,
            .proposal-why-grid {
                grid-template-columns: 1fr;
            }

            .proposal-investment-total {
                align-items: flex-start;
                flex-direction: column;
            }

            .proposal-investment-total strong {
                text-align: left;
            }

                        .proposal-approval-box,
            .proposal-signature-grid,
            .proposal-contact-box {
                grid-template-columns: 1fr;
            }

            .proposal-signature-page {
                min-height: 820px;
            }

            .proposal-signature-page h2 {
                font-size: 38px;
            }

        }

                @page {
            size: A4;
            margin: 0;
        }

        @media print {
            html,
            body {
                width: 210mm;
                margin: 0;
                padding: 0;
                background: #FFFFFF;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .proposal-document {
                width: 210mm;
                max-width: none;
                margin: 0;
            }

            .proposal-page {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 18mm;
                border-radius: 0;
                box-shadow: none;
                page-break-after: always;
                break-after: page;
                overflow: hidden;
            }

            .proposal-page:last-child {
                page-break-after: auto;
                break-after: auto;
            }

            .proposal-cover,
            .proposal-signature-page {
                min-height: 297mm;
            }

            .proposal-content-card,
            .proposal-kpi-card,
            .proposal-service-card,
            .proposal-assessment-card,
            .proposal-benefit-card,
            .proposal-why-card,
            .proposal-investment-card,
            .proposal-value-card,
            .proposal-timeline-content,
            .proposal-next-step > div {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .proposal-service-grid,
            .proposal-benefit-grid,
            .proposal-why-grid,
            .proposal-kpi-grid,
            .proposal-assessment-grid,
            .proposal-content-grid,
            .proposal-investment-layout,
            .proposal-signature-grid,
            .proposal-contact-box,
            .proposal-approval-box {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            a {
                color: inherit;
                text-decoration: none;
            }
        }

        @media screen and (min-width: 768px) {
            .proposal-page {
                transition:
                    transform 0.2s ease,
                    box-shadow 0.2s ease;
            }

            .proposal-page:hover {
                transform: translateY(-2px);
                box-shadow:
                    0 28px 80px rgba(15, 23, 42, 0.18);
            }
        }
    </style>
</head>

<body>

<div class="proposal-document">

    <section class="proposal-page proposal-cover">
        

        <div class="proposal-cover-top">

            <div class="proposal-brand">

                <div class="proposal-brand-mark">
                    LP
                </div>

                <div class="proposal-brand-text">
                    <strong>
                        {{ $prepared_by ?? config('app.name', 'WebApp Infoway') }}
                    </strong>

                    <span>
                        AI Digital Transformation Consultant
                    </span>
                </div>

            </div>

            <span class="proposal-confidential">
                Confidential Proposal
            </span>

        </div>

        <div class="proposal-cover-main">

            <span class="proposal-cover-eyebrow">
                Strategic Business Proposal
            </span>

            <h1>
                {{ $proposalTitle ?? $proposal_title ?? 'Digital Transformation Proposal' }}
            </h1>

            <p class="proposal-cover-subtitle">
                A tailored digital growth, technology and automation proposal
                prepared specifically for {{ $prepared_for ?? $company->company_name }}.
            </p>

            <div class="proposal-cover-grid">

                <div class="proposal-cover-meta">
                    <small>Prepared For</small>

                    <strong>
                        {{ $prepared_for ?? $company->company_name }}
                    </strong>
                </div>

                <div class="proposal-cover-meta">
                    <small>Prepared By</small>

                    <strong>
                        {{ $prepared_by ?? config('app.name', 'WebApp Infoway') }}
                    </strong>
                </div>

                <div class="proposal-cover-meta">
                    <small>Proposal Date</small>

                    <strong>
                        {{ $generated_date ?? now()->format('d M Y') }}
                    </strong>
                </div>

            </div>

        </div>

        <div class="proposal-cover-bottom">

            <p class="proposal-cover-note">
                This document contains confidential recommendations prepared
                exclusively for {{ $prepared_for ?? $company->company_name }}.
                The contents should not be shared with third parties without
                prior written permission.
            </p>

            <span class="proposal-cover-page-number">
                01
            </span>

        </div>

    </section>

        <section class="proposal-page">

        <div class="proposal-section-header">

            <div>
                <span class="proposal-section-eyebrow">
                    Next Steps
                </span>

                <h2>
                    Recommended Path Forward
                </h2>

                <p>
                    A practical sequence to move from proposal review to project kickoff.
                </p>
            </div>

            <span class="proposal-page-number">
                07
            </span>

        </div>

        <div class="proposal-next-steps">

            <div class="proposal-next-step">

                <span>
                    01
                </span>

                <div>
                    <h3>
                        Discovery Meeting
                    </h3>

                    <p>
                        Confirm priorities, stakeholders, current systems and desired business outcomes.
                    </p>
                </div>

            </div>

            <div class="proposal-next-step">

                <span>
                    02
                </span>

                <div>
                    <h3>
                        Requirement Validation
                    </h3>

                    <p>
                        Review workflows, finalize scope and confirm the recommended implementation phases.
                    </p>
                </div>

            </div>

            <div class="proposal-next-step">

                <span>
                    03
                </span>

                <div>
                    <h3>
                        Commercial Approval
                    </h3>

                    <p>
                        Approve pricing, timeline, payment milestones and delivery responsibilities.
                    </p>
                </div>

            </div>

            <div class="proposal-next-step">

                <span>
                    04
                </span>

                <div>
                    <h3>
                        Project Kickoff
                    </h3>

                    <p>
                        Begin discovery, assign project stakeholders and start the agreed first phase.
                    </p>
                </div>

            </div>

        </div>

        <div class="proposal-content-grid proposal-content-grid-balanced mt-24">

            <div class="proposal-content-card">

                <div class="proposal-content-card-heading">

                    <span class="proposal-content-icon">
                        10
                    </span>

                    <div>
                        <h3>
                            Immediate Recommended Action
                        </h3>

                        <p>
                            Suggested next step for this opportunity.
                        </p>
                    </div>

                </div>

                <div class="proposal-copy">
                    {!! nl2br(e(
                        $next_best_action
                        ?? 'Schedule a discovery meeting with the relevant decision-maker.'
                    )) !!}
                </div>

            </div>

            <div class="proposal-content-card">

                <div class="proposal-content-card-heading">

                    <span class="proposal-content-icon proposal-content-icon-purple">
                        11
                    </span>

                    <div>
                        <h3>
                            Key Decision Makers
                        </h3>

                        <p>
                            Recommended stakeholders to involve.
                        </p>
                    </div>

                </div>

                <div class="proposal-copy proposal-preline">
                    {{ $decision_makers
                        ?? 'Owner, founder, director, operations head, marketing head or technology decision-maker.' }}
                </div>

            </div>

        </div>

        <div class="proposal-approval-box">

            <div>
                <small>
                    Proposal Validity
                </small>

                <strong>
                    30 Days From Proposal Date
                </strong>
            </div>

            <div>
                <small>
                    Implementation Start
                </small>

                <strong>
                    Subject to Approval and Availability
                </strong>
            </div>

            <div>
                <small>
                    Commercial Terms
                </small>

                <strong>
                    Finalized During Discovery
                </strong>
            </div>

        </div>

    </section>

    <section class="proposal-page proposal-signature-page">

        <div class="proposal-signature-main">

            <span class="proposal-section-eyebrow">
                Proposal Acceptance
            </span>

            <h2>
                Let’s Build the Next Phase of Growth
            </h2>

            <p>
                This proposal has been prepared to provide a clear starting point.
                Final scope, timeline and commercial terms will be confirmed after
                the discovery and requirement-validation process.
            </p>

            <div class="proposal-signature-grid">

                <div class="proposal-signature-card">

                    <small>
                        Prepared By
                    </small>

                    <strong>
                        {{ $prepared_by ?? config('app.name', 'WebApp Infoway') }}
                    </strong>

                    <span>
                        AI Digital Transformation Consultant
                    </span>

                    <div class="proposal-signature-line"></div>

                    <p>
                        Authorized Signature
                    </p>

                </div>

                <div class="proposal-signature-card">

                    <small>
                        Accepted By
                    </small>

                    <strong>
                        {{ $prepared_for ?? $company->company_name }}
                    </strong>

                    <span>
                        Client Representative
                    </span>

                    <div class="proposal-signature-line"></div>

                    <p>
                        Authorized Signature
                    </p>

                </div>

            </div>

            <div class="proposal-contact-box">

                <div>
                    <small>
                        Website
                    </small>

                    <strong>
                        {{ config('app.url') ?: 'https://webappinfoway.com' }}
                    </strong>
                </div>

                <div>
                    <small>
                        Proposal Date
                    </small>

                    <strong>
                        {{ $generated_date ?? now()->format('d M Y') }}
                    </strong>
                </div>

                <div>
                    <small>
                        Prepared For
                    </small>

                    <strong>
                        {{ $prepared_for ?? $company->company_name }}
                    </strong>
                </div>

            </div>

        </div>

        <div class="proposal-signature-footer">

            <p>
                Thank you for the opportunity to present this proposal.
            </p>

            <span>
                08
            </span>

        </div>

    </section>
</div>

</body>
</html>

