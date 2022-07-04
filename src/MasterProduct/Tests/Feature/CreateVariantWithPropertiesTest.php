<?php

declare(strict_types=1);

/**
 * Contains the CreateVariantWithPropertiesTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-06-23
 *
 */

namespace Vanilo\MasterProduct\Tests\Feature;

use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\MasterProduct\Models\MasterProductVariant;
use Vanilo\MasterProduct\Tests\TestCase;
use Vanilo\Properties\Models\Property;
use Vanilo\Properties\Models\PropertyValue;

class CreateVariantWithPropertiesTest extends TestCase
{
    /** @test */
    public function a_variant_can_be_created_based_on_one_property()
    {
        $pazolini = MasterProduct::create([
            'name' => 'Pazolini',
        ]);

        $shoeSize = Property::create(['name' => 'Shoe Size', 'type' => 'integer']);
        PropertyValue::create([
            'title' => '37',
            'value' => 37,
            'property_id' => $shoeSize->id,
        ]);

        /** @var MasterProductVariant $size37 */
        $pazoliniOfSize37 = $pazolini->createVariant([
            'properties' => ['shoe-size' => 37],
            'sku' => 'PZLBL-037',
            'stock' => 9
        ]);

        $this->assertEquals('PZLBL-037', $pazoliniOfSize37->sku);
        $this->assertEquals(9, $pazoliniOfSize37->stock);
        //$this->assertEquals($pazoliniOfSize37->valueOfProperty());
    }
}
