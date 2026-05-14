<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface Settleable extends Chargeable
{
    public function getSettledAmount(): float;

    public function getToBeSettledAmount(): float;

    public function isFullySettled(): bool;
}
