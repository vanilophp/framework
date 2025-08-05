<?php

declare(strict_types=1);

/**
 * Contains the CartTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-13
 *
 */

namespace Vanilo\Checkout\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Checkout\Facades\Checkout;
use Vanilo\Checkout\Tests\Example\Cart;
use Vanilo\Checkout\Tests\Example\Product;
use Vanilo\Contracts\CheckoutSubject;

class CartTest extends TestCase
{
    protected Product $productEarly2018;

    protected Product $productNormal2018;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productEarly2018 = new Product(1, 'Laracon EU 2018 Ticket Early Bird', 399);
        $this->productNormal2018 = new Product(2, 'Laracon EU 2018 Ticket', 599);
    }

    #[Test] public function an_arbitrary_object_implementing_checkoutsubject_can_be_set_as_cart()
    {
        $cart = new Cart();

        Checkout::setCart($cart);

        $this->assertInstanceOf(CheckoutSubject::class, Checkout::getCart());
    }

    #[Test] public function checkout_total_matches_cart_total()
    {
        $cart = new Cart();

        Checkout::setCart($cart);

        $cart->addItem($this->productEarly2018);
        $this->assertEquals(399, Checkout::total());

        $cart->addItem($this->productEarly2018);
        $this->assertEquals(798, Checkout::total());

        $cart->addItem($this->productEarly2018, 2);
        $this->assertEquals(1596, Checkout::total());

        $cart->addItem($this->productNormal2018);
        $this->assertEquals(2195, Checkout::total());
    }

    #[Test] public function checkout_can_return_products()
    {
        $cart = new Cart();

        $cart->addItem($this->productEarly2018);
        $cart->addItem($this->productNormal2018);

        Checkout::setCart($cart);

        $this->assertCount(2, Checkout::getCart()->getItems());
    }
}
