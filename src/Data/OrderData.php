<?php

namespace MHD\Tradebyte\Data;

use SimpleXMLElement;

/**
 * @link https://www.tradebyte.io/documentation/structure-of-an-order-in-tb-order-format/order-data-order_data/
 */
class OrderData
{
    /**
     * @var SimpleXMLElement
     */
    private $XMLElement;

    public function __construct(SimpleXMLElement $XMLElement)
    {
        $this->XMLElement = $XMLElement;
    }

    public function getOrderDate(): string
    {
        return $this->XMLElement->ORDER_DATE;
    }

    public function getTotalItemAmount(): float
    {
        return (float)$this->XMLElement->TOTAL_ITEM_AMOUNT;
    }
}
