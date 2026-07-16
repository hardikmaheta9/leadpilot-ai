<?php

namespace App\Services\Documents;

use App\Models\CompanyAiProposal;

class ProposalTrackingService
{
    public function recordView(CompanyAiProposal $proposal): void
    {
        $proposal->forceFill([
            'proposal_status' => in_array($proposal->proposal_status, ['draft', 'sent'], true)
                ? 'viewed'
                : $proposal->proposal_status,
            'viewed_at' => $proposal->viewed_at ?? now(),
            'last_viewed_at' => now(),
            'view_count' => ((int) $proposal->view_count) + 1,
        ])->save();
    }

    public function recordDownload(CompanyAiProposal $proposal): void
    {
        $proposal->forceFill([
            'downloaded_at' => $proposal->downloaded_at ?? now(),
            'download_count' => ((int) $proposal->download_count) + 1,
        ])->save();
    }

    public function respond(CompanyAiProposal $proposal, string $action, ?string $note): void
    {
        $updates = ['client_response_note' => $note];

        if ($action === 'accept') {
            $updates += [
                'proposal_status' => 'accepted',
                'accepted_at' => now(),
                'rejected_at' => null,
                'change_requested_at' => null,
            ];
        } elseif ($action === 'reject') {
            $updates += [
                'proposal_status' => 'rejected',
                'rejected_at' => now(),
                'accepted_at' => null,
                'change_requested_at' => null,
            ];
        } else {
            $updates += [
                'proposal_status' => 'changes_requested',
                'change_requested_at' => now(),
                'accepted_at' => null,
                'rejected_at' => null,
            ];
        }

        $proposal->forceFill($updates)->save();
    }
}
