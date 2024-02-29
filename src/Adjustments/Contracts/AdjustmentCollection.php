<?php

declare(strict_types=1);

/**
 * Contains the AdjustmentCollection interface.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-28
 *
 */

namespace Vanilo\Adjustments\Contracts;

use ArrayAccess;
use Countable;
use IteratorAggregate;

interface AdjustmentCollection extends IteratorAggregate, ArrayAccess, Countable
{
    public function adjustable(): Adjustable;

    public function total(): float;

    public function isEmpty(): bool;

    public function isNotEmpty(): bool;

    public function create(Adjuster $adjuster): Adjustment;

    public function add(Adjustment $adjustment): void;

    public function remove(Adjustment $adjustment): void;

    public function clear(): void;

    public function deleteByType(AdjustmentType $type): void;

    public function first(): ?Adjustment;

    public function last(): ?Adjustment;

    /** Returns a **copy** of the collection containing the entries of the given type */
    public function byType(AdjustmentType $type): AdjustmentCollection;

    /** Returns a **copy** of the collection containing the entries except the given types */
    public function exceptTypes(AdjustmentType ...$types): AdjustmentCollection;
}
