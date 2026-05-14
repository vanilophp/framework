<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface CurrencyAware
{
    public function getCurrency(): string;
}
