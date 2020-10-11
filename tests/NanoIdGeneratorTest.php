<?php
/**
 * Contains the NanoIdGeneratorTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-09-25
 *
 */

namespace Vanilo\Order\Tests;

use Vanilo\Order\Generators\NanoIdGenerator;

class NanoIdGeneratorTest extends TestCase
{
    /** @var NanoIdGenerator */
    private $generator;

    public function setUp(): void
    {
        parent::setUp();

        $this->generator = new NanoIdGenerator();
    }

    /** @test */
    public function it_generates_a_12_char_long_string_by_default()
    {
        $nr = $this->generator->generateNumber();
        $this->assertEquals(12, strlen($nr));
    }

    /** @test */
    public function it_generates_12_char_long_strings_two_hundred_thousand_times()
    {
        for ($i = 0; $i < 200000; $i++) {
            $this->assertEquals(12, strlen($this->generator->generateNumber()));
        }
    }

    /** @test */
    public function it_is_unique_when_called_in_a_rapid_consecutive_100k_times()
    {
        $numbers = [];
        for ($k = 0; $k < 100000; $k++) {
            $numbers[] = $this->generator->generateNumber();
        }

        $this->assertEquals(count($numbers), count(array_unique($numbers)));
    }

    /** @test */
    public function size_of_the_id_can_be_specified_within_the_constructor()
    {
        $generator = new NanoIdGenerator(7);

        for ($i = 0; $i < 100; $i++) {
            $this->assertEquals(7, strlen($generator->generateNumber()));
        }

        $generator = new NanoIdGenerator(21);

        for ($i = 0; $i < 100; $i++) {
            $this->assertEquals(21, strlen($generator->generateNumber()));
        }
    }

    /** @test */
    public function the_alphabet_can_be_specified_within_the_constructor()
    {
        $alphabetsAndSizes = [
            'abcdefghijklmnopqrstwxyz'                                         => 12,
            'abcdefghjklmnopqrstwxyzABCDEFGHJKLMNOPQRSTWXYZ123456789'          => 21,
            '0123456789'                                                       => 8,
            '_-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' => 21,
            '[]{}|:<>!@#$%^&*()-_=+~'                                          => 10
        ];

        foreach ($alphabetsAndSizes as $alphabet => $size) {
            $generator = new NanoIdGenerator($size, $alphabet);
            $charTable = str_split($alphabet);
            for ($i = 0; $i < 1000; $i++) {
                $id = $generator->generateNumber();
                foreach (str_split($id) as $char) {
                    $this->assertContains($char, $charTable);
                }
            }
        }
    }

    /** @test */
    public function size_can_be_changed_in_config()
    {
        $sizes = [21, 15, 9, 4];
        foreach ($sizes as $size) {
            config(['vanilo.order.number.nano_id.size' => $size]);
            $generator = new NanoIdGenerator();

            for ($i = 0; $i < 100; $i++) {
                $this->assertEquals($size, strlen($generator->generateNumber()));
            }
        }
    }

    /** @test */
    public function alphabet_can_be_changed_in_config()
    {
        $alphabets = [
            '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            '0123456789',
            'abcdefghijklmnopqrstuvwxyz',
            'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        ];

        foreach ($alphabets as $alphabet) {
            config(['vanilo.order.number.nano_id.alphabet' => $alphabet]);
            $generator = new NanoIdGenerator();

            $charTable = str_split($alphabet);
            for ($i = 0; $i < 1000; $i++) {
                $id = $generator->generateNumber();
                foreach (str_split($id) as $char) {
                    $this->assertContains($char, $charTable);
                }
            }
        }
    }
}
