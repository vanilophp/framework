<?php

declare(strict_types=1);

/**
 * Contains the CartItemConfigurationTest class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-03-19
 *
 */

namespace Vanilo\Cart\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Tests\Dummies\Product;

class CartItemConfigurationTest extends TestCase
{
    #[Test] public function a_cart_items_configuration_can_be_saved()
    {
        $product = Product::create([
            'name' => 'Tasty Burger',
            'price' => 12.99
        ]);

        $item = Cart::addItem($product, 1, ['attributes' => [
            'configuration' => ['extra_cheese', 'bacon'],
        ]]);

        $this->assertEquals(['extra_cheese', 'bacon'], $item->configuration());
    }

    #[Test] public function when_adding_a_product_that_has_a_different_configuration_then_two_separate_cart_items_will_be_added()
    {
        $product = Product::create([
            'name' => 'Eleven Burger',
            'price' => 17.99
        ]);

        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['extra_coleslaw']]]);
        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['no_pommes', 'extra_coleslaw']]]);

        $this->assertCount(2, Cart::getItems());
        $this->assertEquals(['extra_coleslaw'], Cart::getItems()->first()->configuration());
        $this->assertEquals(['no_pommes', 'extra_coleslaw'], Cart::getItems()->last()->configuration());
    }

    #[Test] public function when_adding_a_product_where_one_has_an_associative_array_config_and_the_other_one_a_list_then_two_separate_items_will_be_created()
    {
        $product = Product::create([
            'name' => 'Juicy Lucy',
            'price' => 15.99
        ]);

        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['extra_coleslaw']]]);
        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['pommes' => false, 'bacon' => 2]]]);

        $this->assertCount(2, Cart::getItems());
        $this->assertEquals(['extra_coleslaw'], Cart::getItems()->first()->configuration());
        $this->assertEquals(['pommes' => false, 'bacon' => 2], Cart::getItems()->last()->configuration());

        Cart::clear();

        // Now doing it in the opposite order

        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['pommes' => false, 'bacon' => 2]]]);
        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['extra_coleslaw']]]);

        $this->assertCount(2, Cart::getItems());
        $this->assertEquals(['pommes' => false, 'bacon' => 2], Cart::getItems()->first()->configuration());
        $this->assertEquals(['extra_coleslaw'], Cart::getItems()->last()->configuration());
    }

    #[Test] public function it_creates_one_item_if_the_configurations_match()
    {
        $product = Product::create([
            'name' => 'Italian Burger',
            'price' => 16.99
        ]);

        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['extra_coleslaw']]]);
        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['extra_coleslaw']]]);

        $this->assertCount(1, Cart::getItems());
        $this->assertEquals(['extra_coleslaw'], Cart::getItems()->first()->configuration());
    }

    #[Test] public function it_groups_configurationless_entries_and_keeps_configured_ones_separated()
    {
        $product = Product::create([
            'name' => 'Wendy\'s Burger',
            'price' => 16.99
        ]);

        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['extra_coleslaw']]]);
        Cart::addItem($product);
        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['extra_coleslaw']]]);
        Cart::addItem($product);
        Cart::addItem($product);
        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['extra_coleslaw']]]);
        Cart::addItem($product);

        $this->assertCount(2, Cart::getItems());
        $this->assertEquals(['extra_coleslaw'], Cart::getItems()->first()->configuration());
        $this->assertEquals(3, Cart::getItems()->first()->getQuantity());
        $this->assertEquals([], Cart::getItems()->last()->configuration());
        $this->assertEquals(4, Cart::getItems()->last()->getQuantity());
    }

    #[Test] public function it_distinguishes_configurations_across_multiple_add_to_cart_calls()
    {
        $product = Product::create([
            'name' => 'McFarm',
            'price' => 12.99
        ]);

        Cart::addItem($product);
        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['double_meat']]]);
        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['mustard_sauce' => false]]]);
        Cart::addItem($product);
        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['mustard_sauce' => false]]]);
        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['mustard_sauce' => false]]]);
        Cart::addItem($product);
        Cart::addItem($product, 1, ['attributes' => ['configuration' => ['double_meat']]]);

        $this->assertCount(3, Cart::getItems());

        $this->assertEquals([], Cart::getItems()->get(0)->configuration());
        $this->assertEquals(3, Cart::getItems()->get(0)->getQuantity());

        $this->assertEquals(['double_meat'], Cart::getItems()->get(1)->configuration());
        $this->assertEquals(2, Cart::getItems()->get(1)->getQuantity());

        $this->assertEquals(['mustard_sauce' => false], Cart::getItems()->get(2)->configuration());
        $this->assertEquals(3, Cart::getItems()->get(2)->getQuantity());
    }
}
