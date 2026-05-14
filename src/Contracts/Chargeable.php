<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface Chargeable extends CurrencyAware
{
    public function total(): float;
}
