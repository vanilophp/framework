<?php

declare(strict_types=1);

/**
 * Contains the SimpleTaxEngineDriver class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-28
 *
 */

namespace Vanilo\Taxes\Drivers;

use Konekt\Address\Query\Zones;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Contracts\TaxEngineDriver;
use Vanilo\Taxes\Contracts\TaxRate;
use Vanilo\Taxes\Models\TaxRateProxy;

class SimpleTaxEngineDriver implements TaxEngineDriver
{
    public function __construct(
        private readonly bool $useShippingAddress = false,
    ) {
    }

    public function resolveTaxRate(Taxable $taxable, ?Billpayer $billpayer = null, ?Address $shippingAddress = null): ?TaxRate
    {
        if (null === $address = $this->useShippingAddress ? $shippingAddress : $billpayer?->getBillingAddress()) {
            return null;
        }

        if (null === $zone = Zones::withTaxationScope()->theAddressBelongsTo($address)->first()) {
            return null;
        }

        return TaxRateProxy::findOneByZoneAndCategory($zone, $taxable->getTaxCategory(), true);
    }
}
