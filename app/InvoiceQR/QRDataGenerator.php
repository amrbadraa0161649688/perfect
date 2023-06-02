<?php
namespace App\InvoiceQR;
use InvalidArgumentException;

class QRDataGenerator {
  
    protected $data = [];


    private function __construct($data)
    {
        $this->data = array_filter($data, function ($tag) {
            return $tag instanceof QRElement;
        });

        if (count($this->data) === 0) {
            throw new InvalidArgumentException('invalid data structure');
        }
    }

   
    public static function fromArray(array $data): QRDataGenerator
    {
        return new self($data);
    }

    /**
     * Encodes an TLV data structure.
     *
     * @return string Returns a string representing the encoded TLV data structure.
     */
    public function toTLV(): string
    {
        return implode('', array_map(function ($element) {
            return (string) $element;
        }, $this->data));
    }

    /**
     * Encodes an TLV as base64
     *
     * @return string Returns the TLV as base64 encode.
     */
    public function toBase64(): string
    {
        return base64_encode($this->toTLV());
    }
}