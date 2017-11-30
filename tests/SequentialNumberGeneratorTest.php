<?php
/**
 * Contains the SequentialNumberGeneratorTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-30
 *
 */


namespace Vanilo\Order\Tests;


use Vanilo\Order\Factories\OrderFactory;
use Vanilo\Order\Generators\SequentialNumberGenerator;

class SequentialNumberGeneratorTest extends TestCase
{
    protected $item;

    public function setUp()
    {
        parent::setUp();

        $this->item = [
            'product_type' => 'product',
            'product_id'   => 1,
            'price'        => 129,
            'quantity'     => 1
        ];
    }

    /**
     * @test
     */
    public function it_returns_1_with_default_ie_no_configuration()
    {
        $seq = new SequentialNumberGenerator();

        $this->assertEquals('1', $seq->generateNumber());
    }

    /**
     * @test
     */
    public function the_first_order_generated_with_has_number_1()
    {
        $factory = new OrderFactory(new SequentialNumberGenerator());

        $order = $factory->createFromDataArray([], [$this->item]);

        $this->assertEquals('1', $order->getNumber());
    }

    /**
     * @test
     */
    public function the_second_order_generated_with_has_number_2()
    {
        $factory = new OrderFactory(new SequentialNumberGenerator());

        $firstOrder  = $factory->createFromDataArray([], [$this->item]);
        $secondOrder = $factory->createFromDataArray([], [$this->item]);

        $this->assertEquals('2', $secondOrder->getNumber());
    }

    /**
     * @test
     */
    public function orders_get_consecutive_numbers()
    {
        $factory = new OrderFactory(new SequentialNumberGenerator());

        for ($i = 1; $i < 11; $i++) {
            $order = $factory->createFromDataArray([], [$this->item]);
            $this->assertEquals((string)$i, $order->getNumber());
        }
    }

    /**
     * @test
     */
    public function order_numbers_can_be_prefixed_from_config()
    {
        config(['vanilo.order.number.sequential_number.prefix' => 'PO']);

        $factory = new OrderFactory(new SequentialNumberGenerator());

        $order = $factory->createFromDataArray([], [$this->item]);

        $this->assertEquals('PO1', $order->getNumber());
    }

    /**
     * @test
     */
    public function order_numbers_can_be_padded_from_config()
    {
        config(['vanilo.order.number.sequential_number.pad_length' => 4]);

        $factory = new OrderFactory(new SequentialNumberGenerator());

        $order = $factory->createFromDataArray([], [$this->item]);

        $this->assertEquals('0001', $order->getNumber());
    }

    /**
     * @test
     */
    public function number_pad_string_can_be_set_in_config()
    {
        config(['vanilo.order.number.sequential_number.pad_length' => 4]);
        config(['vanilo.order.number.sequential_number.pad_string' => 'X']);

        $factory = new OrderFactory(new SequentialNumberGenerator());

        $order = $factory->createFromDataArray([], [$this->item]);

        $this->assertEquals('XXX1', $order->getNumber());
    }

    /**
     * @test
     */
    public function starting_number_can_be_set_in_config()
    {
        config(['vanilo.order.number.sequential_number.start_sequence_from' => 10000]);

        $factory = new OrderFactory(new SequentialNumberGenerator());

        $order = $factory->createFromDataArray([], [$this->item]);

        $this->assertEquals('10000', $order->getNumber());
    }

    /**
     * @test
     */
    public function custom_starting_number_doesnt_break_sequentiality()
    {
        config(['vanilo.order.number.sequential_number.start_sequence_from' => 1000]);

        $factory = new OrderFactory(new SequentialNumberGenerator());

        for ($i = 1000; $i < 1025; $i++) {
            $order = $factory->createFromDataArray([], [$this->item]);
            $this->assertEquals((string)$i, $order->getNumber());
        }
    }

    /**
     * @test
     * @dataProvider versatileProvider
     */
    public function versatile_formatting_can_be_achieved($prefix, $start, $padLen, $padStr, $generateX, $expected)
    {
        config(['vanilo.order.number.sequential_number.prefix' => $prefix]);
        config(['vanilo.order.number.sequential_number.start_sequence_from' => $start]);
        config(['vanilo.order.number.sequential_number.pad_length' => $padLen]);
        config(['vanilo.order.number.sequential_number.pad_string' => $padStr]);

        $factory = new OrderFactory(new SequentialNumberGenerator());

        $numbers = [];

        for ($i = 0; $i < $generateX; $i++) {
            $order = $factory->createFromDataArray([], [$this->item]);
            $numbers[] = $order->getNumber();
        }

        $this->assertEquals($expected, $numbers);
    }

    public function versatileProvider()
    {
        return [
            ['C', '100000', 1, '0', 1, ['C100000']],
            ['C', '100000', 1, '0', 3, ['C100000','C100001','C100002']],
            ['PX-', '1', 4, '0', 4, ['PX-0001','PX-0002','PX-0003','PX-0004']],
            ['', '1000', 5, '1K', 2, ['11000','11001']], //because str_pad truncates it
        ];

    }


}