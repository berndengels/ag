<?php

namespace App\View\Components\Button;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ResetButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public ?string $label = 'Reset',
        public ?string $css = 'btn btn-secondary reset btn-sm inline ms-2'
    )
    {
        parent::__construct();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.'.$this->framework.'.button.reset-button');
    }
}
