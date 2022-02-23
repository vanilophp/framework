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

use Vanilo\Links\Models\LinkType;
use Vanilo\Links\Query\Establish;
use Vanilo\Links\Query\Get;
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
        LinkType::create(['name' => 'Variant']);

        Establish::an('variant')->link()->basedOn(1)->between($green)->and($blue);

        $variants = Get::the('variant')->links()->basedOn(1)->of($green);
        $this->assertCount(1, $variants);
        $this->assertEquals($blue->id, $variants->first()->id);
    }

    // @todo test and implement ->group() alternative
    // @todo test property resolution by slug ::usePropertyModel...
    // @todo implement and(...$product) to take multiple models
}
