<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests\Examples;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Vanilo\Cart\Contracts\Cart;
use Vanilo\Cart\Contracts\CartItem;
use Vanilo\Contracts\Buyable;

class DummyCart implements Cart
{
    public function __construct(
        private int $itemCount = 5
    ) {
    }

    public function addItem(Buyable $product, float|int $qty = 1, array $params = []): CartItem
    {
        // TODO: Implement addItem() method.
    }

    public function removeItem(CartItem $item): void
    {
        // TODO: Implement removeItem() method.
    }

    public function removeProduct(Buyable $product): void
    {
        // TODO: Implement removeProduct() method.
    }

    public function clear(): void
    {
        // TODO: Implement clear() method.
    }

    public function itemCount(): int
    {
        return $this->itemCount;
    }

    public function getUser(): ?Authenticatable
    {
        // TODO: Implement getUser() method.
    }

    public function setUser(int|Authenticatable|string|null $user): void
    {
        // TODO: Implement setUser() method.
    }

    public function getItems(): Collection
    {
        // TODO: Implement getItems() method.
    }

    public function itemsTotal(): float
    {
        // TODO: Implement itemsTotal() method.
    }

    public function total(): float
    {
        // TODO: Implement total() method.
    }
}
