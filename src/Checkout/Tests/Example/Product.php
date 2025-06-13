<?php

declare(strict_types=1);

/**
 * Contains the Product test class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-13
 *
 */

namespace Vanilo\Checkout\Tests\Example;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Vanilo\Contracts\Buyable;

class Product implements Buyable
{
    public $id;

    public $name;

    public $price;

    public function __construct($id, $name, $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function getId(): int|string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getOriginalPrice(): ?float
    {
        return null;
    }

    public function hasAHigherOriginalPrice(): bool
    {
        return false;
    }

    public function hasImage(): bool
    {
        return false;
    }

    public function getThumbnailUrl(): ?string
    {
        return null;
    }

    public function getImageUrl(string $variant = ''): ?string
    {
        return null;
    }

    public function morphTypeName(): string
    {
        return 'product';
    }

    public function addSale(Carbon $date, $units = 1): void
    {
        // not implemented here
    }

    public function removeSale($units = 1): void
    {
        // not implemented here
    }

    public function imageCount(): int
    {
        return 0;
    }

    public function getThumbnailUrls(): Collection
    {
        return collect([]);
    }

    public function getImageUrls(string $variant = ''): Collection
    {
        return collect([]);
    }
}
