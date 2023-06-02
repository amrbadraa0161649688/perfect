<?php
namespace App\InvoiceQR;
class TotalAmountElement extends QRElement{
    
    public function __construct($value)
    {
        parent::__construct(4, $value);
    }
}