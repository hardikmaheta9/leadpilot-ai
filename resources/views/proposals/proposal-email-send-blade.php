<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $proposal->proposal_title }}</title>
</head>

<body style="margin:0;background:#f1f5f9;font-family:Arial,sans-serif;color:#0f172a;">

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f1f5f9;padding:30px 12px;">
    <tr>
        <td align="center">

            <table role="presentation" width="640" cellspacing="0" cellpadding="0" style="width:100%;max-width:640px;background:#ffffff;border-radius:16px;overflow:hidden;">

                <tr>
                    <td style="padding:28px;background:#172554;color:#ffffff;">
                        <div style="font-size:22px;font-weight:700;">
                            WebApp Infoway
                        </div>

                        <div style="margin-top:6px;font-size:13px;opacity:.8;">
                            Enterprise Digital Transformation Proposal
                        </div>
                    </td>
                </tr>

                <tr>
                    <td style="padding:30px;">

                        <div style="white-space:pre-line;font-size:15px;line-height:1.7;">{{ $messageBody }}</div>

                        <div style="margin-top:26px;">

                            <a
                                href="{{ $publicUrl }}"
                                style="display:inline-block;padding:13px 20px;background:#2563eb;color:#ffffff;text-decoration:none;border-radius:10px;font-weight:700;"
                            >
                                Open Secure Proposal
                            </a>

                        </div>

                        <p style="margin-top:24px;color:#64748b;font-size:12px;line-height:1.6;">
                            A PDF copy is attached. The secure proposal link allows
                            you to view, download, accept, reject, or request changes.
                        </p>

                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
