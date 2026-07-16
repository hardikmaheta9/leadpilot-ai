<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendProposalRequest;
use App\Models\Company;
use App\Models\CompanyAiProposal;
use App\Services\AI\AIProposalBuilderService;
use App\Services\Documents\ProposalDeliveryService;
use App\Services\Documents\ProposalDocxService;
use App\Services\Documents\ProposalPdfService;
use App\Services\Documents\ProposalTrackingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AIProposalController extends Controller
{
    public function __construct(
        private AIProposalBuilderService $builder,
        private ProposalPdfService $pdf,
        private ProposalDocxService $docx,
        private ProposalDeliveryService $delivery,
        private ProposalTrackingService $tracking,
    ) {
    }

    public function generate(Company $company): RedirectResponse
    {
        $proposal = $this->builder->generate($company);

        return redirect()->route('companies.proposal.show', compact('company', 'proposal'))
            ->with('success', 'New proposal version generated successfully.');
    }

    public function show(Company $company, CompanyAiProposal $proposal): View
    {
        $this->ensureOwnership($company, $proposal);

        return view('proposals.show', compact('company', 'proposal'));
    }

    public function latest(Company $company): RedirectResponse
    {
        $proposal = CompanyAiProposal::forCompany($company->uuid)
            ->latestVersion()
            ->firstOrFail();

        return redirect()->route('companies.proposal.show', compact('company', 'proposal'));
    }

    public function history(Company $company): View
    {
        $proposals = CompanyAiProposal::forCompany($company->uuid)
            ->orderByDesc('version')
            ->paginate(15);

        return view('proposals.history', compact('company', 'proposals'));
    }

    public function analytics(Company $company): View
    {
        $proposals = CompanyAiProposal::forCompany($company->uuid)->get();

        $stats = [
            'total' => $proposals->count(),
            'sent' => $proposals->whereNotNull('sent_at')->count(),
            'viewed' => $proposals->where('view_count', '>', 0)->count(),
            'accepted' => $proposals->where('proposal_status', 'accepted')->count(),
            'rejected' => $proposals->where('proposal_status', 'rejected')->count(),
            'views' => (int) $proposals->sum('view_count'),
            'downloads' => (int) $proposals->sum('download_count'),
        ];

        return view('proposals.analytics', compact('company', 'stats', 'proposals'));
    }

    public function pdf(Company $company, CompanyAiProposal $proposal): BinaryFileResponse
    {
        $this->ensureOwnership($company, $proposal);
        $path = $this->pdf->generate($proposal);
        $this->tracking->recordDownload($proposal);

        return response()->download(
            Storage::disk('public')->path($path),
            "proposal-v{$proposal->version}.pdf"
        );
    }

    public function docx(Company $company, CompanyAiProposal $proposal): BinaryFileResponse
    {
        $this->ensureOwnership($company, $proposal);
        $path = $this->docx->generate($proposal);
        $this->tracking->recordDownload($proposal);

        return response()->download(
            Storage::disk('public')->path($path),
            "proposal-v{$proposal->version}.docx"
        );
    }

    public function compose(Company $company, CompanyAiProposal $proposal): View
    {
        $this->ensureOwnership($company, $proposal);

        $defaultTo = $company->contacts()
            ->orderByDesc('is_primary')
            ->value('email') ?: $company->email;

        return view('proposals.send', compact('company', 'proposal', 'defaultTo'));
    }

    public function send(
        SendProposalRequest $request,
        Company $company,
        CompanyAiProposal $proposal
    ): RedirectResponse {
        $this->ensureOwnership($company, $proposal);

        $data = $request->validated();

        $this->delivery->send(
            proposal: $proposal,
            to: $data['to'],
            subject: $data['subject'],
            message: $data['message'],
            cc: $data['cc'] ?? null,
            bcc: $data['bcc'] ?? null,
            sentBy: auth()->id(),
        );

        return redirect()->route('companies.proposal.show', compact('company', 'proposal'))
            ->with('success', 'Proposal sent successfully.');
    }

    private function ensureOwnership(Company $company, CompanyAiProposal $proposal): void
    {
        abort_unless($proposal->company_uuid === $company->uuid, 404);
    }
}
