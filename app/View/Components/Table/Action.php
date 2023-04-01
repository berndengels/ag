<?php

namespace App\View\Components\Table;

use Closure;
use App\Helper\DataBinder;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class Action extends Main
{
    public Model $model;
    public string $paramName;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public ?bool $edit = false,
        public ?bool $delete = false,
        public ?bool $show = false,
        public ?bool $info = false,
        public ?string $routePrefix = 'admin.'
    )
    {
        parent::__construct();
        $this->model = app(DataBinder::class)->get();
        $this->paramName = lcfirst(Str::singular(class_basename($this->model)));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.'.$this->framework.'.table.action', [
            'model'         => $this->model,
            'modelParam'    => $this->paramName,
            'info'          => $this->info,
            'show'          => $this->show,
            'edit'          => $this->edit,
            'delete'        => $this->delete,
            'routePrefix'   => $this->routePrefix,
        ]);
    }
}
