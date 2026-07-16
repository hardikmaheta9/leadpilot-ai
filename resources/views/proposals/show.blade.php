<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $proposal->proposal_title }}</title>

    <style>
        * { box-sizing: border-box; }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #dce5f1;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .proposal-viewer-toolbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 14px 24px;
            border-bottom: 1px solid #dbe4f0;
            background: rgba(255,255,255,.97);
            box-shadow: 0 8px 24px rgba(15,23,42,.09);
        }

        .proposal-viewer-title h1 {
            margin: 0;
            color: #0b1636;
            font-size: 18px;
            line-height: 1.3;
        }

        .proposal-viewer-title p {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 12px;
        }

        .proposal-viewer-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .proposal-viewer-btn {
            display: inline-flex;
            min-height: 40px;
            padding: 10px 16px;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .proposal-viewer-btn-primary {
            color: #fff;
            background: #2563eb;
        }

        .proposal-viewer-btn-light {
            color: #0b1636;
            border-color: #dbe4f0;
            background: #fff;
        }

        .proposal-viewer-content {
            padding: 28px 0;
        }

        @media (max-width: 767px) {
            .proposal-viewer-toolbar {
                align-items: flex-start;
                flex-direction: column;
                padding: 14px;
            }

            .proposal-viewer-actions {
                width: 100%;
            }

            .proposal-viewer-btn {
                flex: 1;
            }

            .proposal-viewer-content {
                padding: 12px 0;
            }
        }

        @media print {
            .proposal-viewer-toolbar {
                display: none !important;
            }

            .proposal-viewer-content {
                padding: 0 !important;
            }

            html,
            body {
                background: #fff !important;
            }
        }
    </style>
</head>

<body>

<header class="proposal-viewer-toolbar">
    <div class="proposal-viewer-title">
        <h1>{{ $proposal->proposal_title }}</h1>
        <p>
            Version {{ $proposal->version }}
            ·
            {{ optional($proposal->generated_at)->format('d M Y H:i') }}
        </p>
    </div>

    <div class="proposal-viewer-actions">
        <button
            type="button"
            onclick="window.print()"
            class="proposal-viewer-btn proposal-viewer-btn-primary"
        >
            Print / Save PDF
        </button>

        <a
            href="{{ route('companies.show', [
                'uuid' => $company->uuid,
                'tab' => 'ai',
            ]) }}"
            class="proposal-viewer-btn proposal-viewer-btn-light"
        >
            Back to AI Insights
        </a>
    </div>
</header>

<main class="proposal-viewer-content">
    {!! $proposal->proposal_html !!}
</main>

</body>
</html>
