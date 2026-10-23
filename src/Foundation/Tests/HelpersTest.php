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

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Foundation\Models\Product as FoundationProduct;
use Vanilo\Foundation\Tests\Examples\IndependentMaster;
use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\Product\Models\Product as BaseProduct;

class HelpersTest extends TestCaseWithoutDB
{
    #[Test] public function the_base_product_is_not_a_master_product()
    {
        $this->assertFalse(is_master_product(new BaseProduct()));
    }

    #[Test] public function the_foundation_product_is_not_a_master_product()
    {
        $this->assertFalse(is_master_product(new FoundationProduct()));
    }

    #[Test] public function the_base_master_product_is_a_master_product()
    {
        $this->assertTrue(is_master_product(new MasterProduct()));
    }

    #[Test] public function another_class_implementing_the_master_product_interface_is_a_master_product()
    {
        $this->assertTrue(is_master_product(new IndependentMaster()));
    }

    #[Test] public function the_format_price_helper_accepts_sprintf_arguments()
    {
        config(['vanilo.foundation.currency.sign' => '€']);

        config(['vanilo.foundation.currency.format' => '%1$g %2$s']);
        $this->assertEquals('1500 €', format_price(1500));

        config(['vanilo.foundation.currency.format' => '%2$s%1$.2f']);
        $this->assertEquals('€15.99', format_price(15.995));

        config(['vanilo.foundation.currency.format' => '%2$s%1$.3f']);
        $this->assertEquals('€15.995', format_price(15.995));

        config(['vanilo.foundation.currency.sign' => '$']);
        config(['vanilo.foundation.currency.format' => '%2$s%1$.2f']);
        $this->assertEquals('$16.00', format_price(15.999));

        config(['vanilo.foundation.currency.format' => '%2$s%1$.3f']);
        $this->assertEquals('$15.999', format_price(15.999));
    }

    #[Test] public function the_format_price_helper_accepts_decimal_separator()
    {
        config(['vanilo.foundation.currency.decimal_separator' => ',']);
        config(['vanilo.foundation.currency.sign' => '£']);
        config(['vanilo.foundation.currency.format' => '%1$.2f %2$s']);

        $this->assertEquals('12,99 £', format_price(12.99));
    }

    #[Test] public function the_format_price_helper_does_not_use_a_thousand_separator_by_default()
    {
        config(['vanilo.foundation.currency.sign' => '€']);
        config(['vanilo.foundation.currency.format' => '%1$g %2$s']);

        $this->assertEquals('1500 €', format_price(1500));
    }

    #[Test] public function the_format_price_helper_accepts_thousands_separator()
    {
        config(['vanilo.foundation.currency.decimal_separator' => ',']);
        config(['vanilo.foundation.currency.thousand_separator' => ' ']);
        config(['vanilo.foundation.currency.sign' => '£']);
        config(['vanilo.foundation.currency.format' => '%1$g %2$s']);

        $this->assertEquals('1 300 £', format_price(1300));
    }

    #[Test] public function the_format_price_helper_handles_thousands_separator_with_decimals_and_different_formats()
    {
        config(['vanilo.foundation.currency.sign' => '$']);
        config(['vanilo.foundation.currency.decimal_separator' => '.']);
        config(['vanilo.foundation.currency.thousand_separator' => ',']);
        config(['vanilo.foundation.currency.format' => '%2$s%1$.2f']);

        $this->assertEquals('$1,234,567.89', format_price(1234567.89));

        config(['vanilo.foundation.currency.decimal_separator' => ',']);
        config(['vanilo.foundation.currency.thousand_separator' => '.']);
        $this->assertEquals('$1.234.567,89', format_price(1234567.89));

        // Multi-character thousand separator should be ignored
        config(['vanilo.foundation.currency.thousand_separator' => '   ']);
        $this->assertEquals('$1234567,89', format_price(1234567.89));

        // Empty thousand separator should be ignored
        config(['vanilo.foundation.currency.thousand_separator' => '']);
        $this->assertEquals('$1234567,89', format_price(1234567.89));

        // Format without decimals
        config(['vanilo.foundation.currency.thousand_separator' => ',']);
        config(['vanilo.foundation.currency.format' => '%2$s%1$d']);
        $this->assertEquals('$1,234,567', format_price(1234567));
    }
}
