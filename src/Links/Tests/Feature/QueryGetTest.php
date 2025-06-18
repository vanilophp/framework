<?php

declare(strict_types=1);

/**
 * Contains the QueryGetTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-11
 *
 */

namespace Vanilo\Links\Tests\Feature;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Foundation\Models\MasterProductVariant;
use Vanilo\Links\Models\LinkGroup;
use Vanilo\Links\Models\LinkGroupItem;
use Vanilo\Links\Models\LinkType;
use Vanilo\Links\Query\Establish;
use Vanilo\Links\Query\Get;
use Vanilo\Links\Tests\Dummies\Property;
use Vanilo\Links\Tests\Dummies\TestLinkableProduct;
use Vanilo\Links\Tests\TestCase;

class QueryGetTest extends TestCase
{
    private TestLinkableProduct $galaxyS22;

    private TestLinkableProduct $galaxyS22Plus;

    private TestLinkableProduct $galaxyS22Ultra;

    private LinkType $series;

    private LinkType $variant;

    private LinkGroup $groupSeries;

    private LinkGroup $groupVariant;

    public function setUp(): void
    {
        parent::setUp();
        $this->createTestData();
    }

    /** @test */
    public function two_linked_products_can_be_queried()
    {
        $attrs = ['link_group_id' => $this->groupSeries->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->galaxyS22->id]));
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->galaxyS22Plus->id]));
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->galaxyS22Ultra->id]));

        $linksOfS22 = Get::the('series')->links()->of($this->galaxyS22);

        $this->assertInstanceOf(Collection::class, $linksOfS22);
        $this->assertCount(2, $linksOfS22);
        $this->assertContains($this->galaxyS22Plus->id, $linksOfS22->map->id);
        $this->assertContains($this->galaxyS22Ultra->id, $linksOfS22->map->id);
    }

    /** @test */
    public function it_returns_the_linked_models_when_they_have_the_same_id_but_different_type()
    {
        $product = TestLinkableProduct::create(['name' => 'Simple Product']);
        $master = MasterProduct::create(['name' => 'Master Product']);
        $variant = MasterProductVariant::create(['name' => 'Variant 1', 'sku' => 'SKU', 'master_product_id' => $master->id]);

        // Make sure the master and variant product IDs are the same as the product's ID
        $master->id = $product->id;
        $master->save();
        $variant->id = $product->id;
        $variant->save();

        $attrs = ['link_group_id' => $this->groupSeries->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $product->id, 'linkable_type' => morph_type_of($product)]));
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $master->id, 'linkable_type' => morph_type_of($master)]));
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $variant->id, 'linkable_type' => morph_type_of($variant)]));

        $linksOfProduct = Get::the('series')->links()->of($product);

        $this->assertCount(2, $linksOfProduct);
        $this->assertTrue($linksOfProduct[0]->is($master));
        $this->assertTrue($linksOfProduct[1]->is($variant));
    }

    /** @test */
    public function it_returns_the_link_items_when_they_have_the_same_id_but_different_type()
    {
        $product = TestLinkableProduct::create(['name' => 'Simple Product']);
        $master = MasterProduct::create(['name' => 'Master Product']);
        $variant = MasterProductVariant::create(['name' => 'Variant 1', 'sku' => 'SKU', 'master_product_id' => $master->id]);

        // Make sure the master and variant product IDs are the same as the product's ID
        $master->id = $product->id;
        $master->save();
        $variant->id = $product->id;
        $variant->save();

        $attrs = ['link_group_id' => $this->groupSeries->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $product->id, 'linkable_type' => morph_type_of($product)]));
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $master->id, 'linkable_type' => morph_type_of($master)]));
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $variant->id, 'linkable_type' => morph_type_of($variant)]));

        $linkItemsOfProduct = Get::the('series')->linkItems()->of($product);

        $this->assertCount(2, $linkItemsOfProduct);
    }

    /** @test */
    public function calling_on_models_without_links_returns_an_empty_collection()
    {
        $seriesLinks = Get::the('series')->links()->of($this->galaxyS22);

        $this->assertInstanceOf(Collection::class, $seriesLinks);
        $this->assertCount(0, $seriesLinks);
    }

    /** @test */
    public function calling_on_models_that_arent_in_a_group_returns_an_empty_collection()
    {
        $attrs = ['link_group_id' => $this->groupSeries->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->galaxyS22Ultra->id]));
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->galaxyS22Plus->id]));

        $seriesLinks = Get::the('series')->links()->of($this->galaxyS22);

        $this->assertInstanceOf(Collection::class, $seriesLinks);
        $this->assertCount(0, $seriesLinks);
    }

    /** @test */
    public function calling_on_models_that_arent_in_the_given_group_returns_an_empty_collection()
    {
        $attrs = ['link_group_id' => $this->groupSeries->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->galaxyS22->id]));
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->galaxyS22Plus->id]));

        $variantLinks = Get::the('variant')->links()->of($this->galaxyS22);

        $this->assertInstanceOf(Collection::class, $variantLinks);
        $this->assertCount(0, $variantLinks);
    }

    /** @test */
    public function groups_of_different_types_arent_mixed_up()
    {
        // Series Group
        $attrs1 = ['link_group_id' => $this->groupSeries->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs1, ['linkable_id' => $this->galaxyS22->id]));
        LinkGroupItem::create(array_merge($attrs1, ['linkable_id' => $this->galaxyS22Plus->id]));

        // Variant Group
        $attrs2 = ['link_group_id' => $this->groupVariant->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs2, ['linkable_id' => $this->galaxyS22->id]));
        LinkGroupItem::create(array_merge($attrs2, ['linkable_id' => $this->galaxyS22Ultra->id]));

        $seriesLinks = Get::the('series')->links()->of($this->galaxyS22);
        $variantLinks = Get::the('variant')->links()->of($this->galaxyS22);

        $this->assertCount(1, $seriesLinks);
        $this->assertContains($this->galaxyS22Plus->id, $seriesLinks->map->id);

        $this->assertCount(1, $variantLinks);
        $this->assertContains($this->galaxyS22Ultra->id, $variantLinks->map->id);
    }

    /** @test */
    public function an_optional_property_can_be_specified_to_filter_groups_by()
    {
        $prop22 = Property::create(['name' => '22', 'type' => 'text']);
        $prop33 = Property::create(['name' => '33', 'type' => 'text']);

        $variantGroup22 = LinkGroup::create(['link_type_id' => $this->variant->id, 'property_id' => $prop22->id])->fresh();
        $variantGroup33 = LinkGroup::create(['link_type_id' => $this->variant->id, 'property_id' => $prop33->id])->fresh();

        // Variant 22 Group
        $attrs22 = ['link_group_id' => $variantGroup22->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs22, ['linkable_id' => $this->galaxyS22->id]));
        LinkGroupItem::create(array_merge($attrs22, ['linkable_id' => $this->galaxyS22Plus->id]));

        // Variant 33 Group
        $attrs33 = ['link_group_id' => $variantGroup33->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs33, ['linkable_id' => $this->galaxyS22->id]));
        LinkGroupItem::create(array_merge($attrs33, ['linkable_id' => $this->galaxyS22Ultra->id]));

        $allVariants = Get::the('variant')->links()->of($this->galaxyS22);
        $this->assertCount(2, $allVariants);
        $this->assertContains($this->galaxyS22Ultra->id, $allVariants->map->id);
        $this->assertContains($this->galaxyS22Plus->id, $allVariants->map->id);

        $variants22 = Get::the('variant')->links()->basedOn($prop22->id)->of($this->galaxyS22);
        $this->assertCount(1, $variants22);
        $this->assertContains($this->galaxyS22Plus->id, $variants22->map->id);

        $variants33 = Get::the('variant')->links()->basedOn($prop33->id)->of($this->galaxyS22);
        $this->assertCount(1, $variants33);
        $this->assertContains($this->galaxyS22Ultra->id, $variants33->map->id);
    }

    /** @test */
    public function the_property_filter_can_be_passed_by_slug()
    {
        Get::usePropertiesModel(Property::class);

        Property::upsert(['name' => 'Series', 'slug' => 'series', 'type' => 'string'], ['slug']);
        $seriesProperty = Property::findBySlug('series');
        Property::upsert(['name' => 'Screen', 'slug' => 'screen', 'type' => 'string'], ['slug']);
        $screenProperty = Property::findBySlug('screen');
        $groupSeries = LinkGroup::create(['link_type_id' => $this->variant->id, 'property_id' => $seriesProperty->id])->fresh();
        $groupScreen = LinkGroup::create(['link_type_id' => $this->variant->id, 'property_id' => $screenProperty->id])->fresh();

        // Variant "Series" Group
        $attrsSeries = ['link_group_id' => $groupSeries->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrsSeries, ['linkable_id' => $this->galaxyS22->id]));
        LinkGroupItem::create(array_merge($attrsSeries, ['linkable_id' => $this->galaxyS22Plus->id]));
        LinkGroupItem::create(array_merge($attrsSeries, ['linkable_id' => $this->galaxyS22Ultra->id]));

        // Variant "Screen" Group
        $attrsScreen = ['link_group_id' => $groupScreen->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrsScreen, ['linkable_id' => $this->galaxyS22->id]));
        LinkGroupItem::create(array_merge($attrsScreen, ['linkable_id' => $this->galaxyS22Plus->id]));

        $variantsBySeries = Get::the('variant')->links()->basedOn('series')->of($this->galaxyS22);
        $this->assertCount(2, $variantsBySeries);
        $this->assertContains($this->galaxyS22Plus->id, $variantsBySeries->map->id);
        $this->assertContains($this->galaxyS22Ultra->id, $variantsBySeries->map->id);

        $variantsByScreen = Get::the('variant')->links()->basedOn('screen')->of($this->galaxyS22);
        $this->assertCount(1, $variantsByScreen);
        $this->assertContains($this->galaxyS22Plus->id, $variantsByScreen->map->id);
    }

    /** @test */
    public function it_can_retrieve_the_link_groups_of_a_model()
    {
        // Series Group
        $attrs1 = ['link_group_id' => $this->groupSeries->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs1, ['linkable_id' => $this->galaxyS22->id]));
        // Variant Group
        $attrs2 = ['link_group_id' => $this->groupVariant->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs2, ['linkable_id' => $this->galaxyS22->id]));

        $seriesGroups = Get::the('series')->groups()->of($this->galaxyS22);
        $this->assertCount(1, $seriesGroups);
        $this->assertInstanceOf(LinkGroup::class, $seriesGroups->first());
    }

    /** @test */
    public function it_resolves_the_types_via_a_magic_method_call()
    {
        $attrs1 = ['link_group_id' => $this->groupSeries->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs1, ['linkable_id' => $this->galaxyS22->id]));
        LinkGroupItem::create(array_merge($attrs1, ['linkable_id' => $this->galaxyS22Plus->id]));

        $seriesLinks = Get::series()->links()->of($this->galaxyS22);
        $this->assertCount(1, $seriesLinks);
        $this->assertInstanceOf(TestLinkableProduct::class, $seriesLinks->first());
        $this->assertContains($this->galaxyS22Plus->id, $seriesLinks->map->id);
    }

    /** @test */
    public function it_throws_an_invalid_argument_exception_if_attempting_to_fetch_an_inexistent_link_type()
    {
        $this->expectException(InvalidArgumentException::class);

        Get::the('inexistent-link-type-slug');
    }

    /** @test */
    public function the_links_helper_function_returns_a_get_query()
    {
        $this->assertInstanceOf(Get::class, links('series'));
    }

    /** @test */
    public function the_links_helper_function_accepts_type_as_first_argument()
    {
        $attrs1 = ['link_group_id' => $this->groupSeries->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs1, ['linkable_id' => $this->galaxyS22->id]));
        LinkGroupItem::create(array_merge($attrs1, ['linkable_id' => $this->galaxyS22Plus->id]));

        $seriesLinks = links('series')->of($this->galaxyS22);
        $this->assertCount(1, $seriesLinks);
        $this->assertInstanceOf(TestLinkableProduct::class, $seriesLinks->first());
        $this->assertContains($this->galaxyS22Plus->id, $seriesLinks->map->id);
    }

    /** @test */
    public function the_links_helper_function_accepts_an_optional_property_as_second_argument()
    {
        Get::usePropertiesModel(Property::class);

        Property::upsert(['name' => 'Screen', 'slug' => 'screen', 'type' => 'string'], ['slug']);
        $screenProperty = Property::findBySlug('screen');
        $groupScreen = LinkGroup::create(['link_type_id' => $this->variant->id, 'property_id' => $screenProperty->id])->fresh();

        $attrs = ['link_group_id' => $groupScreen->id, 'linkable_type' => TestLinkableProduct::class];
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->galaxyS22->id]));
        LinkGroupItem::create(array_merge($attrs, ['linkable_id' => $this->galaxyS22Plus->id]));

        $variantsByScreen = links('variant', 'screen')->of($this->galaxyS22);
        $this->assertCount(1, $variantsByScreen);
        $this->assertContains($this->galaxyS22Plus->id, $variantsByScreen->map->id);
    }

    /** @test */
    public function the_link_groups_helper_returns_link_groups_in_which_the_model_is_included()
    {
        LinkGroupItem::create([
            'link_group_id' => $this->groupSeries->id,
            'linkable_type' => TestLinkableProduct::class,
            'linkable_id' => $this->galaxyS22->id,
        ]);
        LinkGroupItem::create([
            'link_group_id' => $this->groupVariant->id,
            'linkable_type' => TestLinkableProduct::class,
            'linkable_id' => $this->galaxyS22->id,
        ]);

        $seriesGroups = link_groups('series')->of($this->galaxyS22);
        $this->assertCount(1, $seriesGroups);
        $this->assertInstanceOf(LinkGroup::class, $seriesGroups->first());

        $variantGroups = link_groups('variant')->of($this->galaxyS22);
        $this->assertCount(1, $variantGroups);
        $this->assertInstanceOf(LinkGroup::class, $variantGroups->first());
    }

    /** @test */
    public function unidirectional_links_can_be_properly_queried()
    {
        $phone = TestLinkableProduct::create(['name' => 'iPhone 17'])->fresh();
        $caseX = TestLinkableProduct::create(['name' => 'iPhone 17 Plastic Case X'])->fresh();
        $caseY = TestLinkableProduct::create(['name' => 'iPhone 17 Plastic Case Y'])->fresh();
        LinkType::create(['name' => 'Sleeves']);

        Establish::a('sleeves')->unidirectional()->link()->between($phone)->and($caseX);
        Establish::a('sleeves')->unidirectional()->link()->between($phone)->and($caseY);

        $sleeves = Get::the('sleeves')->links()->of($phone);
        $this->assertCount(2, $sleeves);
        $this->assertEquals($caseX->id, $sleeves->first()->id);
        $this->assertEquals($caseY->id, $sleeves->last()->id);

        $this->assertCount(0, Get::the('sleeves')->links()->of($caseX));
        $this->assertCount(0, Get::the('sleeves')->links()->of($caseY));

        $sleeveItems = Get::the('sleeves')->linkItems()->of($phone);
        $this->assertCount(2, $sleeveItems);
        $this->assertEquals($caseX->id, $sleeveItems->first()->linkable_id);
        $this->assertEquals($caseY->id, $sleeveItems->last()->linkable_id);

        $this->assertCount(0, Get::the('sleeves')->linkItems()->of($caseX));
        $this->assertCount(0, Get::the('sleeves')->linkItems()->of($caseY));
    }

    protected function setUpDatabase($app)
    {
        $this->loadMigrationsFrom(dirname(__DIR__) . '/migrations_of_property_module');
        parent::setUpDatabase($app);
    }

    private function createTestData(): void
    {
        $this->galaxyS22 = TestLinkableProduct::create(['name' => 'Galaxy S22']);
        $this->galaxyS22Plus = TestLinkableProduct::create(['name' => 'Galaxy S22 Plus']);
        $this->galaxyS22Ultra = TestLinkableProduct::create(['name' => 'Galaxy S22 Ultra']);

        $this->series = LinkType::create(['name' => 'Series']);
        $this->variant = LinkType::create(['name' => 'Variant']);

        $this->groupSeries = LinkGroup::create(['link_type_id' => $this->series->id])->fresh();
        $this->groupVariant = LinkGroup::create(['link_type_id' => $this->variant->id])->fresh();
    }
}
