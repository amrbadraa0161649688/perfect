<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class WayBillExports implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $way_pills;

    public function __construct($way_pills)
    {
        $this->way_pills = $way_pills;
    }

    public function view(): View
    {
        return view('Exports.waybill', [
            'way_pills' => $this->way_pills
        ]);
    }
}
