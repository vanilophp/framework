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

namespace Vanilo\Foundation\Tests;

use Vanilo\Foundation\Models\MasterProductVariant;
use Vanilo\MasterProduct\Models\MasterProduct;
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

        /** @var MasterProductVariant $pazoliniOfSize37 */
        $pazoliniOfSize37 = $pazolini->createVariant([
            'properties' => ['shoe-size' => 37],
            'sku' => 'PZLBL-037',
            'stock' => 9
        ]);

        $this->assertEquals('PZLBL-037', $pazoliniOfSize37->sku);
        $this->assertEquals(9, $pazoliniOfSize37->stock);
        $this->assertEquals(37, $pazoliniOfSize37->valueOfProperty('shoe-size')->getCastedValue());
    }

    public function variants_differ_based_on_one_property()
    {
        $skechers = MasterProduct::create([
            'name' => 'Skechers',
        ]);

        $shoeSize = Property::create(['name' => 'Shoe Size', 'type' => 'integer']);
        PropertyValue::create([
            'title' => '37',
            'value' => 37,
            'property_id' => $shoeSize->id,
        ]);
        PropertyValue::create([
            'title' => '38',
            'value' => 38,
            'property_id' => $shoeSize->id,
        ]);

        /** @var MasterProductVariant $skechers37 */
        $skechers37 = $skechers->createVariant([
            'properties' => ['shoe-size' => 37],
            'sku' => 'SK-037',
            'stock' => 7,
        ]);
        /** @var MasterProductVariant $skechers38 */
        $skechers38 = $skechers->createVariant([
            'properties' => ['shoe-size' => 38],
            'sku' => 'SK-038',
            'stock' => 8,
        ]);

        $this->assertEquals('SK-037', $skechers37->fresh()->sku);
        $this->assertEquals(7, $skechers37->fresh()->stock);
        $this->assertEquals(37, $skechers37->fresh()->valueOfProperty('shoe-size')->getCastedValue());

        $this->assertEquals('SK-038', $skechers38->fresh()->sku);
        $this->assertEquals(8, $skechers38->fresh()->stock);
        $this->assertEquals(38, $skechers38->fresh()->valueOfProperty('shoe-size')->getCastedValue());
    }
}
