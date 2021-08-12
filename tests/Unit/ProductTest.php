<?php

namespace Tests\Unit;

use MHD\Tradebyte\Data\Csv\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testNormalizedProduct()
    {
        $expected = [
            'p_nr' => '12345',
            'p_brand' => 'Music Player',
            'p_name' => 'MP3 Player 9001',
            'p_text' => 'It plays music',
            'p_keywords' => 'MP3, Music, Portable',
            'p_active' => 1,
            'p_bullet{0}' => 'Listen to your favourite music',
            'p_bullet{1}' => 'And your favourite audiobooks',
            'p_comp[foo]' => 'bar',
            'p_tag[foo]{0}' => 'MP3',
            'p_tag[foo]{1}' => 'Music',
        ];

        $product = new Product();
        $product->id = '12345';
        $product->brand = 'Music Player';
        $product->name = 'MP3 Player 9001';
        $product->description = 'It plays music';
        $product->keywords = 'MP3, Music, Portable';

        $product->setAvailability(true);

        $product->addBulletPoint('Listen to your favourite music');
        $product->addBulletPoint('And your favourite audiobooks');

        $product->addComponent('foo', 'bar');

        $product->addTag('foo', 'MP3');
        $product->addTag('foo', 'Music');

        $this->assertEquals($expected, $product->toArray());
    }

    /**
     * @dataProvider flattenAvailabilityDataProvider
     */
    public function testFlattenAvailability(Product $product, array $expected)
    {
        $this->assertEquals($expected, $product->getFlattenedAvailability());
    }

    public function flattenAvailabilityDataProvider()
    {
        yield [
            (new Product())
                ->setAvailability(true),
            ['p_active' => 1]
        ];

        yield [
            (new Product())
                ->setAvailability(false),
            ['p_active' => 0]
        ];

        yield [
            (new Product())
                ->setAvailability(true, 'chxx'),
            ['p_active[chxx]' => 1]
        ];
    }

    /**
     * @dataProvider flattenBulletPointsDataProvider
     */
    public function testFlattenBulletPoints(Product $product, array $expected)
    {
        $this->assertEquals($expected, $product->getFlattenedBulletPoints());
    }

    public function flattenBulletPointsDataProvider()
    {
        yield [
            (new Product())
                ->addBulletPoint('bullet'),
            [
                'p_bullet{0}' => 'bullet'
            ]
        ];

        yield [
            (new Product())
                ->addBulletPoint('bullet one')
                ->addBulletPoint('bullet two'),
            [
                'p_bullet{0}' => 'bullet one',
                'p_bullet{1}' => 'bullet two'
            ]
        ];
    }

    /**
     * @dataProvider flattenComponentsDataProvider
     */
    public function testFlattenComponents(Product $product, array $expected)
    {
        $this->assertEquals($product->getFlattenedComponents(), $expected);
    }

    public function flattenComponentsDataProvider()
    {
        yield [
            (new Product())
                ->addComponent('chxx', 'component'),
            [
                'p_comp[chxx]' => 'component'
            ]
        ];

        yield [
            (new Product())
                ->addComponent('key one', 'component one')
                ->addComponent('key two', 'component two'),
            [
                'p_comp[key one]' => 'component one',
                'p_comp[key two]' => 'component two',
            ]
        ];
    }

    /**
     * @dataProvider flattenTagsDataProvider
     */
    public function testFlattenTags(Product $product, array $expected)
    {
        $this->assertEquals($expected, $product->getFlattenedTags());
    }

    public function flattenTagsDataProvider()
    {
        yield [
            (new Product())
                ->addTag('key', 'tag'),
            [
                'p_tag[key]{0}' => 'tag'
            ]
        ];

        yield [
            (new Product())
                ->addTag('foo', 'tag one')
                ->addTag('bar', 'tag two')
                ->addTag('foo', 'tag three'),
            [
                'p_tag[foo]{0}' => 'tag one',
                'p_tag[foo]{1}' => 'tag three',
                'p_tag[bar]{0}' => 'tag two'
            ]
        ];
    }
}
