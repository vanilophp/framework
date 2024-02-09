<?php

declare(strict_types=1);

/**
 * Contains the SampleTaxable class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-08
 *
 */

namespace Vanilo\Taxes\Tests\Dummies;

use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Contracts\TaxCategory;

class SampleTaxable implements Taxable
{
    public function getTaxCategory(): TaxCategory
    {
        return \Vanilo\Taxes\Models\TaxCategory::create(['name' => 'normal']);
    }
}
