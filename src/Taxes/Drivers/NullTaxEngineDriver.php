<?php

declare(strict_types=1);

/**
 * Contains the NullTaxEngineDriver class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-08
 *
 */

namespace Vanilo\Taxes\Drivers;

use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Contracts\TaxEngineDriver;
use Vanilo\Taxes\Contracts\TaxRate;

class NullTaxEngineDriver implements TaxEngineDriver
{
    public const ID = 'none';

    public function resolveTaxRate(Taxable $taxable, ?Billpayer $billpayer = null, ?Address $shippingAddress = null): ?TaxRate
    {
        return null;
    }
}
