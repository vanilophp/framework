<?php
/**
 * Contains the OrderFactoryTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-30
 *
 */


namespace Vanilo\Order\Tests;


use Illuminate\Support\Facades\Event;
use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Order\Events\OrderWasCreated;
use Vanilo\Order\Exceptions\CreateOrderException;
use Vanilo\Order\Models\OrderStatusProxy;
use Vanilo\Order\Tests\Dummies\Product;

class OrderFactoryTest extends TestCase
{
    /** @var Product */
    protected $mazdaRX8;

    /** @var OrderFactoryContract */
    protected $factory;

    public function setUp()
    {
        parent::setUp();

        $this->mazdaRX8 = Product::create([
            'name'  => 'Mazda RX-8',
            'sku'   => 'SE3P',
            'price' => 10899
        ]);

        $this->factory = app(OrderFactoryContract::class);
    }

    /**
     * @test
     */
    public function order_can_be_created_from_simple_array_of_attributes()
    {
        $order = $this->factory->createFromDataArray(
            [
                'number' => 'QW3SAX'
            ],
            [
                [
                    'product_type' => 'product',
                    'product_id'   => $this->mazdaRX8->getId(),
                    'name'         => $this->mazdaRX8->getName(),
                    'quantity'     => 1,
                    'price'        => $this->mazdaRX8->getPrice()
                ]
            ]
        );

        $this->assertInstanceOf(Order::class, $order);
        $this->assertCount(1, $order->getItems());

        // Let's see if DB was properly hit
        $order = $order->fresh();

        $this->assertEquals('QW3SAX', $order->number);
        $this->assertEquals(OrderStatusProxy::defaultValue(), $order->status->value());

        // Testing the item
        $this->assertCount(1, $order->items);
        $item  = $order->items->first();
        $this->assertEquals($this->mazdaRX8->getName(), $item->name);
        $this->assertEquals($this->mazdaRX8->getPrice(), $item->price);
        $this->assertEquals(1, $item->quantity);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_trying_to_create_an_order_without_items()
    {
        $this->expectException(CreateOrderException::class);

        $this->factory->createFromDataArray([], []);
    }

    /**
     * @test
     */
    public function automatically_generates_an_order_number_if_none_was_passed()
    {
        $order = $this->factory->createFromDataArray([], [
            [
                'product_type' => 'product',
                'product_id'   => $this->mazdaRX8->getId(),
                'name'         => $this->mazdaRX8->getName(),
                'quantity'     => 1,
                'price'        => $this->mazdaRX8->getPrice()
            ]
        ]);

        $this->assertNotEmpty($order->getNumber());
    }

    /**
     * @test
     */
    public function order_was_created_event_gets_emitted_when_creating_an_order()
    {
        Event::fake();

        $order = $this->factory->createFromDataArray([], [
            [
                'product_type' => 'product',
                'product_id'   => $this->mazdaRX8->getId(),
                'name'         => $this->mazdaRX8->getName(),
                'quantity'     => 1,
                'price'        => $this->mazdaRX8->getPrice()
            ]
        ]);

        Event::assertDispatched(OrderWasCreated::class, function ($event) use ($order) {
            return $event->getOrder()->getNumber() === $order->getNumber();
        });

    }
}
