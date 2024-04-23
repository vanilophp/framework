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
use Vanilo\Properties\Exceptions\UnknownPropertyException;
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

    /** @test */
    public function a_value_can_be_assigned_to_a_model_by_property_and_scalar_value_when_the_property_value_exists()
    {
        $cake = Product::create(['name' => 'Kiwi Cake']);

        $color = factory(Property::class)->create(['name' => 'Color']);
        $taste = factory(Property::class)->create(['name' => 'Taste']);

        factory(PropertyValue::class)->create([
            'value' => 'green',
            'property_id' => $color->id,
        ]);

        factory(PropertyValue::class)->create([
            'value' => 'tart',
            'property_id' => $taste->id,
        ]);

        $cake->assignPropertyValue($color, 'green');
        $cake->assignPropertyValue('taste', 'tart');

        $this->assertInstanceOf(PropertyValue::class, $cake->valueOfProperty('color'));
        $this->assertEquals('green', $cake->valueOfProperty('color')->getCastedValue());
        $this->assertEquals('tart', $cake->valueOfProperty('taste')->getCastedValue());
    }

    /** @test */
    public function a_non_existent_value_will_be_created_when_assigned_to_a_model_by_scalar_value()
    {
        $booklet = Product::create(['name' => 'Booklet']);
        $color = factory(Property::class)->create(['name' => 'Color']);

        $booklet->assignPropertyValue($color, 'purple');

        $this->assertInstanceOf(PropertyValue::class, $booklet->valueOfProperty('color'));
        $this->assertEquals('purple', $booklet->valueOfProperty('color')->getCastedValue());
        $this->assertModelExists($booklet->valueOfProperty('color'));
    }

    /** @test */
    public function multiple_values_can_be_assigned_to_a_model_by_scalar_value()
    {
        $sword = Product::create(['name' => 'Sword']);
        factory(Property::class)->create(['name' => 'Length']);
        factory(Property::class)->create(['name' => 'Shape']);

        $sword->assignPropertyValues([
            'length' => 'medium',
            'shape' => 'Hua lu guy',
        ]);

        $this->assertEquals('medium', $sword->valueOfProperty('length')->getCastedValue());
        $this->assertEquals('Hua lu guy', $sword->valueOfProperty('shape')->getCastedValue());
    }

    /** @test */
    public function multiple_entries_can_be_replaced_by_scalar_key_value_pairs_at_once()
    {
        $wheel = Product::create(['name' => 'Wheel']);
        $finish = Property::create(['name' => 'Finish', 'slug' => 'finish', 'type' => 'text']);
        $diameter = Property::create(['name' => 'Diameter', 'slug' => 'diameter', 'type' => 'integer']);
        $brand = Property::create(['name' => 'Brand', 'slug' => 'brand', 'type' => 'text']);
        $finish->propertyValues()->createMany([
            ['title' => 'Glossy', 'value' => 'glossy'],
            ['title' => 'Matte', 'value' => 'matte'],
            ['title' => 'Cube', 'value' => 'cube'],
        ]);
        $diameter->propertyValues()->createMany([
            ['title' => '16"', 'value' => 16],
            ['title' => '17"', 'value' => 17],
            ['title' => '18"', 'value' => 18],
        ]);
        $brand->propertyValues()->createMany([
            ['title' => 'Dezent', 'value' => 'dezent'],
            ['title' => 'Carmani', 'value' => 'carmani'],
            ['title' => 'Borbet', 'value' => 'borbet'],
            ['title' => 'ATS', 'value' => 'ats'],
        ]);

        $wheel->replacePropertyValuesByScalar(['finish' => 'glossy', 'diameter' => 16]);

        $this->assertEquals(16, $wheel->valueOfProperty('diameter')->getCastedValue());
        $this->assertEquals('glossy', $wheel->valueOfProperty('finish')->getCastedValue());
        $this->assertNull($wheel->valueOfProperty('brand'));

        $wheel->replacePropertyValuesByScalar(['finish' => 'matte', 'diameter' => 17]);
        $wheel->refresh();

        $this->assertEquals(17, $wheel->valueOfProperty('diameter')->getCastedValue());
        $this->assertEquals('matte', $wheel->valueOfProperty('finish')->getCastedValue());
        $this->assertNull($wheel->valueOfProperty('brand'));

        $wheel->replacePropertyValuesByScalar(['diameter' => 17, 'brand' => 'ats']);
        $wheel->refresh();

        $this->assertEquals(17, $wheel->valueOfProperty('diameter')->getCastedValue());
        $this->assertNull($wheel->valueOfProperty('finish'));
        $this->assertEquals('ats', $wheel->valueOfProperty('brand')->getCastedValue());

        $wheel->replacePropertyValuesByScalar(['diameter' => 18, 'brand' => 'ats', 'finish' => 'matte']);
        $wheel->refresh();

        $this->assertEquals(18, $wheel->valueOfProperty('diameter')->getCastedValue());
        $this->assertEquals('matte', $wheel->valueOfProperty('finish')->getCastedValue());
        $this->assertEquals('ats', $wheel->valueOfProperty('brand')->getCastedValue());

        $wheel->replacePropertyValuesByScalar([]);
        $wheel->refresh();

        $this->assertNull($wheel->valueOfProperty('finish'));
        $this->assertNull($wheel->valueOfProperty('brand'));
        $this->assertNull($wheel->valueOfProperty('diameter'));
    }

    /** @test */
    public function replace_by_scalar_will_create_new_values_if_necessary()
    {
        $tyre = Product::create(['name' => 'Tyre']);
        $origin = Property::create(['name' => 'Origin', 'slug' => 'origin', 'type' => 'text']);
        $origin->propertyValues()->createMany([
            ['title' => 'China', 'value' => 'china'],
            ['title' => 'India', 'value' => 'india'],
        ]);

        $tyre->replacePropertyValuesByScalar(['origin' => 'canada']);
        $this->assertEquals('canada', $tyre->fresh()->valueOfProperty('origin')?->getCastedValue());
    }

    /** @test */
    public function attempting_to_assign_values_with_inexistent_properties_throws_an_exception()
    {
        $this->expectException(UnknownPropertyException::class);

        $shelf = Product::create(['name' => 'Shelf']);
        $shelf->assignPropertyValues([
            'no-such-thing' => 'here',
        ]);
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
