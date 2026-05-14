<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface Priceable extends CurrencyAware
{
    public function hasPrice(): bool;

    public function getPrice(): ?float;
}
