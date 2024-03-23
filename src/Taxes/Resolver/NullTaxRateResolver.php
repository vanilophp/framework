<?php

declare(strict_types=1);

/**
 * Contains the NullTaxRateResolver class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-08
 *
 */

namespace Vanilo\Taxes\Resolver;

use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Contracts\TaxRate;
use Vanilo\Taxes\Contracts\TaxRateResolver;

class NullTaxRateResolver implements TaxRateResolver
{
    public const ID = 'none';

    public function findTaxRate(Taxable $taxable, ?Billpayer $billpayer = null, ?Address $shippingAddress = null): ?TaxRate
    {
        return null;
    }
}
