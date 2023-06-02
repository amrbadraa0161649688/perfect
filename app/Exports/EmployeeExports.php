<?php

namespace App\Exports;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class EmployeeExports implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $employees;

    public function __construct($employees)
    {
        $this->employees = $employees;
    }

    public function view(): View
    {

        return \view('Exports.employee', [

            'employees' => $this->employees
        ]);

    }
}
