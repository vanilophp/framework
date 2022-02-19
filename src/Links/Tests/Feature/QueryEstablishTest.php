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

use Vanilo\Links\Tests\Dummies\TestProduct;
use Vanilo\Links\Tests\TestCase;

class QueryEstablishTest extends TestCase
{
    /** @ test */
    public function two_products_can_be_linked_together()
    {
        $product1 = TestProduct::create(['name' => 'iPhone Black']);
        $product2 = TestProduct::create(['name' => 'iPhone White']);

        Link::this($product1)->to($product2)->as('upsell');
        Link::between($product1)->and($product2)->ofType('upsell')->establish();
        Link::between($product1)->and($product2)->ofType('upsell')->establish();

        Establish::an('upsell')->link()->between($product1)->and($product2);
        Establish::a('similar')->link()->between($product1)->and($product2);
        Establish::a('variant')->link()->basedOn('shoe-size')->between($product1)->and($product2);

        Eliminate::the('upsell')->link()->between($product1)->and($product2);
        Eliminate::the('upsell')->group()->constitutedBy($product1)->and($product2);
        Eliminate::model($product1)->fromThe('upsell')->group()->of($product2);
    }
}
