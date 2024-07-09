<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests;

use Carbon\Carbon;
use Vanilo\Promotion\Models\Promotion;

class PromotionTest extends TestCase
{
    /** @test */
    public function all_mutable_fields_can_be_mass_assigned()
    {
        $now = Carbon::now()->startOfDay()->toDateTimeString();
        $nextMonth = Carbon::now()->addMonths(1)->endOfDay()->toDateTimeString();

        $promotion = Promotion::create([
            'code' => 'PROMO-1',
            'name' => 'Awesome promotion',
            'description' => 'The description',
            'priority' => 4,
            'exclusive' => false,
            'usage_limit' => 15,
            'used' => 1,
            'coupon_based' => false,
            'starts_at' => $now,
            'ends_at' => $nextMonth,
            'applies_to_discounted' => false,

        ]);

        $this->assertEquals('PROMO-1', $promotion->code);
        $this->assertEquals('Awesome promotion', $promotion->name);
        $this->assertEquals('The description', $promotion->description);
        $this->assertEquals(4, $promotion->priority);
        $this->assertFalse($promotion->exclusive);
        $this->assertEquals(15, $promotion->usage_limit);
        $this->assertEquals(1, $promotion->used);
        $this->assertFalse($promotion->coupon_based);
        $this->assertEquals($now, $promotion->starts_at);
        $this->assertEquals($nextMonth, $promotion->ends_at);
        $this->assertFalse($promotion->applies_to_discounted);
    }

    /** @test */
    public function all_mutable_fields_can_be_set()
    {
        $now = Carbon::now()->startOfDay()->startOfDay()->toDateTimeString();
        $nextMonth = Carbon::now()->addMonth()->endOfDay()->toDateTimeString();

        $promotion = new Promotion();

        $promotion->code = 'PROMO-1';
        $promotion->name = 'Awesome promotion';
        $promotion->description = 'The description';
        $promotion->priority = 4;
        $promotion->exclusive = false;
        $promotion->usage_limit = 15;
        $promotion->used = 1;
        $promotion->coupon_based = false;
        $promotion->applies_to_discounted = false;
        $promotion->starts_at = $now;
        $promotion->ends_at = $nextMonth;


        $this->assertEquals('PROMO-1', $promotion->code);
        $this->assertEquals('Awesome promotion', $promotion->name);
        $this->assertEquals('The description', $promotion->description);
        $this->assertEquals(4, $promotion->priority);
        $this->assertFalse($promotion->exclusive);
        $this->assertEquals(15, $promotion->usage_limit);
        $this->assertEquals(1, $promotion->used);
        $this->assertFalse($promotion->coupon_based);
        $this->assertEquals($now, $promotion->starts_at->toDateTimeString());
        $this->assertEquals($nextMonth, $promotion->ends_at->toDateTimeString());
        $this->assertFalse($promotion->applies_to_discounted);
    }

    /** @test */
    public function code_must_be_unique()
    {
        $this->expectExceptionMessageMatches('/UNIQUE constraint failed/');

        $p1 = Promotion::create([
            'name' => 'Promo 1',
            'code' => 'PROMO-1',
        ]);

        $p2 = Promotion::create([
            'name' => 'Promo 2',
            'code' => 'PROMO-1',
        ]);

        $this->assertNotEquals($p1->code, $p2->code);
    }
}
