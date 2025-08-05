<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Checkout\Facades\Checkout;
use Vanilo\Foundation\Tests\Factories\ProductFactory;
use Vanilo\Foundation\Tests\Factories\ShippingCategoryFactory;
use Vanilo\Foundation\Tests\TestCase;

class ExtendedCartItemTest extends TestCase
{
    #[Test] public function it_uses_the_buyables_shipping_category_to_determine_whether_the_item_is_shippable()
    {
        $nonShippingCategory = ShippingCategoryFactory::new()->notShippable()->create();
        $product = ProductFactory::new()->create(['shipping_category_id' => $nonShippingCategory->id]);

        Cart::addItem($product);
        Checkout::setCart(Cart::model());

        $this->assertCount(0, Checkout::getShippableItems());

        $shippableCategory = ShippingCategoryFactory::new()->create();
        $product2 = ProductFactory::new()->create(['shipping_category_id' => $shippableCategory->id]);
        $product3 = ProductFactory::new()->create(['shipping_category_id' => $shippableCategory->id]);

        Cart::addItem($product2);
        Cart::addItem($product3);

        $this->assertCount(1, Checkout::get());
        $this->assertCount(2, Checkout::getShippableItems());
    }
}
