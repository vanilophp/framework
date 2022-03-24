<?php

declare(strict_types=1);

/**
 * Contains the AdjustmentTest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-27
 *
 */

namespace Vanilo\Adjustments\Tests\Unit;

use Vanilo\Adjustments\Adjusters\SimpleShippingFee;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Adjustments\Contracts\Adjuster;
use Vanilo\Adjustments\Contracts\Adjustment as AdjustmentContract;
use Vanilo\Adjustments\Models\Adjustment;
use Vanilo\Adjustments\Models\AdjustmentType;
use Vanilo\Adjustments\Tests\Examples\Order;
use Vanilo\Adjustments\Tests\TestCase;

class AdjustmentTest extends TestCase
{
    /** @test */
    public function it_can_be_created_with_minimal_fields()
    {
        $adjustment = Adjustment::create([
            'type' => AdjustmentType::TAX,
            'adjustable_type' => 'order',
            'adjustable_id' => 1,
            'adjuster' => 'fixed_amount',
            'title' => 'Sales tax',
        ]);

        $this->assertInstanceOf(Adjustment::class, $adjustment);
        $this->assertInstanceOf(AdjustmentContract::class, $adjustment);
    }

    /** @test */
    public function the_type_field_is_an_enum()
    {
        $adjustment = Adjustment::create([
            'type' => AdjustmentType::TAX,
            'adjustable_type' => 'order',
            'adjustable_id' => 1,
            'adjuster' => 'fixed_amount',
            'title' => 'Sales tax',
        ]);

        $this->assertInstanceOf(AdjustmentType::class, $adjustment->type);
        $this->assertTrue($adjustment->type->equals(AdjustmentType::TAX()));
    }

    /** @test */
    public function is_not_locked_by_default()
    {
        $adjustment = Adjustment::create([
            'type' => AdjustmentType::TAX,
            'adjustable_type' => 'order',
            'adjustable_id' => 1,
            'adjuster' => 'fixed_amount',
            'title' => 'Sales tax',
        ])->fresh();

        $this->assertFalse($adjustment->is_locked);
    }

    /** @test */
    public function is_not_included_by_default()
    {
        $adjustment = Adjustment::create([
            'type' => AdjustmentType::TAX,
            'adjustable_type' => 'order',
            'adjustable_id' => 1,
            'adjuster' => 'fixed_amount',
            'title' => 'Sales tax',
        ])->fresh();

        $this->assertFalse($adjustment->is_included);
    }

    /** @test */
    public function all_mutable_fields_can_be_mass_assigned()
    {
        $adjustment = Adjustment::create([
            'type' => AdjustmentType::SHIPPING,
            'adjustable_type' => 'order',
            'adjustable_id' => 22791,
            'adjuster' => 'fixed_amount',
            'origin' => 'xgs123',
            'title' => 'Shipping',
            'description' => 'UPS Ground Delivery (1-3 days)',
            'data' => ['yo' => 'mo', 'do' => 'jo'],
            'amount' => 3.99,
            'is_locked' => true,
            'is_included' => true,
        ]);

        $this->assertEquals(AdjustmentType::SHIPPING, $adjustment->type->value());
        $this->assertEquals('order', $adjustment->adjustable_type);
        $this->assertEquals(22791, $adjustment->adjustable_id);
        $this->assertEquals('fixed_amount', $adjustment->adjuster);
        $this->assertEquals('xgs123', $adjustment->origin);
        $this->assertEquals('Shipping', $adjustment->title);
        $this->assertEquals('UPS Ground Delivery (1-3 days)', $adjustment->description);
        $this->assertEquals(['yo' => 'mo', 'do' => 'jo'], $adjustment->data);
        $this->assertEquals(3.99, $adjustment->amount);
        $this->assertTrue($adjustment->is_locked);
        $this->assertTrue($adjustment->is_included);
    }

    /** @test */
    public function all_mutable_fields_can_be_set()
    {
        $adjustment = new Adjustment();

        $adjustment->type = AdjustmentType::PROMOTION;
        $adjustment->adjustable_type = 'order_item';
        $adjustment->adjustable_id = 225487;
        $adjustment->adjuster = 'fixed_amount';
        $adjustment->origin = 555;
        $adjustment->title = 'Discount';
        $adjustment->description = 'Boxing Day Sale';
        $adjustment->data = ['be' => 'ba', 'bu' => 'bi'];
        $adjustment->amount = 11;
        $adjustment->is_locked = true;
        $adjustment->is_included = true;
        $adjustment->save();

        $adjustment = $adjustment->fresh();

        $this->assertEquals(AdjustmentType::PROMOTION, $adjustment->type->value());
        $this->assertEquals('order_item', $adjustment->adjustable_type);
        $this->assertEquals(225487, $adjustment->adjustable_id);
        $this->assertEquals('fixed_amount', $adjustment->adjuster);
        $this->assertEquals('555', $adjustment->origin);
        $this->assertEquals('Discount', $adjustment->title);
        $this->assertEquals('Boxing Day Sale', $adjustment->description);
        $this->assertIsArray($adjustment->data);
        $this->assertEquals(['be' => 'ba', 'bu' => 'bi'], $adjustment->data);
        $this->assertEquals(11, $adjustment->amount);
        $this->assertTrue($adjustment->is_locked);
        $this->assertTrue($adjustment->is_included);
    }

    /** @test */
    public function the_interface_getters_are_working_on_the_model()
    {
        /** @var Adjustment $adjustment */
        $adjustment = Adjustment::create([
            'type' => AdjustmentType::TAX,
            'adjustable_type' => 'order',
            'adjustable_id' => 15496,
            'adjuster' => 'fixed_amount',
            'origin' => 'MMM',
            'title' => 'VAT',
            'description' => 'VAT 21% (NL)',
            'data' => ['buff' => 'muff'],
            'amount' => 21.22,
            'is_locked' => true,
            'is_included' => true,
        ]);

        $this->assertTrue($adjustment->getType()->isTax());
        $this->assertEquals('MMM', $adjustment->getOrigin());
        $this->assertEquals('VAT', $adjustment->getTitle());
        $this->assertEquals('VAT 21% (NL)', $adjustment->getDescription());
        $this->assertEquals(['buff' => 'muff'], $adjustment->getData());
        $this->assertEquals(21.22, $adjustment->getAmount());
        $this->assertTrue($adjustment->isLocked());
        $this->assertTrue($adjustment->isIncluded());
    }

    /** @test */
    public function data_fields_can_be_obtained_using_the_dot_notation()
    {
        $adjustment = Adjustment::create([
            'type' => AdjustmentType::PROMOTION,
            'adjustable_type' => 'order',
            'adjustable_id' => 1,
            'adjuster' => 'fixed_amount',
            'title' => 'Sales tax',
            'data' => ['percent' => 10, 'customer' => ['group' => 1, 'type' => 'gov']]
        ]);

        $this->assertEquals(10, $adjustment->getData('percent'));
        $this->assertEquals(1, $adjustment->getData('customer.group'));
        $this->assertEquals('gov', $adjustment->getData('customer.type'));
        $this->assertEquals(['group' => 1, 'type' => 'gov'], $adjustment->getData('customer'));
        $this->assertNull($adjustment->getData('meow'));
    }

    /** @test */
    public function it_can_be_locked()
    {
        $adjustment = Adjustment::create([
            'type' => AdjustmentType::TAX,
            'adjustable_type' => 'order',
            'adjustable_id' => 1,
            'adjuster' => 'fixed_amount',
            'title' => 'Sales tax',
        ]);

        $this->assertFalse($adjustment->isLocked());
        $adjustment->lock();

        $this->assertTrue($adjustment->isLocked());

        $adjustment = $adjustment->fresh();
        $this->assertTrue($adjustment->isLocked());
    }

    /** @test */
    public function it_can_be_unlocked()
    {
        $adjustment = Adjustment::create([
            'type' => AdjustmentType::TAX,
            'adjustable_type' => 'order',
            'adjustable_id' => 1,
            'adjuster' => 'fixed_amount',
            'title' => 'Sales tax',
            'is_locked' => true,
        ]);

        $this->assertTrue($adjustment->isLocked());
        $adjustment->unlock();

        $this->assertFalse($adjustment->isLocked());

        $adjustment = $adjustment->fresh();
        $this->assertFalse($adjustment->isLocked());
    }

    /** @test */
    public function it_resolves_the_adjustable_if_the_adjustable_type_is_a_fqcn()
    {
        $order = Order::create(['items_total' => 120]);

        /** @var Adjustment $adjustment */
        $adjustment = Adjustment::create([
            'type' => AdjustmentType::TAX,
            'adjustable_type' => Order::class,
            'adjustable_id' => $order->id,
            'adjuster' => 'fixed_amount',
            'title' => 'Sales tax',
            'is_locked' => true,
        ]);

        $this->assertInstanceOf(Adjustable::class, $adjustment->getAdjustable());
        $this->assertInstanceOf(Order::class, $adjustment->getAdjustable());
    }

    /** @test */
    public function it_resolves_the_adjuster_if_the_adjuster_is_a_fqcn()
    {
        $order = Order::create(['items_total' => 120]);
        $adjustment = Adjustment::create([
            'type' => AdjustmentType::TAX,
            'adjustable_type' => Order::class,
            'adjustable_id' => $order->id,
            'adjuster' => SimpleShippingFee::class,
            'title' => 'Sales tax',
            'is_locked' => true,
        ]);

        $this->assertInstanceOf(Adjuster::class, $adjustment->getAdjuster());
        $this->assertInstanceOf(SimpleShippingFee::class, $adjustment->getAdjuster());
    }

    /** @test */
    public function it_is_a_charge_if_it_increases_the_total()
    {
        $adjustment = Adjustment::create([
            'type' => AdjustmentType::TAX,
            'adjustable_type' => 'order',
            'adjustable_id' => 1,
            'adjuster' => 'fixed_amount',
            'title' => 'Sales tax',
        ]);

        $this->assertInstanceOf(Adjustment::class, $adjustment);
        $this->assertInstanceOf(AdjustmentContract::class, $adjustment);
    }
}
