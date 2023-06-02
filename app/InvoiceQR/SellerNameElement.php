<?php
namespace App\InvoiceQR;
class SellerNameElement extends QRElement{
    
    public function __construct($value)
    {
        parent::__construct(1, $value);
    }
}