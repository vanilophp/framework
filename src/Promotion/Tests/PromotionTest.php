<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests;

use Carbon\Carbon;
use Vanilo\Promotion\Models\Promotion;
use Vanilo\Promotion\PromotionRuleTypes;
use Vanilo\Promotion\Rules\CartQuantity;
use Vanilo\Promotion\Tests\Factories\PromotionFactory;

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
            'is_exclusive' => false,
            'usage_limit' => 15,
            'usage_count' => 2,
            'is_coupon_based' => false,
            'starts_at' => $now,
            'ends_at' => $nextMonth,
            'applies_to_discounted' => false,
        ]);

        $this->assertEquals('Awesome promotion', $promotion->name);
        $this->assertEquals('The description', $promotion->description);
        $this->assertEquals(4, $promotion->priority);
        $this->assertFalse($promotion->is_exclusive);
        $this->assertEquals(15, $promotion->usage_limit);
        $this->assertEquals(2, $promotion->usage_count);
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
        $promotion->is_exclusive = false;
        $promotion->usage_limit = 15;
        $promotion->usage_count = 1;
        $promotion->is_coupon_based = false;
        $promotion->applies_to_discounted = false;
        $promotion->starts_at = $now;
        $promotion->ends_at = $nextMonth;

        $this->assertEquals('Awesome promotion', $promotion->name);
        $this->assertEquals('The description', $promotion->description);
        $this->assertEquals(4, $promotion->priority);
        $this->assertFalse($promotion->is_exclusive);
        $this->assertEquals(15, $promotion->usage_limit);
        $this->assertEquals(1, $promotion->usage_count);
        $this->assertFalse($promotion->is_coupon_based);
        $this->assertEquals($now, $promotion->starts_at->toDateTimeString());
        $this->assertEquals($nextMonth, $promotion->ends_at->toDateTimeString());
        $this->assertFalse($promotion->applies_to_discounted);
    }

    /** @test */
    public function the_fields_are_of_proper_types()
    {
        $promotion = Promotion::create([
            'name' => 'Typed Promotion',
            'priority' => 4,
            'usage_limit' => 100,
            'usage_count' => 35,
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::parse('next month'),
        ]);

        $promotion = Promotion::find($promotion->id);

        $this->assertIsInt($promotion->priority);
        $this->assertIsInt($promotion->usage_limit);
        $this->assertIsInt($promotion->usage_count);
        $this->assertIsBool($promotion->is_exclusive);
        $this->assertIsBool($promotion->is_coupon_based);
        $this->assertIsBool($promotion->applies_to_discounted);
        $this->assertInstanceOf(Carbon::class, $promotion->starts_at);
        $this->assertInstanceOf(Carbon::class, $promotion->ends_at);
        $this->assertInstanceOf(Carbon::class, $promotion->created_at);
        $this->assertInstanceOf(Carbon::class, $promotion->updated_at);
    }

    /** @test */
    public function can_determine_if_its_valid()
    {
        $validPromotionA = PromotionFactory::new([
            'ends_at' => Carbon::now()->addMonth(),
            'usage_limit' => 100,
            'usage_count' => 35,
        ])->create();

        $validPromotionB = PromotionFactory::new([
            'usage_limit' => 100,
            'usage_count' => 35,
        ])->create();

        $invalidPromotionA = PromotionFactory::new([
            'ends_at' => Carbon::now()->addMonth(),
            'usage_limit' => 100,
            'usage_count' => 101,
        ])->create();

        $invalidPromotionB = PromotionFactory::new([
            'ends_at' => Carbon::now()->subMonths(),
            'usage_limit' => 100,
            'usage_count' => 5,
        ])->create();

        $this->assertTrue($validPromotionA->isValid());
        $this->assertTrue($validPromotionB->isValid());
        $this->assertFalse($invalidPromotionA->isValid());
        $this->assertFalse($invalidPromotionB->isValid());
        $this->assertFalse($validPromotionA->isValid(Carbon::now()->addYear()));
        $this->assertTrue($validPromotionA->isValid(Carbon::now()->addWeek()));
    }

    /** @test */
    public function it_can_add_rule_and_validate()
    {
        $promotion = PromotionFactory::new()->create();
        $promotion->addRule(PromotionRuleTypes::make(CartQuantity::ID), ['count' => 3]);

        $this->assertEquals(1, $promotion->rules()->count());
        $this->assertEquals(['count' => 3], $promotion->rules()->first()->configuration);
        $this->assertEquals(CartQuantity::ID, $promotion->rules()->first()->type);
    }
}
