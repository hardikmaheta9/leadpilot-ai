<?php

namespace App\Services\Documents;

use App\Mail\ProposalMail;
use App\Models\CompanyAiProposal;
use Illuminate\Support\Facades\Mail;

class ProposalDeliveryService
{
    public function __construct(
        private ProposalPdfService $pdfService
    ) {
    }

    public function send(
        CompanyAiProposal $proposal,
        string $to,
        string $subject,
        string $message,
        ?string $cc = null,
        ?string $bcc = null,
        ?int $sentBy = null,
    ): void {
        $publicUrl = route('proposals.public.show', $proposal->public_token);
        $pdfAbsolutePath = $this->pdfService->absolutePath($proposal);

        $mailer = Mail::to($to);

        if ($cc) {
            $mailer->cc(array_filter(array_map('trim', explode(',', $cc))));
        }

        if ($bcc) {
            $mailer->bcc(array_filter(array_map('trim', explode(',', $bcc))));
        }

        $mailer->send(new ProposalMail(
            proposal: $proposal,
            messageBody: $message,
            publicUrl: $publicUrl,
            pdfAbsolutePath: $pdfAbsolutePath,
            customSubject: $subject,
        ));

        $proposal->forceFill([
            'proposal_status' => 'sent',
            'email_sent_to' => $to,
            'email_sent_by' => $sentBy,
            'sent_at' => now(),
        ])->save();
    }
}
