<?php

declare(strict_types=1);

/**
 * Contains the CreateVariantTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-06-23
 *
 */

namespace Vanilo\MasterProduct\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\MasterProduct\Tests\TestCase;

class CreateVariantTest extends TestCase
{
    #[Test] public function a_variant_can_be_created_without_property()
    {
        $pazolini = MasterProduct::create([
            'name' => 'Pazolini',
        ]);

        $size37 = $pazolini->createVariant([
            'properties' => ['shoe-size' => 37],
            'sku' => 'PZLBL-037',
            'stock' => 9
        ]);

        $this->assertEquals('PZLBL-037', $size37->sku);
        $this->assertEquals(9, $size37->stock);
    }

    #[Test] public function multiple_variants_can_be_created_without_a_property()
    {
        $pazolini = MasterProduct::create([
            'name' => 'Pazolini',
        ]);

        $pazolini->createVariant([
            'properties' => ['shoe-size' => 38],
            'sku' => 'PZLBL-038',
            'stock' => 3
        ]);

        $pazolini->createVariant([
            'properties' => ['shoe-size' => 39],
            'sku' => 'PZLBL-039',
            'stock' => 7
        ]);

        $this->assertCount(2, $pazolini->variants);
        $this->assertContains('PZLBL-038', $pazolini->variants->map->sku->all());
        $this->assertContains('PZLBL-039', $pazolini->variants->map->sku->all());
    }
}
