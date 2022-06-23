<?php

declare(strict_types=1);

/**
 * Contains the ModelPropertyValuesTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Tests;

use Illuminate\Database\Schema\Blueprint;
use Vanilo\Properties\Models\Property;
use Vanilo\Properties\Models\PropertyValue;
use Vanilo\Properties\Tests\Examples\Product;

class ModelPropertyValuesTest extends TestCase
{
    /** @test */
    public function it_can_be_assigned_to_an_arbitrary_model()
    {
        $product = Product::create([
            'name' => 'Turbine Kreuzberg'
        ]);

        $haha = factory(PropertyValue::class)->create([
            'value' => 'haha'
        ]);

        $sixteen = factory(PropertyValue::class)->create([
            'property_id' => factory(Property::class)->create(['type' => 'integer'])->id,
            'value' => 16
        ]);

        $product->propertyValues()->save($haha);
        $product->propertyValues()->save($sixteen);

        $this->assertCount(2, $product->propertyValues);
        $this->assertEquals('haha', $product->propertyValues->first()->value);
        $this->assertIsInt($product->propertyValues->last()->getCastedValue());
        $this->assertEquals(16, $product->propertyValues->last()->getCastedValue());
    }

    /** @test */
    public function value_of_a_property_can_be_returned_by_property_slug()
    {
        $lamp = Product::create([
            'name' => 'Bicycle Lamp'
        ]);

        $color = factory(Property::class)->create(['name' => 'Color']);

        $red = factory(PropertyValue::class)->create([
            'value' => 'red',
            'property_id' => $color->id,
        ]);

        $lamp->addPropertyValue($red);

        $this->assertInstanceOf(PropertyValue::class, $lamp->valueOfProperty('color'));
        $this->assertEquals('red', $lamp->valueOfProperty('color')->getCastedValue());
    }

    /** @test */
    public function value_of_a_property_returns_null_if_the_property_is_not_assigned()
    {
        $flex = Product::create([
            'name' => 'Bosch PWS 750-115 Angle Grinder'
        ]);

        factory(PropertyValue::class)->create([
            'value' => 'green',
            'property_id' => factory(Property::class)->create(['name' => 'Color'])->id,
        ]);

        $this->assertNull($flex->valueOfProperty('color'));
    }

    /** @test */
    public function value_of_a_property_returns_null_if_the_property_does_not_exist()
    {
        $drill = Product::create([
            'name' => 'Milwaukee M18 2-Tool Brushless Combo Kit'
        ]);

        $this->assertNull($drill->valueOfProperty('color'));
    }

    protected function setUpDatabase($app)
    {
        parent::setUpDatabase($app);

        $app['db']->connection()->getSchemaBuilder()->create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
    }
}
