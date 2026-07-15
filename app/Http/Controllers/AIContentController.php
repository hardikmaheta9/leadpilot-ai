<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\AI\AIContentGenerationService;
use Illuminate\Http\RedirectResponse;

class AIContentController extends Controller
{
    public function __construct(
        private AIContentGenerationService $generator
    ) {
    }

    public function generateAll(
        Company $company
    ): RedirectResponse {

        $this->generator->generateAll($company);

        return back()->with(
            'success',
            'AI sales content generated successfully.'
        );
    }

    public function generate(
        Company $company,
        string $type
    ): RedirectResponse {

        $this->generator->generateByType(
            $company,
            $type
        );

        return back()->with(
            'success',
            ucfirst(str_replace('_', ' ', $type))
            . ' generated successfully.'
        );
    }
}