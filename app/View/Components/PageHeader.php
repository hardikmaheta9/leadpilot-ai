<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PageHeader extends Component
{
    public function __construct(
        public string $title,
        public ?string $subtitle = null,
        public ?string $actionUrl = null,
        public ?string $actionLabel = null,
        public ?string $actionIcon = null,
    ) {
    }

    public function render(): View|Closure|string
    {
        return view('components.page-header');
    }
}