<?php

declare(strict_types=1);

use Vanilo\Shipment\Contracts\ShippingCategory as ShippingCategoryContract;
use Vanilo\Shipment\Contracts\ShippingCategoryMatchingCondition as ShippingCategoryMatchingConditionContract;
use Vanilo\Shipment\Models\ShippingCategory;
use Vanilo\Shipment\Models\ShippingCategoryMatchingCondition;
use Vanilo\Shipment\Models\ShippingMethod;
use Vanilo\Shipment\Tests\TestCase;

class ShippingMethodCategoryTest extends TestCase
{
    /** @test */
    public function a_shipping_method_has_no_shipping_category_by_default()
    {
        $method = ShippingMethod::create(['name' => 'Canada Post'])->fresh();

        $this->assertNull($method->shipping_category_id);
        $this->assertFalse($method->hasShippingCategory());
        $this->assertNull($method->getShippingCategory());
    }

    /** @test */
    public function a_shipping_method_can_have_a_shipping_category()
    {
        $shippingCategory = ShippingCategory::create(['name' => 'Stackable']);
        $method = ShippingMethod::create(['name' => 'Canada Post', 'shipping_category_id' => $shippingCategory->id])->fresh();

        $this->assertEquals($shippingCategory->id, $method->shipping_category_id);
        $this->assertTrue($method->hasShippingCategory());
        $this->assertInstanceOf(ShippingCategoryContract::class, $method->getShippingCategory());
        $this->assertEquals($shippingCategory->id, $method->getShippingCategory()->id);
    }

    /** @test */
    public function shipping_category_matching_condition_is_none_by_default()
    {
        $method = ShippingMethod::create(['name' => 'Canada Post'])->fresh();

        $this->assertInstanceOf(ShippingCategoryMatchingConditionContract::class, $method->shipping_category_matching_condition);
        $this->assertEquals(ShippingCategoryMatchingCondition::NONE(), $method->shipping_category_matching_condition);
        $this->assertTrue($method->shipping_category_matching_condition->is_none);
        $this->assertTrue($method->shipping_category_matching_condition->isNone());
    }

    /** @test */
    public function a_shipping_method_can_be_assigned_a_shipping_category_matching_condition()
    {
        $method = ShippingMethod::create([
            'name' => 'Canada Post',
            'shipping_category_matching_condition' => ShippingCategoryMatchingCondition::AT_LEAST_ONE()
        ])->fresh();

        $this->assertInstanceOf(ShippingCategoryMatchingConditionContract::class, $method->shipping_category_matching_condition);
        $this->assertEquals(ShippingCategoryMatchingCondition::AT_LEAST_ONE(), $method->shipping_category_matching_condition);
        $this->assertTrue($method->shipping_category_matching_condition->is_at_least_one);
        $this->assertTrue($method->shipping_category_matching_condition->isAtLeastOne());
    }
}
