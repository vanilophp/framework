<?php

declare(strict_types=1);

/**
 * Contains the Adjustable interface.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-27
 *
 */

namespace Vanilo\Adjustments\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Adjustable
{
    public function itemsTotal(): float;

    /**
     * @todo add this to the interface in v4
     * public function invalidateAdjustments(): void
     */
    public function adjustments(): AdjustmentCollection;

    public function adjustmentsRelation(): MorphMany;

    public function recalculateAdjustments(): void;
}
