<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class CustomersSuppliersExport implements FromView, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function title(): string
    {
        return 'customers & suppliers';
    }

    public function view(): View
    {
        $company = Session('company') ? Session('company') : auth()->user()->company;
        return view('exports.customerSuppliers', [
            'customers' => Customer::where('company_group_id', $company->company_group_id)
                ->whereIn('customer_category', [1, 2])
                ->select('customer_id', 'customer_name_full_ar', 'customer_category')->get()
        ]);
    }
}
