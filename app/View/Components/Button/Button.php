<?php

namespace App\View\Components\Button;

use Illuminate\View\Component;

abstract class Button extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    protected $framework;

    public function __construct(
        public string $route
    )
    {
        $this->framework = config('frontend.framework');
    }
}
