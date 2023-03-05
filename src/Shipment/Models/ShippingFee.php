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
    protected ?object $adjuster;
    private DetailedAmount $detailedAmount;

    /**
     * @param float|DetailedAmount|null $amount
     * @param \Vanilo\Adjustments\Contracts\Adjuster|null $adjuster
     */
    public function __construct(float|DetailedAmount $amount, ?object $adjuster = null)
    {
        $this->adjuster = $adjuster;
        $this->detailedAmount = $amount instanceof DetailedAmount ? $amount : new \Vanilo\Support\Dto\DetailedAmount($amount);
    }

    /**
     * We don't set the return type on the language level
     * since the adjustments module is optional
     *
     * @return null|\Vanilo\Adjustments\Contracts\Adjuster
     */
    public function getAdjuster(): ?object
    {
        return $this->adjuster;
    }

    public function amount(): DetailedAmount
    {
        return $this->detailedAmount;
    }
}
