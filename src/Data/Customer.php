<?php

namespace MHD\Tradebyte\Data;

use SimpleXMLElement;

/**
 * @link https://www.tradebyte.io/documentation/structure-of-an-order-in-tb-order-format/shipping-and-accounting-address-sell_to-and-ship_to/
 */
class Customer
{
    /**
     * @var SimpleXMLElement
     */
    private $XMLElement;

    public function __construct(SimpleXMLElement $XMLElement)
    {
        $this->XMLElement = $XMLElement;
    }

    public function getFirstName(): string
    {
        return $this->XMLElement->FIRSTNAME;
    }

    public function getLastName(): string
    {
        return $this->XMLElement->LASTNAME;
    }

    public function getStreetNo(): string
    {
        return $this->XMLElement->STREET_NO;
    }

    public function getZip(): string
    {
        return $this->XMLElement->ZIP;
    }

    public function getCity(): string
    {
        return $this->XMLElement->CITY;
    }

    public function getCountryCode(): string
    {
        return $this->XMLElement->COUNTRY;
    }
}
