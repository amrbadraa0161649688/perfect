<?php

namespace App\Exports;

use App\Models\Account;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class AccountExport implements FromView, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function title(): string
    {
        return 'accounts';
    }

    public function view(): View
    {
        $company = Session('company') ? Session('company') : auth()->user()->company;
        return view('exports.accounts', [
            'accounts' => Account::where('company_group_id', $company->company_group_id)
                ->where('acc_level', 5)
                ->select('acc_id', 'acc_name_ar', 'acc_code')->get()
        ]);
    }
}
