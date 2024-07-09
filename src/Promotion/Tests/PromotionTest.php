<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests;

use Carbon\Carbon;
use Vanilo\Promotion\Models\Promotion;

class PromotionTest extends TestCase
{
    /** @test */
    public function it_can_be_created_with_minimal_data()
    {
        $promotion = Promotion::create(['name' => 'Sample Promotion']);
        $this->assertInstanceOf(Promotion::class, $promotion);
        $this->assertEquals('Sample Promotion', $promotion->name);
    }

    /** @test */
    public function all_mutable_fields_can_be_mass_assigned()
    {
        $now = Carbon::now()->startOfDay()->toDateTimeString();
        $nextMonth = Carbon::now()->addMonths(1)->endOfDay()->toDateTimeString();

        $promotion = Promotion::create([
            'name' => 'Awesome promotion',
            'description' => 'The description',
            'priority' => 4,
            'exclusive' => false,
            'usage_limit' => 15,
            'used' => 1,
            'is_coupon_based' => false,
            'starts_at' => $now,
            'ends_at' => $nextMonth,
            'applies_to_discounted' => false,
        ]);

        $this->assertEquals('Awesome promotion', $promotion->name);
        $this->assertEquals('The description', $promotion->description);
        $this->assertEquals(4, $promotion->priority);
        $this->assertFalse($promotion->exclusive);
        $this->assertEquals(15, $promotion->usage_limit);
        $this->assertEquals(1, $promotion->used);
        $this->assertFalse($promotion->is_coupon_based);
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

        $promotion->name = 'Awesome promotion';
        $promotion->description = 'The description';
        $promotion->priority = 4;
        $promotion->exclusive = false;
        $promotion->usage_limit = 15;
        $promotion->used = 1;
        $promotion->is_coupon_based = false;
        $promotion->applies_to_discounted = false;
        $promotion->starts_at = $now;
        $promotion->ends_at = $nextMonth;

        $this->assertEquals('Awesome promotion', $promotion->name);
        $this->assertEquals('The description', $promotion->description);
        $this->assertEquals(4, $promotion->priority);
        $this->assertFalse($promotion->exclusive);
        $this->assertEquals(15, $promotion->usage_limit);
        $this->assertEquals(1, $promotion->used);
        $this->assertFalse($promotion->is_coupon_based);
        $this->assertEquals($now, $promotion->starts_at->toDateTimeString());
        $this->assertEquals($nextMonth, $promotion->ends_at->toDateTimeString());
        $this->assertFalse($promotion->applies_to_discounted);
    }
}
