<?php

declare(strict_types=1);

/**
 * Contains the MasterProductVariantTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-06-13
 *
 */

namespace Vanilo\MasterProduct\Tests\Unit;

use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\MasterProduct\Models\MasterProductVariant;
use Vanilo\MasterProduct\Tests\TestCase;

class MasterProductVariantTest extends TestCase
{
    /** @test */
    public function a_variant_can_be_created_with_minimal_data()
    {
        $master = MasterProduct::create([
            'name' => 'PUMA Men Super Liga OG Retro',
        ]);
        $variant = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'B079M6TMTJ0',
        ]);

        $this->assertNotNull($variant->id);
        $this->assertEquals('B079M6TMTJ0', $variant->sku);
    }

    /** @test */
    public function a_variant_does_not_have_a_slug()
    {
        $master = MasterProduct::create([
            'name' => 'PUMA Men Super Liga OG Retro',
        ]);
        $variant = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'B079M6TMTJ0',
            'slug' => 'enforced-slug'
        ]);

        $this->assertNull($variant->slug);
    }
}
