<?php

namespace MHD\Tradebyte\Data\Csv;

use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @link https://infocenter.tradebyte.com/wp-content/uploads/docs/manual-basic-en/3005_Part4b_Master%20Data%20Import%20via%20CSV-EN.pdf
 */
class Article implements NormalizableInterface
{
    public const UNIT_PIECE = 'ST';
    public const UNIT_CENTIMETER = 'CM';
    public const UNIT_SQUARE_METER = 'QM';

    public const VAT_RATE_NORMAL = 1;
    public const VAT_RATE_REDUCED = 2;
    public const VAT_RATE_FREE = 3;

    /**
     * @var string
     */
    public $productId;
    /**
     * @var string
     */
    public $articleId;
    /**
     * @var array
     */
    private $components = [];
    /**
     * @var array
     */
    private $media = [];
    /**
     * @var array
     */
    private $retailPrices = [];
    /**
     * @var int
     */
    public $stock;
    /**
     * @var string
     */
    public $unit;
    /**
     * @var array
     */
    private $vatRates = [];
    /**
     * @var float
     */
    public $weight;

    public function addComponent(string $key, string $component): self
    {
        $this->components[$key] = $component;

        return $this;
    }

    public function addMedia(string $key, string $media): self
    {
        if (!isset($this->media[$key])) {
            $this->media[$key] = [];
        }

        $this->media[$key][] = $media;

        return $this;
    }

    public function addRetailPrice(string $channel, float $price): self
    {
        $this->retailPrices[$channel] = $price;

        return $this;
    }

    public function addVatRate(string $channel, int $vatRate): self
    {
        $this->vatRates[$channel] = $vatRate;

        return $this;
    }

    public function normalize(NormalizerInterface $normalizer, string $format = null, array $context = [])
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return array_merge(
            [
                'p_nr' => $this->productId,
                'a_nr' => $this->articleId,
                'a_stock' => $this->stock,
                'a_unit' => $this->unit,
                'a_weight' => $this->weight,
            ],
            $this->getFlattenedComponents(),
            $this->getFlattenedMedia(),
            $this->getFlattenedRetailPrices(),
            $this->getFlattenedVatRates()
        );
    }

    public function getFlattenedComponents(): array
    {
        $flattened = [];
        foreach ($this->components as $key => $value) {
            $flattened["a_comp[{$key}]"] = $value;
        }

        return $flattened;
    }

    public function getFlattenedMedia(): array
    {
        $flattened = [];
        foreach ($this->media as $key => $media) {
            foreach ($media as $index => $value) {
                $flattened["a_media[{$key}]{{$index}}"] = $value;
            }
        }

        return $flattened;
    }

    public function getFlattenedRetailPrices(): array
    {
        $flattened = [];
        foreach ($this->retailPrices as $channel => $price) {
            $flattened["a_vk[{$channel}]"] = $price;
        }

        return $flattened;
    }

    public function getFlattenedVatRates(): array
    {
        $flattened = [];
        foreach ($this->vatRates as $channel => $vatRate) {
            $flattened["a_mwst[{$channel}]"] = $vatRate;
        }

        return $flattened;
    }
}
