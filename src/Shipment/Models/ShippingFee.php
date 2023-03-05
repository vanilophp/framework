<?php

declare(strict_types=1);

/**
 * Contains the ShippingFee class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

namespace Vanilo\Shipment\Models;

use Vanilo\Contracts\DetailedAmount;

class ShippingFee
{
    private DetailedAmount $detailedAmount;

    private bool $isEstimate;

    public function __construct(float|DetailedAmount $amount, bool $isEstimate = false)
    {
        $this->isEstimate = $isEstimate;
        $this->detailedAmount = $amount instanceof DetailedAmount ? $amount : new \Vanilo\Support\Dto\DetailedAmount($amount);
    }

    public function amount(): DetailedAmount
    {
        return $this->detailedAmount;
    }

    public function isEstimate(): bool
    {
        return $this->isEstimate;
    }
}
