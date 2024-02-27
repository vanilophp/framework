<?php

declare(strict_types=1);

/**
 * Contains the ExampleTaxEngine class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-26
 *
 */

namespace Vanilo\Foundation\Tests\Examples;

use Vanilo\Contracts\Address;
use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Contracts\TaxRate;
use Vanilo\Taxes\Contracts\TaxRateResolver;
use Vanilo\Taxes\Models\TaxCategoryType;

/**
 * This is an example tax rate resolver
 * dedicated for unit-test only with
 * the following hard-coded logic
 *  - physical products: 19%
 *  - shipping: 7%
 *  - everything else: 15%
 */
class ExampleTaxEngine implements TaxRateResolver
{
    public const ID = 'example';

    public function findTaxRate(Taxable $taxable, ?Address $billingAddress = null, ?Address $shippingAddress = null): ?TaxRate
    {
        $rate = match ($taxable->getTaxCategory()->getType()->value()) {
            TaxCategoryType::PHYSICAL_GOODS => 19,
            TaxCategoryType::TRANSPORT_SERVICES => 7,
            default => 15,
        };

        return \Vanilo\Taxes\Models\TaxRate::firstOrCreate([
            'rate' => $rate,
            'name' => "$rate%",
            'calculator' => 'example',
            'configuration' => ['rate' => $rate],
        ]);
    }
}
