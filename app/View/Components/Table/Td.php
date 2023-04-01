<?php

namespace App\View\Components\Table;

use Closure;
use App\Helper\DataBinder;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Helper\HandleDataValue;
use Illuminate\Database\Eloquent\Model;

class Td extends Component
{
    use HandleDataValue;

    private $style;
    private $col;
    public Model $model;
    public mixed $data;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public string $field,
        public $bind = null,
        public ?string $class = null,
        public ?string $link = null,
        public ?string $target = '_self',
        public ?string $icon = null,
        public ?int $short = null,
        public mixed $append = null,
        public ?string $seperator = null,
        public ?bool $translate = false,
        public ?string $email = null,
        public ?string $color = null,
        public ?bool $fon = false,
        public ?string $dateformat = null
    )
    {
        $this->model = app(DataBinder::class)->get();
        if(false !== stristr($field, ':')) {
            [$f, $c] = explode(':', $field);
            $this->style = ($c  !== 'sm' && $c) ? "$class d-none d-{$c}-table-cell" : $class;
            $this->col = $f;
        } else {
            $this->style = $class;
            $this->col = $field;
        }

        if($email) {
            $this->icon = 'fas fa-at';
            $this->target = '_blank';
        }
        if($fon) {
            $this->icon = 'fas fa-phone';
            $this->target = '_blank';
        }
        $this->setData($this->col, $bind);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.'.$this->framework.'.table.td', [
            'model' => $this->model,
            'bind'  => $this->bind,
            'style' => $this->style,
            'col'   => $this->col,
            'data'  => $this->data,
            'link'  => $this->link,
            'target'  => $this->target,
            'icon'    => $this->icon,
            'short'   => $this->short,
            'color'   => $this->color,
            'dateformat'    => $this->dateformat,
        ]);
    }
}
