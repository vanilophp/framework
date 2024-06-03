<?php

declare(strict_types=1);

/**
 * Contains the HelpersTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-11-29
 *
 */

namespace Vanilo\Foundation\Tests;

use Vanilo\Foundation\Models\Product as FoundationProduct;
use Vanilo\Foundation\Tests\Examples\IndependentMaster;
use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\Product\Models\Product as BaseProduct;

class HelpersTest extends TestCase
{
    /** @test */
    public function the_base_product_is_not_a_master_product()
    {
        $this->assertFalse(is_master_product(new BaseProduct()));
    }

    /** @test */
    public function the_foundation_product_is_not_a_master_product()
    {
        $this->assertFalse(is_master_product(new FoundationProduct()));
    }

    /** @test */
    public function the_base_master_product_is_a_master_product()
    {
        $this->assertTrue(is_master_product(new MasterProduct()));
    }

    /** @test */
    public function another_class_implementing_the_master_product_interface_is_a_master_product()
    {
        $this->assertTrue(is_master_product(new IndependentMaster()));
    }

    /** @test */
    public function the_format_price_helper_accepts_decimal_separator()
    {
        config(['vanilo.foundation.currency.decimal_separator' => ',']);
        config(['vanilo.foundation.currency.sign' => '£']);
        config(['vanilo.foundation.currency.format' => '%1$.2f %2$s']);

        $this->assertEquals('12,99 £', format_price(12.99));
    }
}
