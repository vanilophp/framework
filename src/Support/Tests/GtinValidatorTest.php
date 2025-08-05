<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vanilo\Support\Validation\GtinValidator;

class GtinValidatorTest extends TestCase
{
    #[Test] public function a_valid_gtin8_should_pass()
    {
        $this->assertTrue(GtinValidator::isValid('80000006'));
    }

    #[Test] public function an_invalid_gtin8_should_not_pass()
    {
        $this->assertFalse(GtinValidator::isValid('80000000'));
    }

    #[Test] public function a_valid_gtin12_should_pass()
    {
        $this->assertTrue(GtinValidator::isValid('120000000005'));
    }

    #[Test] public function an_invalid_gtin12_should_not_pass()
    {
        $this->assertFalse(GtinValidator::isValid('120000000000'));
    }

    #[Test] public function a_valid_gtin13_should_pass()
    {
        $this->assertTrue(GtinValidator::isValid('1300000000000'));
    }

    #[Test] public function an_invalid_gtin13_should_not_pass()
    {
        $this->assertFalse(GtinValidator::isValid('1300000000001'));
    }

    #[Test] public function a_valid_gtin14_should_pass()
    {
        $this->assertTrue(GtinValidator::isValid('14000000000003'));
    }

    #[Test] public function an_invalid_gtin14_should_not_pass()
    {
        $this->assertFalse(GtinValidator::isValid('14000000000000'));
    }

    #[Test] public function a_valid_gtin_integer_value_should_pass()
    {
        $this->assertTrue(GtinValidator::isValid(80000006));
    }

    #[Test] public function a_too_short_value_should_not_pass()
    {
        $this->assertFalse(GtinValidator::isValid('7000003'));
    }

    #[Test] public function a_too_long_value_should_not_pass()
    {
        $this->assertFalse(GtinValidator::isValid('150000000000004'));
    }

    #[Test] public function a_value_with_a_length_of_nine_digits_should_not_pass()
    {
        $this->assertFalse(GtinValidator::isValid('900000001'));
    }

    #[Test] public function a_value_with_a_length_of_ten_digits_should_not_pass()
    {
        $this->assertFalse(GtinValidator::isValid('1000000007'));
    }

    #[Test] public function a_value_with_a_length_of_eleven_digits_should_not_pass()
    {
        $this->assertFalse(GtinValidator::isValid('11000000006'));
    }

    #[Test] public function zeros_should_not_pass()
    {
        $this->assertFalse(GtinValidator::isValid('0000000000000'));
    }

    #[Test] public function a_non_numeric_string_should_not_pass()
    {
        $this->assertFalse(GtinValidator::isValid('string'));
    }
}
