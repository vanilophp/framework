<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests\Examples;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\AdjustmentCollection;
use Vanilo\Adjustments\Support\ArrayAdjustmentCollection;
use Vanilo\Cart\Contracts\CartItem;
use Vanilo\Contracts\Buyable;
use Vanilo\Contracts\Schematized;

class DummyCartItem implements CartItem, Adjustable
{
    private ArrayAdjustmentCollection $adjustments;

    public function __construct(
        private float $preAdjustmentsTotal,
        private int $quantity
    ) {
        $this->adjustments = new ArrayAdjustmentCollection($this);
    }

    public function preAdjustmentTotal(): float
    {
        return $this->preAdjustmentsTotal;
    }

    public function invalidateAdjustments(): void
    {
        // TODO: Implement invalidateAdjustments() method.
    }

    public function adjustments(): AdjustmentCollection
    {
        return $this->adjustments;
    }

    public function adjustmentsRelation(): MorphMany
    {
        // TODO: Implement adjustmentsRelation() method.
    }

    public function recalculateAdjustments(): void
    {
        // TODO: Implement recalculateAdjustments() method.
    }

    public function getBuyable(): Buyable
    {
        // TODO: Implement getBuyable() method.
    }

    public function hasParent(): bool
    {
        return false;
    }

    public function getParent(): ?self
    {
        return null;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function total(): float
    {
        // TODO: Implement total() method.
    }

    public function configuration(): ?array
    {
        // TODO: Implement configuration() method.
    }

    public function hasConfiguration(): bool
    {
        // TODO: Implement hasConfiguration() method.
    }

    public function doesntHaveConfiguration(): bool
    {
        // TODO: Implement doesntHaveConfiguration() method.
    }

    public function getConfigurationSchema(): ?Schematized
    {
        // TODO: Implement getConfigurationSchema() method.
    }
}
