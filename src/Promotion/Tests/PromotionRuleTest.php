<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Tests;

use Nette\Schema\ValidationException;
use Vanilo\Promotion\Models\Promotion;
use Vanilo\Promotion\Models\PromotionRule;
use Vanilo\Promotion\Rules\CartQuantity;
use Vanilo\Promotion\Tests\Examples\DummyCart;
use Vanilo\Promotion\Tests\Factories\PromotionFactory;

class PromotionRuleTest extends TestCase
{
    /** @test */
    public function it_can_be_created_with_minimal_data()
    {
        $rule = PromotionRule::create(
            ['type' => 'awesome', 'promotion_id' => PromotionFactory::new()->create()->id]
        );

        $this->assertInstanceOf(PromotionRule::class, $rule);
        $this->assertInstanceOf(Promotion::class, $rule->promotion);
        $this->assertEquals('awesome', $rule->type);
    }

    /** @test */
    public function it_can_store_and_retrieve_configuration()
    {
        $rule = PromotionRule::create(
            [
                'type' => 'awesome',
                'promotion_id' => PromotionFactory::new()->create()->id,
                'configuration' => ['count' => 10],
            ]
        );

        $this->assertEquals(['count' => 10], $rule->configuration());
    }

    /** @test */
    public function it_can_run_the_type_passing()
    {
        $ruleA = PromotionRule::create(
            [
                'type' => CartQuantity::getID(),
                'promotion_id' => PromotionFactory::new()->create()->id,
                'configuration' => ['count' => 10],
            ]
        );

        $ruleB = PromotionRule::create(
            [
                'type' => CartQuantity::getID(),
                'promotion_id' => PromotionFactory::new()->create()->id,
                'configuration' => ['count' => 3],
            ]
        );

        $this->assertEquals(['count' => 10], $ruleA->configuration());
        $this->assertTrue($ruleA->isRuleTypPassing(new DummyCart()));

        $this->assertEquals(['count' => 3], $ruleB->configuration());
        $this->assertFalse($ruleB->isRuleTypPassing(new DummyCart()));
    }

    /** @test */
    public function throws_exception_if_configuration_needed_but_its_not_there()
    {
        $this->expectException(ValidationException::class);

        $rule = PromotionRule::create(
            ['type' => CartQuantity::getID(), 'promotion_id' => PromotionFactory::new()->create()->id]
        );

        $rule->isRuleTypPassing(new DummyCart());
    }
}
