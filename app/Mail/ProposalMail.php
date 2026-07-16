<?php

namespace App\Mail;

use App\Models\CompanyAiProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProposalMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public CompanyAiProposal $proposal,
        public string $messageBody,
        public string $publicUrl,
        public string $pdfAbsolutePath,
        public ?string $customSubject = null,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->customSubject ?: $this->proposal->proposal_title
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.proposals.send'
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->pdfAbsolutePath)
                ->as('proposal-v' . $this->proposal->version . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
