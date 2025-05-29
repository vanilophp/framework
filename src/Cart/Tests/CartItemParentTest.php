<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Cart\Contracts\CartItem;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Tests\Dummies\Course;
use Vanilo\Cart\Tests\Dummies\Product;
use Vanilo\Cart\Tests\TestCase;

class CartItemParentTest extends TestCase
{
    private Product $product;

    private Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::create([
            'name' => 'Zen Garden',
            'price' => 30,
        ]);

        $this->course = Course::create([
            'title' => 'Course Title',
            'price' => 40,
        ]);
    }

    #[Test] public function the_parent_item_id_can_be_passed_as_a_parameter_to_add_to_cart_method()
    {
        $mainItem = Cart::addItem($this->course);
        $subItem = Cart::addItem($this->product, 1, ['attributes' => ['parent_id' => $mainItem->id]]);

        $subItem->refresh();

        $this->assertEquals($mainItem->id, $subItem->parent_id);
    }

    #[Test] public function an_item_with_parent_can_be_added_via_the_dedicated_method()
    {
        $mainItem = Cart::addItem($this->course);
        $subItem = Cart::addSubItem($mainItem, $this->product);

        $subItem->refresh();

        $this->assertEquals($mainItem->id, $subItem->parent_id);
    }

    #[Test] public function the_parent_item_can_be_retrieved_as_a_model()
    {
        $mainItem = Cart::addItem($this->course);
        $subItem = Cart::addSubItem($mainItem, $this->product);

        $subItem->refresh();

        $this->assertTrue($subItem->hasParent());
        $parentItem = $subItem->getParent();
        $this->assertInstanceOf(CartItem::class, $parentItem);
        $this->assertEquals($mainItem->id, $parentItem->id);
    }
}
