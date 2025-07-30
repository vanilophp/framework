<?php

declare(strict_types=1);

/**
 * Contains the TimeHashTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-30
 *
 */

namespace Vanilo\Order\Tests;

use Carbon\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Order\Generators\TimeHashGenerator;

class TimeHashTest extends TestCaseWithoutDB
{
    protected TimeHashGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $this->generator = new TimeHashGenerator();
    }

    #[Test] public function it_generates_a_13_char_long_string()
    {
        $number = $this->generator->generateNumber();

        $this->assertMatchesRegularExpression('/^[A-Z0-9]{3}-[A-Z0-9]{4}-[A-Z0-9]{4}$/m', $number);
        $this->assertEquals(13, strlen($number));
    }

    #[Test] public function it_generates_13_char_long_strings_five_thousand_times()
    {
        for ($i = 0; $i < 5000; $i++) {
            $this->assertEquals(13, strlen($this->generator->generateNumber()));
        }
    }

    #[Test] public function it_is_unique_when_called_in_a_rapid_consecutive_ten_times()
    {
        $numbers = [];
        for ($i = 0; $i < 10; $i++) {
            $numbers[] = $this->generator->generateNumber();
        }

        $this->assertEquals(count($numbers), count(array_unique($numbers)));
    }

    #[Test] public function it_is_unique_when_called_in_a_rapid_consecutive_three_times_ten_times_with_small_delays()
    {
        $numbers = [];
        for ($k = 0; $k < 3; $k++) {
            for ($i = 0; $i < 10; $i++) {
                $numbers[] = $this->generator->generateNumber();
                usleep(10000); //0.01s
            }
            usleep(10000); //0.01s
        }

        $this->assertEquals(count($numbers), count(array_unique($numbers)));
    }

    #[Test] public function number_length_is_14_if_the_extra_digit_is_enabled()
    {
        $generator = new TimeHashGenerator(uppercase: true, extraDigit: true);

        $this->assertMatchesRegularExpression('/^[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/m', $generator->generateNumber());

        for ($i = 0; $i < 100; $i++) {
            $this->assertEquals(14, strlen($generator->generateNumber()));
        }
    }

    #[Test] public function it_is_unique_when_called_in_a_rapid_consecutive_ten_thousand_times_with_an_extra_digit()
    {
        $generator = new TimeHashGenerator(extraDigit: true);
        $numbers = [];
        for ($k = 0; $k < 10; $k++) {
            for ($i = 0; $i < 10; $i++) {
                $numbers[] = $generator->generateNumber();
            }
        }

        $this->assertEquals(count($numbers), count(array_unique($numbers)));
    }

    #[Test] public function number_length_is_18_if_high_variance_is_enabled()
    {
        $this->generator->setHighVariance(true);

        for ($i = 0; $i < 100; $i++) {
            $this->assertEquals(18, strlen($this->generator->generateNumber()));
        }
    }

    #[Test] public function it_is_unique_with_high_variance_enabled_when_called_in_a_rapid_consecutive_ten_thousand_times()
    {
        $this->generator->setHighVariance(true);
        $numbers = [];
        for ($i = 0; $i < 10000; $i++) {
            $numbers[] = $this->generator->generateNumber();
        }

        $this->assertEquals(count($numbers), count(array_unique($numbers)));
    }

    #[Test] public function can_be_configured_to_return_numbers_in_uppercase()
    {
        $generator = new TimeHashGenerator(uppercase: false);
        $number = $generator->generateNumber();
        $this->assertEquals($number, strtolower($number));
        $this->assertNotEquals($number, strtoupper($number));

        $generator = new TimeHashGenerator(uppercase: true);
        $number = $generator->generateNumber();

        $this->assertEquals($number, strtoupper($number));
        $this->assertNotEquals($number, strtolower($number));
    }

    #[DataProvider('edgeCaseDateProvider')] #[Test] public function length_is_13_in_edge_cases_as_well($date)
    {
        Carbon::setTestNow($date);

        for ($i = 0; $i < 10; $i++) {
            $number = $this->generator->generateNumber();
            $len = strlen($number);
            $this->assertEquals(13, $len, sprintf('The generated id `%s` should be 13 char long but it is %d', $number, $len));
        }
    }

    public static function edgeCaseDateProvider()
    {
        return [
            ['2000-01-01 00:00:00'],
            ['2009-01-01 00:00:00'],
            ['2017-01-01 00:00:00'],
            ['2017-12-31 23:59:59'],
            ['2018-01-01 00:00:00'],
            ['2018-01-01 00:00:01'],
            ['2020-02-29 00:00:01'],
            ['2020-02-29 23:59:59'],
            ['2046-12-31 23:59:59'],
            ['2047-01-01 03:00:00'],
            ['2100-01-01 00:00:00'],
            ['2100-01-01 23:59:59'],
            ['2100-01-02 00:00:59'],
            ['2127-09-27 23:59:59']
        ];
    }

    #[Test] public function start_base_date_can_be_changed_in_config()
    {
        $generator = new TimeHashGenerator(startBaseDate: '2013-11-27');

        Carbon::setTestNow('2013-11-28');

        $this->assertStringStartsWith('001-', $generator->generateNumber());
    }
}
