<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface SaleItem extends Configurable
{
    /** @todo make this nullable in Vanilo 6. There can be cases where the buyable is deleted at the time of the retrieval */
    public function getBuyable(): Buyable;

    public function getName(): string;

    public function getQuantity(): float;

    public function getPrice(): float;

    public function total(): float;
}
