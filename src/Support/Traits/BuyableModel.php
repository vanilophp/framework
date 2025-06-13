<?php

declare(strict_types=1);

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
        return (float) $this->price;
    }

    public function getOriginalPrice(): ?float
    {
        return $this->original_price ? (float) $this->original_price : null;
    }

    public function hasAHigherOriginalPrice(): bool
    {
        return $this->original_price !== null && $this->original_price > $this->price;
    }

    public function addSale(Carbon $date, int|float $units = 1): void
    {
        $this->last_sale_at = $date;
        $this->units_sold += $units;
        $this->save();
    }

    public function removeSale(int|float $units = 1): void
    {
        $this->units_sold -= $units;
        $this->save();
    }

    public function morphTypeName(): string
    {
        return shorten(static::class);
    }
}
