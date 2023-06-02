<?php

namespace App\View\Components\employees;

use Illuminate\View\Component;

class medicalRequest extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $employees;
    public $insuranceCategories;
    public $insuranceTypes;
    public $stringNumber;

    public function __construct($employees, $insuranceCategories, $insuranceTypes,$stringNumber)
    {
        //
        $this->employees = $employees;
        $this->insuranceCategories = $insuranceCategories;
        $this->insuranceTypes = $insuranceTypes;
        $this->stringNumber = $stringNumber;
    }


    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employees.medical-request');
    }
}
