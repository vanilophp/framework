<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests\Rules;

use Nette\Schema\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Promotion\PromotionRuleTypes;
use Vanilo\Promotion\Rules\CartQuantity;
use Vanilo\Promotion\Tests\Examples\DummyCart;
use Vanilo\Promotion\Tests\TestCase;

class CartQuantityTest extends TestCase
{
    #[Test] public function can_be_created()
    {
        $ruleType = PromotionRuleTypes::make(CartQuantity::ID);

        $this->assertInstanceOf(CartQuantity::class, $ruleType);
    }

    #[Test] public function throws_exception_if_configuration_is_wrong()
    {
        $this->expectException(ValidationException::class);
        $cartQuantityRule = PromotionRuleTypes::make(CartQuantity::ID);

        $cartQuantityRule->isPassing(new DummyCart(), ['wrong' => 'config']);
    }

    #[Test] public function passes_if_rule_is_valid()
    {
        $cartQuantityRuleType = PromotionRuleTypes::make(CartQuantity::ID);

        $this->assertTrue($cartQuantityRuleType->isPassing(new DummyCart(4), ['count' => 3]));
        $this->assertFalse($cartQuantityRuleType->isPassing(new DummyCart(6), ['count' => 7]));
    }
}
