<?php

declare(strict_types=1);

namespace Vanilo\Product\Contracts;

use Konekt\Enum\EnumInterface;

interface ProductAvailabilityScope extends EnumInterface
{
    public function getStates(): array;
}
