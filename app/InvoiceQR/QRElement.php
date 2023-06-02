<?php
namespace App\InvoiceQR;

class QRElement {

    protected $name;

    protected $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * its important to get the number of bytes of a string instated of number of characters
     *
     * @return false|int
     */
    public function getLength()
    {
        return strlen($this->value);
    }

    /**
     * @return string Returns a string representing the encoded TLV data structure.
     */
    public function __toString()
    {
        $value = (string) $this->getValue();

        return $this->toHex($this->getName()).$this->toHex($this->getLength()).($value);
    }

    /**
     * To convert the string value to hex.
     *
     * @param $value
     *
     * @return false|string
     */
    protected function toHex($value)
    {
        return pack("H*", sprintf("%02X", $value));
    }
}