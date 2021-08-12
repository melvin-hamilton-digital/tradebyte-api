<?php

namespace MHD\Tradebyte\Data;

use SimpleXMLElement;

/**
 * @link https://www.tradebyte.io/documentation/structure-of-an-order-in-tb-order-format/order-items-item/
 */
class Item
{
    /**
     * @var SimpleXMLElement
     */
    private $XMLElement;

    public function __construct(SimpleXMLElement $XMLElement)
    {
        $this->XMLElement = $XMLElement;
    }

    public function getSku(): string
    {
        return $this->XMLElement->SKU;
    }

    public function getQuantity(): int
    {
        return (int)$this->XMLElement->QUANTITY;
    }

    public function getPrice(): float
    {
        return (float)$this->XMLElement->ITEM_PRICE;
    }
}
