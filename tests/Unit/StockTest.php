<?php

namespace Tests\Unit;

use MHD\Tradebyte\Data\Stock;
use PHPUnit\Framework\TestCase;

class StockTest extends TestCase
{
    public function setUp(): void
    {
        $this->stock = new Stock();
    }

    /**
     * @dataProvider addArticleDataProvider
     */
    public function testAddArticle(string $expected, array ...$articles)
    {
        foreach ($articles as [$sku, $quantity]) {
            $this->stock->addArticle($sku, $quantity);
        }

        $this->assertEquals(
            $expected,
            $this->stock->asXml()
        );
    }

    public function addArticleDataProvider()
    {
        yield [
            "<?xml version=\"1.0\"?>\n<TBSTOCK/>\n"
        ];

        yield [
            "<?xml version=\"1.0\"?>\n<TBSTOCK><ARTICLE><A_NR>111</A_NR><A_STOCK>1</A_STOCK></ARTICLE></TBSTOCK>\n",
            ["111", 1],
        ];

        yield [
            "<?xml version=\"1.0\"?>\n<TBSTOCK><ARTICLE><A_NR>222</A_NR><A_STOCK>2</A_STOCK></ARTICLE><ARTICLE><A_NR>333</A_NR><A_STOCK>3</A_STOCK></ARTICLE></TBSTOCK>\n",
            ["222", 2],
            ["333", 3],
        ];
    }
}
