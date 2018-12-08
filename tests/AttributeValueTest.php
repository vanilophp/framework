<?php
/**
 * Contains the AttributeValueTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Attributes\Tests;

use Vanilo\Attributes\Models\Attribute;
use Vanilo\Attributes\Models\AttributeValue;

class AttributeValueTest extends TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $attribute = Attribute::create(['name' => 'A', 'type' => 'integer']);

        $value1 = AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value'        => 1,
            'title'        => '1'
        ]);

        $value2 = AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value'        => 2,
            'title'        => '2'
        ]);

        $this->assertEquals('1', $value1->value);
        $this->assertEquals('2', $value2->value);
    }

    /** @test */
    public function get_value_method_returns_the_transformed_value_on_integer_fields()
    {
        $attribute = Attribute::create(['name' => 'B', 'type' => 'integer']);

        $value3007 = AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value'        => 3007,
            'title'        => '3007'
        ]);

        $this->assertEquals(3007, $value3007->getValue());
        $this->assertInternalType('int', $value3007->getValue());
    }

    /** @test */
    public function get_value_method_returns_the_transformed_value_on_boolean_fields()
    {
        $attribute = Attribute::create(['name' => 'C', 'type' => 'boolean']);

        $valueTrue = AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value'        => true,
            'title'        => 'Yes'
        ]);

        $valueFalse = AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value'        => false,
            'title'        => 'No'
        ]);

        $valueZero = AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value'        => 0,
            'title'        => 'No'
        ]);

        $this->assertEquals(true, $valueTrue->getValue());
        $this->assertInternalType('boolean', $valueTrue->getValue());

        $this->assertEquals(false, $valueFalse->getValue());
        $this->assertInternalType('boolean', $valueFalse->getValue());

        $this->assertEquals(false, $valueZero->getValue());
        $this->assertInternalType('boolean', $valueZero->getValue());
    }

    /** @test */
    public function get_value_method_returns_the_transformed_value_on_number_fields()
    {
        $attribute = Attribute::create(['name' => 'D', 'type' => 'number']);

        $value11point27 = AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value'        => 11.27,
            'title'        => '11.27'
        ]);

        $this->assertEquals(11.27, $value11point27->getValue());
        $this->assertInternalType('double', $value11point27->getValue());
    }

    /** @test */
    public function the_settings_field_is_an_array()
    {
        $attribute = Attribute::create(['name' => 'E', 'type' => 'text']);

        $valueX = AttributeValue::create([
            'attribute_id' => $attribute->id,
            'value'        => 'x',
            'title'        => 'X',
            'settings'     => ['x' => 123, 'y' => 456]
        ]);

        $this->assertEquals(['x' => 123, 'y' => 456], $valueX->settings);
    }
}
