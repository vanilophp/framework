<?php

declare(strict_types=1);

/**
 * Contains the LinkGroupItemTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-16
 *
 */

namespace Vanilo\Links\Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Links\Contracts\LinkGroupItem as LinkGroupItemContract;
use Vanilo\Links\Models\LinkGroup;
use Vanilo\Links\Models\LinkGroupItem;
use Vanilo\Links\Models\LinkType;
use Vanilo\Links\Tests\Dummies\TestProduct;
use Vanilo\Links\Tests\TestCase;

class LinkGroupItemTest extends TestCase
{
    #[Test] public function it_can_be_created()
    {
        $item = LinkGroupItem::create([
            'link_group_id' => LinkGroup::create([
                'link_type_id' => LinkType::create(['name' => 'X-Sell'])->id,
            ])->id,
            'linkable_id' => TestProduct::create(['name' => 'Something'])->id,
            'linkable_type' => TestProduct::class,
        ])->fresh();

        $this->assertInstanceOf(LinkGroupItem::class, $item);
        $this->assertInstanceOf(LinkGroupItemContract::class, $item);
    }

    #[Test] public function the_parent_group_is_accessible_as_model()
    {
        $item = LinkGroupItem::create([
            'link_group_id' => LinkGroup::create([
                'link_type_id' => LinkType::create(['name' => 'Others are viewing'])->id,
            ])->id,
            'linkable_id' => TestProduct::create(['name' => 'Furry Bra'])->id,
            'linkable_type' => TestProduct::class,
        ]);

        $this->assertInstanceOf(LinkGroup::class, $item->group);
        $this->assertEquals('Others are viewing', $item->group->type->name);
    }

    #[Test] public function one_model_can_only_be_added_once()
    {
        $group = LinkGroup::create([
            'link_type_id' => LinkType::create(['name' => 'Similar product'])->id
        ]);
        $product = TestProduct::create(['name' => 'Furry Bra']);

        LinkGroupItem::create([
            'link_group_id' => $group->id,
            'linkable_id' => $product->id,
            'linkable_type' => $product::class,
        ]);

        $this->expectExceptionMessageMatches('/constraint/');

        LinkGroupItem::create([
            'link_group_id' => $group->id,
            'linkable_id' => $product->id,
            'linkable_type' => $product::class,
        ]);
    }

    #[Test] public function items_can_be_retrieved_from_the_parent_group_relation()
    {
        $group = LinkGroup::create([
            'link_type_id' => LinkType::create(['name' => 'Variant'])->id
        ]);
        $pink = TestProduct::create(['name' => 'Furry Bra Pink']);
        $purple = TestProduct::create(['name' => 'Furry Bra Purple']);

        $attributes = ['link_group_id' => $group->id, 'linkable_type' => TestProduct::class];
        LinkGroupItem::create(array_merge($attributes, ['linkable_id' => $pink->id]));
        LinkGroupItem::create(array_merge($attributes, ['linkable_id' => $purple->id]));

        $this->assertInstanceOf(Collection::class, $group->items);
        $this->assertCount(2, $group->items);
    }

    #[Test] public function the_linkable_object_can_be_accessed_via_the_polymorphic_relationship()
    {
        $group = LinkGroup::create([
            'link_type_id' => LinkType::create(['name' => 'Designer'])->id
        ]);
        $milky = TestProduct::create(['name' => 'Milky White']);

        LinkGroupItem::create([
            'link_group_id' => $group->id,
            'linkable_id' => $milky->id,
            'linkable_type' => TestProduct::class,
        ]);

        $linkable = $group->items->first()->linkable;
        $this->assertInstanceOf(TestProduct::class, $linkable);
        $this->assertEquals('Milky White', $linkable->name);
    }
}
