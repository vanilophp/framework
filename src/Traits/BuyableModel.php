<?php
/**
 * Contains the BuyableModel trait.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-31
 *
 */

namespace Vanilo\Support\Traits;

use Carbon\Carbon;

/**
 * Trait for Eloquent Models with default implementation of the Buyable interface
 */
trait BuyableModel
{
    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function addSale(Carbon $date, $units = 1): void
    {
        $this->last_sale_at = $date;
        $this->units_sold += $units;
        $this->save();
    }

    public function removeSale($units = 1): void
    {
        $this->units_sold -= $units;
        $this->save();
    }

    public function morphTypeName(): string
    {
        return shorten(static::class);
    }
}
