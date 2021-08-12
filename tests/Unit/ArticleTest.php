<?php

namespace Tests\Unit;

use MHD\Tradebyte\Data\Csv\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    public function testArticleNormalization()
    {
        $article = new Article();
        $article->productId = 'Foo';
        $article->articleId = 'Foobar';
        $article->stock = 123;
        $article->unit = Article::UNIT_PIECE;
        $article->weight = 3.14;

        $article->addComponent('foo', 'bar');
        $article->addMedia('image', 'foobar.jpg');
        $article->addRetailPrice('chxx', 123.45);
        $article->addVatRate('chxx', Article::VAT_RATE_NORMAL);

        $expected = [
            'p_nr' => 'Foo',
            'a_nr' => 'Foobar',
            'a_stock' => 123,
            'a_unit' => 'ST',
            'a_weight' => 3.14,
            'a_comp[foo]' => 'bar',
            'a_media[image]{0}' => 'foobar.jpg',
            'a_vk[chxx]' => 123.45,
            'a_mwst[chxx]' => 1,
        ];

        $this->assertEquals($expected, $article->toArray());
    }

    /**
     * @dataProvider flattenRetailPricesDataProvider
     */
    public function testFlattenRetailPrices(Article $article, array $expected)
    {
        $this->assertEquals($expected, $article->getFlattenedRetailPrices());
    }

    /**
     * @dataProvider flattenComponentsDataProvider
     */
    public function testFlattenComponents(Article $article, array $expected)
    {
        $this->assertEquals($article->getFlattenedComponents(), $expected);
    }

    public function flattenComponentsDataProvider()
    {
        yield [
            (new Article())
                ->addComponent('chxx', 'component'),
            [
                'a_comp[chxx]' => 'component'
            ]
        ];

        yield [
            (new Article())
                ->addComponent('key one', 'component one')
                ->addComponent('key two', 'component two'),
            [
                'a_comp[key one]' => 'component one',
                'a_comp[key two]' => 'component two',
            ]
        ];
    }

    /**
     * @dataProvider flattenMediaDataProvider
     */
    public function testFlattenMedia(Article $product, array $expected)
    {
        $this->assertEquals($expected, $product->getFlattenedMedia());
    }

    public function flattenMediaDataProvider()
    {
        yield [
            (new Article())
                ->addMedia('image', 'article.jpg'),
            [
                'a_media[image]{0}' => 'article.jpg'
            ]
        ];

        yield [
            (new Article())
                ->addMedia('foo', 'tag one')
                ->addMedia('bar', 'tag two')
                ->addMedia('foo', 'tag three'),
            [
                'a_media[foo]{0}' => 'tag one',
                'a_media[foo]{1}' => 'tag three',
                'a_media[bar]{0}' => 'tag two'
            ]
        ];
    }

    public function flattenRetailPricesDataProvider()
    {
        yield [
            (new Article())
                ->addRetailPrice('chxx', 1.23),
            [
                'a_vk[chxx]' => 1.23
            ]
        ];

        yield [
            (new Article())
                ->addRetailPrice('foo', 1.23)
                ->addRetailPrice('bar', 4.56),
            [
                'a_vk[foo]' => 1.23,
                'a_vk[bar]' => 4.56,
            ]
        ];
    }
}
