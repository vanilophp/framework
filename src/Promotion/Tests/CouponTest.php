<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests;

use Carbon\Carbon;
use Vanilo\Promotion\Models\Coupon;
use Vanilo\Promotion\Models\Promotion;

class CouponTest extends TestCase
{
    /** @test */
    public function all_mutable_fields_can_be_mass_assigned()
    {
        $coupon = Coupon::create([
            'promotion_id' => factory(Promotion::class)->create()->id,
            'code' => 'coupon',
            'per_customer_usage_limit' => 2,
            'usage_limit' => 4,
            'used' => 4,
            'expiresAt' => Carbon::now()->endOfDay()->toDateTimeString(),
        ]);

        $this->assertEquals('coupon', $coupon->code);
        $this->assertEquals(2, $coupon->per_customer_usage_limit);
        $this->assertEquals(4, $coupon->usage_limit);
        $this->assertEquals(4, $coupon->used);
        $this->assertEquals(Carbon::now()->endOfDay()->toDateTimeString(), $coupon->expiresAt);
    }

    /** @test */
    public function all_mutable_fields_can_be_set()
    {
        $coupon = new Coupon();

        $coupon->code = 'coupon';
        $coupon->per_customer_usage_limit = 1;
        $coupon->usage_limit = 15;
        $coupon->used = 4;
        $coupon->expiresAt = Carbon::now()->endOfDay()->toDateTimeString();

        $this->assertEquals('coupon', $coupon->code);
        $this->assertEquals(1, $coupon->per_customer_usage_limit);
        $this->assertEquals(15, $coupon->usage_limit);
        $this->assertEquals(4, $coupon->used);
        $this->assertEquals(Carbon::now()->endOfDay()->toDateTimeString(), $coupon->expiresAt);
    }

    /** @test */
    public function code_must_be_unique()
    {
        $this->expectExceptionMessageMatches('/UNIQUE constraint failed/');

        $c1 = Coupon::create([
            'code' => 'coupon-1',
            'promotion_id' => factory(Promotion::class)->create()->id,
        ]);

        $c2 = Coupon::create([
            'code' => 'coupon-1',
            'promotion_id' => factory(Promotion::class)->create()->id,
        ]);

        $this->assertNotEquals($c1->code, $c2->code);
    }
}
