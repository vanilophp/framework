<?php

declare(strict_types=1);

/**
 * Contains the ShippingFeeCalculator interface.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-28
 *
 */

namespace Vanilo\Shipment\Contracts;

use Vanilo\Contracts\PurchaseIntent;
use Vanilo\Contracts\Schematized;
use Vanilo\Contracts\Shippable;
use Vanilo\Shipment\Models\ShippingFee;

interface ShippingFeeCalculator extends Schematized
{
    public static function getName(): string;

    /**
     * We don't set the return type on the language level
     * since the adjustments module is optional
     *
     * @return null|\Vanilo\Adjustments\Contracts\Adjuster
     */
    public function getAdjuster(?array $configuration = null): ?object;

    public function calculate(null|PurchaseIntent|Shippable $subject = null, ?array $configuration = null): ShippingFee;
}
