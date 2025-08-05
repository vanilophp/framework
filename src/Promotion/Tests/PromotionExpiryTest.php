<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Promotion\Models\Promotion;

class PromotionExpiryTest extends TestCase
{
    #[Test] public function it_is_not_expired_if_the_end_date_is_in_the_future()
    {
        $this->assertFalse((new Promotion(['ends_at' => Carbon::now()->addDay()]))->isExpired());
    }

    #[Test] public function it_is_not_expired_if_the_end_date_is_null()
    {
        $this->assertFalse((new Promotion())->isExpired());
    }

    #[Test] public function it_is_expired_if_the_end_date_is_now()
    {
        $this->assertTrue((new Promotion(['ends_at' => new \DateTime()]))->isExpired());
    }

    #[Test] public function it_is_expired_if_the_end_date_is_in_the_past()
    {
        $this->assertTrue((new Promotion(['ends_at' => Carbon::yesterday()]))->isExpired());
    }

    #[Test] public function it_can_tell_if_it_will_be_expired_at_a_future_date()
    {
        $tomorrow = new \DateTime('+1day');
        $this->assertTrue((new Promotion(['ends_at' => new \DateTime()]))->isExpired($tomorrow));
        $this->assertFalse((new Promotion(['ends_at' => new \DateTime('+2day')]))->isExpired($tomorrow));
        $this->assertFalse((new Promotion())->isExpired($tomorrow));
    }

    #[Test] public function it_can_tell_if_it_was_expired_at_a_past_date()
    {
        $yesterday = new \DateTime('-1day');
        $this->assertFalse((new Promotion(['ends_at' => new \DateTime()]))->isExpired($yesterday));
        $this->assertTrue((new Promotion(['ends_at' => new \DateTime('-2day')]))->isExpired($yesterday));
        $this->assertFalse((new Promotion())->isExpired($yesterday));
    }
}
