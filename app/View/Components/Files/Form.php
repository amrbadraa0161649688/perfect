<?php

namespace App\View\Components\files;

use Illuminate\View\Component;

class Form extends Component
{
    public $required;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($required = true)
    {
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.files.form');
    }
}
