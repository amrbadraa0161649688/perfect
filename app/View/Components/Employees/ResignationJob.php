<?php

namespace App\View\Components\employees;

use Illuminate\View\Component;

class ResignationJob extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $employees;
    public $stringNumber;
    public $stopWorkingReasons;

    public function __construct($employees, $stringNumber, $stopWorkingReasons)
    {
        $this->employees = $employees;
        $this->stringNumber = $stringNumber;
        $this->stopWorkingReasons = $stopWorkingReasons;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employees.resignation-job');
    }
}
