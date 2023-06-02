<?php

namespace App\View\Components\employees;

use Illuminate\View\Component;

class ancestorsRequest extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $employees;
    public $stringNumber;
    public $accountPeriods;

    public function __construct($employees, $stringNumber, $accountPeriods)
    {
        $this->employees = $employees;
        $this->stringNumber = $stringNumber;
        $this->accoutnPeriods = $accountPeriods;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employees.ancestors-request');
    }
}
