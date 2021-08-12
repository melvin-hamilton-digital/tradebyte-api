<?php

namespace MHD\Tradebyte\Data;

use Generator;
use SimpleXMLElement;

/**
 * @link https://www.tradebyte.io/documentation/structure-of-an-order-in-tb-order-format/order-order/
 */
class Order
{
    /**
     * @var SimpleXMLElement
     */
    private $orderElement;

    public function __construct(SimpleXMLElement $orderElement)
    {
        $this->orderElement = $orderElement;
    }

    public function getOrderData(): OrderData
    {
        return new OrderData($this->orderElement->ORDER_DATA);
    }

    public function getSellTo(): Customer
    {
        return new Customer($this->orderElement->SELL_TO);
    }

    public function getShipTo(): Customer
    {
        return new Customer($this->orderElement->SHIP_TO);
    }

    /**
     * @return Generator|Item[]
     */
    public function getItems(): Generator
    {
        foreach ($this->orderElement->ITEMS->ITEM as $item) {
            yield new Item($item);
        }
    }
}
