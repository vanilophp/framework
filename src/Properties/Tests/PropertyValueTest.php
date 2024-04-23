<?php

declare(strict_types=1);

/**
 * Contains the PropertyValueTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Tests;

use Vanilo\Properties\Models\Property;
use Vanilo\Properties\Models\PropertyValue;

class PropertyValueTest extends TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $property = factory(Property::class)->create(['type' => 'integer']);

        $value1 = PropertyValue::create([
            'property_id' => $property->id,
            'value' => 1,
            'title' => '1'
        ]);

        $value2 = PropertyValue::create([
            'property_id' => $property->id,
            'value' => 2,
            'title' => '2'
        ]);

        $this->assertEquals('1', $value1->value);
        $this->assertEquals('2', $value2->value);
    }

    /** @test */
    public function get_value_method_returns_the_transformed_value_on_integer_fields()
    {
        $property = factory(Property::class)->create(['type' => 'integer']);

        $value3007 = PropertyValue::create([
            'property_id' => $property->id,
            'value' => 3007,
            'title' => '3007'
        ]);

        $this->assertEquals(3007, $value3007->getCastedValue());
        $this->assertIsInt($value3007->getCastedValue());
    }

    /** @test */
    public function get_value_method_returns_the_transformed_value_on_boolean_fields()
    {
        $property = factory(Property::class)->create(['type' => 'boolean']);

        $valueTrue = PropertyValue::create([
            'property_id' => $property->id,
            'value' => true,
            'title' => 'Yes'
        ]);

        $valueFalse = PropertyValue::create([
            'property_id' => $property->id,
            'value' => 'false',
            'title' => 'No'
        ]);

        $valueZero = PropertyValue::create([
            'property_id' => $property->id,
            'value' => '0',
            'title' => 'No'
        ]);

        $this->assertEquals(true, $valueTrue->getCastedValue());
        $this->assertIsBool($valueTrue->getCastedValue());

        $this->assertEquals(false, $valueFalse->getCastedValue());
        $this->assertIsBool($valueFalse->getCastedValue());

        $this->assertEquals(false, $valueZero->getCastedValue());
        $this->assertIsBool($valueZero->getCastedValue());
    }

    /** @test */
    public function get_value_method_returns_the_transformed_value_on_number_fields()
    {
        $property = factory(Property::class)->create(['type' => 'number']);

        $value11point27 = PropertyValue::create([
            'property_id' => $property->id,
            'value' => 11.27,
            'title' => '11.27'
        ]);

        $this->assertEquals(11.27, $value11point27->getCastedValue());
        $this->assertIsFloat($value11point27->getCastedValue());
    }

    /** @test */
    public function the_settings_field_is_an_array()
    {
        $property = factory(Property::class)->create();

        $valueX = PropertyValue::create([
            'property_id' => $property->id,
            'value' => 'x',
            'title' => 'X',
            'settings' => ['x' => 123, 'y' => 456]
        ]);

        $this->assertEquals(['x' => 123, 'y' => 456], $valueX->settings);
    }

    /** @test */
    public function it_can_be_retrieved_by_the_property_slug_and_value()
    {
        /** @var Property $color */
        $color = Property::create(['name' => 'Color', 'type' => 'text']);
        $color->propertyValues()->createMany([
            ['title' => 'Gold'],
            ['title' => 'Red'],
            ['title' => 'White'],
        ]);

        $goldColor = PropertyValue::findByPropertyAndValue('color', 'gold');
        $this->assertInstanceOf(PropertyValue::class, $goldColor);
        $this->assertEquals($color->id, $goldColor->property->id);
        $this->assertEquals('gold', $goldColor->value);
    }

    /** @test */
    public function it_can_be_retrieved_by_the_property_slug_and_value_when_the_value_is_int()
    {
        /** @var Property $doors */
        $doors = Property::create(['name' => 'doors', 'type' => 'integer']);
        $doors->propertyValues()->createMany([
            ['title' => 'Three', 'value' => 3],
            ['title' => 'Four', 'value' => 4],
            ['title' => 'Five', 'value' => 5],
        ]);

        $fourDoor = PropertyValue::findByPropertyAndValue('doors', 4);
        $this->assertInstanceOf(PropertyValue::class, $fourDoor);
        $this->assertEquals($doors->id, $fourDoor->property->id);
        $this->assertEquals(4, $fourDoor->value);
    }

    /** @test */
    public function it_can_be_retrieved_by_the_property_slug_and_value_when_the_value_is_boolean()
    {
        /** @var Property $sunroof */
        $sunroof = Property::create(['name' => 'sunroof', 'type' => 'boolean']);
        $sunroof->propertyValues()->createMany([
            ['title' => 'Yes', 'value' => true],
            ['title' => 'No', 'value' => false],
        ]);

        $withSunroof = PropertyValue::findByPropertyAndValue('sunroof', true);
        $this->assertInstanceOf(PropertyValue::class, $withSunroof);
        $this->assertEquals($sunroof->id, $withSunroof->property->id);
        $this->assertEquals(true, $withSunroof->value);
    }

    /** @test */
    public function the_property_and_value_finder_returns_null_when_attempting_to_locate_by_a_nonexistent_property_slug()
    {
        $this->assertNull(PropertyValue::findByPropertyAndValue('hey-i-am-so-stupid', 'gold'));
    }

    /** @test */
    public function multiple_entries_can_be_returned_by_scalar_key_value_pairs()
    {
        $shape = Property::create(['name' => 'Shape', 'type' => 'text']);
        $material = Property::create(['name' => 'Material', 'type' => 'text']);
        $shape->propertyValues()->createMany([
            ['title' => 'Heart', 'value' => 'heart'],
            ['title' => 'Sphere', 'value' => 'sphere'],
            ['title' => 'Cube', 'value' => 'cube'],
        ]);
        $material->propertyValues()->createMany([
            ['title' => 'Wood', 'value' => 'wood'],
            ['title' => 'Glass', 'value' => 'glass'],
            ['title' => 'Metal', 'value' => 'metal'],
            ['title' => 'Plastic', 'value' => 'plastic'],
        ]);

        $values = PropertyValue::getByScalarPropertiesAndValues([
            'shape' => 'heart',
            'material' => 'wood',
        ]);
        $this->assertCount(2, $values);
        $this->assertInstanceOf(PropertyValue::class, $values[0]);
        $this->assertInstanceOf(PropertyValue::class, $values[1]);
        $this->assertContains('shape', $values->map->property->map->slug);
        $this->assertContains('material', $values->map->property->map->slug);
        $this->assertContains('heart', $values->map->value);
        $this->assertContains('wood', $values->map->value);
    }

    /** @test */
    public function it_returns_an_empty_resultset_if_not_values_get_passed_to_get_by_scalar_key_value_pairs()
    {
        $season = Property::create(['name' => 'Season', 'type' => 'text']);
        $season->propertyValues()->createMany([
            ['title' => 'Winter'],
            ['title' => 'Summer'],
            ['title' => 'All Seasons'],
        ]);

        $values = PropertyValue::getByScalarPropertiesAndValues([]);
        $this->assertCount(0, $values);
    }
}
