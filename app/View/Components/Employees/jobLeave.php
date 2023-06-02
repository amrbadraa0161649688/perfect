<?php

namespace App\View\Components\employees;

use Illuminate\View\Component;

class jobLeave extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $stringNumber;
    public $employees;
    public $stopWorkingReasons;
    public $systemCodeItems;

    public function __construct($employees, $stringNumber, $stopWorkingReasons, $systemCodeItems)
    {
        $this->employees = $employees;
        $this->stringNumber = $stringNumber;
        $this->stopWorkingReasons = $stopWorkingReasons;
        $this->systemCodeItems = $systemCodeItems;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employees.job-leave');
    }
}
