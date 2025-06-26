<?php

namespace App\View\Components\templates;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class form extends Component
{
    public bool $edit;
    public bool $disable;
    public bool $delete = false;

    public function __construct(bool $edit = false, bool $disable = false, bool $delete = false)
    {
        $this->edit = $edit;
        $this->disable = $disable;
        $this->delete = $delete;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.templates.form');
    }
}
