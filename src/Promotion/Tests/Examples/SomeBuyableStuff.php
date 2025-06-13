<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests\Examples;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Vanilo\Contracts\Buyable;

class SomeBuyableStuff implements Buyable
{
    public function __construct(
        public string|int $id,
        public string $name,
        public float $price,
    ) {
    }

    public function getId(): string|int
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

    public function addSale(Carbon $date, float|int $units = 1): void
    {
        // TODO: Implement addSale() method.
    }

    public function removeSale(float|int $units = 1): void
    {
        // TODO: Implement removeSale() method.
    }

    public function morphTypeName(): string
    {
        // TODO: Implement morphTypeName() method.
    }

    public function hasImage(): bool
    {
        // TODO: Implement hasImage() method.
    }

    public function imageCount(): int
    {
        // TODO: Implement imageCount() method.
    }

    public function getThumbnailUrl(): ?string
    {
        // TODO: Implement getThumbnailUrl() method.
    }

    public function getThumbnailUrls(): Collection
    {
        // TODO: Implement getThumbnailUrls() method.
    }

    public function getImageUrl(string $variant = ''): ?string
    {
        // TODO: Implement getImageUrl() method.
    }

    public function getImageUrls(string $variant = ''): Collection
    {
        // TODO: Implement getImageUrls() method.
    }
}
