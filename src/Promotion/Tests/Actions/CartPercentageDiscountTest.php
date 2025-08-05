<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests\Actions;

use Nette\Schema\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Adjustments\Adjusters\PercentDiscount;
use Vanilo\Promotion\Actions\CartPercentageDiscount;
use Vanilo\Promotion\PromotionActionTypes;
use Vanilo\Promotion\Tests\Examples\SampleAdjustable;
use Vanilo\Promotion\Tests\TestCase;

class CartPercentageDiscountTest extends TestCase
{
    #[Test] public function it_has_a_name()
    {
        $this->assertNotEmpty(CartPercentageDiscount::getName());
    }

    #[Test] public function it_can_be_created_from_the_registry()
    {
        $fixedDiscount = PromotionActionTypes::make(CartPercentageDiscount::DEFAULT_ID);

        $this->assertInstanceOf(CartPercentageDiscount::class, $fixedDiscount);
    }

    #[Test] public function it_throws_a_validation_exception_if_the_percent_is_missing_from_the_configuration()
    {
        $this->expectException(ValidationException::class);

        $discount = new CartPercentageDiscount();

        $discount->getAdjuster([]);
    }

    #[Test] public function it_throws_a_validation_exception_if_the_configured_percent_is_higher_than_100()
    {
        $this->expectException(ValidationException::class);

        $discount = new CartPercentageDiscount();

        $discount->getAdjuster(['percent' => 101]);
    }

    #[Test] public function it_throws_a_validation_exception_if_the_configured_percent_is_negative()
    {
        $this->expectException(ValidationException::class);

        $discount = new CartPercentageDiscount();

        $discount->getAdjuster(['percent' => -3]);
    }

    #[Test] public function the_title_contains_the_configured_percent()
    {
        $this->assertStringContainsString('7%', (new CartPercentageDiscount())->getTitle(['percent' => 7]));
    }

    #[Test] public function the_title_warns_if_the_percent_configuration_is_missing()
    {
        $this->assertStringContainsString('Invalid', (new CartPercentageDiscount())->getTitle([]));
    }

    #[Test] public function it_returns_a_percent_discount_adjuster_if_the_configuration_is_correct()
    {
        $this->assertInstanceOf(PercentDiscount::class, (new CartPercentageDiscount())->getAdjuster(['percent' => 20]));
    }

    #[Test] public function it_adds_a_promotion_adjustment_to_the_subject_if_applied()
    {
        $discount = new CartPercentageDiscount();
        $subject = new SampleAdjustable(179);

        $discount->apply($subject, ['percent' => 10]);

        $this->assertCount(1, $subject->adjustments());
        $adjustment = $subject->adjustments()->first();
        $this->assertInstanceOf(PercentDiscount::class, $adjustment->getAdjuster());
        $this->assertEquals(-17.9, $adjustment->getAmount());
    }
}
