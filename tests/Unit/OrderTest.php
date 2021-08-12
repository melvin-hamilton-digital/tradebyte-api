<?php

namespace Tests\Unit;

use MHD\Tradebyte\Data\Order;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

class OrderTest extends TestCase
{
    /**
     * @link https://www.tradebyte.io/documentation/examples-for-product-and-order-data/example-order-file-in-tb-order-format/
     */
    public const EXAMPLE_ORDER = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!-- Start of the order -->
<ORDER>
    <!-- Order data -->
    <ORDER_DATA>
        <!-- Order date -->
        <ORDER_DATE>2013-02-10</ORDER_DATE>
        <!-- Channel sign -->
        <CHANNEL_SIGN>chxx</CHANNEL_SIGN>
        <!-- Unique ID of the order at the channel -->
        <CHANNEL_ID>4712</CHANNEL_ID>
        <!-- Order number at the channel -->
        <CHANNEL_NO>303-6862327-6937118</CHANNEL_NO>
        <!-- Confirmed order; shipping request to merchant -->
        <APPROVED>1</APPROVED>
        <!-- Number of order items -->
        <ITEM_COUNT>1</ITEM_COUNT>
        <!-- Order value -->
        <TOTAL_ITEM_AMOUNT>698</TOTAL_ITEM_AMOUNT>
        <!-- Order data end -->
    </ORDER_DATA>
    <!-- Invoice recipient -->
    <SELL_TO>
        <!-- Customer number of the invoice recipient at the channel -->
        <CHANNEL_NO>5607865</CHANNEL_NO>
        <FIRSTNAME>John</FIRSTNAME>
        <LASTNAME>Doe</LASTNAME>
        <STREET_NO>Teststraße 5</STREET_NO>
        <ZIP>85405</ZIP>
        <CITY>Musterhausen</CITY>
        <COUNTRY>DE</COUNTRY>
        <PHONE_PRIVATE>123</PHONE_PRIVATE>
        <EMAIL>john@doe.de</EMAIL>
    </SELL_TO>
    <!-- Shipping address -->
    <SHIP_TO>
        <CHANNEL_NO>5607865</CHANNEL_NO>
        <FIRSTNAME>John</FIRSTNAME>
        <LASTNAME>Doe</LASTNAME>
        <STREET_NO>Teststraße 5</STREET_NO>
        <ZIP>85405</ZIP>
        <CITY>Musterhausen</CITY>
        <COUNTRY>DE</COUNTRY>
    </SHIP_TO>
    <!-- Order items -->
    <ITEMS>
        <!-- Item 1 -->
        <ITEM>
            <!-- Channel-ID of the order item -->
            <CHANNEL_ID>1</CHANNEL_ID>
            <!-- Article number -->
            <SKU>4689bls</SKU>
            <!-- Channel -->
            <CHANNEL_SKU>00257241717043</CHANNEL_SKU>
            <!-- EAN -->
            <EAN>1234567891255</EAN>
            <!-- Order quantity -->
            <QUANTITY>2</QUANTITY>
            <!-- Invoice text -->
            <BILLING_TEXT>Reinhold bl S</BILLING_TEXT>
            <!-- Price of one article of this item -->
            <ITEM_PRICE>349.00</ITEM_PRICE>
        </ITEM>
    </ITEMS>
</ORDER>
XML;

    public function testGetOrderData()
    {
        $orderElement = new SimpleXMLElement(self::EXAMPLE_ORDER);
        $order = new Order($orderElement);

        $this->assertEquals(
            '2013-02-10',
            $order->getOrderData()->getOrderDate()
        );

        $this->assertEquals(
            698.0,
            $order->getOrderData()->getTotalItemAmount()
        );
    }

    public function testGetSellTo()
    {
        $orderElement = new SimpleXMLElement(self::EXAMPLE_ORDER);
        $order = new Order($orderElement);

        $this->assertEquals(
            'John',
            $order->getSellTo()->getFirstName()
        );

        $this->assertEquals(
            'Doe',
            $order->getSellTo()->getLastName()
        );
    }

    public function testGetItems()
    {
        $orderElement = new SimpleXMLElement(self::EXAMPLE_ORDER);
        $order = new Order($orderElement);

        $this->assertCount(1, $order->getItems());

        $item = $order->getItems()->current();
        $this->assertEquals(
            '4689bls',
            $item->getSku()
        );

        $this->assertEquals(
            2,
            $item->getQuantity()
        );

        $this->assertEquals(
            349.0,
            $item->getPrice()
        );
    }
}
