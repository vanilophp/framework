<?php
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


use Vanilo\Checkout\Facades\Checkout;
use Vanilo\Checkout\Tests\Mocks\Cart;
use Vanilo\Checkout\Tests\Mocks\Product;
use Vanilo\Contracts\CheckoutSubject;

class CartTest extends TestCase
{
    /** @var  Product */
    protected $productEarly2018;

    /** @var  Product */
    protected $productNormal2018;

    /**
     * @test
     */
    public function an_arbitrary_object_implementing_checkoutsubject_can_be_set_as_cart()
    {
        $cart = new Cart();

        Checkout::setCart($cart);

        $this->assertInstanceOf(CheckoutSubject::class, Checkout::getCart());
    }

    /**
     * @test
     */
    public function checkout_total_matches_cart_total()
    {
        $checkout = new Checkout();
        $cart     = new Cart();

        $checkout->setCart($cart);

        $cart->addItem($this->productEarly2018);
        $this->assertEquals(399, $checkout->total());

        $cart->addItem($this->productEarly2018);
        $this->assertEquals(798, $checkout->total());

        $cart->addItem($this->productEarly2018, 2);
        $this->assertEquals(1596, $checkout->total());

        $cart->addItem($this->productNormal2018);
        $this->assertEquals(2195, $checkout->total());
    }

    /**
     * @test
     */
    public function checkout_can_return_products()
    {
        $checkout = new Checkout();
        $cart     = new Cart();

        $cart->addItem($this->productEarly2018);
        $cart->addItem($this->productNormal2018);

        $checkout->setCart($cart);

        $this->assertCount(2, $checkout->getCart()->getItems());
    }

    public function setUp()
    {
        parent::setUp();

        $this->productEarly2018  = new Product(1, 'Laracon EU 2018 Ticket Early Bird', 399);
        $this->productNormal2018 = new Product(2, 'Laracon EU 2018 Ticket', 599);
    }
}
