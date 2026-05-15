<?php

declare(strict_types=1);

/**
 * Contains the ShippingMethod class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-09-08
 *
 */

namespace Vanilo\Foundation\Models;

use Carbon\Carbon;
use Vanilo\Channel\Traits\Channelable;
use Vanilo\Contracts\Buyable;
use Vanilo\Shipment\Models\ShippingMethod as BaseShippingMethod;
use Vanilo\Support\Traits\BuyableNoImage;
use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Traits\BelongsToTaxCategory;

class ShippingMethod extends BaseShippingMethod implements Taxable, Buyable
{
    use BelongsToTaxCategory;
    use BuyableNoImage;
    use Channelable;

    public function getId(): int|string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return 0;
    }

    public function getOriginalPrice(): ?float
    {
        return null;
    }

    public function hasAHigherOriginalPrice(): bool
    {
        return false;
    }

    public function addSale(Carbon $date, int|float $units = 1): void
    {
        $this->last_usage_at = $date;
        $this->usage_count += $units;
        $this->save();
    }

    public function removeSale(int|float $units = 1): void
    {
        $this->usage_count -= $units;
        $this->save();
    }

    public function morphTypeName(): string
    {
        return 'shipping_method';
    }
}
