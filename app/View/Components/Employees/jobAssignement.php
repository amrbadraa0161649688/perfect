<?php

namespace App\View\Components\employees;

use Illuminate\View\Component;

class jobAssignement extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $employees;
    public $stringNumber;
    public $companies;

    public function __construct($employees, $stringNumber, $companies)
    {
        $this->employees = $employees;
        $this->stringNumber = $stringNumber;
        $this->companies = $companies;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employees.job-assignement');
    }
}
