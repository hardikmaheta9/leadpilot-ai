<?php

namespace App\Console\Commands;

use App\Models\CompanyAiProposal;
use Illuminate\Console\Command;

class ProposalExpireCommand extends Command
{
    protected $signature = 'proposal:expire';
    protected $description = 'Mark proposals with expired public links as expired.';

    public function handle(): int
    {
        $count = CompanyAiProposal::query()
            ->whereNotNull('public_token_expires_at')
            ->where('public_token_expires_at', '<', now())
            ->whereNotIn('proposal_status', ['accepted', 'rejected', 'expired'])
            ->update([
                'proposal_status' => 'expired',
                'expires_at' => now(),
            ]);

        $this->info("Expired {$count} proposal(s).");

        return self::SUCCESS;
    }
}
