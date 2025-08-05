<?php

declare(strict_types=1);

/**
 * Contains the QueryEliminateLinksTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-23
 *
 */

namespace Vanilo\Links\Tests\Feature;

use Illuminate\Database\Eloquent\Relations\Relation;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Links\Models\LinkType;
use Vanilo\Links\Query\Eliminate;
use Vanilo\Links\Query\Establish;
use Vanilo\Links\Query\Get;
use Vanilo\Links\Tests\Dummies\TestLinkableMorphedProduct;
use Vanilo\Links\Tests\Dummies\TestProduct;
use Vanilo\Links\Tests\TestCase;

class QueryEliminateLinksTest extends TestCase
{
    #[Test] public function it_removes_the_model_from_the_existing_links()
    {
        $product1 = TestProduct::create(['name' => 'Product 1']);
        $product2 = TestProduct::create(['name' => 'Product 2']);

        LinkType::create(['name' => 'See also']);

        Establish::a('see-also')->link()->between($product1)->and($product2);
        $this->assertCount(1, Get::the('see-also')->links()->of($product1));

        Eliminate::the('see-also')->link()->between($product1)->and($product2);
        $this->assertCount(0, Get::the('see-also')->links()->of($product1));
    }

    #[Test] public function the_first_model_remains_in_the_group()
    {
        $product3 = TestProduct::create(['name' => 'Product 3']);
        $product4 = TestProduct::create(['name' => 'Product 4']);

        LinkType::create(['name' => 'See also']);

        Establish::a('see-also')->link()->between($product3)->and($product4);
        $this->assertCount(1, Get::the('see-also')->links()->of($product3));

        Eliminate::the('see-also')->link()->between($product3)->and($product4);
        $this->assertCount(0, Get::the('see-also')->links()->of($product3));

        $this->assertCount(1, Get::the('see-also')->groups()->of($product3));
        $this->assertCount(0, Get::the('see-also')->groups()->of($product4));
    }

    #[Test] public function it_can_remove_multiple_model()
    {
        $product5 = TestProduct::create(['name' => 'Product 5']);
        $product6 = TestProduct::create(['name' => 'Product 6']);
        $product7 = TestProduct::create(['name' => 'Product 7']);
        $product8 = TestProduct::create(['name' => 'Product 8']);

        LinkType::create(['name' => 'Variant']);

        Establish::a('variant')
            ->link()
            ->between($product5)
            ->and($product6, $product7, $product8);

        $this->assertCount(3, Get::the('variant')->links()->of($product5));

        Eliminate::the('variant')
            ->link()
            ->between($product5)
            ->and($product6, $product7, $product8);

        $this->assertCount(0, Get::the('variant')->links()->of($product5));
        $this->assertCount(1, Get::the('variant')->groups()->of($product5));
        $this->assertCount(0, Get::the('variant')->groups()->of($product6));
        $this->assertCount(0, Get::the('variant')->groups()->of($product7));
        $this->assertCount(0, Get::the('variant')->groups()->of($product8));
    }

    #[Test] public function it_can_remove_links_between_morphed_models()
    {
        Relation::morphMap(['lmproduct' => TestLinkableMorphedProduct::class]);

        $service1 = TestLinkableMorphedProduct::create(['name' => 'Service 1'])->fresh();
        $service2 = TestLinkableMorphedProduct::create(['name' => 'Service 2'])->fresh();
        LinkType::create(['name' => 'Similar']);

        Establish::a('similar')->link()->between($service1)->and($service2);

        $this->assertCount(1, Get::the('similar')->links()->of($service1));
        $this->assertCount(1, Get::the('similar')->links()->of($service2));

        Eliminate::the('similar')->link()->between($service1)->and($service2);

        $this->assertCount(0, Get::the('similar')->links()->of($service1));
        $this->assertCount(0, Get::the('similar')->links()->of($service2));
    }

    // @todo implement it
    /**
     *
     * Eliminate::the('upsell')->group()->constitutedBy($product1)->and($product2);
     * Eliminate::model($product1)->fromThe('upsell')->group()->of($product2);
     *
     */
}
