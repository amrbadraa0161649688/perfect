<?php
namespace App\InvoiceQR;
class InvoiceDateElement extends QRElement{
    
    public function __construct($value)
    {
        parent::__construct(3, $value);
    }
}