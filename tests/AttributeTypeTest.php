<?php
/**
 * Contains the AttributeTypeTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-12-08
 *
 */

namespace Vanilo\Attributes\Tests;

use Vanilo\Attributes\AttributeTypes;
use Vanilo\Attributes\Exceptions\UnknownAttributeTypeException;
use Vanilo\Attributes\Models\Attribute;
use Vanilo\Attributes\Tests\Examples\MaterialAttributeType;
use Vanilo\Attributes\Types\Boolean;
use Vanilo\Attributes\Types\Integer;
use Vanilo\Attributes\Types\Number;
use Vanilo\Attributes\Types\Text;

class AttributeTypeTest extends TestCase
{
    /**
     * @test
     * @dataProvider builtInTypesProvider
     */
    public function built_in_types_are_available(string $typeName, string $expectedClass)
    {
        $attribute = Attribute::create([
            'name' => sprintf('Example %s attribute', ucfirst($typeName)),
            'type' => $typeName
        ]);

        $this->assertInstanceOf($expectedClass, $attribute->getType());
    }

    /** @test */
    public function new_types_can_be_registered()
    {
        AttributeTypes::register('material', MaterialAttributeType::class);

        $attribute = Attribute::create([
            'name' => 'Material',
            'type' => 'material'
        ]);

        $this->assertInstanceOf(MaterialAttributeType::class, $attribute->getType());
    }

    /** @test */
    public function attempting_to_retrieve_an_unregistered_attribute_type_throws_an_exception()
    {
        $attribute = Attribute::create([
            'name' => 'I am bad',
            'type' => 'i do not exist'
        ]);

        $this->expectException(UnknownAttributeTypeException::class);
        $attribute->getType();
    }

    /** @test */
    public function registering_an_attribute_type_without_implementing_the_attribute_type_interface_is_not_allowed()
    {
        $this->expectException(\InvalidArgumentException::class);
        AttributeTypes::register('whatever', \stdClass::class);
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
