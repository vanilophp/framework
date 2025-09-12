<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests\Examples;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\AdjustmentCollection;
use Vanilo\Adjustments\Support\ArrayAdjustmentCollection;
use Vanilo\Adjustments\Support\RecalculatesAdjustments;
use Vanilo\Contracts\CheckoutSubject;

class SampleAdjustable implements Adjustable, CheckoutSubject
{
    use RecalculatesAdjustments;

    private ArrayAdjustmentCollection $adjustments;

    public function __construct(
        private readonly float $preAdjustmentsTotal = 0,
    ) {
        $this->adjustments = new ArrayAdjustmentCollection($this);
    }

    public function preAdjustmentTotal(): float
    {
        return $this->preAdjustmentsTotal;
    }

    public function invalidateAdjustments(): void
    {
        $this->adjustments->clear();
    }

    public function adjustments(): AdjustmentCollection
    {
        return $this->adjustments;
    }

    public function adjustmentsRelation(): MorphMany
    {
        // TODO: Implement adjustmentsRelation() method.
    }

    public function getItems(): Collection
    {
        return collect();
    }

    public function itemsTotal(): float
    {
        return 0;
    }

    public function total(): float
    {
        return 0;
    }
}
