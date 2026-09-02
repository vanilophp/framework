<?php

declare(strict_types=1);

namespace Vanilo\Order\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Order\Models\Order;
use Vanilo\Order\Models\OrderItem;
use Vanilo\Order\Tests\Dummies\Product;

class OrderItemParentTest extends TestCase
{
    private Product $product1;

    private Product $product2;

    private Product $product3;

    public function setUp(): void
    {
        parent::setUp();

        $this->product1 = Product::create([
            'name' => 'Zen Garden',
            'price' => 30,
        ]);

        $this->product2 = Product::create([
            'name' => 'Karesansui Niwa',
            'price' => 40,
        ]);

        $this->product3 = Product::create([
            'name' => 'Zhejiang Hanshen',
            'price' => 50,
        ]);
    }

    #[Test] public function the_parent_item_id_can_be_passed_as_a_parameter_to_add_to_order_method()
    {
        $order = Order::create([
            'number' => 'FVJH8'
        ]);

        $item1 = $order->items()->create([
            'product_type' => 'product',
            'product_id' => $this->product3->getId(),
            'quantity' => 1,
            'name' => $this->product3->getName(),
            'price' => $this->product3->getPrice()
        ]);

        $order->items()->create([
            'product_type' => 'product',
            'product_id' => $this->product2->getId(),
            'quantity' => 1,
            'name' => $this->product2->getName(),
            'price' => $this->product2->getPrice(),
            'parent_id' => $item1->id,
        ]);

        // Re-fetch from DB
        $order = $order->fresh();

        $mainItem = $order->items->where('product_id', $this->product3->getId())->first();
        $subItem = $order->items->where('product_id', $this->product2->getId())->first();

        $this->assertEquals($mainItem->id, $subItem->parent_id);
    }

    #[Test] public function the_parent_item_can_be_retrieved_as_a_model()
    {
        $order = Order::create([
            'number' => 'FVJH8'
        ]);

        $item1 = $order->items()->create([
            'product_type' => 'product',
            'product_id' => $this->product2->getId(),
            'quantity' => 1,
            'name' => $this->product2->getName(),
            'price' => $this->product2->getPrice()
        ]);

        $order->items()->create([
            'product_type' => 'product',
            'product_id' => $this->product1->getId(),
            'quantity' => 1,
            'name' => $this->product1->getName(),
            'price' => $this->product1->getPrice(),
            'parent_id' => $item1->id,
        ]);

        // Re-fetch from DB
        $order = $order->fresh();

        $mainItem = $order->items->where('product_id', $this->product2->getId())->first();
        $subItem = $order->items->where('product_id', $this->product1->getId())->first();

        $this->assertFalse($mainItem->hasParent());

        $this->assertTrue($subItem->hasParent());
        $parentItem = $subItem->getParent();
        $this->assertInstanceOf(OrderItem::class, $parentItem);
        $this->assertEquals($mainItem->id, $parentItem->id);
    }
}
