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
    /** @todo rename this in v4. In case of a cart item or and order item, this name is just stupid */
    public function itemsTotal(): float;

    /**
     * @todo add this to the interface in v4
     * public function invalidateAdjustments(): void
     */
    public function adjustments(): AdjustmentCollection;

    public function adjustmentsRelation(): MorphMany;

    public function recalculateAdjustments(): void;
}
