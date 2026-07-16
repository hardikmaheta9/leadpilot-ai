<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $proposal->proposal_title }}</title>
    <style>
        *{box-sizing:border-box}html,body{margin:0;padding:0;background:#dce5f1;font-family:"Segoe UI",Arial,sans-serif;color:#0f172a}
        .pp-toolbar{position:sticky;top:0;z-index:1000;display:flex;align-items:center;justify-content:space-between;gap:20px;padding:14px 24px;border-bottom:1px solid #dbe4f0;background:rgba(255,255,255,.97);box-shadow:0 8px 24px rgba(15,23,42,.09)}
        .pp-title h1{margin:0;color:#0b1636;font-size:18px;line-height:1.3}.pp-title p{margin:4px 0 0;color:#64748b;font-size:12px}
        .pp-actions{display:flex;gap:10px;flex-wrap:wrap}.pp-btn{display:inline-flex;min-height:40px;padding:10px 16px;align-items:center;justify-content:center;border:1px solid transparent;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none;cursor:pointer}.pp-btn-primary{color:#fff;background:#2563eb}.pp-btn-light{color:#0b1636;border-color:#dbe4f0;background:#fff}
        .pp-content{padding:28px 0}.pp-response{width:calc(100% - 32px);max-width:980px;margin:28px auto 40px;padding:28px;border:1px solid #dbe4f0;border-radius:20px;background:#fff;box-shadow:0 18px 48px rgba(15,23,42,.1)}
        .pp-response h2{margin:0;color:#0b1636;font-size:24px}.pp-response p{margin:10px 0 0;color:#64748b;font-size:14px;line-height:1.65}.pp-response textarea{width:100%;min-height:120px;margin-top:20px;padding:14px 16px;resize:vertical;border:1px solid #cbd5e1;border-radius:12px;font:inherit;color:#0f172a;background:#fff}
        .pp-response-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}.pp-action{display:inline-flex;min-height:42px;padding:11px 17px;align-items:center;justify-content:center;border:0;border-radius:10px;color:#fff;font-size:13px;font-weight:800;cursor:pointer}.pp-accept{background:#059669}.pp-reject{background:#dc2626}.pp-changes{background:#d97706}
        .pp-message{width:calc(100% - 32px);max-width:980px;margin:24px auto 0;padding:16px 18px;border-left:5px solid #059669;border-radius:12px;background:#ecfdf5;color:#065f46;font-weight:700}.pp-status{display:inline-flex;margin-top:18px;padding:9px 13px;border-radius:999px;background:#eef2ff;color:#3730a3;font-size:12px;font-weight:800}
        @media(max-width:767px){.pp-toolbar{align-items:flex-start;flex-direction:column;padding:14px}.pp-actions{width:100%}.pp-btn{flex:1}.pp-content{padding:12px 0}.pp-response{padding:20px}.pp-response-actions{flex-direction:column}.pp-action{width:100%}}
        @media print{.pp-toolbar,.pp-response,.pp-message{display:none!important}.pp-content{padding:0!important}html,body{background:#fff!important}}
    </style>
</head>
<body>
<header class="pp-toolbar">
    <div class="pp-title">
        <h1>{{ $proposal->proposal_title }}</h1>
        <p>Version {{ $proposal->version }} · {{ optional($proposal->generated_at)->format('d M Y H:i') }}</p>
    </div>
    <div class="pp-actions">
        <a href="{{ route('proposals.public.download', $proposal->public_token) }}" class="pp-btn pp-btn-primary">Download PDF</a>
        <button type="button" onclick="window.print()" class="pp-btn pp-btn-light">Print</button>
    </div>
</header>

@if(session('success'))
    <div class="pp-message">{{ session('success') }}</div>
@endif

<main class="pp-content">
    {!! $proposal->proposal_html !!}
</main>

<section class="pp-response">
    <h2>Respond to this Proposal</h2>
    <p>You may accept the proposal, reject it, or request changes. Add an optional note before submitting your response.</p>

    @if($proposal->isTerminal())
        <span class="pp-status">Current status: {{ \Illuminate\Support\Str::headline($proposal->proposal_status) }}</span>
        @if($proposal->client_response_note)
            <p style="margin-top:14px;"><strong>Your note:</strong> {{ $proposal->client_response_note }}</p>
        @endif
    @else
        <form method="POST" action="{{ route('proposals.public.respond', $proposal->public_token) }}">
            @csrf
            <textarea name="note" placeholder="Optional note or requested changes">{{ old('note') }}</textarea>
            @error('note')
                <div style="margin-top:8px;color:#dc2626;font-size:12px;">{{ $message }}</div>
            @enderror
            <div class="pp-response-actions">
                <button type="submit" name="action" value="accept" class="pp-action pp-accept">Accept Proposal</button>
                <button type="submit" name="action" value="reject" class="pp-action pp-reject">Reject Proposal</button>
                <button type="submit" name="action" value="changes" class="pp-action pp-changes">Request Changes</button>
            </div>
        </form>
    @endif
</section>
</body>
</html>
