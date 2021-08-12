<?php

namespace MHD\Tradebyte\Data;

/**
 * @link https://www.tradebyte.io/documentation/structure-and-content-of-order-messages/order-messages-message/
 */
class Message
{
    public const TYPE_NO_INVENTORY = 'NO_INVENTORY';
    public const TYPE_SHIP = 'SHIP';

    public const NODE_TYPE = 'MESSAGE_TYPE';
    public const NODE_ORDER_ID = 'TB_ORDER_ID';
    public const NODE_ORDER_ITEM_ID = 'TB_ORDER_ITEM_ID';
    public const NODE_SKU = 'SKU';
    public const NODE_QUANTITY = 'QUANTITY';

    public const CORRECT_NODE_ORDER = [
        self::NODE_TYPE,
        self::NODE_ORDER_ID,
        self::NODE_ORDER_ITEM_ID,
        self::NODE_SKU,
        self::NODE_QUANTITY,
    ];

    private $nodes = [];

    public function setType(string $type): self
    {
        $this->nodes[self::NODE_TYPE] = $type;

        return $this;
    }

    public function setOrderId(int $orderId): self
    {
        $this->nodes[self::NODE_ORDER_ID] = $orderId;

        return $this;
    }

    public function setOrderItemId(int $orderItemId): self
    {
        $this->nodes[self::NODE_ORDER_ITEM_ID] = $orderItemId;

        return $this;
    }

    public function setSku(string $sku): self
    {
        $this->nodes[self::NODE_SKU] = $sku;

        return $this;
    }

    public function setQuantity(int $quantity): self
    {
        $this->nodes[self::NODE_QUANTITY] = $quantity;

        return $this;
    }

    public function getNodes(): array
    {
        $nodes = [];
        foreach (self::CORRECT_NODE_ORDER as $nodeName) {
            if (array_key_exists($nodeName, $this->nodes)) {
                $nodes[$nodeName] = $this->nodes[$nodeName];
            }
        }

        return $nodes;
    }
}
