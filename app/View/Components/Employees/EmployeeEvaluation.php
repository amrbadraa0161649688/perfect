<?php

namespace App\View\Components\employees;

use Illuminate\View\Component;

class EmployeeEvaluation extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $employees;
    public $stringNumber;
    public $employeeEvaluationTypes;
    public $company;
    public $perEmployees;
    public $interviewEvaluations;
    public $employeeEvaluations;

    public function __construct($employees, $stringNumber, $employeeEvaluationTypes, $company,
                                $perEmployees, $interviewEvaluations, $employeeEvaluations)
    {
        $this->employees = $employees;
        $this->stringNumber = $stringNumber;
        $this->employeeEvaluationTypes = $employeeEvaluationTypes;
        $this->company = $company;
        $this->perEmployees = $perEmployees;
        $this->interviewEvaluations = $interviewEvaluations;
        $this->employeeEvaluations = $employeeEvaluations;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.employees.employee-evaluation');
    }
}
