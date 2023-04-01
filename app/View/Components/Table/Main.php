<?php

namespace App\View\Components\Table;

use Illuminate\View\Component;

abstract class Main extends Component
{
    protected $framework;

    /**
     * @param $framework
     */
    public function __construct()
    {
        $this->framework = config('frontend.framework');
    }
}
