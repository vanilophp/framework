<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests\Rules;

use Nette\Schema\ValidationException;
use Vanilo\Promotion\PromotionRuleTypes;
use Vanilo\Promotion\Rules\CartQuantity;
use Vanilo\Promotion\Tests\Examples\DummyCart;
use Vanilo\Promotion\Tests\TestCase;

class CartQuantityTest extends TestCase
{
    /** @test */
    public function can_be_created()
    {
        PromotionRuleTypes::register(CartQuantity::getID(), CartQuantity::class);

        $rule = PromotionRuleTypes::make(CartQuantity::getID());

        $this->assertInstanceOf(CartQuantity::class, $rule);
    }

    /** @test */
    public function throws_exception_if_configuration_is_wrong()
    {
        $this->expectException(ValidationException::class);
        PromotionRuleTypes::register(CartQuantity::getID(), CartQuantity::class);
        $rule = PromotionRuleTypes::make(CartQuantity::getID());

        $this->assertFalse($rule->isPassing(new DummyCart()));
    }

    /** @test */
    public function passes_if_rule_is_valid()
    {
        PromotionRuleTypes::register(CartQuantity::getID(), CartQuantity::class);
        $ruleA = PromotionRuleTypes::make(CartQuantity::getID())->setConfiguration(['count' => 3]);
        $ruleB = PromotionRuleTypes::make(CartQuantity::getID())->setConfiguration(['count' => 6]);

        $this->assertFalse($ruleA->isPassing(new DummyCart()));
        $this->assertTrue($ruleB->isPassing(new DummyCart()));
    }
}

