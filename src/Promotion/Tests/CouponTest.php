<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests;

use Carbon\Carbon;
use Vanilo\Promotion\Models\Coupon;
use Vanilo\Promotion\Tests\Factories\CouponFactory;
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

    /** @test */
    public function can_return_coupon_by_code()
    {
        CouponFactory::new(['code' => 'test-code'])->create();

        $this->assertEquals('test-code', Coupon::findByCode('test-code')->code);
    }

    /** @test */
    public function cat_return_promotion()
    {
        $promotion = PromotionFactory::new(['name' => 'Test promo'])->create();
        $coupon = CouponFactory::new(['promotion_id' => $promotion->id])->create();

        $this->assertEquals('Test promo', $coupon->getPromotion()->name);
    }

    /** @test */
    public function determines_if_its_depleted()
    {
        $depleted = CouponFactory::new(['usage_limit' => 3, 'usage_count' => 3])->create();
        $notDepleted = CouponFactory::new(['usage_limit' => 3, 'usage_count' => 2])->create();

        $this->assertTrue($depleted->isDepleted());
        $this->assertFalse($notDepleted->isDepleted());
    }

    /** @test */
    public function determines_if_its_expired()
    {
        $expiredCoupon = CouponFactory::new(['expires_at' => Carbon::now()->subWeek()])->create();
        $notExpired = CouponFactory::new(['expires_at' => Carbon::now()->addWeek()])->create();

        $this->assertTrue($expiredCoupon->isExpired());
        $this->assertFalse($notExpired->isExpired());
    }

    /** @test */
    public function determines_if_can_be_used()
    {
        $canBeUsed = CouponFactory::new([
            'expires_at' => Carbon::now()->addWeek(),
            'usage_limit' => 3,
            'usage_count' => 2,
        ])->create();

        $cantBeUsedA = CouponFactory::new([
            'expires_at' => Carbon::now()->subWeek(),
            'usage_limit' => 3,
            'usage_count' => 2,
        ])->create();

        $cantBeUsedB = CouponFactory::new([
            'expires_at' => Carbon::now()->addweek(),
            'usage_limit' => 3,
            'usage_count' => 3,
        ])->create();

        $this->assertTrue($canBeUsed->canBeUsed());
        $this->assertFalse($cantBeUsedA->canBeUsed());
        $this->assertFalse($cantBeUsedB->canBeUsed());
    }
}
