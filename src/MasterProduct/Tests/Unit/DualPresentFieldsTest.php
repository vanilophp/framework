<?php

declare(strict_types=1);

/**
 * Contains the DualPresentFieldsTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-06-16
 *
 */

namespace Vanilo\MasterProduct\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\MasterProduct\Models\MasterProductVariant;
use Vanilo\MasterProduct\Tests\TestCase;
use Vanilo\Product\Models\ProductState;

class DualPresentFieldsTest extends TestCase
{
    #[Test] public function the_name_of_the_master_is_used_on_the_variant_if_the_variant_name_is_null()
    {
        $master = MasterProduct::create([
            'name' => 'Makita Cordless Driver Drill',
        ]);
        $variant1 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'DDF485',
        ]);
        $variant2 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'DDF485RTJ',
        ]);

        $this->assertFalse($variant1->hasOwnName());
        $this->assertEquals('Makita Cordless Driver Drill', $variant1->name);

        $this->assertFalse($variant2->hasOwnName());
        $this->assertEquals('Makita Cordless Driver Drill', $variant2->name);
    }

    #[Test] public function own_name_is_used_on_the_variant_if_the_variant_has_its_own_name()
    {
        $master = MasterProduct::create([
            'name' => 'Makita Akku-Bohrschrauber LXT DDF485',
        ]);
        $ddf485 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'DDF485',
        ]);
        $ddf485rtj = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'name' => 'Makita Akku-Bohrschrauber LXT DDF485RTJ',
            'sku' => 'DDF485RTJ',
        ]);
        $ddf485z = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'name' => 'Makita Akku-Bohrschrauber LXT DDF485Z',
            'sku' => 'DDF485Z',
        ]);

        $this->assertFalse($ddf485->hasOwnName());
        $this->assertEquals('Makita Akku-Bohrschrauber LXT DDF485', $ddf485->name);

        $this->assertTrue($ddf485rtj->hasOwnName());
        $this->assertEquals('Makita Akku-Bohrschrauber LXT DDF485RTJ', $ddf485rtj->name);

        $this->assertTrue($ddf485z->hasOwnName());
        $this->assertEquals('Makita Akku-Bohrschrauber LXT DDF485Z', $ddf485z->name);
    }

    #[Test] public function the_price_of_the_master_is_used_on_the_variant_if_the_variant_price_is_null()
    {
        $master = MasterProduct::create([
            'name' => 'Edinburgh Raspberry Gin',
            'price' => 22.79,
        ]);
        $variant1 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin1',
        ]);
        $variant2 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin2',
        ]);

        $this->assertFalse($variant1->hasOwnPrice());
        $this->assertEquals(22.79, $variant1->price);

        $this->assertFalse($variant2->hasOwnPrice());
        $this->assertEquals(22.79, $variant2->price);
    }

    #[Test] public function own_price_is_used_on_the_variant_if_the_variant_has_its_own_price()
    {
        $master = MasterProduct::create([
            'name' => 'Edinburgh Raspberry Gin',
        ]);
        $gin02 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin02',
            'price' => 9.99,
        ]);
        $gin05 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin05',
            'price' => 19.99,
        ]);
        $gin07 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin07',
            'price' => 28.99,
        ]);

        $this->assertTrue($gin02->hasOwnPrice());
        $this->assertEquals(9.99, $gin02->price);

        $this->assertTrue($gin05->hasOwnPrice());
        $this->assertEquals(19.99, $gin05->price);

        $this->assertTrue($gin07->hasOwnPrice());
        $this->assertEquals(28.99, $gin07->price);
    }

    #[Test] public function the_original_price_of_the_master_is_used_on_the_variant_if_the_variant_original_price_is_null()
    {
        $master = MasterProduct::create([
            'name' => 'Edinburgh Raspberry Gin',
            'original_price' => 22.79,
        ]);
        $variant1 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin1',
        ]);
        $variant2 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin2',
        ]);

        $this->assertFalse($variant1->hasOwnOriginalPrice());
        $this->assertEquals(22.79, $variant1->original_price);

        $this->assertFalse($variant2->hasOwnOriginalPrice());
        $this->assertEquals(22.79, $variant2->original_price);
    }

    #[Test] public function own_original_price_is_used_on_the_variant_if_the_variant_has_its_own_original_price()
    {
        $master = MasterProduct::create([
            'name' => 'Edinburgh Raspberry Gin',
        ]);
        $gin02 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin02',
            'original_price' => 9.99,
        ]);
        $gin05 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin05',
            'original_price' => 19.99,
        ]);
        $gin07 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin07',
            'original_price' => 28.99,
        ]);

        $this->assertTrue($gin02->hasOwnOriginalPrice());
        $this->assertEquals(9.99, $gin02->original_price);

        $this->assertTrue($gin05->hasOwnOriginalPrice());
        $this->assertEquals(19.99, $gin05->original_price);

        $this->assertTrue($gin07->hasOwnOriginalPrice());
        $this->assertEquals(28.99, $gin07->original_price);
    }

    #[Test] public function the_excerpt_of_the_master_is_used_on_the_variant_if_the_variant_excerpt_is_null()
    {
        $master = MasterProduct::create([
            'name' => 'Edinburgh Raspberry Gin',
            'excerpt' => 'Create the sweetest cocktails with this Gin.',
        ]);
        $variant1 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin1',
        ]);
        $variant2 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin2',
        ]);

        $this->assertFalse($variant1->hasOwnExcerpt());
        $this->assertEquals('Create the sweetest cocktails with this Gin.', $variant1->excerpt);

        $this->assertFalse($variant2->hasOwnExcerpt());
        $this->assertEquals('Create the sweetest cocktails with this Gin.', $variant2->excerpt);
    }

    #[Test] public function own_excerpt_is_used_on_the_variant_if_the_variant_has_its_own_excerpt()
    {
        $master = MasterProduct::create([
            'name' => 'Edinburgh Raspberry Gin',
            'excerpt' => 'Create the sweetest cocktails with this Gin.',
        ]);
        $gin02 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin02',
            'excerpt' => 'Take this in your packet and let the festival start!',
        ]);
        $gin05 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin05',
        ]);

        $this->assertTrue($gin02->hasOwnExcerpt());
        $this->assertEquals('Take this in your packet and let the festival start!', $gin02->excerpt);

        $this->assertFalse($gin05->hasOwnExcerpt());
        $this->assertEquals('Create the sweetest cocktails with this Gin.', $gin05->excerpt);
    }

    #[Test] public function the_subtitle_of_the_master_is_used_on_the_variant_if_the_variant_subtitle_is_null()
    {
        $master = MasterProduct::create([
            'name' => 'C30 Unflavored Energy Gel',
            'subtitle' => 'The gold standard',
        ]);
        $variant1 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'unfl1',
        ]);
        $variant2 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'unfl2',
        ]);

        $this->assertFalse($variant1->hasOwnSubtitle());
        $this->assertEquals('The gold standard', $variant1->subtitle);

        $this->assertFalse($variant2->hasOwnSubtitle());
        $this->assertEquals('The gold standard', $variant2->subtitle);
    }

    #[Test] public function own_subtitle_is_used_on_the_variant_if_the_variant_has_its_own_subtitle()
    {
        $master = MasterProduct::create([
            'name' => 'C30 Unflavored Energy Gel',
            'subtitle' => 'The gold standard',
        ]);
        $unfl1 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'unflv01',
            'subtitle' => 'The white gold standard',
        ]);
        $unfl2 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'unflv02',
        ]);
        $unfl3 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'unflv03',
            'subtitle' => 'The double standard',
        ]);

        $this->assertTrue($unfl1->hasOwnSubtitle());
        $this->assertEquals('The white gold standard', $unfl1->subtitle);

        $this->assertFalse($unfl2->hasOwnSubtitle());
        $this->assertEquals('The gold standard', $unfl2->subtitle);

        $this->assertTrue($unfl3->hasOwnSubtitle());
        $this->assertEquals('The double standard', $unfl3->subtitle);
    }

    #[Test] public function the_state_of_the_master_is_used_on_the_variant_if_the_variant_state_is_null()
    {
        $master = MasterProduct::create([
            'name' => 'Edinburgh Raspberry Gin',
            'state' => ProductState::RETIRED,
        ]);
        $variant1 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'XXS1',
        ]);
        $variant2 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'XXS2',
        ]);

        $this->assertFalse($variant1->hasOwnState());
        $this->assertInstanceOf(ProductState::class, $variant1->state);
        ;
        $this->assertTrue($variant1->state->equals(ProductState::RETIRED()));

        $this->assertFalse($variant2->hasOwnState());
        $this->assertInstanceOf(ProductState::class, $variant2->state);
        ;
        $this->assertTrue($variant2->state->equals(ProductState::RETIRED()));
    }

    #[Test] public function own_state_is_used_on_the_variant_if_the_variant_has_its_own_state()
    {
        $master = MasterProduct::create([
            'name' => 'Edinburgh Raspberry Gin',
            'state' => ProductState::UNLISTED,
        ]);
        $varRetired = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'VRS1',
            'state' => ProductState::RETIRED,
        ]);
        $varActive = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'VAS2',
            'state' => ProductState::ACTIVE(),
        ]);
        $varAgnostic = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'VAG3',
        ]);

        $this->assertTrue($varRetired->hasOwnState());
        $this->assertInstanceOf(ProductState::class, $varRetired->state);
        ;
        $this->assertTrue($varRetired->state->equals(ProductState::RETIRED()));

        $this->assertTrue($varActive->hasOwnState());
        $this->assertInstanceOf(ProductState::class, $varActive->state);
        ;
        $this->assertTrue($varActive->state->equals(ProductState::ACTIVE()));

        $this->assertFalse($varAgnostic->hasOwnState());
        $this->assertInstanceOf(ProductState::class, $varAgnostic->state);
        ;
        $this->assertTrue($varAgnostic->state->equals(ProductState::UNLISTED()));
    }

    #[Test] public function the_dimensions_of_the_master_is_used_on_the_variant_if_the_variant_dimensions_are_null()
    {
        $master = MasterProduct::create([
            'name' => 'Edinburgh Raspberry Gin',
            'height' => 11.35,
            'width' => 19,
            'length' => 25.80,
            'weight' => 2,
        ]);
        $variant = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin1',
        ]);

        $this->assertFalse($variant->hasOwnHeight());
        $this->assertEquals(11.35, $variant->height);
        $this->assertFalse($variant->hasOwnWidth());
        $this->assertEquals(19, $variant->width);
        $this->assertFalse($variant->hasOwnLength());
        $this->assertEquals(25.80, $variant->length);
        $this->assertFalse($variant->hasOwnWeight());
        $this->assertEquals(2, $variant->weight);
    }

    #[Test] public function own_dimensions_are_used_on_the_variant_if_the_variant_has_its_own_dimensions()
    {
        $master = MasterProduct::create([
            'name' => 'Edinburgh Raspberry Gin',
        ]);
        $gin02 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin02',
            'height' => 11,
            'width' => 22,
            'length' => 33,
            'weight' => 2,
        ]);
        $gin05 = MasterProductVariant::create([
            'master_product_id' => $master->id,
            'sku' => 'gin05',
            'height' => 15,
            'width' => 25,
            'length' => 35,
            'weight' => 3,
        ]);

        $this->assertTrue($gin02->hasOwnHeight());
        $this->assertEquals(11, $gin02->height);
        $this->assertTrue($gin02->hasOwnWidth());
        $this->assertEquals(22, $gin02->width);
        $this->assertTrue($gin02->hasOwnLength());
        $this->assertEquals(33, $gin02->length);
        $this->assertTrue($gin02->hasOwnWeight());
        $this->assertEquals(2, $gin02->weight);

        $this->assertTrue($gin05->hasOwnHeight());
        $this->assertEquals(15, $gin05->height);
        $this->assertTrue($gin05->hasOwnWidth());
        $this->assertEquals(25, $gin05->width);
        $this->assertTrue($gin05->hasOwnLength());
        $this->assertEquals(35, $gin05->length);
        $this->assertTrue($gin05->hasOwnWeight());
        $this->assertEquals(3, $gin05->weight);
    }
}
