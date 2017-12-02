<?php
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
use Vanilo\Order\Generators\TimeHashGenerator;

class TimeHashTest extends TestCase
{
    /** @var TimeHashGenerator */
    protected $generator;

    public function setUp()
    {
        parent::setUp();

        $this->generator = new TimeHashGenerator();
    }


    /**
     * @test
     */
    public function it_generates_a_13_char_long_string()
    {
        $nr = $this->generator->generateNumber();
        $this->assertEquals(13, strlen($nr));
    }

    /**
     * @test
     */
    public function it_generates_13_char_long_strings_ten_thousand_times()
    {
        for ($i = 0; $i < 10000; $i++) {
            $this->assertEquals(13, strlen($this->generator->generateNumber()));
        }
    }

    /**
     * @test
     */
    public function it_is_unique_when_called_in_a_rapid_consecutive_ten_times()
    {
        $numbers = [];
        for ($i = 0; $i < 10; $i++) {
            $numbers[] = $this->generator->generateNumber();
        }

        $this->assertEquals(count($numbers), count(array_unique($numbers)));
    }

    /**
     * @test
     */
    public function it_is_unique_when_called_in_a_rapid_consecutive_ten_times_ten_times_with_small_delays()
    {
        $numbers = [];
        // This is the most edgy test case, increase number of iterations and it'll be more prone to fail
        for ($k = 0; $k < 10; $k++) {
            for ($i = 0; $i < 10; $i++) {
                $numbers[] = $this->generator->generateNumber();
            }
            usleep(20000);//0.02s
        }

        $this->assertEquals(count($numbers), count(array_unique($numbers)));
    }

    /**
     * @test
     */
    public function number_length_is_18_if_high_variance_is_enabled()
    {
        $this->generator->setHighVariance(true);

        for ($i = 0; $i < 100; $i++) {
            $this->assertEquals(18, strlen($this->generator->generateNumber()));
        }
    }

    /**
     * @test
     */
    public function it_is_unique_with_high_variance_enabled_when_called_in_a_rapid_consecutive_ten_thousand_times()
    {
        $this->generator->setHighVariance(true);
        $numbers = [];
        for ($i = 0; $i < 10000; $i++) {
            $numbers[] = $this->generator->generateNumber();
        }

        $this->assertEquals(count($numbers), count(array_unique($numbers)));
    }

    /**
     * @test
     */
    public function can_be_configured_to_return_numbers_in_uppercase()
    {
        $number =  $this->generator->generateNumber();
        $this->assertEquals($number, strtolower($number));
        $this->assertNotEquals($number, strtoupper($number));

        config(['vanilo.order.number.time_hash.uppercase' => true]);
        // Generate a new one to reread configuration
        $this->generator = new TimeHashGenerator();

        $number = $this->generator->generateNumber();

        $this->assertEquals($number, strtoupper($number));
        $this->assertNotEquals($number, strtolower($number));
    }

    /**
     * @test
     * @dataProvider edgeCaseDateProvider
     */
    public function length_is_13_in_edge_cases_as_well($date)
    {
        Carbon::setTestNow($date);

        for ($i = 0; $i < 10; $i++) {
            $this->assertEquals(13, strlen($this->generator->generateNumber()));
        }
    }

    public function edgeCaseDateProvider()
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

    /**
     * @test
     */
    public function start_base_date_can_be_changed_in_config()
    {
        config(['vanilo.order.number.time_hash.start_base_date' => '2013-11-27']);
        // Generate a new one to reread configuration
        $this->generator = new TimeHashGenerator();

        Carbon::setTestNow('2013-11-28');

        $this->assertStringStartsWith('001-', $this->generator->generateNumber());
    }
}
