<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposalResponseRequest;
use App\Models\CompanyAiProposal;
use App\Services\Documents\ProposalPdfService;
use App\Services\Documents\ProposalTrackingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicProposalController extends Controller
{
    public function __construct(
        private ProposalTrackingService $tracking,
        private ProposalPdfService $pdf,
    ) {
    }

    public function show(string $token): View
    {
        $proposal = $this->findValid($token);
        $this->tracking->recordView($proposal);

        return view('proposals.public.show', compact('proposal'));
    }

    public function download(string $token): BinaryFileResponse
    {
        $proposal = $this->findValid($token);
        $path = $this->pdf->generate($proposal);
        $this->tracking->recordDownload($proposal);

        return response()->download(
            Storage::disk('public')->path($path),
            "proposal-v{$proposal->version}.pdf"
        );
    }

    public function respond(ProposalResponseRequest $request, string $token): RedirectResponse
    {
        $proposal = $this->findValid($token);
        $this->tracking->respond(
            $proposal,
            $request->validated('action'),
            $request->validated('note')
        );

        return back()->with('success', 'Your response has been recorded.');
    }

    private function findValid(string $token): CompanyAiProposal
    {
        $proposal = CompanyAiProposal::where('public_token', $token)->firstOrFail();

        abort_unless($proposal->hasValidPublicLink(), 410);

        return $proposal;
    }
}
