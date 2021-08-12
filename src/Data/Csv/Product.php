<?php

namespace MHD\Tradebyte\Data\Csv;

use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @link https://infocenter.tradebyte.com/wp-content/uploads/docs/manual-basic-en/3005_Part4b_Master%20Data%20Import%20via%20CSV-EN.pdf
 */
class Product implements NormalizableInterface
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $brand;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string
     */
    public $keywords;
    /**
     * @var bool[]
     */
    private $availability = [];
    /**
     * @var array
     */
    private $bulletPoints = [];
    /**
     * @var array
     */
    private $components = [];
    /**
     * @var array
     */
    private $tags = [];

    public function setAvailability(bool $isAvailable, ?string $channel = null): self
    {
        if ($channel === null) {
            $this->availability = $isAvailable;
        } else {
            if (!is_array($this->availability)) {
                $this->availability = [];
            }
            $this->availability[$channel] = $isAvailable;
        }

        return $this;
    }

    public function addBulletPoint(string $bulletPoint): self
    {
        $this->bulletPoints[] = $bulletPoint;

        return $this;
    }

    public function addComponent(string $key, string $component): self
    {
        $this->components[$key] = $component;

        return $this;
    }

    public function addTag(string $key, string $tag): self
    {
        if (!isset($this->tags[$key])) {
            $this->tags[$key] = [];
        }

        $this->tags[$key][] = $tag;

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
                'p_nr' => $this->id,
                'p_brand' => $this->brand,
                'p_name' => $this->name,
                'p_text' => $this->description,
                'p_keywords' => $this->keywords,
            ],
            $this->getFlattenedAvailability(),
            $this->getFlattenedBulletPoints(),
            $this->getFlattenedComponents(),
            $this->getFlattenedTags()
        );
    }

    public function getFlattenedAvailability(): array
    {
        $flattened = [];
        if (is_array($this->availability)) {
            foreach ($this->availability as $channel => $active) {
                $flattened["p_active[{$channel}]"] = $active ? 1 : 0;
            }
        } else {
            $flattened['p_active'] = $this->availability ? 1 : 0;
        }

        return $flattened;
    }

    public function getFlattenedBulletPoints(): array
    {
        $flattened = [];
        foreach ($this->bulletPoints as $index => $bulletPoint) {
            $flattened["p_bullet{{$index}}"] = $bulletPoint;
        }

        return $flattened;
    }

    public function getFlattenedComponents(): array
    {
        $flattened = [];
        foreach ($this->components as $key => $component) {
            $flattened["p_comp[{$key}]"] = $component;
        }

        return $flattened;
    }

    public function getFlattenedTags(): array
    {
        $flattened = [];
        foreach ($this->tags as $key => $tags) {
            foreach ($tags as $index => $tag) {
                $flattened["p_tag[{$key}]{{$index}}"] = $tag;
            }
        }

        return $flattened;
    }
}
