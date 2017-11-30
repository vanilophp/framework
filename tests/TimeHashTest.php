<?php
/**
 * Contains the CompactHashTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-30
 *
 */


namespace Vanilo\Order\Tests;


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
}
