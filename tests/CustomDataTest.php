<?php
/**
 * Contains the CustomDataTest class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-09-28
 *
 */

namespace Vanilo\Checkout\Tests;

use Vanilo\Checkout\Facades\Checkout;

class CustomDataTest extends TestCase
{
    /**
     * @test
     */
    public function custom_attribute_can_be_set()
    {
        Checkout::setCustomAttribute('hello', 'kitty');

        $this->assertEquals('kitty', Checkout::getCustomAttribute('hello'));
    }
}
