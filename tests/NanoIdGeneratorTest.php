<?php

declare(strict_types=1);

/**
 * Contains the NanoIdGeneratorTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-30
 *
 */

namespace Vanilo\Support\Tests;

use PHPUnit\Framework\TestCase;
use Vanilo\Support\Generators\NanoIdGenerator;

class NanoIdGeneratorTest extends TestCase
{
    /** @test */
    public function it_generates_a_21_char_long_string_by_default()
    {
        $nr = (new NanoIdGenerator())->generate();
        $this->assertEquals(21, strlen($nr));
    }

    /** @test */
    public function it_generates_21_char_long_strings_two_hundred_thousand_times()
    {
        $generator = new NanoIdGenerator();

        for ($i = 0; $i < 200000; $i++) {
            $this->assertEquals(21, strlen($generator->generate()));
        }
    }

    /** @test */
    public function it_is_unique_when_called_in_a_rapid_consecutive_100k_times()
    {
        $numbers = [];
        $generator = new NanoIdGenerator();

        for ($k = 0; $k < 100000; $k++) {
            $numbers[] = $generator->generate();
        }

        $this->assertEquals(count($numbers), count(array_unique($numbers)));
    }

    /** @test */
    public function size_of_the_id_can_be_specified_within_the_constructor()
    {
        $generator = new NanoIdGenerator(9);

        for ($i = 0; $i < 100; $i++) {
            $this->assertEquals(9, strlen($generator->generate()));
        }

        $generator = new NanoIdGenerator(35);

        for ($i = 0; $i < 100; $i++) {
            $this->assertEquals(35, strlen($generator->generate()));
        }
    }

    /** @test */
    public function the_alphabet_can_be_specified_within_the_constructor()
    {
        $alphabetsAndSizes = [
            'abcdefghijklmnopqrstwxyz' => 12,
            'abcdefghjklmnopqrstwxyzABCDEFGHJKLMNOPQRSTWXYZ123456789' => 21,
            '0123456789' => 8,
            '_-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' => 21,
            '[]{}|:<>!@#$%^&*()-_=+~' => 10
        ];

        foreach ($alphabetsAndSizes as $alphabet => $size) {
            $generator = new NanoIdGenerator($size, $alphabet);
            $charTable = str_split($alphabet);
            for ($i = 0; $i < 1000; $i++) {
                $id = $generator->generate();
                foreach (str_split($id) as $char) {
                    $this->assertContains($char, $charTable);
                }
            }
        }
    }
}
