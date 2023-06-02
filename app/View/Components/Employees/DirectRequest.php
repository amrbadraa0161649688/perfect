<?php

namespace App\View\Components\Employees;

use Illuminate\View\Component;

class DirectRequest extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $employees;
    public $stringNumber;

    public function __construct($employees ,$stringNumber)
    {
        //
        $this->employees=$employees;
        $this->stringNumber = $stringNumber;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employees.direct-request');
    }
}
