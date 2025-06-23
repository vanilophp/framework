<?php

declare(strict_types=1);

use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Foundation\Models\MasterProductVariant;
use Vanilo\Foundation\Models\Product;
use Vanilo\Foundation\Tests\TestCase;
use Vanilo\Shipment\Models\ShippingCategory;

class ShippingCategoryTest extends TestCase
{
    /** @test */
    public function the_product_model_does_not_belong_to_a_shipping_category_by_default()
    {
        $product = factory(Product::class)->create([]);

        $this->assertNull($product->shipping_category_id);
        $this->assertNull($product->shippingCategory);
        $this->assertNull($product->getShippingCategory());
    }

    /** @test */
    public function the_master_product_model_does_not_belong_to_a_shipping_category_by_default()
    {
        $masterProduct = factory(MasterProduct::class)->create([]);

        $this->assertNull($masterProduct->shipping_category_id);
        $this->assertNull($masterProduct->shippingCategory);
        $this->assertNull($masterProduct->getShippingCategory());
    }

    /** @test */
    public function the_variant_model__does_not_belong_to_a_shipping_category_by_default()
    {
        $variant = factory(MasterProductVariant::class)->create([]);

        $this->assertNull($variant->shipping_category_id);
        $this->assertNull($variant->shippingCategory);
        $this->assertNull($variant->getShippingCategory());
    }

    /** @test */
    public function the_product_model_can_belong_to_a_shipping_category()
    {
        $shippingCategory = ShippingCategory::create(['name' => 'Standard']);
        $product = factory(Product::class)->create([
            'shipping_category_id' => $shippingCategory->id,
        ]);

        $this->assertInstanceOf(ShippingCategory::class, $product->shippingCategory);
        $this->assertEquals($shippingCategory->id, $product->shipping_category_id);
        $this->assertEquals($shippingCategory->id, $product->shippingCategory->id);
        $this->assertEquals($shippingCategory->id, $product->getShippingCategory()->id);
    }

    /** @test */
    public function the_master_product_model_can_belong_to_a_shipping_category()
    {
        $shippingCategory = ShippingCategory::create(['name' => 'Standard']);
        $masterProduct = factory(MasterProduct::class)->create([
            'shipping_category_id' => $shippingCategory->id,
        ]);

        $this->assertInstanceOf(ShippingCategory::class, $masterProduct->shippingCategory);
        $this->assertEquals($shippingCategory->id, $masterProduct->shipping_category_id);
        $this->assertEquals($shippingCategory->id, $masterProduct->shippingCategory->id);
        $this->assertEquals($shippingCategory->id, $masterProduct->getShippingCategory()->id);
    }

    /** @test */
    public function the_variant_model_can_belong_to_a_shipping_category()
    {
        $shippingCategory = ShippingCategory::create(['name' => 'Standard']);
        $variant = factory(MasterProductVariant::class)->create([
            'shipping_category_id' => $shippingCategory->id,
        ]);

        $this->assertInstanceOf(ShippingCategory::class, $variant->shippingCategory);
        $this->assertEquals($shippingCategory->id, $variant->shipping_category_id);
        $this->assertEquals($shippingCategory->id, $variant->shippingCategory->id);
        $this->assertEquals($shippingCategory->id, $variant->getShippingCategory()->id);
    }

    /** @test */
    public function the_variant_model_shadows_the_master_products_shipping_category()
    {
        $masterShippingCategory = ShippingCategory::create(['name' => 'Master Shipping Category']);
        $variantShippingCategory = ShippingCategory::create(['name' => 'Variant Shipping Category']);
        $masterProduct = factory(MasterProduct::class)->create([
            'shipping_category_id' => $masterShippingCategory->id,
        ]);
        $variant = factory(MasterProductVariant::class)->create([
            'master_product_id' => $masterProduct->id,
            'shipping_category_id' => $variantShippingCategory->id,
        ]);

        $this->assertInstanceOf(ShippingCategory::class, $variant->shippingCategory);
        $this->assertTrue($variant->hasOwnShippingCategory());
        $this->assertEquals($variantShippingCategory->id, $variant->shipping_category_id);
        $this->assertEquals($variantShippingCategory->id, $variant->shippingCategory->id);
        $this->assertEquals($variantShippingCategory->id, $variant->getShippingCategory()->id);
    }

    /** @test */
    public function the_variant_model_uses_the_master_products_shipping_category_if_it_doest_specify_one_of_his_own()
    {
        $masterShippingCategory = ShippingCategory::create(['name' => 'Master Shipping Category']);
        $masterProduct = factory(MasterProduct::class)->create([
            'shipping_category_id' => $masterShippingCategory->id,
        ]);
        $variant = factory(MasterProductVariant::class)->create([
            'master_product_id' => $masterProduct->id,
        ]);

        $this->assertInstanceOf(ShippingCategory::class, $variant->shippingCategory);
        $this->assertFalse($variant->hasOwnShippingCategory());
        $this->assertEquals($masterShippingCategory->id, $variant->shipping_category_id);
        $this->assertEquals($masterShippingCategory->id, $variant->shippingCategory->id);
        $this->assertEquals($masterShippingCategory->id, $variant->getShippingCategory()->id);
    }

    /** @test */
    public function the_variant_model_has_no_shipping_category_if_it_has_none_of_his_own_and_master_has_none()
    {
        $masterProduct = factory(MasterProduct::class)->create([]);
        $variant = factory(MasterProductVariant::class)->create([
            'master_product_id' => $masterProduct->id,
        ]);

        $this->assertNull($variant->shipping_category_id);
        $this->assertFalse($variant->hasOwnShippingCategory());
        $this->assertNull($variant->shippingCategory);
        $this->assertNull($variant->getShippingCategory());
    }

    /** @test */
    public function the_variant_model_returns_the_masters_belongs_to_relationship_if_it_doesnt_specify_its_own_shipping_category()
    {
        $masterShippingCategory = ShippingCategory::create(['name' => 'Master Shipping Category']);
        $masterProduct = factory(MasterProduct::class)->create([
            'shipping_category_id' => $masterShippingCategory->id,
        ]);
        $variant = factory(MasterProductVariant::class)->create([
            'master_product_id' => $masterProduct->id,
        ]);

        $this->assertInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class, $variant->shippingCategory());
    }
}
