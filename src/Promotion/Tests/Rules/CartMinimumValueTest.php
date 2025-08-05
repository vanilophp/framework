<?php

declare(strict_types=1);

/**
 * Contains the CartMinimumValueTest class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-08-26
 *
 */

namespace Vanilo\Promotion\Tests\Rules;

use Nette\Schema\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Promotion\PromotionRuleTypes;
use Vanilo\Promotion\Rules\CartMinimumValue;
use Vanilo\Promotion\Tests\Examples\DummyAdjustableCart;
use Vanilo\Promotion\Tests\Examples\DummyCart;
use Vanilo\Promotion\Tests\TestCase;

class CartMinimumValueTest extends TestCase
{
    #[Test] public function it_can_be_created()
    {
        $ruleType = PromotionRuleTypes::make(CartMinimumValue::DEFAULT_ID);

        $this->assertInstanceOf(CartMinimumValue::class, $ruleType);
    }

    #[Test] public function it_throws_an_exception_if_the_configuration_is_empty()
    {
        $this->expectException(ValidationException::class);
        $rule = PromotionRuleTypes::make(CartMinimumValue::DEFAULT_ID);

        $rule->isPassing(new DummyCart(), []);
    }

    #[Test] public function it_passes_if_the_rule_is_valid_and_the_items_total_is_above_the_threshold()
    {
        $rule = new CartMinimumValue();

        $this->assertTrue($rule->isPassing(new DummyCart(1, 20), ['amount' => 19.99]));
    }

    #[Test] public function it_passes_if_the_items_total_equals_the_threshold()
    {
        $rule = new CartMinimumValue();

        $this->assertTrue($rule->isPassing(new DummyCart(1, 19.99), ['amount' => 19.99]));
    }

    #[Test] public function it_does_not_pass_if_the_items_total_is_below_the_threshold()
    {
        $rule = new CartMinimumValue();

        $this->assertFalse($rule->isPassing(new DummyCart(1, 19.98), ['amount' => 19.99]));
    }

    #[Test] public function it_passes_if_the_pre_adjustment_total_is_above_the_threshold()
    {
        $rule = new CartMinimumValue();

        $this->assertTrue($rule->isPassing(new DummyAdjustableCart(20), ['amount' => 19.99]));
    }

    #[Test] public function it_passes_if_the_pre_adjustment_total_equals_the_threshold()
    {
        $rule = new CartMinimumValue();

        $this->assertTrue($rule->isPassing(new DummyAdjustableCart(19.99), ['amount' => 19.99]));
    }

    #[Test] public function it_does_not_pass_if_the_pre_adjustment_total_is_below_the_threshold()
    {
        $rule = new CartMinimumValue();

        $this->assertFalse($rule->isPassing(new DummyAdjustableCart(19.98), ['amount' => 19.99]));
    }
}
