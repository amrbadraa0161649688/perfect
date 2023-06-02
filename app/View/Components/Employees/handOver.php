<?php

namespace App\View\Components\employees;

use Illuminate\View\Component;

class handOver extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $employees;
    public $stringNumber;
    public $handOverItems;
    public $handOverStatuses;

    public function __construct($employees, $stringNumber, $handOverItems, $handOverStatuses)
    {
        $this->employees = $employees;
        $this->stringNumber = $stringNumber;
        $this->handOverItems = $handOverItems;
        $this->handOverStatuses = $handOverStatuses;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employees.hand-over');
    }
}
