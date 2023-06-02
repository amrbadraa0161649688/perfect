<?php

namespace App\Exports;

use App\Models\Branch;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class BranchesExport implements FromView, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function title(): string
    {
        return 'branches';
    }

    public function view(): View
    {
        $company = Session('company') ? Session('company') : auth()->user()->company;
        return view('exports.branches', [
            'branches' => Branch::where('company_group_id', $company->company_group_id)
                ->select('branch_id', 'branch_name_ar')->get()
        ]);
    }
}
