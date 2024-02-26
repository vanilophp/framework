<?php

declare(strict_types=1);

/**
 * Contains the ArrayAdjustmentCollection class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-28
 *
 */

namespace Vanilo\Adjustments\Support;

use ArrayIterator;
use Traversable;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Adjustments\Contracts\Adjustment;
use Vanilo\Adjustments\Contracts\AdjustmentCollection as AdjustmentCollectionContract;
use Vanilo\Adjustments\Contracts\AdjustmentType;

class ArrayAdjustmentCollection implements AdjustmentCollectionContract
{
    /** @var Adjustment[] */
    private array $items = [];

    private Adjustable $adjustable;

    public function __construct(Adjustable $adjustable)
    {
        $this->adjustable = $adjustable;
    }

    public function adjustable(): Adjustable
    {
        return $this->adjustable;
    }

    public function total(): float
    {
        $result = 0;
        foreach ($this->items as $adjustment) {
            $result += $adjustment->getAmount();
        }

        return $result;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    public function add(Adjustment $adjustment): void
    {
        $this->items[] = $adjustment;
    }

    public function create(Adjuster $adjuster): Adjustment
    {
        $adjustment = $adjuster->createAdjustment($this->adjustable);
        $this->add($adjustment);

        return $adjustment;
    }

    public function remove(Adjustment $adjustment): void
    {
        foreach ($this->items as $key => $item) {
            if ($item === $adjustment) {
                unset($this->items[$key]);
            }
        }
    }

    public function clear(): void
    {
        foreach ($this->items as $key => $item) {
            unset($this->items[$key]);
        }
    }

    public function deleteByType(AdjustmentType $type): void
    {
        foreach ($this->items as $key => $item) {
            if ($type->equals($item->getType())) {
                unset($this->items[$key]);
            }
        }
    }

    public function byType(AdjustmentType $type): AdjustmentCollectionContract
    {
        $result = new self($this->adjustable);
        foreach ($this->items as $adjustment) {
            if ($type->equals($adjustment->getType())) {
                $result->add($adjustment);
            }
        }

        return $result;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!is_object($value) || !($value instanceof Adjustment)) {
            throw new \InvalidArgumentException('Only objects implementing the Adjustment interface can be used');
        }

        $this->items[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function first(): ?Adjustment
    {
        return $this->items[0] ?? null;
    }

    public function last(): ?Adjustment
    {
        return $this->items[$this->count() - 1] ?? null;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
