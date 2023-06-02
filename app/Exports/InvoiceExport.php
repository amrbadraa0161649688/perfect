<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InvoiceExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $invoices;

    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }

    public function view(): View
    {
        return view('Exports.invoices', [
            'invoices' => $this->invoices
        ]);
    }
}
