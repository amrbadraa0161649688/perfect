<?php
namespace App\InvoiceQR;

class TaxNoElement extends QRElement{
    
    public function __construct($value)
    {
        parent::__construct(2, $value);
    }
}