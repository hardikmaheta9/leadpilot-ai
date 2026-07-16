<?php

namespace App\Console\Commands;

use App\Models\CompanyAiProposal;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class ProposalRepairCommand extends Command
{
    protected $signature = 'proposal:repair
        {--company= : Repair proposals for one company UUID only}
        {--dry-run : Show changes without saving them}';

    protected $description =
        'Repair proposal versions, latest flags, statuses and archive state.';

    public function handle(): int
    {
        $companyUuid = $this->option('company');
        $dryRun = (bool) $this->option('dry-run');

        $query = CompanyAiProposal::query()
            ->orderBy('company_uuid')
            ->orderByDesc('version')
            ->orderByDesc('id');

        if ($companyUuid) {
            $query->where('company_uuid', $companyUuid);
        }

        $proposals = $query->get();

        if ($proposals->isEmpty()) {
            $this->warn('No proposals found.');

            return self::SUCCESS;
        }

        $stats = [
            'companies' => 0,
            'proposals' => $proposals->count(),
            'latest_fixed' => 0,
            'statuses_fixed' => 0,
            'versions_fixed' => 0,
            'archived_fixed' => 0,
        ];

        try {
            DB::transaction(function () use (
                $proposals,
                $dryRun,
                &$stats
            ): void {
                $proposals
                    ->groupBy('company_uuid')
                    ->each(function (
                        Collection $companyProposals,
                        string $companyUuid
                    ) use ($dryRun, &$stats): void {
                        $stats['companies']++;

                        $ordered = $companyProposals
                            ->sortBy([
                                ['version', 'desc'],
                                ['id', 'desc'],
                            ])
                            ->values();

                        $total = $ordered->count();

                        $ordered->each(function (
                            CompanyAiProposal $proposal,
                            int $index
                        ) use (
                            $total,
                            $dryRun,
                            &$stats
                        ): void {
                            $expectedLatest = $index === 0;
                            $expectedVersion = $total - $index;

                            $updates = [];

                            if (
                                (bool) $proposal->is_latest
                                !== $expectedLatest
                            ) {
                                $updates['is_latest'] = $expectedLatest;
                                $stats['latest_fixed']++;
                            }

                            if (
                                (int) $proposal->version
                                !== $expectedVersion
                            ) {
                                $updates['version'] = $expectedVersion;
                                $stats['versions_fixed']++;
                            }

                            if ($expectedLatest) {
                                if (
                                    empty($proposal->proposal_status)
                                    || $proposal->proposal_status === 'archived'
                                ) {
                                    $updates['proposal_status'] = 'draft';
                                    $stats['statuses_fixed']++;
                                }

                                if ($proposal->archived_at !== null) {
                                    $updates['archived_at'] = null;
                                    $stats['archived_fixed']++;
                                }
                            } else {
                                if (
                                    empty($proposal->proposal_status)
                                    || $proposal->proposal_status === 'draft'
                                ) {
                                    $updates['proposal_status'] = 'archived';
                                    $stats['statuses_fixed']++;
                                }

                                if ($proposal->archived_at === null) {
                                    $updates['archived_at'] = now();
                                    $stats['archived_fixed']++;
                                }
                            }

                            if (!$dryRun && $updates !== []) {
                                $proposal->update($updates);
                            }
                        });
                    });
            });
        } catch (Throwable $exception) {
            $this->error(
                'Proposal repair failed: '
                . $exception->getMessage()
            );

            return self::FAILURE;
        }

        $this->newLine();

        $this->table(
            ['Item', 'Count'],
            [
                ['Companies checked', $stats['companies']],
                ['Proposals checked', $stats['proposals']],
                ['Latest flags fixed', $stats['latest_fixed']],
                ['Statuses fixed', $stats['statuses_fixed']],
                ['Versions fixed', $stats['versions_fixed']],
                ['Archive dates fixed', $stats['archived_fixed']],
            ]
        );

        if ($dryRun) {
            $this->warn(
                'Dry run completed. No database changes were saved.'
            );
        } else {
            $this->info(
                'Proposal records repaired successfully.'
            );
        }

        return self::SUCCESS;
    }
}