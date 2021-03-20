<?php

declare(strict_types=1);
/**
 * Contains the PropertyTypeTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Properties\Tests;

use Vanilo\Properties\Exceptions\UnknownPropertyTypeException;
use Vanilo\Properties\Models\Property;
use Vanilo\Properties\PropertyTypes;
use Vanilo\Properties\Tests\Examples\MaterialPropertyType;
use Vanilo\Properties\Types\Boolean;
use Vanilo\Properties\Types\Integer;
use Vanilo\Properties\Types\Number;
use Vanilo\Properties\Types\Text;

class PropertyTypeTest extends TestCase
{
    /**
     * @test
     * @dataProvider builtInTypesProvider
     */
    public function built_in_types_are_available(string $typeName, string $expectedClass)
    {
        $property = Property::create([
            'name' => sprintf('Example %s property', ucfirst($typeName)),
            'type' => $typeName
        ]);

        $this->assertInstanceOf($expectedClass, $property->getType());
    }

    /** @test */
    public function new_types_can_be_registered()
    {
        PropertyTypes::register('material', MaterialPropertyType::class);

        $property = Property::create([
            'name' => 'Material',
            'type' => 'material'
        ]);

        $this->assertInstanceOf(MaterialPropertyType::class, $property->getType());
    }

    /** @test */
    public function attempting_to_retrieve_an_unregistered_property_type_throws_an_exception()
    {
        $property = Property::create([
            'name' => 'I am bad',
            'type' => 'i do not exist'
        ]);

        $this->expectException(UnknownPropertyTypeException::class);
        $property->getType();
    }

    /** @test */
    public function registering_a_property_type_without_implementing_the_interface_is_not_allowed()
    {
        $this->expectException(\InvalidArgumentException::class);
        PropertyTypes::register('whatever', \stdClass::class);
    }

    public function builtInTypesProvider()
    {
        return [
            ['text', Text::class],
            ['boolean', Boolean::class],
            ['integer', Integer::class],
            ['number', Number::class],
        ];
    }
}
