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
use Vanilo\Product\Models\ProductState;

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

    /** @test */
    public function a_variant_has_a_description()
    {
        $master = MasterProduct::create([
            'name' => 'PUMA Men Super Liga OG Retro',
        ]);
        $variant = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'B079M6TMTJ0',
            'description' => 'Variants have a description',
        ]);

        $this->assertEquals('Variants have a description', $variant->description);
    }

    /** @test */
    public function a_variant_also_has_a_state()
    {
        $master = MasterProduct::create([
            'name' => 'PUMA Men Super Liga OG Retro',
        ]);
        $variant = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'B079M6TMTJ0',
            'state' => ProductState::ACTIVE,
        ]);

        $this->assertEquals(ProductState::ACTIVE(), $variant->state);
    }

    /** @test */
    public function a_variant_does_not_have_ext_title()
    {
        $master = MasterProduct::create([
            'name' => 'PUMA Men Super Liga OG Retro',
        ]);
        $variant = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'B079M6TMTJ0',
            'ext_title' => 'PUMA Men Super Liga OG Retro Green-White 42.5 Real Leather',
        ]);

        $this->assertNull($variant->ext_title);
    }

    /** @test */
    public function a_variant_does_not_have_meta_keywords()
    {
        $master = MasterProduct::create([
            'name' => 'PUMA Men Super Liga OG Retro',
        ]);
        $variant = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'B079M6TMTJ0',
            'meta_keywords' => 'puma, men, leather',
        ]);

        $this->assertNull($variant->meta_keywords);
    }

    /** @test */
    public function a_variant_does_not_have_meta_description()
    {
        $master = MasterProduct::create([
            'name' => 'PUMA Men Super Liga OG Retro',
        ]);
        $variant = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'B079M6TMTJ0',
            'meta_description' => 'Buy PUMA Men&#39;s Super Liga OG Retro Lace-Up Fashion Sneaker and other Fashion Sneakers.',
        ]);

        $this->assertNull($variant->meta_description);
    }
}
