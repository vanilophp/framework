<?php

declare(strict_types=1);

/**
 * Contains the CartItem class.
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
use Vanilo\Cart\Models\CartItem as BaseCartItem;

class CartItem extends BaseCartItem implements Adjustable
{
    use HasAdjustmentsViaRelation;
    use RecalculatesAdjustments;

    /** @todo rename this in v4 along with the renaming of this method in the Adjustable interface */
    public function itemsTotal(): float
    {
        return $this->total();
    }
}
