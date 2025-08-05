<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Promotion\PromotionRuleTypes;
use Vanilo\Promotion\Tests\Examples\CartTotalRule;
use Vanilo\Promotion\Tests\Examples\NthOrderRule;

class PromotionRuleTypesTest extends TestCase
{
    #[Test] public function new_payments_can_be_registered()
    {
        $originalCount = count(PromotionRuleTypes::choices());
        PromotionRuleTypes::register('nt_order', NthOrderRule::class);
        PromotionRuleTypes::register('cart_total', CartTotalRule::class);

        $this->assertCount($originalCount + 2, PromotionRuleTypes::choices());
    }

    #[Test] public function registered_gateway_instances_can_be_returned()
    {
        PromotionRuleTypes::register('nt_order', NthOrderRule::class);

        $this->assertInstanceOf(NthOrderRule::class, PromotionRuleTypes::make('nt_order'));
    }

    #[Test] public function attempting_to_retrieve_an_unregistered_gateway_returns_null()
    {
        $this->assertNull(PromotionRuleTypes::getClassOf('randomness'));
    }

    #[Test] public function registering_a_gateway_without_implementing_the_interface_is_not_allowed()
    {
        $this->expectException(\InvalidArgumentException::class);
        PromotionRuleTypes::register('whatever', \stdClass::class);
    }
}
