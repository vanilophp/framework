<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface SaleItem extends Configurable
{
    public function getBuyable(): Buyable;

    public function getName(): string;

    public function getQuantity(): float;

    public function getPrice(): float;

    public function total(): float;
}
