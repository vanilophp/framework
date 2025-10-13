<?php

declare(strict_types=1);

/**
 * Contains the TaxEngineDriver interface.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-08
 *
 */

namespace Vanilo\Taxes\Contracts;

use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;

interface TaxEngineDriver
{
    public function resolveTaxRate(Taxable $taxable, ?Billpayer $billpayer = null, ?Address $shippingAddress = null): ?TaxRate;

    /** @todo Add this in v6 by extending `Registerable`: "public static function getName(): string;" */
}
