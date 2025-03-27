<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests\Examples;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\AdjustmentCollection;
use Vanilo\Cart\Contracts\Cart;
use Vanilo\Cart\Contracts\CartItem;
use Vanilo\Contracts\Buyable;

class DummyAdjustableCart implements Cart, Adjustable
{
    private array $items = [];

    public function __construct(
        private float $preAdjustmentTotal
    ) {
    }

    public function preAdjustmentTotal(): float
    {
        return $this->preAdjustmentTotal;
    }

    public function invalidateAdjustments(): void
    {
        // TODO: Implement invalidateAdjustments() method.
    }

    public function adjustments(): AdjustmentCollection
    {
        // TODO: Implement adjustments() method.
    }

    public function adjustmentsRelation(): MorphMany
    {
        // TODO: Implement adjustmentsRelation() method.
    }

    public function recalculateAdjustments(): void
    {
        // TODO: Implement recalculateAdjustments() method.
    }

    public function addItem(Buyable $product, float|int $qty = 1, array $params = []): CartItem
    {
        $item = new DummyCartItem($qty * $product->getPrice(), $qty);
        $this->items[] = $item;

        return $item;
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
        $this->items = [];
    }

    public function itemCount(): int
    {
        return count($this->items);
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
        return collect($this->items);
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
