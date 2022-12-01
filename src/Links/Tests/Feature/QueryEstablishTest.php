<?php

declare(strict_types=1);

/**
 * Contains the QueryEstablishTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-19
 *
 */

namespace Vanilo\Links\Tests\Feature;

use Illuminate\Database\Eloquent\Relations\Relation;
use Vanilo\Links\Models\LinkType;
use Vanilo\Links\Query\Establish;
use Vanilo\Links\Query\Get;
use Vanilo\Links\Tests\Dummies\Property;
use Vanilo\Links\Tests\Dummies\TestLinkableMorphedProduct;
use Vanilo\Links\Tests\Dummies\TestLinkableProduct;
use Vanilo\Links\Tests\Dummies\TestProduct;
use Vanilo\Links\Tests\TestCase;

class QueryEstablishTest extends TestCase
{
    /** @test */
    public function two_products_can_be_linked_together()
    {
        $iphone12 = TestLinkableProduct::create(['name' => 'iPhone 12'])->fresh();
        $iphone13 = TestLinkableProduct::create(['name' => 'iPhone 13'])->fresh();
        LinkType::create(['name' => 'Related Product']);

        Establish::a('related-product')->link()->between($iphone12)->and($iphone13);

        $this->assertCount(1, $iphone12->links('related-product'));
        $this->assertEquals($iphone13->id, $iphone12->links('related-product')->first()->id);

        $this->assertCount(1, $iphone13->links('related-product'));
        $this->assertEquals($iphone12->id, $iphone13->links('related-product')->first()->id);
    }

    /** @test */
    public function query_can_be_started_with_a_or_an()
    {
        $galaxyS22 = TestProduct::create(['name' => 'Galaxy S21'])->fresh();
        $galaxyS22Ultra = TestProduct::create(['name' => 'Galaxy S22 Ultra'])->fresh();
        LinkType::create(['name' => 'Upsell']);

        Establish::an('upsell')->link()->between($galaxyS22)->and($galaxyS22Ultra);

        $upsells = Get::the('upsell')->links()->of($galaxyS22);
        $this->assertCount(1, $upsells);
        $this->assertEquals($galaxyS22Ultra->id, $upsells->first()->id);
    }

    /** @test */
    public function models_can_be_linked_by_type_and_property()
    {
        $green = TestProduct::create(['name' => 'T-Shirt Green'])->fresh();
        $blue = TestProduct::create(['name' => 'T-Shirt Blue'])->fresh();
        $color = Property::create(['name' => 'Color', 'type' => 'text'])->fresh();
        LinkType::create(['name' => 'Variant']);

        Establish::a('variant')->link()->basedOn($color->id)->between($green)->and($blue);

        $variants = Get::the('variant')->links()->basedOn($color->id)->of($green);
        $this->assertCount(1, $variants);
        $this->assertEquals($blue->id, $variants->first()->id);
    }

    /** @test */
    public function models_can_be_linked_by_property_slug()
    {
        Establish::usePropertiesModel(Property::class);
        Get::usePropertiesModel(Property::class);

        Property::create(['name' => 'Screen', 'slug' => 'screen', 'type' => 'string'])->fresh();
        $laptop13 = TestProduct::create(['name' => 'Laptop 13"'])->fresh();
        $laptop15 = TestProduct::create(['name' => 'Laptop 15"'])->fresh();
        LinkType::create(['name' => 'Variant']);

        Establish::a('variant')->link()->basedOn('screen')->between($laptop13)->and($laptop15);

        $variants = Get::the('variant')->links()->basedOn('screen')->of($laptop13);
        $this->assertCount(1, $variants);
        $this->assertEquals($laptop15->id, $variants->first()->id);
    }

    /** @test */
    public function multiple_products_can_be_linked_together()
    {
        $iphone12 = TestLinkableProduct::create(['name' => 'iPhone 12'])->fresh();
        $iphone13 = TestLinkableProduct::create(['name' => 'iPhone 13'])->fresh();
        $iphone14 = TestLinkableProduct::create(['name' => 'iPhone 14'])->fresh();
        LinkType::create(['name' => 'Series']);

        Establish::a('series')->link()->between($iphone12)->and($iphone13, $iphone14);

        $this->assertCount(2, $iphone12->links('series'));
        $this->assertEquals($iphone13->id, $iphone12->links('series')->first()->id);
        $this->assertEquals($iphone14->id, $iphone12->links('series')->last()->id);
    }

    /** @test */
    public function morphed_models_can_be_linked_together()
    {
        Relation::morphMap(['lmproduct' => TestLinkableMorphedProduct::class]);

        $prod1 = TestLinkableMorphedProduct::create(['name' => 'Product 1'])->fresh();
        $prod2 = TestLinkableMorphedProduct::create(['name' => 'Product 2'])->fresh();
        LinkType::create(['name' => 'Variant']);

        Establish::a('variant')->link()->between($prod1)->and($prod2);

        $this->assertCount(1, Get::the('variant')->links()->of($prod1));
        $this->assertCount(1, Get::the('variant')->links()->of($prod2));
    }

    protected function setUpDatabase($app)
    {
        $this->loadMigrationsFrom(dirname(__DIR__) . '/migrations_of_property_module');
        parent::setUpDatabase($app);
    }

    // @todo test and implement ->group() alternative
}
