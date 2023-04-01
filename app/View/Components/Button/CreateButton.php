<?php

namespace App\View\Components\Button;

use Closure;
use Illuminate\Contracts\View\View;
use function view;

class CreateButton extends Button
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.'.$this->framework.'.button.create-button');
    }
}
