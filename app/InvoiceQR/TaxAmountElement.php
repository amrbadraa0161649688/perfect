<?php
namespace App\InvoiceQR;
class TaxAmountElement extends QRElement{
    
    public function __construct($value)
    {
        parent::__construct(5, $value);
    }
}