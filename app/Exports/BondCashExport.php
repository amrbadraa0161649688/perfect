<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BondCashExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $bonds;

    public function __construct($bonds)
    {
        $this->bonds = $bonds;
    }


    public function view(): View
    {
        return view('Exports.bonds-cash', [
            'bonds' => $this->bonds
        ]);
    }

}
