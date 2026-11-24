<?php

declare(strict_types=1);

/**
 * Contains the EmbeddedTaxDeductionTest class.
 *
 * @copyright   Copyright (c) 2026 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2026-11-24
 *
 */

namespace Vanilo\Adjustments\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Adjustments\Adjusters\EmbeddedTaxDeduction;
use Vanilo\Adjustments\Adjusters\SimpleDiscount;
use Vanilo\Adjustments\Models\AdjustmentType;
use Vanilo\Adjustments\Tests\Examples\Order;
use Vanilo\Adjustments\Tests\TestCase;

class EmbeddedTaxDeductionTest extends TestCase
{
    #[Test] public function it_deducts_embedded_tax_from_a_gross_order_total()
    {
        $order = Order::create(['items_total' => 119]);
        $adjustment = $order->adjustments()->create(new EmbeddedTaxDeduction(19));

        $this->assertCount(1, $order->adjustments());
        $this->assertTrue($adjustment->getType()->equals(AdjustmentType::TAX()));
        $this->assertTrue($adjustment->isCredit());
        $this->assertFalse($adjustment->isCharge());
        $this->assertFalse($adjustment->isIncluded());
        $this->assertTrue($adjustment->isNotIncluded());
        $this->assertEquals(-19, $adjustment->getAmount());
        $this->assertEquals(-19, $order->adjustments()->total());
        $this->assertEquals(100, $order->total());
    }

    #[Test] public function it_extracts_embedded_tax_with_rounding_instead_of_applying_a_net_percentage()
    {
        $order = Order::create(['items_total' => 100]);
        $adjustment = $order->adjustments()->create(new EmbeddedTaxDeduction(20));

        // 20% embedded VAT in 100 gross is 100 / 120 * 20 = 16.67 (not 20)
        $this->assertEquals(-16.67, $adjustment->getAmount());
        $this->assertEquals(-16.67, $order->adjustments()->total());
        $this->assertEquals(83.33, $order->total());
    }

    #[Test] public function it_supports_fractional_tax_rates()
    {
        $order = Order::create(['items_total' => 108.10]);
        $adjustment = $order->adjustments()->create(new EmbeddedTaxDeduction(8.1));

        $this->assertEquals(-8.1, $adjustment->getAmount());
        $this->assertEquals(100, $order->total());
    }

    #[Test] public function it_deducts_zero_when_the_rate_is_zero()
    {
        $order = Order::create(['items_total' => 100]);
        $adjustment = $order->adjustments()->create(new EmbeddedTaxDeduction(0));

        $this->assertEquals(0, $adjustment->getAmount());
        $this->assertFalse($adjustment->isCredit());
        $this->assertFalse($adjustment->isCharge());
        $this->assertEquals(100, $order->total());
    }

    #[Test] public function it_stores_the_rate_and_reproduces_the_adjuster_from_an_adjustment()
    {
        $order = Order::create(['items_total' => 119]);
        $adjustment = $order->adjustments()->create(new EmbeddedTaxDeduction(19));

        $this->assertEquals(19, $adjustment->getData('rate'));

        $reproduced = $adjustment->getAdjuster();
        $this->assertInstanceOf(EmbeddedTaxDeduction::class, $reproduced);

        $anotherOrder = Order::create(['items_total' => 238]);
        $anotherAdjustment = $anotherOrder->adjustments()->create($reproduced);

        $this->assertEquals(-38, $anotherAdjustment->getAmount());
        $this->assertEquals(200, $anotherOrder->total());
    }

    #[Test] public function title_description_and_lock_state_can_be_configured()
    {
        $adjuster = new EmbeddedTaxDeduction(19);
        $adjuster->setTitle('VAT 19% Export Deduction');
        $adjuster->setDescription('Embedded domestic VAT removed for non-EU export');
        $adjuster->lock();

        $order = Order::create(['items_total' => 119]);
        $adjustment = $order->adjustments()->create($adjuster);

        $this->assertEquals('VAT 19% Export Deduction', $adjustment->getTitle());
        $this->assertEquals('Embedded domestic VAT removed for non-EU export', $adjustment->getDescription());
        $this->assertTrue($adjustment->isLocked());
    }

    #[Test] public function it_is_unlocked_by_default()
    {
        $order = Order::create(['items_total' => 119]);
        $adjustment = $order->adjustments()->create(new EmbeddedTaxDeduction(19));

        $this->assertFalse($adjustment->isLocked());
    }

    #[Test] public function recalculating_without_changes_is_idempotent()
    {
        $order = Order::create(['items_total' => 119]);
        $adjustment = $order->adjustments()->create(new EmbeddedTaxDeduction(19));

        $this->assertEquals(-19, $adjustment->getAmount());
        $this->assertEquals(100, $order->total());

        $adjustment->getAdjuster()->recalculate($adjustment, $order);
        $adjustment->getAdjuster()->recalculate($adjustment, $order);

        $this->assertEquals(-19, $adjustment->getAmount());
        $this->assertEquals(-19, $order->adjustments()->total());
        $this->assertEquals(100, $order->total());
    }

    #[Test] public function recalculating_updates_the_deduction_when_the_order_total_changes()
    {
        $order = Order::create(['items_total' => 119]);
        $adjustment = $order->adjustments()->create(new EmbeddedTaxDeduction(19));

        $this->assertEquals(-19, $adjustment->getAmount());
        $this->assertEquals(100, $order->total());

        $order->items_total = 238;
        $order->save();

        $adjustment->getAdjuster()->recalculate($adjustment, $order);

        $order->invalidateAdjustments();
        $this->assertEquals(-38, $adjustment->getAmount());
        $this->assertEquals(-38, $order->adjustments()->total());
        $this->assertEquals(200, $order->total());
    }

    #[Test] public function it_calculates_embedded_tax_on_the_discounted_gross_total_and_stays_idempotent_on_recalculation()
    {
        $order = Order::create(['items_total' => 238]);
        $order->adjustments()->create(new SimpleDiscount(119));
        $taxDeduction = $order->adjustments()->create(new EmbeddedTaxDeduction(19));

        // 238 gross - 119 discount = 119 gross -> embedded 19% VAT = -19 -> 100 net
        $this->assertEquals(-19, $taxDeduction->getAmount());
        $this->assertEquals(100, $order->total());

        $taxDeduction->getAdjuster()->recalculate($taxDeduction, $order);

        $this->assertEquals(-19, $taxDeduction->getAmount());
        $this->assertEquals(100, $order->total());
    }
}
