<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $proposalTitle ?? 'Enterprise Digital Transformation Proposal' }}</title>

    <style>
        :root {
            --ep-navy: #0b1636;
            --ep-navy-2: #152a66;
            --ep-blue: #2563eb;
            --ep-blue-soft: #eaf2ff;
            --ep-purple: #7c3aed;
            --ep-purple-soft: #f1eaff;
            --ep-cyan: #0891b2;
            --ep-cyan-soft: #e7f9fd;
            --ep-green: #059669;
            --ep-green-soft: #e9fbf4;
            --ep-orange: #d97706;
            --ep-orange-soft: #fff6e6;
            --ep-red: #dc2626;
            --ep-red-soft: #fff0f0;
            --ep-gold: #b7791f;
            --ep-text: #111827;
            --ep-body: #334155;
            --ep-muted: #64748b;
            --ep-border: #dbe4f0;
            --ep-soft: #f7f9fc;
            --ep-white: #ffffff;
            --ep-radius: 20px;
            --ep-shadow: 0 22px 55px rgba(15, 23, 42, .13);
        }

        * {
            box-sizing: border-box;
        }

        html {
            background: #dce5f1;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #dce5f1;
            color: var(--ep-text);
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, p, ul, ol, dl {
            margin-top: 0;
        }

        p {
            margin-bottom: 0;
        }

        .ep-document {
            width: 100%;
            max-width: 980px;
            margin: 0 auto;
        }

        .ep-page {
            position: relative;
            display: flex;
            min-height: 1120px;
            margin: 0 0 30px;
            padding: 54px 58px 34px;
            overflow: hidden;
            flex-direction: column;
            border-radius: 28px;
            background:
                linear-gradient(180deg, rgba(37, 99, 235, .035), transparent 210px),
                var(--ep-white);
            box-shadow: var(--ep-shadow);
        }

        .ep-page::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 7px;
            background: linear-gradient(90deg, var(--ep-blue), var(--ep-purple), var(--ep-cyan));
        }

        .ep-page-content {
            position: relative;
            z-index: 1;
            flex: 1;
        }

        .ep-section-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 28px;
            margin-bottom: 28px;
            padding-bottom: 22px;
            border-bottom: 1px solid var(--ep-border);
        }

        .ep-section-eyebrow {
            display: block;
            margin-bottom: 7px;
            color: var(--ep-blue);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .13em;
            text-transform: uppercase;
        }

        .ep-section-header h2 {
            margin: 0;
            color: var(--ep-navy);
            font-size: 32px;
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -.025em;
        }

        .ep-section-header p {
            max-width: 680px;
            margin-top: 10px;
            color: var(--ep-muted);
            font-size: 13px;
            line-height: 1.65;
        }

        .ep-section-badge {
            display: inline-flex;
            min-width: 46px;
            height: 34px;
            padding: 0 12px;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: var(--ep-blue-soft);
            color: var(--ep-blue);
            font-size: 11px;
            font-weight: 800;
        }

        .ep-footer {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-top: auto;
            padding-top: 18px;
            border-top: 1px solid var(--ep-border);
            color: var(--ep-muted);
            font-size: 10px;
        }

        .ep-footer strong {
            color: var(--ep-navy);
            font-size: 11px;
        }

        .ep-card {
            overflow: hidden;
            border: 1px solid var(--ep-border);
            border-radius: var(--ep-radius);
            background: var(--ep-white);
            box-shadow: 0 10px 26px rgba(15, 23, 42, .055);
        }

        .ep-card-header {
            padding: 17px 20px;
            border-bottom: 1px solid var(--ep-border);
            background: linear-gradient(180deg, #fbfdff, #f5f8fc);
        }

        .ep-card-header h3 {
            margin: 0;
            color: var(--ep-navy);
            font-size: 15px;
            font-weight: 800;
        }

        .ep-card-header p {
            margin-top: 4px;
            color: var(--ep-muted);
            font-size: 11px;
        }

        .ep-card-body {
            padding: 20px;
            color: var(--ep-body);
        }

        .ep-grid-2,
        .ep-grid-3,
        .ep-grid-4,
        .ep-grid-6 {
            display: grid;
            gap: 18px;
        }

        .ep-grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .ep-grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .ep-grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        .ep-grid-6 { grid-template-columns: repeat(3, minmax(0, 1fr)); }

        .ep-mt-18 { margin-top: 18px; }
        .ep-mt-24 { margin-top: 24px; }
        .ep-mb-18 { margin-bottom: 18px; }
        .ep-mb-24 { margin-bottom: 24px; }

        .ep-label-value {
            display: grid;
            grid-template-columns: minmax(125px, .65fr) 1.35fr;
            gap: 18px;
            align-items: start;
            padding: 12px 0;
            border-bottom: 1px solid var(--ep-border);
        }

        .ep-label-value:last-child {
            border-bottom: 0;
        }

        .ep-label-value dt,
        .ep-label-value .ep-label {
            color: var(--ep-muted);
            font-size: 11px;
            font-weight: 700;
        }

        .ep-label-value dd,
        .ep-label-value .ep-value {
            margin: 0;
            color: var(--ep-navy);
            font-size: 12px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .ep-copy {
            color: var(--ep-body);
            font-size: 13px;
            line-height: 1.8;
        }

        .ep-list {
            display: grid;
            gap: 10px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .ep-list li {
            position: relative;
            padding-left: 24px;
            color: var(--ep-body);
            font-size: 12px;
            line-height: 1.65;
        }

        .ep-list li::before {
            content: "✓";
            position: absolute;
            top: 1px;
            left: 0;
            display: inline-flex;
            width: 17px;
            height: 17px;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--ep-green-soft);
            color: var(--ep-green);
            font-size: 10px;
            font-weight: 900;
        }

        .ep-numbered-row {
            display: grid;
            grid-template-columns: 44px 1fr;
            gap: 16px;
            align-items: start;
        }

        .ep-number-badge {
            display: inline-flex;
            width: 44px;
            height: 44px;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: #fff;
            background: linear-gradient(135deg, var(--ep-blue), var(--ep-purple));
            box-shadow: 0 8px 20px rgba(37, 99, 235, .2);
            font-size: 11px;
            font-weight: 900;
        }

        .ep-progress {
            height: 8px;
            margin-top: 12px;
            overflow: hidden;
            border-radius: 999px;
            background: #e8edf5;
        }

        .ep-progress > span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, var(--ep-blue), var(--ep-cyan));
        }

        .ep-progress-purple > span {
            background: linear-gradient(90deg, var(--ep-purple), #a855f7);
        }

        .ep-progress-green > span {
            background: linear-gradient(90deg, var(--ep-green), #34d399);
        }

        .ep-progress-orange > span {
            background: linear-gradient(90deg, var(--ep-orange), #f59e0b);
        }

        .ep-status-pill {
            display: inline-flex;
            padding: 6px 10px;
            align-items: center;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .ep-status-high {
            color: #b91c1c;
            background: var(--ep-red-soft);
        }

        .ep-status-medium {
            color: #92400e;
            background: var(--ep-orange-soft);
        }

        .ep-status-low {
            color: #1d4ed8;
            background: var(--ep-blue-soft);
        }

        .ep-accent-box {
            padding: 22px;
            border: 1px solid #cbdcf8;
            border-left: 5px solid var(--ep-blue);
            border-radius: 18px;
            background: linear-gradient(135deg, #f4f8ff, #fbfdff);
        }

        .ep-accent-box h3 {
            margin: 0 0 8px;
            color: var(--ep-navy);
            font-size: 15px;
        }

        .ep-accent-box p {
            color: var(--ep-body);
            font-size: 12px;
            line-height: 1.7;
        }

        /* Cover */
        .ep-cover {
            justify-content: space-between;
            color: #fff;
            background:
                radial-gradient(circle at 84% 14%, rgba(255,255,255,.18), transparent 25%),
                radial-gradient(circle at 12% 84%, rgba(14,165,233,.26), transparent 28%),
                linear-gradient(145deg, #08132e 0%, #153274 48%, #7034d7 100%);
        }

        .ep-cover::before {
            height: 9px;
            background: linear-gradient(90deg, #38bdf8, #a78bfa, #22d3ee);
        }

        .ep-cover .ep-page-content {
            display: flex;
            flex-direction: column;
        }

        .ep-cover-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .ep-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .ep-brand-mark {
            display: inline-flex;
            width: 58px;
            height: 58px;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,.24);
            border-radius: 18px;
            background: rgba(255,255,255,.12);
            font-size: 18px;
            font-weight: 900;
        }

        .ep-brand-text strong {
            display: block;
            font-size: 18px;
        }

        .ep-brand-text span {
            display: block;
            margin-top: 3px;
            color: rgba(255,255,255,.7);
            font-size: 10px;
            letter-spacing: .11em;
            text-transform: uppercase;
        }

        .ep-cover-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .ep-cover-badge {
            display: inline-flex;
            padding: 8px 12px;
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 999px;
            background: rgba(255,255,255,.09);
            color: #fff;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .ep-cover-main {
            max-width: 790px;
            margin: auto 0;
            padding: 70px 0;
        }

        .ep-cover-eyebrow {
            display: block;
            margin-bottom: 18px;
            color: #bfdbfe;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .17em;
            text-transform: uppercase;
        }

        .ep-cover h1 {
            max-width: 760px;
            margin: 0;
            font-size: 55px;
            line-height: 1.05;
            font-weight: 900;
            letter-spacing: -.045em;
        }

        .ep-cover-subtitle {
            max-width: 680px;
            margin-top: 24px;
            color: rgba(255,255,255,.78);
            font-size: 17px;
            line-height: 1.7;
        }

        .ep-cover-meta-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-top: 38px;
        }

        .ep-cover-meta {
            min-height: 96px;
            padding: 17px;
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 18px;
            background: rgba(255,255,255,.085);
        }

        .ep-cover-meta small {
            display: block;
            color: rgba(255,255,255,.58);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .09em;
            text-transform: uppercase;
        }

        .ep-cover-meta strong {
            display: block;
            margin-top: 9px;
            color: #fff;
            font-size: 13px;
            line-height: 1.45;
        }

        .ep-cover .ep-footer {
            border-color: rgba(255,255,255,.16);
            color: rgba(255,255,255,.62);
        }

        .ep-cover .ep-footer strong {
            color: #fff;
        }

        /* Dashboard */
        .ep-kpi-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .ep-kpi {
            min-height: 156px;
            padding: 20px;
            border: 1px solid var(--ep-border);
            border-radius: 18px;
            background: linear-gradient(145deg, #fff, #f8fbff);
        }

        .ep-kpi:nth-child(2) { background: linear-gradient(145deg, #fff, var(--ep-purple-soft)); }
        .ep-kpi:nth-child(3) { background: linear-gradient(145deg, #fff, var(--ep-green-soft)); }
        .ep-kpi:nth-child(4) { background: linear-gradient(145deg, #fff, var(--ep-orange-soft)); }
        .ep-kpi:nth-child(5) { background: linear-gradient(145deg, #fff, var(--ep-cyan-soft)); }
        .ep-kpi:nth-child(6) { background: linear-gradient(145deg, #fff, var(--ep-blue-soft)); }

        .ep-kpi-label {
            display: block;
            color: var(--ep-muted);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .ep-kpi-value {
            display: block;
            margin-top: 9px;
            color: var(--ep-navy);
            font-size: 26px;
            line-height: 1;
            font-weight: 900;
        }

        .ep-kpi-note {
            display: block;
            margin-top: 10px;
            color: var(--ep-muted);
            font-size: 10px;
        }

        /* SWOT */
        .ep-swot {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .ep-swot-item {
            min-height: 150px;
            padding: 18px;
            border-radius: 18px;
        }

        .ep-swot-item h3 {
            margin: 0 0 9px;
            font-size: 14px;
        }

        .ep-swot-item p {
            color: var(--ep-body);
            font-size: 11px;
            line-height: 1.65;
        }

        .ep-swot-strength { background: var(--ep-green-soft); border: 1px solid #b8ecd9; }
        .ep-swot-weakness { background: var(--ep-red-soft); border: 1px solid #ffd2d2; }
        .ep-swot-opportunity { background: var(--ep-blue-soft); border: 1px solid #cbdcf8; }
        .ep-swot-risk { background: var(--ep-orange-soft); border: 1px solid #f4d9aa; }

        /* Audit */
        .ep-audit-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .ep-audit-card {
            padding: 18px;
            border: 1px solid var(--ep-border);
            border-radius: 18px;
            background: #fff;
        }

        .ep-audit-card small {
            display: block;
            color: var(--ep-muted);
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .ep-audit-card strong {
            display: block;
            margin-top: 8px;
            color: var(--ep-navy);
            font-size: 21px;
            font-weight: 900;
        }

        .ep-status-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .ep-status-card {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            align-items: center;
            min-height: 72px;
            padding: 15px 17px;
            border: 1px solid var(--ep-border);
            border-radius: 16px;
            background: var(--ep-soft);
        }

        .ep-status-card span {
            color: var(--ep-muted);
            font-size: 11px;
            font-weight: 700;
        }

        .ep-status-card strong {
            color: var(--ep-navy);
            font-size: 12px;
            font-weight: 900;
        }

        /* Opportunities / services */
        .ep-opportunity-grid,
        .ep-service-grid,
        .ep-benefit-grid,
        .ep-why-grid,
        .ep-case-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .ep-opportunity-card,
        .ep-service-card,
        .ep-benefit-card,
        .ep-why-card,
        .ep-case-card {
            padding: 19px;
            border: 1px solid var(--ep-border);
            border-radius: 18px;
            background: linear-gradient(145deg, #fff, #f8fbff);
            box-shadow: 0 8px 20px rgba(15,23,42,.045);
        }

        .ep-opportunity-top,
        .ep-service-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
        }

        .ep-opportunity-card h3,
        .ep-service-card h3,
        .ep-benefit-card h3,
        .ep-why-card h3,
        .ep-case-card h3 {
            margin: 0;
            color: var(--ep-navy);
            font-size: 14px;
            line-height: 1.35;
        }

        .ep-opportunity-card > p,
        .ep-service-card > p,
        .ep-benefit-card > p,
        .ep-why-card > p,
        .ep-case-card > p {
            margin-top: 9px;
            color: var(--ep-muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .ep-opportunity-meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
            margin-top: 13px;
            padding-top: 13px;
            border-top: 1px solid var(--ep-border);
        }

        .ep-opportunity-meta div {
            min-width: 0;
        }

        .ep-opportunity-meta small {
            display: block;
            color: var(--ep-muted);
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .ep-opportunity-meta strong {
            display: block;
            margin-top: 5px;
            color: var(--ep-navy);
            font-size: 10px;
            line-height: 1.35;
            overflow-wrap: anywhere;
        }

        .ep-service-index,
        .ep-why-index {
            display: inline-flex;
            width: 38px;
            height: 38px;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 12px;
            color: var(--ep-blue);
            background: var(--ep-blue-soft);
            font-size: 10px;
            font-weight: 900;
        }

        /* Roadmap */
        .ep-roadmap {
            position: relative;
            display: grid;
            gap: 14px;
        }

        .ep-roadmap::before {
            content: "";
            position: absolute;
            top: 22px;
            bottom: 22px;
            left: 22px;
            width: 2px;
            background: linear-gradient(180deg, var(--ep-blue), var(--ep-purple));
        }

        .ep-roadmap-item {
            position: relative;
            display: grid;
            grid-template-columns: 44px 1fr;
            gap: 15px;
            align-items: start;
        }

        .ep-roadmap-card {
            min-height: 94px;
            padding: 17px 19px;
            border: 1px solid var(--ep-border);
            border-radius: 17px;
            background: #fff;
        }

        .ep-roadmap-card h3 {
            margin: 0;
            color: var(--ep-navy);
            font-size: 14px;
        }

        .ep-roadmap-card p {
            margin-top: 7px;
            color: var(--ep-muted);
            font-size: 11px;
        }

        /* Investment */
        .ep-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            border: 1px solid var(--ep-border);
            border-radius: 18px;
        }

        .ep-table th,
        .ep-table td {
            padding: 13px 14px;
            border-bottom: 1px solid var(--ep-border);
            vertical-align: top;
            text-align: left;
        }

        .ep-table th {
            color: #fff;
            background: linear-gradient(90deg, var(--ep-navy-2), var(--ep-purple));
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .ep-table td {
            color: var(--ep-body);
            font-size: 11px;
        }

        .ep-table tr:last-child td {
            border-bottom: 0;
        }

        .ep-table td:last-child,
        .ep-table th:last-child {
            text-align: right;
        }

        .ep-investment-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-top: 16px;
            padding: 18px 20px;
            border-radius: 18px;
            color: #fff;
            background: linear-gradient(135deg, var(--ep-navy-2), var(--ep-purple));
        }

        .ep-investment-total span {
            font-size: 11px;
            font-weight: 700;
        }

        .ep-investment-total strong {
            font-size: 20px;
            font-weight: 900;
        }

        .ep-payment-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .ep-payment-card {
            padding: 17px;
            border-radius: 17px;
            background: var(--ep-soft);
            border: 1px solid var(--ep-border);
        }

        .ep-payment-card strong {
            display: block;
            color: var(--ep-blue);
            font-size: 20px;
        }

        .ep-payment-card span {
            display: block;
            margin-top: 6px;
            color: var(--ep-muted);
            font-size: 10px;
        }

        /* Terms / signature */
        .ep-term-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .ep-term-card {
            min-height: 118px;
            padding: 17px;
            border: 1px solid var(--ep-border);
            border-radius: 16px;
            background: var(--ep-soft);
        }

        .ep-term-card h3 {
            margin: 0;
            color: var(--ep-navy);
            font-size: 13px;
        }

        .ep-term-card p {
            margin-top: 7px;
            color: var(--ep-muted);
            font-size: 10px;
            line-height: 1.6;
        }

        .ep-signature {
            color: #fff;
            background:
                radial-gradient(circle at 84% 14%, rgba(255,255,255,.18), transparent 25%),
                linear-gradient(145deg, #08132e 0%, #153274 52%, #7034d7 100%);
        }

        .ep-signature::before {
            background: linear-gradient(90deg, #38bdf8, #a78bfa, #22d3ee);
        }

        .ep-signature h2 {
            margin: 0;
            max-width: 760px;
            font-size: 42px;
            line-height: 1.1;
        }

        .ep-signature-intro {
            max-width: 720px;
            margin-top: 18px;
            color: rgba(255,255,255,.74);
            font-size: 13px;
            line-height: 1.75;
        }

        .ep-signature-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-top: 38px;
        }

        .ep-signature-card {
            min-height: 220px;
            padding: 22px;
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 19px;
            background: rgba(255,255,255,.08);
        }

        .ep-signature-card small {
            display: block;
            color: rgba(255,255,255,.58);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .ep-signature-card strong {
            display: block;
            margin-top: 9px;
            font-size: 17px;
        }

        .ep-signature-card span {
            display: block;
            margin-top: 4px;
            color: rgba(255,255,255,.68);
            font-size: 10px;
        }

        .ep-sign-line {
            height: 1px;
            margin-top: 66px;
            background: rgba(255,255,255,.42);
        }

        .ep-sign-label {
            margin-top: 8px;
            color: rgba(255,255,255,.55);
            font-size: 9px;
            text-transform: uppercase;
        }

        .ep-contact-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 18px;
        }

        .ep-contact-item {
            padding: 15px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 15px;
            background: rgba(255,255,255,.07);
        }

        .ep-contact-item small {
            display: block;
            color: rgba(255,255,255,.56);
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .ep-contact-item strong {
            display: block;
            margin-top: 6px;
            color: #fff;
            font-size: 10px;
            overflow-wrap: anywhere;
        }

        .ep-signature .ep-footer {
            border-color: rgba(255,255,255,.16);
            color: rgba(255,255,255,.62);
        }

        .ep-signature .ep-footer strong {
            color: #fff;
        }

        @media (max-width: 767px) {
            body { padding: 12px; }

            .ep-page {
                min-height: auto;
                padding: 28px 24px 24px;
                border-radius: 18px;
            }

            .ep-section-header {
                flex-direction: column;
            }

            .ep-grid-2,
            .ep-grid-3,
            .ep-grid-4,
            .ep-grid-6,
            .ep-kpi-grid,
            .ep-audit-grid,
            .ep-status-grid,
            .ep-opportunity-grid,
            .ep-service-grid,
            .ep-benefit-grid,
            .ep-why-grid,
            .ep-case-grid,
            .ep-payment-grid,
            .ep-term-grid,
            .ep-signature-grid,
            .ep-contact-grid,
            .ep-swot {
                grid-template-columns: 1fr;
            }

            .ep-cover-top {
                flex-direction: column;
            }

            .ep-cover-badges {
                justify-content: flex-start;
            }

            .ep-cover h1 {
                font-size: 38px;
            }

            .ep-cover-meta-grid {
                grid-template-columns: 1fr;
            }

            .ep-label-value {
                grid-template-columns: 1fr;
                gap: 5px;
            }

            .ep-opportunity-meta {
                grid-template-columns: 1fr;
            }

            .ep-table {
                display: block;
                overflow-x: auto;
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
                background: #fff;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .ep-document {
                width: 210mm;
                max-width: none;
                margin: 0;
            }

            .ep-page {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 14mm 15mm 10mm;
                border-radius: 0;
                box-shadow: none;
                page-break-after: always;
                break-after: page;
            }

            .ep-page:last-child {
                page-break-after: auto;
                break-after: auto;
            }

            .ep-card,
            .ep-kpi,
            .ep-audit-card,
            .ep-status-card,
            .ep-opportunity-card,
            .ep-service-card,
            .ep-benefit-card,
            .ep-why-card,
            .ep-case-card,
            .ep-roadmap-card,
            .ep-term-card,
            .ep-signature-card,
            .ep-contact-item,
            .ep-table,
            .ep-numbered-row {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .ep-cover,
            .ep-signature {
                min-height: 297mm;
            }
        }
    </style>
</head>
<body>

<div class="ep-document">
    @include('proposals.enterprise.sections.cover')
    @include('proposals.enterprise.sections.dashboard')
    @include('proposals.enterprise.sections.company')
    @include('proposals.enterprise.sections.website')
    @include('proposals.enterprise.sections.opportunities')
    @include('proposals.enterprise.sections.services')
    @include('proposals.enterprise.sections.roadmap')
    @include('proposals.enterprise.sections.investment')
    @include('proposals.enterprise.sections.roi')
    @include('proposals.enterprise.sections.why-us')
    @include('proposals.enterprise.sections.case-studies')
    @include('proposals.enterprise.sections.terms')
    @include('proposals.enterprise.sections.signature')
</div>

</body>
</html>
