<?php

namespace App\Services\Documents;

class ProposalRenderer
{
    public function render(array $proposal): string
    {
        return view('proposals.enterprise.layout', $proposal)->render();
    }
}
