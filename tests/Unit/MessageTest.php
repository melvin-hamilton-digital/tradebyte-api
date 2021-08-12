<?php

namespace Tests\Unit;

use MHD\Tradebyte\Data\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /**
     * @dataProvider getNodesOrderDataProvider
     */
    public function testGetNodesOrder(Message $message, array $expectedOrder)
    {
        $nodes = $message->getNodes();

        $this->assertEquals($expectedOrder, array_keys($nodes));
    }

    public function getNodesOrderDataProvider()
    {
        yield [
            (new Message())
                ->setOrderId(12)
                ->setOrderItemId(34),
            [
                'TB_ORDER_ID',
                'TB_ORDER_ITEM_ID'
            ]
        ];

        yield [
            (new Message())
                ->setOrderItemId(34)
                ->setOrderId(12),
            [
                'TB_ORDER_ID',
                'TB_ORDER_ITEM_ID',
            ]
        ];

        yield [
            (new Message())
                ->setOrderItemId(34)
                ->setOrderId(12)
                ->setType('foo')
                ->setQuantity(56)
                ->setSku('123456'),
            Message::CORRECT_NODE_ORDER
        ];
    }
}
