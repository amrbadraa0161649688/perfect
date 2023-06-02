<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class JournalDataExport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function sheets(): array
    {
        return [
            new CustomersSuppliersExport(),
            new JournalExport(),
            new AccountExport(),
            new EmployeesExport(),
            new BranchesExport(),
            new CarsExport(),
        ];
    }
}
