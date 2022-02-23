<?php

declare(strict_types=1);

/**
 * Contains the LInkableTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-18
 *
 */

namespace Vanilo\Links\Tests\Unit;

use Vanilo\Links\Models\LinkGroup;
use Vanilo\Links\Models\LinkGroupItem;
use Vanilo\Links\Models\LinkType;
use Vanilo\Links\Tests\Dummies\TestLinkableProduct;
use Vanilo\Links\Tests\TestCase;
use Vanilo\Links\Tests\TestsDatabasePerformance;
use Vanilo\Links\Traits\Linkable;

class LinkableTest extends TestCase
{
    use TestsDatabasePerformance;

    private LinkType $variantType;

    private LinkGroup $group1;

    private LinkGroup $group2;

    private TestLinkableProduct $red;

    private TestLinkableProduct $yellow;

    private TestLinkableProduct $green;

    private TestLinkableProduct $blue;

    private TestLinkableProduct $white;

    private TestLinkableProduct $purple;

    public function setUp(): void
    {
        parent::setUp();
        $this->createTestData();
    }

    /** @test */
    public function it_returns_the_link_group_items()
    {
        LinkGroupItem::create([
            'linkable_id' => $this->green->id,
            'link_group_id' => $this->group1->id,
            'linkable_type' => TestLinkableProduct::class,
        ]);

        LinkGroupItem::create([
            'linkable_id' => $this->green->id,
            'link_group_id' => $this->group2->id,
            'linkable_type' => TestLinkableProduct::class,
        ]);

        $linkItems = $this->green->includedInLinkGroupItems;
        $this->assertCount(2, $linkItems);
        $this->assertInstanceOf(LinkGroupItem::class, $linkItems->first());
        $this->assertInstanceOf(LinkGroupItem::class, $linkItems->last());
    }

    /** @test */
    public function it_returns_the_groups_the_model_is_part_of()
    {
        LinkGroupItem::create([
            'linkable_id' => $this->yellow->id,
            'link_group_id' => $this->group1->id,
            'linkable_type' => TestLinkableProduct::class,
        ]);

        LinkGroupItem::create([
            'linkable_id' => $this->yellow->id,
            'link_group_id' => $this->group2->id,
            'linkable_type' => TestLinkableProduct::class,
        ]);

        $groups = $this->yellow->linkGroups();
        $this->assertCount(2, $groups);

        $this->assertInstanceOf(LinkGroup::class, $groups->first());
        $this->assertInstanceOf(LinkGroup::class, $groups->last());
    }

    /** @test */
    public function it_doesnt_return_groups_that_the_model_is_not_part_of()
    {
        LinkGroupItem::create([
            'linkable_id' => $this->red->id,
            'link_group_id' => $this->group1->id,
            'linkable_type' => TestLinkableProduct::class,
        ]);

        LinkGroupItem::create([
            'linkable_id' => $this->yellow->id,
            'link_group_id' => $this->group2->id,
            'linkable_type' => TestLinkableProduct::class,
        ]);

        $groups = $this->red->linkGroups();
        $this->assertCount(1, $groups);

        $this->assertInstanceOf(LinkGroup::class, $groups->first());
        $this->assertEquals($this->group1->id, $groups->first()->id);
    }

    /** @test */
    public function it_doesnt_return_groups_that_the_model_is_not_part_of_but_another_model_that_the_model_shares_a_group_is_included_in()
    {
        LinkGroupItem::create([
            'linkable_id' => $this->red->id,
            'link_group_id' => $this->group1->id,
            'linkable_type' => TestLinkableProduct::class,
        ]);

        LinkGroupItem::create([
            'linkable_id' => $this->red->id,
            'link_group_id' => $this->group2->id,
            'linkable_type' => TestLinkableProduct::class,
        ]);

        LinkGroupItem::create([
            'linkable_id' => $this->green->id,
            'link_group_id' => $this->group2->id,
            'linkable_type' => TestLinkableProduct::class,
        ]);

        $groups = $this->green->linkGroups();
        $this->assertCount(1, $groups);

        $this->assertInstanceOf(LinkGroup::class, $groups->first());
        $this->assertEquals($this->group2->id, $groups->first()->id);
    }

    /** @test */
    public function it_returns_the_linked_models_within_a_specific_type()
    {
        $attrs = ['link_group_id' => $this->group1->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->red->id]));
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->green->id]));
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->yellow->id]));

        $redsLinks = $this->red->links($this->variantType);
        $yellowsLinks = $this->yellow->links($this->variantType);
        $greensLinks = $this->green->links($this->variantType);

        $this->assertCount(2, $redsLinks);
        $this->assertEquals(
            [$this->yellow->id, $this->green->id],
            $redsLinks->map(fn ($linkable) => $linkable->id)->all()
        );

        $this->assertCount(2, $yellowsLinks);
        $this->assertEquals(
            [$this->red->id, $this->green->id],
            $yellowsLinks->map(fn ($linkable) => $linkable->id)->all()
        );

        $this->assertCount(2, $greensLinks);
        $this->assertEquals(
            [$this->red->id, $this->yellow->id],
            $greensLinks->map(fn ($linkable) => $linkable->id)->all()
        );
    }

    /** @test */
    public function it_returns_the_linked_models_within_a_specific_type_but_no_models_from_other_groups()
    {
        $attrs1 = ['link_group_id' => $this->group1->id, 'linkable_type' => TestLinkableProduct::class];
        $attrs2 = ['link_group_id' => $this->group2->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs1, ['linkable_id' => $this->red->id]));
        LinkGroupItem::create(array_merge($attrs1, ['linkable_id' => $this->green->id]));

        LinkGroupItem::create(array_merge($attrs2, ['linkable_id' => $this->yellow->id]));
        LinkGroupItem::create(array_merge($attrs2, ['linkable_id' => $this->blue->id]));
        LinkGroupItem::create(array_merge($attrs2, ['linkable_id' => $this->white->id]));

        $redsLinks = $this->red->links($this->variantType);
        $yellowsLinks = $this->yellow->links($this->variantType);
        $greensLinks = $this->green->links($this->variantType);
        $bluesLinks = $this->blue->links($this->variantType);
        $whitesLinks = $this->white->links($this->variantType);

        $this->assertCount(1, $redsLinks);
        $this->assertEquals($this->green->id, $redsLinks->first()->id);

        $this->assertCount(1, $greensLinks);
        $this->assertEquals($this->red->id, $greensLinks->first()->id);

        $this->assertCount(2, $yellowsLinks);
        $this->assertEquals(
            [$this->blue->id, $this->white->id],
            $yellowsLinks->map(fn ($linkable) => $linkable->id)->all()
        );

        $this->assertCount(2, $bluesLinks);
        $this->assertEquals(
            [$this->yellow->id, $this->white->id],
            $bluesLinks->map(fn ($linkable) => $linkable->id)->all()
        );

        $this->assertCount(2, $whitesLinks);
        $this->assertEquals(
            [$this->yellow->id, $this->blue->id],
            $whitesLinks->map(fn ($linkable) => $linkable->id)->all()
        );
    }

    /** @test */
    public function it_uses_an_optimal_query_for_retrieving_links()
    {
        $attrs1 = ['link_group_id' => $this->group1->id, 'linkable_type' => TestLinkableProduct::class];
        $attrs2 = ['link_group_id' => $this->group2->id, 'linkable_type' => TestLinkableProduct::class];

        LinkGroupItem::create(array_merge($attrs1, ['linkable_id' => $this->red->id]));
        LinkGroupItem::create(array_merge($attrs1, ['linkable_id' => $this->green->id]));
        LinkGroupItem::create(array_merge($attrs1, ['linkable_id' => $this->yellow->id]));

        LinkGroupItem::create(array_merge($attrs2, ['linkable_id' => $this->blue->id]));
        LinkGroupItem::create(array_merge($attrs2, ['linkable_id' => $this->white->id]));
        LinkGroupItem::create(array_merge($attrs2, ['linkable_id' => $this->purple->id]));

        $this->startCountingDBQueries();
        $this->startCountingModels();

        $this->red->links($this->variantType);
        $this->yellow->links($this->variantType);
        $this->green->links($this->variantType);
        $this->blue->links($this->variantType);
        $this->white->links($this->variantType);
        $this->purple->links($this->variantType);

        $this->stopCountingModels();
        $this->stopCountingDBQueries();

        /**
         * @todo Optimize this query so that each:
         * - links retrieval is 1 query
         * - it only hydrates the linkables and nothing else
         * @see Linkable:40
         */
        $this->assertModelsHydratedEquals(18, TestLinkableProduct::class);
//        $this->assertTotalModelsHydratedEquals(12);
//        $this->assertDbQueryCountWasExactly(6);
    }

    /** @test */
    public function links_can_be_queried_consecutively_without_an_error()
    {
        $attrs = ['link_group_id' => $this->group1->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->red->id]));
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->yellow->id]));

        // Call it twice to see if it doesn't throw an exception
        $this->red->links($this->variantType); // << double call is intentional!
        $redsLinks = $this->red->links($this->variantType);

        $this->assertCount(1, $redsLinks);
        $this->assertEquals(
            [$this->yellow->id],
            $redsLinks->map(fn ($linkable) => $linkable->id)->all()
        );
    }

    private function createTestData(): void
    {
        $this->red = TestLinkableProduct::create(['name' => 'Red Phone']);
        $this->yellow = TestLinkableProduct::create(['name' => 'Yellow Phone']);
        $this->green = TestLinkableProduct::create(['name' => 'Green Phone']);
        $this->blue = TestLinkableProduct::create(['name' => 'Blue Phone']);
        $this->white = TestLinkableProduct::create(['name' => 'White Phone']);
        $this->purple = TestLinkableProduct::create(['name' => 'Purple Phone']);

        $this->variantType = LinkType::create(['name' => 'Variant']);

        $this->group1 = LinkGroup::create(['link_type_id' => $this->variantType->id])->fresh();
        $this->group2 = LinkGroup::create(['link_type_id' => $this->variantType->id])->fresh();
    }
}
