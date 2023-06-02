<?php

namespace App\View\Components\Employees;

use Illuminate\View\Component;

class VacationRequest extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $vacationTypes;
    public $alterEmployees;
    public $employees;
    public $stringNumber;

    public function __construct($vacationTypes, $alterEmployees, $employees, $stringNumber)
    {
        $this->vacationTypes = $vacationTypes;
        $this->alterEmployees = $alterEmployees;
        $this->employees = $employees;
        $this->stringNumber = $stringNumber;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employees.vacation-request');
    }
}
