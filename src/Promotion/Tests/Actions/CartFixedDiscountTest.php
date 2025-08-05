<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests\Actions;

use Nette\Schema\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Adjustments\Adjusters\SimpleDiscount;
use Vanilo\Promotion\Actions\CartFixedDiscount;
use Vanilo\Promotion\PromotionActionTypes;
use Vanilo\Promotion\Tests\Examples\SampleAdjustable;
use Vanilo\Promotion\Tests\TestCase;

class CartFixedDiscountTest extends TestCase
{
    #[Test] public function it_can_be_created_from_the_registry()
    {
        $fixedDiscount = PromotionActionTypes::make(CartFixedDiscount::DEFAULT_ID);

        $this->assertInstanceOf(CartFixedDiscount::class, $fixedDiscount);
    }

    #[Test] public function it_throws_an_exception_if_the_configuration_is_wrong()
    {
        $this->expectException(ValidationException::class);

        $fixedDiscount = new CartFixedDiscount();

        $fixedDiscount->getAdjuster(['wrong' => 'configuration']);
    }

    #[Test] public function it_returns_a_simple_discount_adjuster_if_the_configuration_is_correct()
    {
        $this->assertInstanceOf(SimpleDiscount::class, (new CartFixedDiscount())->getAdjuster(['amount' => 10]));
    }

    #[Test] public function it_adds_a_promotion_adjustment_to_the_subject_if_applied()
    {
        $discount = new CartFixedDiscount();
        $subject = new SampleAdjustable();

        $discount->apply($subject, ['amount' => 5]);

        $this->assertCount(1, $subject->adjustments());
        $adjustment = $subject->adjustments()->first();
        $this->assertInstanceOf(SimpleDiscount::class, $adjustment->getAdjuster());
        $this->assertEquals(-5, $adjustment->getAmount());
    }
}
