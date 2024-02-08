<?php

declare(strict_types=1);

/**
 * Contains the DummyTaxDriver class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-08
 *
 */

namespace Vanilo\Taxes\Tests\Dummies;

use Vanilo\Contracts\Address;
use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Contracts\TaxRate;
use Vanilo\Taxes\Contracts\TaxRateResolver;

class DummyTaxDriver implements TaxRateResolver
{
    public const TEST_RATE = 66;

    public function findTaxRate(Taxable $taxable, ?Address $billingAddress = null, ?Address $shippingAddress = null): ?TaxRate
    {
        return new \Vanilo\Taxes\Models\TaxRate(['rate' => self::TEST_RATE]);
    }
}
