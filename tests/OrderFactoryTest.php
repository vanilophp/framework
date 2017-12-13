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
use Vanilo\Address\Models\Address;
use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Order\Events\OrderWasCreated;
use Vanilo\Order\Exceptions\CreateOrderException;
use Vanilo\Order\Models\Billpayer;
use Vanilo\Order\Models\OrderStatusProxy;
use Vanilo\Order\Tests\Dummies\Product;

class OrderFactoryTest extends TestCase
{
    /** @var Product */
    protected $mazdaRX8;

    /** @var Product */
    protected $volvoV90;

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

        $this->volvoV90 = Product::create([
            'name'  => 'Volvo V90',
            'sku'   => 'B4204T20',
            'price' => 59600
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

    /**
     * @test
     */
    public function item_quantity_is_1_by_default_if_none_gets_passed()
    {
        $order = $this->factory->createFromDataArray([],
            [
                [
                    'product_type' => 'product',
                    'product_id'   => $this->mazdaRX8->getId(),
                    'name'         => $this->mazdaRX8->getName(),
                    'price'        => $this->mazdaRX8->getPrice()
                ]
            ]
        );

        $item = $order->getItems()->first();
        $this->assertEquals(1, $item->quantity);

        // Let's see if DB was properly hit
        $order = $order->fresh();

        $item = $order->getItems()->first();
        $this->assertEquals(1, $item->quantity);
    }

    /**
     * @test
     */
    public function item_quantity_does_not_get_altered_if_a_value_was_passed()
    {
        $order = $this->factory->createFromDataArray([],
            [
                [
                    'product'  => $this->mazdaRX8,
                    'quantity' => 3
                ]
            ]
        );

        $item = $order->getItems()->first();
        $this->assertEquals(3, $item->quantity);

        // Let's see if DB was properly hit
        $order = $order->fresh();

        $item = $order->getItems()->first();
        $this->assertEquals(3, $item->quantity);
    }

    /**
     * @test
     */
    public function item_can_be_created_from_a_buyable()
    {
        $order = $this->factory->createFromDataArray([], [
            [
                'product'  => $this->mazdaRX8
            ]
        ]);

        $item = $order->getItems()->first();
        $this->assertEquals(1, $item->quantity);
        $this->assertEquals($this->mazdaRX8->getName(), $item->name);
        $this->assertEquals($this->mazdaRX8->getPrice(), $item->price);
        $this->assertEquals($this->mazdaRX8->getId(), $item->product_id);
        $this->assertEquals($this->mazdaRX8->morphTypeName(), $item->product_type);

        // Let's see if DB was properly hit
        $order = $order->fresh();

        $item = $order->getItems()->first();
        $this->assertEquals(1, $item->quantity);
        $this->assertEquals($this->mazdaRX8->getName(), $item->name);
        $this->assertEquals($this->mazdaRX8->getPrice(), $item->price);
        $this->assertEquals($this->mazdaRX8->getId(), $item->product_id);
        $this->assertEquals($this->mazdaRX8->morphTypeName(), $item->product_type);
    }

    /**
     * @test
     */
    public function items_can_be_passed_in_a_mixed_manner_so_that_one_is_a_buyable_another_is_simple_attributes()
    {
        $order = $this->factory->createFromDataArray([], [
            [
                'product_type' => 'product',
                'product_id'   => $this->mazdaRX8->getId(),
                'name'         => $this->mazdaRX8->getName(),
                'price'        => $this->mazdaRX8->getPrice()
            ],
            [
                'product'  => $this->volvoV90
            ],

        ]);

        $this->assertCount(2, $order->getItems());

        $mazda = $order->getItems()->first();
        $this->assertEquals(1, $mazda->quantity);
        $this->assertEquals($this->mazdaRX8->getName(), $mazda->name);
        $this->assertEquals($this->mazdaRX8->getPrice(), $mazda->price);
        $this->assertEquals($this->mazdaRX8->getId(), $mazda->product_id);
        $this->assertEquals($this->mazdaRX8->morphTypeName(), $mazda->product_type);

        $volvo = $order->getItems()->last();
        $this->assertEquals(1, $volvo->quantity);
        $this->assertEquals($this->volvoV90->getName(), $volvo->name);
        $this->assertEquals($this->volvoV90->getPrice(), $volvo->price);
        $this->assertEquals($this->volvoV90->getId(), $volvo->product_id);
        $this->assertEquals($this->volvoV90->morphTypeName(), $volvo->product_type);

        // Let's see if DB was properly hit
        $order = $order->fresh();

        $this->assertCount(2, $order->getItems());

        $mazda = $order->getItems()->first();
        $this->assertEquals(1, $mazda->quantity);
        $this->assertEquals($this->mazdaRX8->getName(), $mazda->name);
        $this->assertEquals($this->mazdaRX8->getPrice(), $mazda->price);
        $this->assertEquals($this->mazdaRX8->getId(), $mazda->product_id);
        $this->assertEquals($this->mazdaRX8->morphTypeName(), $mazda->product_type);

        $volvo = $order->getItems()->last();
        $this->assertEquals(1, $volvo->quantity);
        $this->assertEquals($this->volvoV90->getName(), $volvo->name);
        $this->assertEquals($this->volvoV90->getPrice(), $volvo->price);
        $this->assertEquals($this->volvoV90->getId(), $volvo->product_id);
        $this->assertEquals($this->volvoV90->morphTypeName(), $volvo->product_type);
    }

    /**
     * @test
     */
    public function separate_billing_address_entry_gets_created_for_the_order()
    {
        $order = $this->factory->createFromDataArray(
        [
            'billpayer' => [
                'address' => [
                    'name'       => 'Johnny Bravo',
                    'country_id' => 'US',
                    'city'       => 'Aron City',
                    'address'    => '12 Sandy Baker Street'
                ]
            ]
        ],
        [
            [
                'product'  => $this->volvoV90
            ],
        ]);

        $this->assertNotEmpty($order->billpayer_id);
    }
}
