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
use Traversable;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Adjustments\Contracts\Adjustment;
use Vanilo\Adjustments\Contracts\AdjustmentCollection;
use Vanilo\Adjustments\Contracts\AdjustmentType;
use Vanilo\Adjustments\Models\AdjustmentTypeProxy;

class RelationAdjustmentCollection implements AdjustmentCollection
{
    private Adjustable $model;

    /** @var array<AdjustmentType>|null $typeFilter */
    private ?array $typeFilter = null;

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

    public function total(bool $withIncluded = false): float
    {
        return $withIncluded ?
            floatval($this->eloquentCollection()->sum('amount'))
            :
            floatval($this->eloquentCollection()->filter->isNotIncluded()->sum('amount'));
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

    public function clear(): void
    {
        $this->eloquentCollection()->each(fn (Adjustment|Model $adjustment) => $adjustment->delete());
    }

    public function deleteByType(AdjustmentType $type): void
    {
        $this->eloquentCollection()->each(function (Adjustment|Model $adjustment) use ($type) {
            if ($type->equals($adjustment->getType())) {
                $adjustment->delete();
            }
        });
    }

    public function byType(AdjustmentType $type): AdjustmentCollection
    {
        $result = new self($this->model);
        $result->typeFilter = [$type];

        return $result;
    }

    public function exceptTypes(AdjustmentType ...$types): AdjustmentCollection
    {
        $result = new self($this->model);
        $result->typeFilter = collect(AdjustmentTypeProxy::choices())
            ->except(array_map(fn (AdjustmentType $type) => $type->value(), $types))
            ->mapWithKeys(fn ($label, $value) => AdjustmentTypeProxy::create($value))
            ->toArray();

        return $result;
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->eloquentCollection()->offsetExists($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->eloquentCollection()->offsetGet($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!is_object($value) || !($value instanceof Adjustment)) {
            throw new \InvalidArgumentException('Only objects implementing the Adjustment interface can be used');
        }

        $this->eloquentCollection()->offsetSet($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->eloquentCollection()->offsetUnset($offset);
    }

    public function count(): int
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

    public function getIterator(): Traversable
    {
        return $this->eloquentCollection()->getIterator();
    }

    public function mapInto(string $class)
    {
        return $this->eloquentCollection()->mapInto($class);
    }

    private function relation(): MorphMany
    {
        return $this->model->adjustmentsRelation();
    }

    private function eloquentCollection(): Collection
    {
        /** @var Collection $collection */
        $collection = $this->model->adjustmentsRelation;

        if (empty($this->typeFilter)) {
            return $collection;
        }

        return $collection->filter(fn (Adjustment $adjustment) => $adjustment->type->isAnyOf(...$this->typeFilter));
    }
}
