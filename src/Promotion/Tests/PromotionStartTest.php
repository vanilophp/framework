<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests;

use Illuminate\Support\Carbon;
use Vanilo\Promotion\Models\Promotion;

class PromotionStartTest extends TestCase
{
    /** @test */
    public function it_has_not_started_if_the_start_date_is_in_the_future()
    {
        $this->assertFalse((new Promotion(['starts_at' => Carbon::now()->addDay()]))->hasStarted());
    }

    /** @test */
    public function it_has_started_if_the_start_date_is_null()
    {
        $this->assertTrue((new Promotion())->hasStarted());
    }

    /** @test */
    public function it_has_started_if_the_start_date_is_now()
    {
        $this->assertTrue((new Promotion(['starts_at' => new \DateTime()]))->hasStarted());
    }

    /** @test */
    public function it_has_started_if_the_start_date_is_in_the_past()
    {
        $this->assertTrue((new Promotion(['starts_at' => Carbon::yesterday()]))->hasStarted());
    }

    /** @test */
    public function it_can_tell_if_it_will_be_started_at_a_future_date()
    {
        $tomorrow = new \DateTime('+1day');
        $this->assertTrue((new Promotion(['starts_at' => new \DateTime()]))->hasStarted($tomorrow));
        $this->assertFalse((new Promotion(['starts_at' => new \DateTime('+2day')]))->hasStarted($tomorrow));
        $this->assertTrue((new Promotion())->hasStarted($tomorrow));
    }

    /** @test */
    public function it_can_tell_if_it_was_started_at_a_past_date()
    {
        $yesterday = new \DateTime('-1day');
        $this->assertFalse((new Promotion(['starts_at' => new \DateTime()]))->hasStarted($yesterday));
        $this->assertTrue((new Promotion(['starts_at' => new \DateTime('-2day')]))->hasStarted($yesterday));
        $this->assertTrue((new Promotion())->hasStarted($yesterday));
    }
}
