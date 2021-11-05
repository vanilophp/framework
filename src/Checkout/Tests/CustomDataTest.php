<?php

declare(strict_types=1);

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
    /** @test */
    public function a_custom_attribute_can_be_set()
    {
        Checkout::setCustomAttribute('hello', 'kitty');

        $this->assertEquals('kitty', Checkout::getCustomAttribute('hello'));
    }

    /** @test */
    public function several_custom_attributes_can_be_set()
    {
        Checkout::setCustomAttribute('cz', 'ahoj');
        Checkout::setCustomAttribute('hu', 'szia');
        Checkout::setCustomAttribute('de', 'hallo');

        $this->assertEquals('ahoj', Checkout::getCustomAttribute('cz'));
        $this->assertEquals('szia', Checkout::getCustomAttribute('hu'));
        $this->assertEquals('hallo', Checkout::getCustomAttribute('de'));
    }

    /** @test */
    public function a_custom_attribute_can_be_updated()
    {
        Checkout::setCustomAttribute('eat', 'zucchini');
        $this->assertEquals('zucchini', Checkout::getCustomAttribute('eat'));

        Checkout::setCustomAttribute('eat', 'mushroom');
        $this->assertEquals('mushroom', Checkout::getCustomAttribute('eat'));
    }

    /** @test */
    public function all_custom_attributes_can_be_retrieved_as_single_array()
    {
        Checkout::setCustomAttribute('en', 'Good morning');
        Checkout::setCustomAttribute('nl', 'Goede morgen');
        Checkout::setCustomAttribute('ro', 'Buna dimineata');

        $data = Checkout::getCustomAttributes();

        $this->assertEquals('Good morning', $data['en']);
        $this->assertEquals('Goede morgen', $data['nl']);
        $this->assertEquals('Buna dimineata', $data['ro']);
    }

    /** @test */
    public function put_custom_attributes_completely_replaces_all_existing_custom_data()
    {
        Checkout::setCustomAttribute('xx', '123XYZ');
        Checkout::setCustomAttribute('aa', '789ABC');

        $this->assertEquals('123XYZ', Checkout::getCustomAttribute('xx'));
        $this->assertEquals('789ABC', Checkout::getCustomAttribute('aa'));

        $newData = [
            'zz' => 'zxc',
            'aa' => 'AAA'
        ];

        Checkout::putCustomAttributes($newData);

        $this->assertEquals($newData, Checkout::getCustomAttributes());
        $this->assertNull(Checkout::getCustomAttribute('xx'));
        $this->assertEquals('AAA', Checkout::getCustomAttribute('aa'));
        $this->assertEquals('zxc', Checkout::getCustomAttribute('zz'));
    }
}
