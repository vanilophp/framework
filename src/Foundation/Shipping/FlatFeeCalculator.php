<?php

declare(strict_types=1);

/**
 * Contains the FlatFeeCalculator class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

namespace Vanilo\Foundation\Shipping;

use Vanilo\Adjustments\Adjusters\SimpleShippingFee;
use Vanilo\Contracts\Shippable;
use Vanilo\Shipment\Contracts\ShippingFeeCalculator;
use Vanilo\Shipment\Exceptions\InvalidShippingConfigurationException;
use Vanilo\Shipment\Models\ShippingFee;

class FlatFeeCalculator implements ShippingFeeCalculator
{
    public const ID = 'flat_fee';

    public static function getName(): string
    {
        return __('Flat fee');
    }

    public function calculate(Shippable $shippable, ?array $configuration = null): ShippingFee
    {
        if (!is_array($configuration) || !isset($configuration['cost'])) {
            throw new InvalidShippingConfigurationException('The shipping fee can not be calculated. The `cost` configuration value is missing.');
        }
        $cost = floatval($configuration['cost']);
        $freeThreshold = $configuration['free_threshold'] ?? null;
        $freeThreshold = is_null($freeThreshold) ? null : floatval($freeThreshold);
        $adjuster = new SimpleShippingFee($cost, $freeThreshold);
        $adjuster->setTitle($configuration['title'] ?? __('Shipping fee'));

        return new ShippingFee(0, $adjuster);
    }
}
