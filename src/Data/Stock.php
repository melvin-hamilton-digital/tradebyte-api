<?php

namespace MHD\Tradebyte\Data;

use SimpleXMLElement;

/**
 * @link https://www.tradebyte.io/documentation/structure-of-stock-data-in-tb-stock-format/
 */
class Stock
{
    /**
     * @var SimpleXMLElement
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new SimpleXMLElement("<TBSTOCK/>");
    }

    public function addArticle(string $sku, int $stock)
    {
        $article = $this->articles->addChild("ARTICLE");
        $article->A_NR = $sku;
        $article->A_STOCK = $stock;
    }

    public function asXml(): string
    {
        return $this->articles->asXML();
    }
}
