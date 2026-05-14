<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface PurchaseEvent
{
    public function getPurchase(): PurchaseIntent;
}
