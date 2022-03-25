<?php

declare(strict_types=1);

/**
 * Contains the Cart class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-03-25
 *
 */

namespace Vanilo\Foundation\Models;

use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Support\HasAdjustmentsViaRelation;
use Vanilo\Adjustments\Support\RecalculatesAdjustments;
use Vanilo\Cart\Models\Cart as BaseCart;

class Cart extends BaseCart implements Adjustable
{
    use HasAdjustmentsViaRelation;
    use RecalculatesAdjustments;

    public function total(): float
    {
        return $this->itemsTotal() + $this->adjustments()->total();
    }

    public function itemsTotal(): float
    {
        return $this->items->sum('total');
    }
}
