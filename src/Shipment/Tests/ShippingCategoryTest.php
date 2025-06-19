<?php

declare(strict_types=1);


use Vanilo\Shipment\Contracts\ShippingCategory as ShippingCategoryContract;
use Vanilo\Shipment\Models\ShippingCategory;
use Vanilo\Shipment\Tests\TestCase;

class ShippingCategoryTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $shippingCategory = new ShippingCategory();

        $this->assertInstanceOf(ShippingCategory::class, $shippingCategory);
    }
    
    /** @test */
    public function it_can_be_created_with_minimal_data()
    {
        $shippingCategory = ShippingCategory::create(['name' => 'Stackable']);
    
        $this->assertInstanceOf(ShippingCategory::class, $shippingCategory);
        $this->assertEquals('Stackable', $shippingCategory->getName());
        $this->assertEquals($shippingCategory->id, $shippingCategory->getId());
    }

    /** @test */
    public function it_complies_with_the_interface()
    {
        $shippingCategory = ShippingCategory::create(['name' => 'Stackable'])->fresh();

        $this->assertInstanceOf(ShippingCategoryContract::class, $shippingCategory);

        $instanceViaLookup = ShippingCategory::where('name', 'Stackable')->first();
        $this->assertInstanceOf(ShippingCategoryContract::class, $instanceViaLookup);
        $this->assertEquals('Stackable', $instanceViaLookup->getName());
    }

    /** @test */
    public function it_sets_proper_defaults()
    {
        $shippingCategory = ShippingCategory::create(['name' => 'Standard'])->fresh();

        $this->assertFalse($shippingCategory->isFragile());
        $this->assertFalse($shippingCategory->isHazardous());
        $this->assertTrue($shippingCategory->isStackable());
        $this->assertFalse($shippingCategory->requiresTemperatureControl());
        $this->assertFalse($shippingCategory->requiresSignature());
    }


    /** @test */
    public function it_can_be_set_all_bool_flags()
    {
        $shippingCategory = ShippingCategory::create([
            'name' => 'Non-standard',
            'is_fragile' => true,
            'is_hazardous' => true,
            'is_stackable' => false,
            'requires_temperature_control' => true,
            'requires_signature' => true,
        ])->fresh();

        $this->assertTrue($shippingCategory->isFragile());
        $this->assertTrue($shippingCategory->isHazardous());
        $this->assertFalse($shippingCategory->isStackable());
        $this->assertTrue($shippingCategory->requiresTemperatureControl());
        $this->assertTrue($shippingCategory->requiresSignature());
    }
}
