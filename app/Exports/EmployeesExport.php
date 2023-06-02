<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;

class EmployeesExport implements FromView, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function title(): string
    {
        return 'employees';
    }

    public function view(): View
    {
        $company = Session('company') ? Session('company') : auth()->user()->company;
        return view('exports.employees', [
            'employees' => Employee::where('company_group_id', $company->company_group_id)
                ->select('emp_id', 'emp_name_full_ar')->get()
        ]);
    }
}
