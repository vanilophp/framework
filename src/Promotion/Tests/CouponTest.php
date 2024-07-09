<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests;

use Carbon\Carbon;
use Vanilo\Promotion\Models\Coupon;
use Vanilo\Promotion\Tests\Factories\PromotionFactory;

class CouponTest extends TestCase
{
    /** @test */
    public function all_mutable_fields_can_be_mass_assigned()
    {
        $expiryDate = Carbon::now()->endOfDay();
        $coupon = Coupon::create([
            'promotion_id' => PromotionFactory::new()->create()->id,
            'code' => 'coupon',
            'per_customer_usage_limit' => 2,
            'usage_limit' => 4,
            'usage_count' => 4,
            'expires_at' => $expiryDate,
        ]);

        $coupon = $coupon->refresh();

        $this->assertEquals('coupon', $coupon->code);
        $this->assertEquals(2, $coupon->per_customer_usage_limit);
        $this->assertEquals(4, $coupon->usage_limit);
        $this->assertEquals(4, $coupon->usage_count);
        $this->assertEquals($expiryDate->toDateTimeString(), $coupon->expires_at->toDateTimeString());
    }

    /** @test */
    public function all_mutable_fields_can_be_set()
    {
        $coupon = new Coupon();

        $coupon->code = 'coupon';
        $coupon->per_customer_usage_limit = 1;
        $coupon->usage_limit = 15;
        $coupon->usage_count = 4;
        $coupon->expires_at = Carbon::now()->endOfDay()->toDateTimeString();

        $this->assertEquals('coupon', $coupon->code);
        $this->assertEquals(1, $coupon->per_customer_usage_limit);
        $this->assertEquals(15, $coupon->usage_limit);
        $this->assertEquals(4, $coupon->usage_count);
        $this->assertEquals(Carbon::now()->endOfDay()->toDateTimeString(), $coupon->expires_at);
    }

    /** @test */
    public function code_must_be_unique()
    {
        $this->expectExceptionMessageMatches('/UNIQUE constraint failed/');

        $c1 = Coupon::create([
            'code' => 'coupon-1',
            'promotion_id' => PromotionFactory::new()->create()->id,
        ]);

        $c2 = Coupon::create([
            'code' => 'coupon-1',
            'promotion_id' => PromotionFactory::new()->create()->id,
        ]);
    }

    /** @test */
    public function the_fields_are_of_proper_types()
    {
        $coupon = Coupon::create([
            'promotion_id' => PromotionFactory::new()->create()->id,
            'code' => 'coupon',
            'per_customer_usage_limit' => 2,
            'usage_limit' => 8,
            'usage_count' => 7,
            'expires_at' => Carbon::parse('tomorrow'),
        ]);

        $coupon = $coupon->refresh();

        $this->assertIsInt($coupon->per_customer_usage_limit);
        $this->assertIsInt($coupon->usage_limit);
        $this->assertIsInt($coupon->usage_count);
        $this->assertInstanceOf(Carbon::class, $coupon->expires_at);
        $this->assertInstanceOf(Carbon::class, $coupon->created_at);
        $this->assertInstanceOf(Carbon::class, $coupon->updated_at);
    }
}
