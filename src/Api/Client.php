<?php

namespace MHD\Tradebyte\Api;

use GuzzleHttp\Psr7\Request;
use MHD\Tradebyte\Data\MessagesList;
use MHD\Tradebyte\Data\Stock;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class Client
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function sendMessages(MessagesList $messagesList): ResponseInterface
    {
        $request = new Request('POST', '/messages/', [], $messagesList->asXml());

        return $this->client->sendRequest($request);
    }

    public function sendStock(Stock $stock): ResponseInterface
    {
        $request = new Request('POST', '/articles/stock', [], $stock->asXml());

        return $this->client->sendRequest($request);
    }

    public function getOrders(string $channel): ResponseInterface
    {
        $request = new Request('GET', "/orders/?channel={$channel}");

        return $this->client->sendRequest($request);
    }
}
