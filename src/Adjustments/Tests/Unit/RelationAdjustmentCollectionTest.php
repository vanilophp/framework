<?php

declare(strict_types=1);

/**
 * Contains the RelationAdjustmentCollectionTest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-29
 *
 */

namespace Vanilo\Adjustments\Tests\Unit;

use Countable;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use stdClass;
use Vanilo\Adjustments\Adjusters\SimpleFee;
use Vanilo\Adjustments\Adjusters\SimpleShippingFee;
use Vanilo\Adjustments\Contracts\AdjustmentCollection;
use Vanilo\Adjustments\Models\Adjustment;
use Vanilo\Adjustments\Models\AdjustmentType;
use Vanilo\Adjustments\Support\RelationAdjustmentCollection;
use Vanilo\Adjustments\Tests\Examples\Order;
use Vanilo\Adjustments\Tests\TestCase;

class RelationAdjustmentCollectionTest extends TestCase
{
    #[Test] public function it_implements_the_interface()
    {
        $this->assertInstanceOf(
            AdjustmentCollection::class,
            new RelationAdjustmentCollection(new Order())
        );
    }

    #[Test] public function it_is_empty_by_default()
    {
        $c = new RelationAdjustmentCollection(new Order());

        $this->assertTrue($c->isEmpty());
        $this->assertEmpty($c);
        $this->assertFalse($c->isNotEmpty());
    }

    #[Test] public function it_is_countable()
    {
        $c = new RelationAdjustmentCollection(new Order());

        $this->assertInstanceOf(Countable::class, $c);
        $this->assertCount(0, $c);
    }

    #[Test] public function items_can_be_added_to_it_using_adjusters()
    {
        $order = Order::create(['items_total' => 11]);
        $collection = new RelationAdjustmentCollection($order);

        $collection->create(new SimpleShippingFee(3.99));
        $this->assertCount(1, $collection);
        $this->assertFalse($collection->isEmpty());
        $this->assertTrue($collection->isNotEmpty());
    }

    #[Test] public function items_can_be_added_to_it()
    {
        $order = Order::create(['items_total' => 15]);
        $collection = new RelationAdjustmentCollection($order);

        $collection->add($this->makeAnAdjustment());
        $this->assertCount(1, $collection);
        $this->assertFalse($collection->isEmpty());
        $this->assertTrue($collection->isNotEmpty());

        $collection->add($this->makeAnAdjustment());
        $this->assertCount(2, $collection);
    }

    #[Test] public function an_item_can_be_removed_from_it()
    {
        $collection = new RelationAdjustmentCollection(
            Order::create(['items_total' => 22])
        );

        $adjustment = $this->makeAnAdjustment();
        $collection->add($adjustment);
        $this->assertCount(1, $collection);

        $collection->remove($adjustment);
        $this->assertCount(0, $collection);
    }

    #[Test] public function the_total_can_be_retrieved()
    {
        $collection = new RelationAdjustmentCollection(
            Order::create(['items_total' => 224])
        );

        $collection->add($this->makeAnAdjustment(32.21));
        $collection->add($this->makeAnAdjustment(21.32));

        $this->assertEquals(53.53, $collection->total());
    }

    #[Test] public function the_total_excludes_non_included_elements_by_default()
    {
        $collection = new RelationAdjustmentCollection(
            Order::create(['items_total' => 224])
        );

        $collection->add($this->makeAnAdjustment(40, included: true));
        $collection->add($this->makeAnAdjustment(20, included: false));

        $this->assertEquals(20, $collection->total());
    }

    #[Test] public function the_total_included_adjustments_can_be_incorporated_in_the_total()
    {
        $collection = new RelationAdjustmentCollection(
            Order::create(['items_total' => 224])
        );

        $collection->add($this->makeAnAdjustment(13, included: true));
        $collection->add($this->makeAnAdjustment(21, included: false));

        $this->assertEquals(34, $collection->total(true));
    }

    #[Test] public function items_can_be_accessed_as_array_members()
    {
        $collection = new RelationAdjustmentCollection(
            Order::create(['items_total' => 39])
        );

        $collection->add($this->makeAnAdjustment(10, AdjustmentType::PROMOTION()));
        $collection->add($this->makeAnAdjustment(4.79, AdjustmentType::SHIPPING()));

        $this->assertInstanceOf(Adjustment::class, $collection[0]);
        $this->assertEquals(AdjustmentType::PROMOTION, $collection[0]->getType()->value());
        $this->assertEquals(10, $collection[0]->getAmount());

        $this->assertInstanceOf(Adjustment::class, $collection[1]);
        $this->assertEquals(AdjustmentType::SHIPPING, $collection[1]->getType()->value());
        $this->assertEquals(4.79, $collection[1]->getAmount());
    }

    #[Test] public function existence_of_array_items_can_be_checked_using_isset()
    {
        $collection = new RelationAdjustmentCollection(
            Order::create(['items_total' => 123])
        );

        $collection->add($this->makeAnAdjustment());
        $collection->add($this->makeAnAdjustment());

        $this->assertTrue(isset($collection[0]));
        $this->assertTrue(isset($collection[1]));
        $this->assertFalse(isset($collection[2]));
    }

    #[Test] public function items_can_directly_set_via_array_mutator()
    {
        $collection = new RelationAdjustmentCollection(
            Order::create(['items_total' => 444])
        );

        $collection[0] = new Adjustment();

        $this->assertInstanceOf(Adjustment::class, $collection[0]);
        $this->assertCount(1, $collection);
    }

    #[Test] public function only_adjustment_object_instances_are_accepted_in_the_array_mutator()
    {
        $collection = new RelationAdjustmentCollection(
            Order::create(['items_total' => 123])
        );

        $this->expectException(InvalidArgumentException::class);
        $collection[0] = new stdClass();
    }

    #[Test] public function items_can_be_unset()
    {
        $collection = new RelationAdjustmentCollection(
            Order::create(['items_total' => 59])
        );

        $collection->add($this->makeAnAdjustment(3));
        $collection->add($this->makeAnAdjustment(4));

        $this->assertCount(2, $collection);

        unset($collection[1]);
        $this->assertCount(1, $collection);
        $this->assertEquals(3, $collection[0]->getAmount());
    }

    #[Test] public function items_can_be_filtered_by_type()
    {
        $c = new RelationAdjustmentCollection(
            Order::create(['items_total' => 59])
        );

        $c->add($this->makeAnAdjustment(1, AdjustmentType::PROMOTION()));
        $c->add($this->makeAnAdjustment(1, AdjustmentType::PROMOTION()));
        $c->add($this->makeAnAdjustment(1, AdjustmentType::PROMOTION()));

        $c->add($this->makeAnAdjustment(1, AdjustmentType::TAX()));
        $c->add($this->makeAnAdjustment(1, AdjustmentType::TAX()));

        $c->add($this->makeAnAdjustment(1, AdjustmentType::SHIPPING()));

        $this->assertCount(6, $c);

        $promotions = $c->byType(AdjustmentType::PROMOTION());
        $this->assertInstanceOf(AdjustmentCollection::class, $promotions);
        $this->assertCount(3, $promotions);

        $taxes = $c->byType(AdjustmentType::TAX());
        $this->assertInstanceOf(AdjustmentCollection::class, $taxes);
        $this->assertCount(2, $taxes);

        $shippingFees = $c->byType(AdjustmentType::SHIPPING());
        $this->assertInstanceOf(AdjustmentCollection::class, $shippingFees);
        $this->assertCount(1, $shippingFees);
    }

    private function makeAnAdjustment(float $amount = 4.99, AdjustmentType $type = null, bool $included = false): Adjustment
    {
        return new Adjustment([
            'type' => $type ?? AdjustmentType::SHIPPING(),
            'adjuster' => SimpleFee::class,
            'origin' => null,
            'title' => 'Processing Fee',
            'description' => null,
            'data' => [],
            'amount' => $amount,
            'is_locked' => false,
            'is_included' => $included,
        ]);
    }
}
