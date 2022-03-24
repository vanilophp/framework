<?php

declare(strict_types=1);

/**
 * Contains the RelationAdjustmentCollection class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-28
 *
 */

namespace Vanilo\Adjustments\Support;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Adjustments\Contracts\Adjustment;
use Vanilo\Adjustments\Contracts\AdjustmentCollection;
use Vanilo\Adjustments\Contracts\AdjustmentType;

class RelationAdjustmentCollection implements AdjustmentCollection
{
    private Adjustable $model;

    private ?AdjustmentType $typeFilter = null;

    public function __construct(Adjustable $model)
    {
        $this->model = $model;
    }

    public function adjustable(): Adjustable
    {
        return $this->model;
    }

    public function create(Adjuster $adjuster): Adjustment
    {
        $adjustment = $adjuster->createAdjustment($this->adjustable());
        $this->add($adjustment);

        return $adjustment;
    }

    public function total(): float
    {
        return floatval($this->eloquentCollection()->sum('amount'));
    }

    public function isEmpty(): bool
    {
        return $this->eloquentCollection()->isEmpty();
    }

    public function isNotEmpty(): bool
    {
        return $this->eloquentCollection()->isNotEmpty();
    }

    public function add(Adjustment $adjustment): void
    {
        $adjustment->setAdjustable($this->model);
        $this->relation()->save($adjustment);
        // Refresh the collection so that the new element to shows up
        $this->model->load('adjustmentsRelation');
    }

    public function remove(Adjustment $adjustment): void
    {
        if ($adjustment instanceof Model) {
            $items = $this->eloquentCollection();
            // This is the dirty part where it's flipping from Adjustment to Model
            $items->each(function (Model $item, $key) use ($adjustment, $items) {
                if ($item->getKey() === $adjustment->getKey()) {
                    $item->delete();
                    $items->forget($key);
                }
            });
        }
    }

    public function byType(AdjustmentType $type): AdjustmentCollection
    {
        $result = new self($this->model);
        $result->typeFilter = $type;

        return $result;
    }

    public function offsetExists($offset)
    {
        return $this->eloquentCollection()->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->eloquentCollection()->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        if (!is_object($value) || ! ($value instanceof Adjustment)) {
            throw new \InvalidArgumentException('Only objects implementing the Adjustment interface can be used');
        }

        $this->eloquentCollection()->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->eloquentCollection()->offsetUnset($offset);
    }

    public function count()
    {
        return $this->eloquentCollection()->count();
    }

    public function first(): ?Adjustment
    {
        return $this->eloquentCollection()->first();
    }

    public function last(): ?Adjustment
    {
        return $this->eloquentCollection()->last();
    }

    public function getIterator()
    {
        return $this->eloquentCollection()->getIterator();
    }

    private function relation(): MorphMany
    {
        return $this->model->adjustmentsRelation();
    }

    private function eloquentCollection(): Collection
    {
        /** @var Collection $collection */
        $collection = $this->model->adjustmentsRelation;

        if (null === $this->typeFilter) {
            return $collection;
        }

        return $collection->filter(fn (Adjustment $adjustment) => $this->typeFilter->equals($adjustment->type));
    }
}
