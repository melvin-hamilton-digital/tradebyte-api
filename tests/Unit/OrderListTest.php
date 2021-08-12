<?php

namespace Tests\Unit;

use MHD\Tradebyte\Data\OrdersList;
use PHPUnit\Framework\TestCase;

class OrderListTest extends TestCase
{
    /**
     * @dataProvider getOrdersDataProvider
     */
    public function testGetOrders(string $xml, int $expectedCount)
    {
        $list = new OrdersList($xml);
        $this->assertCount($expectedCount, $list->getOrders());
    }

    public function getOrdersDataProvider()
    {
        yield 'one' => [
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<ORDER_LIST>
    <ORDER xmlns:xsd="http://www.w3.org/2001/XMLSchema">
        <ORDER_DATA />
        <SELL_TO />
        <SHIP_TO />
        <SHIPMENT />
        <PAYMENT />
        <HISTORY />
        <SERVICES />
        <ITEMS />
    </ORDER>
</ORDER_LIST>
XML,
            1
        ];

        yield 'three' => [
            <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<ORDER_LIST>
    <ORDER xmlns:xsd="http://www.w3.org/2001/XMLSchema">
        <ORDER_DATA />
        <SELL_TO />
        <SHIP_TO />
        <SHIPMENT />
        <PAYMENT />
        <HISTORY />
        <SERVICES />
        <ITEMS />
    </ORDER>
    <ORDER xmlns:xsd="http://www.w3.org/2001/XMLSchema">
        <ORDER_DATA />
        <SELL_TO />
        <SHIP_TO />
        <SHIPMENT />
        <PAYMENT />
        <HISTORY />
        <SERVICES />
        <ITEMS />
    </ORDER>
    <ORDER xmlns:xsd="http://www.w3.org/2001/XMLSchema">
        <ORDER_DATA />
        <SELL_TO />
        <SHIP_TO />
        <SHIPMENT />
        <PAYMENT />
        <HISTORY />
        <SERVICES />
        <ITEMS />
    </ORDER>
</ORDER_LIST>
XML,
            3
        ];
    }
}
