<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EmployeeRequests extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employee-requests');
    }
}
