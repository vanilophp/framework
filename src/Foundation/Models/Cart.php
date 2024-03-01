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
use Vanilo\Adjustments\Models\AdjustmentTypeProxy;
use Vanilo\Adjustments\Support\HasAdjustmentsViaRelation;
use Vanilo\Adjustments\Support\RecalculatesAdjustments;
use Vanilo\Cart\Models\Cart as BaseCart;

class Cart extends BaseCart implements Adjustable
{
    use HasAdjustmentsViaRelation;
    use RecalculatesAdjustments;

    public function total(): float
    {
        return $this->preAdjustmentTotal() + $this->adjustments()->total();
    }

    /**
     * It equals to itemsTotal(), meaning that it contains the sum of the cart items,
     * including the adjustments of the cart items. Therefore, the adjustments of
     * the cart itself are fully segregated from the adjustments of cart items
     */
    public function preAdjustmentTotal(): float
    {
        return $this->itemsTotal();
    }

    public function itemsTotal(): float
    {
        return $this->items->sum('total');
    }

    public function shippingAdjustmentsTotal(): float
    {
        return $this->adjustments()->byType(AdjustmentTypeProxy::SHIPPING())->total();
    }

    public function taxAdjustmentsTotal(): float
    {
        return $this->adjustments()->byType(AdjustmentTypeProxy::TAX())->total();
    }

    public function promotionAdjustmentsTotal(): float
    {
        return $this->adjustments()->byType(AdjustmentTypeProxy::PROMOTION())->total();
    }
}
