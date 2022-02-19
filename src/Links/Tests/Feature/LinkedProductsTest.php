<?php

declare(strict_types=1);

/**
 * Contains the LinkedProductsTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-11
 *
 */

namespace Vanilo\Links\Tests\Feature;

use Vanilo\Links\Tests\Dummies\TestProduct;
use Vanilo\Links\Tests\TestCase;

class LinkedProductsTest extends TestCase
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

        Get::the('upsell')->links()->of($product1);
        Get::the('variant')->links()->basedOn('shoe-size')->of($product1);

        // via magic __call:
        Get::variant()->links()->of($product1);
        Get::upsell()->links()->of($product1);

        // in blade templates;
        links('upsell')->of($product1);
        links('variant')->basedOn('shoe-size')->of($product1);
        variants('shoe-size')->of($product1);
    }
}
