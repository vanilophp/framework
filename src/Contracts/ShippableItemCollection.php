<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface ShippableItemCollection extends ItemCollection
{
    public function total(): float;
}
