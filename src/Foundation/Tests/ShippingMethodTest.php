<?php

declare(strict_types=1);

namespace Vanilo\Foundation\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Foundation\Models\ShippingMethod;
use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Models\TaxCategory;

class ShippingMethodTest extends TestCase
{
    #[Test] public function no_adjustment_gets_created_if_the_shipping_method_doesnt_have_a_calculator()
    {
        $taxCategory = TaxCategory::create(['name' => 'Shipping Tax Category']);
        $shippingMethod = ShippingMethod::create(['name' => 'Taxable Delivery', 'tax_category_id' => $taxCategory->id]);

        $shippingMethod = $shippingMethod->fresh();

        $this->assertInstanceof(Taxable::class, $shippingMethod);
        $this->assertEquals($taxCategory->id, $shippingMethod->taxCategory->id);
    }
}
