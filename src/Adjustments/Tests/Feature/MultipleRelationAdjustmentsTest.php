<?php

declare(strict_types=1);

namespace Feature;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Adjustments\Adjusters\PercentDiscount;
use Vanilo\Adjustments\Adjusters\SimpleTax;
use Vanilo\Adjustments\Models\AdjustmentType;
use Vanilo\Adjustments\Tests\Examples\Order;
use Vanilo\Adjustments\Tests\TestCase;

class MultipleRelationAdjustmentsTest extends TestCase
{
    #[Test] public function discounts_are_taken_into_account_when_calculating_tax()
    {
        $order = Order::create(['items_total' => 18.69]);
        $order->adjustments()->create(new PercentDiscount(22.4));
        $order->adjustments()->create(new SimpleTax(7, false));

        $this->assertCount(2, $order->adjustments());
        $this->assertEquals(-4.19, $order->adjustments()->byType(AdjustmentType::PROMOTION())->total());
        $this->assertEquals(1.02, $order->adjustments()->byType(AdjustmentType::TAX())->total());
        $this->assertEquals(15.52, $order->total());
    }

    #[Test] public function deleting_adjustments_by_type_works_as_expected()
    {
        $order = Order::create(['items_total' => 18.69]);
        $order->adjustments()->create(new PercentDiscount(22.4));
        $order->adjustments()->create(new SimpleTax(7, false));

        // See the initial discount and tax are applied properly:
        $this->assertCount(2, $order->adjustments());
        $this->assertEquals(-4.19, $order->adjustments()->byType(AdjustmentType::PROMOTION())->total());
        $this->assertEquals(1.02, $order->adjustments()->byType(AdjustmentType::TAX())->total());
        $this->assertEquals(15.52, $order->total());

        // Changing tax rate:
        $order->adjustments()->deleteByType(AdjustmentType::TAX());
        $this->assertCount(1, $order->adjustments());
        $order->adjustments()->create(new SimpleTax(11, false));

        // See the tax and the discount are recalculated, and correct:
        $this->assertCount(2, $order->adjustments());
        $this->assertEquals(-4.19, $order->adjustments()->byType(AdjustmentType::PROMOTION())->total());
        $this->assertEquals(1.60, $order->adjustments()->byType(AdjustmentType::TAX())->total());
        $this->assertEquals(16.10, $order->total());
    }
}
