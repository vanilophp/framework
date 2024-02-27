<?php

declare(strict_types=1);

/**
 * Contains the NullShippingFeeCalculator class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

namespace Vanilo\Shipment\Calculators;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Vanilo\Shipment\Contracts\ShippingFeeCalculator;
use Vanilo\Shipment\Models\ShippingFee;

class NullShippingFeeCalculator implements ShippingFeeCalculator
{
    public static function getName(): string
    {
        return __('No shipping fee');
    }

    public function calculate(object $subject = null, ?array $configuration = null): ShippingFee
    {
        return new ShippingFee(0);
    }

    public function getAdjuster(?array $configuration = null): ?object
    {
        return null;
    }

    public function getSchema(): Schema
    {
        return Expect::array();
    }

    public function getSchemaSample(array $mergeWith = null): array
    {
        return [];
    }
}
