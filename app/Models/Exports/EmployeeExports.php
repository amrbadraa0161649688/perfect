<?php

namespace App\Models\Exports;

use App\Models\Employee;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;


class EmployeeExports implements FromView
{
    use Exportable;

    /**
     * @return \Illuminat\Support\collection
     */

//    public function collection()
//    {
//        return Employee::all();
//    }

}
