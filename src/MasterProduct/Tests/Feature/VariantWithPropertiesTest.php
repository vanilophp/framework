<?php

declare(strict_types=1);

/**
 * Contains the VariantWithPropertiesTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-11-17
 *
 */

namespace Vanilo\MasterProduct\Tests\Feature;

use Vanilo\MasterProduct\Contracts\MasterProductVariant as MasterProductVariantContract;
use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\MasterProduct\Tests\Examples\VariantWithProperties;
use Vanilo\MasterProduct\Tests\TestCase;
use Vanilo\Properties\Models\Property;

class VariantWithPropertiesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->get('concord')->registerModel(
            MasterProductVariantContract::class,
            VariantWithProperties::class,
        );
    }

    #[Test] public function a_variant_can_be_created_with_properties()
    {
        $officeDesk = MasterProduct::create([
            'name' => 'Office Desk',
        ]);

        Property::create(['name' => 'Color', 'type' => 'text']);

        /** @var VariantWithProperties $white */
        $white = $officeDesk->createVariant([
            'properties' => ['color' => 'white'],
            'sku' => 'OFD-CLW',
            'stock' => 2
        ])->fresh();

        /** @var VariantWithProperties $white */
        $blue = $officeDesk->createVariant([
            'properties' => ['color' => 'blue'],
            'sku' => 'OFD-CLB',
            'stock' => 3
        ]);

        $this->assertEquals('OFD-CLW', $white->sku);
        $this->assertEquals(2, $white->stock);
        $this->assertEquals('white', $white->valueOfProperty('color')->getCastedValue());

        $this->assertEquals('OFD-CLB', $blue->sku);
        $this->assertEquals(3, $blue->stock);
        $this->assertEquals('blue', $blue->valueOfProperty('color')->getCastedValue());
    }
}
