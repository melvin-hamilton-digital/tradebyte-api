<?php

namespace MHD\Tradebyte\Data;

use SimpleXMLElement;

/**
 * @link https://www.tradebyte.io/documentation/structure-and-content-of-order-messages/order-messages-message/
 */
class MessagesList
{
    public function __construct()
    {
        $this->root = new SimpleXMLElement('<MESSAGES_LIST/>');
    }

    public function addMessage(Message $message): self
    {
        $messageElement = $this->root->addChild('MESSAGE');

        foreach ($message->getNodes() as $name => $value) {
            $messageElement->$name = $value;
        }

        return $this;
    }

    public function asXml(): string
    {
        return $this->root->asXML();
    }
}
