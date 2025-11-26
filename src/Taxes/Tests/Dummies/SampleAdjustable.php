<?php

declare(strict_types=1);

namespace Vanilo\Taxes\Tests\Dummies;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\AdjustmentCollection;

class SampleAdjustable implements Adjustable
{
    public function __construct(
        public float $fakePreadjustmentTotal
    ) {
    }

    public function preAdjustmentTotal(): float
    {
        return $this->fakePreadjustmentTotal;
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
}
