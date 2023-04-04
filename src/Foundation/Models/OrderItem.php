<?php

declare(strict_types=1);

/**
 * Contains the OrderItem class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-26
 *
 */

namespace Vanilo\Foundation\Models;

use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Support\HasAdjustmentsViaRelation;
use Vanilo\Adjustments\Support\RecalculatesAdjustments;
use Vanilo\Foundation\Traits\CanBeShipped;
use Vanilo\Order\Models\OrderItem as BaseOrderItem;

class OrderItem extends BaseOrderItem implements Adjustable
{
    use CanBeShipped;
    use HasAdjustmentsViaRelation;
    use RecalculatesAdjustments;

    /** @todo rename this in v4 along with the renaming of this method in the Adjustable interface */
    public function itemsTotal(): float
    {
        return $this->total();
    }
}
