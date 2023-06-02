<?php

namespace App\View\Components\Employees;

use Illuminate\View\Component;

class employeeData extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $employeeRequest;

    public function __construct($employeeRequest)
    {
        $this->employeeRequest = $employeeRequest;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employees.employee-data');
    }
}
