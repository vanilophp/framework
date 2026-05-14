<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface Billable extends Chargeable
{
    public function getBillpayer(): ?Billpayer;
}
