<?php

namespace MHD\Tradebyte\Data;

use SimpleXMLIterator;

/**
 * @link https://www.tradebyte.io/documentation/structure-of-an-order-in-tb-order-format/order-order/
 */
class OrdersList
{
    /**
     * @var string
     */
    private $xml;

    public function __construct(string $xml)
    {
        $this->xml = $xml;
    }

    public function getOrders()
    {
        foreach (new SimpleXMLIterator($this->xml) as $orderElement) {
            yield new Order($orderElement);
        }
    }
}
