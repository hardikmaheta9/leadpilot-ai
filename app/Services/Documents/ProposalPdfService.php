<?php

namespace App\Services\Documents;

use App\Models\CompanyAiProposal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Spatie\Browsershot\Browsershot;
use Throwable;

class ProposalPdfService
{
    public function generate(CompanyAiProposal $proposal, bool $force = false): string
    {
        if (!$force && $proposal->pdf_path && Storage::disk('public')->exists($proposal->pdf_path)) {
            return $proposal->pdf_path;
        }

        $directory = "proposals/{$proposal->company_uuid}/version-{$proposal->version}";
        Storage::disk('public')->makeDirectory($directory);

        $relativePath = $directory . '/' . Str::slug(
            $proposal->proposal_title . '-v' . $proposal->version
        ) . '.pdf';

        $absolutePath = Storage::disk('public')->path($relativePath);

        try {
            $shot = Browsershot::html($proposal->proposal_html)
                ->setNodeModulePath(base_path('node_modules'))
                ->format('A4')
                ->margins(0, 0, 0, 0)
                ->showBackground()
                ->emulateMedia('print')
                ->timeout(120);

            if ($node = config('proposals.browsershot.node_binary')) {
                $shot->setNodeBinary($node);
            }

            if ($npm = config('proposals.browsershot.npm_binary')) {
                $shot->setNpmBinary($npm);
            }

            if ($chrome = config('proposals.browsershot.chrome_path')) {
                $shot->setChromePath($chrome);
            }

            if (config('proposals.browsershot.no_sandbox')) {
                $shot->noSandbox();
            }

            $shot->savePdf($absolutePath);
        } catch (Throwable $e) {
            throw new RuntimeException('Proposal PDF generation failed: ' . $e->getMessage(), 0, $e);
        }

        $proposal->forceFill(['pdf_path' => $relativePath])->save();

        return $relativePath;
    }

    public function absolutePath(CompanyAiProposal $proposal): string
    {
        return Storage::disk('public')->path($this->generate($proposal));
    }
}
