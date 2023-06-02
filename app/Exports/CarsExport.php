<?php

namespace App\Exports;

use App\Models\Trucks;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class CarsExport implements FromView, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function title(): string
    {
        return 'cars';
    }

    public function view(): View
    {
        $company = Session('company') ? Session('company') : auth()->user()->company;
        return view('exports.cars', [
            'cars' => Trucks::where('company_group_id', $company->company_group_id)
                ->select('truck_id', 'truck_code', 'truck_name', 'truck_plate_no')->get()
        ]);
    }
}
