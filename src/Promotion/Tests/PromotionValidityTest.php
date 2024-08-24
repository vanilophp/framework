<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests;

use Carbon\Carbon;
use Vanilo\Promotion\Models\Promotion;

class PromotionValidityTest extends TestCase
{
    /** @test */
    public function it_is_not_valid_if_it_has_not_been_started_yet()
    {
        $this->assertFalse((new Promotion(['starts_at' => Carbon::tomorrow()]))->isValid());
    }

    /** @test */
    public function it_is_not_valid_if_it_has_already_expired()
    {
        $this->assertFalse((new Promotion(['ends_at' => Carbon::tomorrow()]))->isValid());
    }

    /** @test */
    public function it_is_not_valid_if_it_is_depleted()
    {
        $this->assertFalse((new Promotion(['usage_limit' => 1, 'usage_count' => 1]))->isValid());
    }

    /** @test */
    public function it_is_not_valid_if_it_has_started_not_expired_but_is_depleted()
    {
        $promo = new Promotion([
            'usage_limit' => 1,
            'usage_count' => 1,
            'starts_at' => Carbon::yesterday(),
            'ends_at' => Carbon::tomorrow(),
        ]);

        $this->assertFalse($promo->isValid());
    }

    /** @test */
    public function it_is_not_valid_if_it_has_not_started_yet_has_no_expiry_and_is_not_depleted()
    {
        $promo = new Promotion([
            'usage_limit' => 10,
            'usage_count' => 1,
            'starts_at' => Carbon::tomorrow(),
        ]);

        $this->assertFalse($promo->isValid());
    }

    /** @test */
    public function it_is_not_valid_if_it_has_no_start_date_has_expired_and_is_not_depleted()
    {
        $promo = new Promotion([
            'usage_limit' => 10,
            'usage_count' => 1,
            'ends_at' => Carbon::yesterday(),
        ]);

        $this->assertFalse($promo->isValid());
    }

    /** @test */
    public function it_is_valid_if_it_has_no_start_date_has_not_expired_and_is_not_depleted()
    {
        $promo = new Promotion([
            'usage_limit' => 10,
            'usage_count' => 1,
            'ends_at' => Carbon::tomorrow(),
        ]);

        $this->assertTrue($promo->isValid());
    }

    /** @test */
    public function it_is_valid_if_it_has_started_has_not_expired_and_is_not_depleted()
    {
        $promo = new Promotion([
            'usage_limit' => 2,
            'usage_count' => 1,
            'starts_at' => Carbon::yesterday(),
            'ends_at' => Carbon::tomorrow(),
        ]);

        $this->assertTrue($promo->isValid());
    }

    /** @test */
    public function it_is_valid_if_it_has_started_has_not_expired_and_is_unlimited()
    {
        $promo = new Promotion([
            'usage_limit' => null,
            'usage_count' => 10000,
            'starts_at' => Carbon::yesterday(),
            'ends_at' => Carbon::tomorrow(),
        ]);

        $this->assertTrue($promo->isValid());
    }

    /** @test */
    public function it_is_valid_if_it_has_neither_start_nor_end_date_and_is_unlimited()
    {
        $promo = new Promotion();

        $this->assertTrue($promo->isValid());
    }
}
