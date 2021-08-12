<?php

namespace Tests\Unit;

use MHD\Tradebyte\Data\Message;
use MHD\Tradebyte\Data\MessagesList;
use PHPUnit\Framework\TestCase;

class MessageListTest extends TestCase
{
    /**
     * @var MessagesList
     */
    private $messageList;

    public function setUp(): void
    {
        $this->messageList = new MessagesList();
    }

    /**
     * @dataProvider addMessageDataProvider
     */
    public function testAddMessage(string $expected, Message ...$messages)
    {
        foreach ($messages as $message) {
            $this->messageList->addMessage($message);
        }

        $this->assertEquals($expected, $this->messageList->asXml());
    }

    public function addMessageDataProvider()
    {
        yield [
            "<?xml version=\"1.0\"?>\n<MESSAGES_LIST/>\n",
        ];

        yield [
            "<?xml version=\"1.0\"?>\n<MESSAGES_LIST>" .
            "<MESSAGE><MESSAGE_TYPE>foo</MESSAGE_TYPE></MESSAGE>" .
            "</MESSAGES_LIST>\n",
            (new Message())
                ->setType('foo')
        ];

        yield [
            "<?xml version=\"1.0\"?>\n<MESSAGES_LIST>" .
            "<MESSAGE><MESSAGE_TYPE>foo</MESSAGE_TYPE><TB_ORDER_ID>1</TB_ORDER_ID></MESSAGE>" .
            "</MESSAGES_LIST>\n",
            (new Message())
                ->setOrderId(1)
                ->setType('foo')
        ];

        yield [
            "<?xml version=\"1.0\"?>\n<MESSAGES_LIST>" .
            "<MESSAGE><MESSAGE_TYPE>foo</MESSAGE_TYPE><TB_ORDER_ID>1</TB_ORDER_ID><TB_ORDER_ITEM_ID>2</TB_ORDER_ITEM_ID><SKU>foobar</SKU><QUANTITY>3</QUANTITY></MESSAGE>" .
            "</MESSAGES_LIST>\n",
            (new Message())
                ->setOrderItemId(2)
                ->setSku('foobar')
                ->setQuantity(3)
                ->setOrderId(1)
                ->setType('foo')
        ];

        yield [
            "<?xml version=\"1.0\"?>\n<MESSAGES_LIST>" .
            "<MESSAGE><MESSAGE_TYPE>foo</MESSAGE_TYPE></MESSAGE>" .
            "<MESSAGE><MESSAGE_TYPE>foo</MESSAGE_TYPE><TB_ORDER_ID>1</TB_ORDER_ID></MESSAGE>" .
            "</MESSAGES_LIST>\n",
            (new Message())
                ->setType('foo'),
            (new Message())
                ->setOrderId(1)
                ->setType('foo')
        ];
    }
}
