<?php
/**
 * Contains the DecimalTest class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-31
 *
 */

namespace Vanilo\Support\Tests;

use PHPUnit\Framework\TestCase;
use Vanilo\Contracts\Decimal as DecimalContract;
use Vanilo\Support\Decimal;

class DecimalTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $decimal = new Decimal('1.33');

        $this->assertInstanceOf(DecimalContract::class, $decimal);
    }

    /** @test */
    public function it_can_be_created_from_string()
    {
        $decimal = Decimal::create('23.811');

        $this->assertInstanceOf(DecimalContract::class, $decimal);
    }

    /** @test */
    public function it_can_be_created_from_integer()
    {
        $decimal = Decimal::create(35);

        $this->assertInstanceOf(DecimalContract::class, $decimal);
    }

    /** @test */
    public function it_returns_the_precision()
    {
        $decimal = Decimal::create('1.33', 3);

        $this->assertEquals(3, $decimal->precision());
    }

    /** @test */
    public function it_tells_if_is_not_a_number()
    {
        $nan = Decimal::create('NAN');

        $this->assertTrue($nan->isNaN());
    }

    /** @test */
    public function it_tells_if_it_is_infinite()
    {
        $infinite = Decimal::create('INF');

        $this->assertTrue($infinite->isInf());
    }

    /** @test */
    public function it_tells_if_it_is_an_integer()
    {
        $this->assertTrue(Decimal::create('1')->isInteger());
        $this->assertTrue(Decimal::create(2)->isInteger());
        $this->assertFalse(Decimal::create('3.1')->isInteger());
    }

    /** @test */
    public function it_tells_if_it_is_zero()
    {
        $this->assertTrue(Decimal::create('0')->isZero());
        $this->assertTrue(Decimal::create(0)->isZero());
        $this->assertFalse(Decimal::create('0.0000001')->isZero());
    }

    /** @test */
    public function it_tells_if_it_is_a_negative_number()
    {
        $this->assertTrue(Decimal::create(-12)->isNegative());
        $this->assertTrue(Decimal::create('-2588')->isNegative());
        $this->assertFalse(Decimal::create('2588')->isNegative());
    }

    /** @test */
    public function it_tells_if_it_is_a_positive_number()
    {
        $this->assertTrue(Decimal::create(12)->isPositive());
        $this->assertTrue(Decimal::create('2588')->isPositive());
        $this->assertFalse(Decimal::create('-2588')->isPositive());
    }

    /** @test */
    public function it_tells_if_it_is_an_even_number()
    {
        $this->assertTrue(Decimal::create(12)->isEven());
        $this->assertFalse(Decimal::create(11)->isEven());
    }

    /** @test */
    public function it_tells_if_it_is_an_odd_number()
    {
        $this->assertTrue(Decimal::create(11)->isOdd());
        $this->assertFalse(Decimal::create(12)->isOdd());
    }

    /** @test */
    public function it_can_return_the_absolute_value()
    {
        $this->assertEquals(12, Decimal::create('-12')->abs()->toInt());
        $this->assertEquals(13, Decimal::create('13')->abs()->toInt());
    }

    /** @test */
    public function it_can_return_the_negated_value()
    {
        $this->assertEquals(12, Decimal::create('-12')->negate()->toInt());
        $this->assertEquals(-13, Decimal::create('13')->negate()->toInt());
    }

    /** @test */
    public function it_returns_the_closest_integer_towards_negative_infinity()
    {
        $this->assertEquals(12, Decimal::create('12.5')->floor()->toInt());
        $this->assertEquals(12, Decimal::create('12.4999')->floor()->toInt());
        $this->assertEquals(12, Decimal::create('12.99')->floor()->toInt());
    }

    /** @test */
    public function it_returns_the_closest_integer_towards_positive_infinity()
    {
        $this->assertEquals(13, Decimal::create('12.01')->ceil()->toInt());
        $this->assertEquals(13, Decimal::create('12.4999')->ceil()->toInt());
        $this->assertEquals(13, Decimal::create('12.99')->ceil()->toInt());
    }

    /** @test */
    public function it_returns_the_number_after_discarding_all_digits_behind_the_decimal_point()
    {
        $this->assertEquals(15, Decimal::create('15.001')->truncate()->toInt());
        $this->assertEquals(15, Decimal::create('15.4999')->truncate()->toInt());
        $this->assertEquals(15, Decimal::create('15.99')->truncate()->toInt());
    }

    /** @test */
    public function it_can_return_the_rounded_value()
    {
        $this->assertEquals('22', Decimal::create('22.001')->round()->toString());
        $this->assertEquals(15, Decimal::create('15.4999')->round()->toString());
        $this->assertEquals(16, Decimal::create('15.99')->round()->toString());
        $this->assertEquals('16.2', Decimal::create('16.199')->round(1)->toString());
        $this->assertEquals('33.49', Decimal::create('33.494')->round(2)->toString());
        $this->assertEquals('31.445', Decimal::create('31.44459')->round(3)->toString());
    }

    /** @test */
    public function it_can_return_the_decimal_without_trailing_zeroes()
    {
        // Prove that it doesn't trim by default:
        $this->assertEquals('12.12000000', Decimal::create('12.12000000')->toString());
        // Now the real case:
        $this->assertEquals('12.12', Decimal::create('12.12000000')->trim()->toString());
    }

    /** @test */
    public function it_can_return_formatted_to_a_fixed_number_of_decimal_places()
    {
        $this->assertEquals('12.12', Decimal::create('12.121212')->toFixed(2));
        $this->assertEquals('12.121', Decimal::create('12.121212')->toFixed(3));
        $this->assertEquals('12.125', Decimal::create('12.12499')->toFixed(3));
    }

    /** @test */
    public function it_can_compare_for_equality()
    {
        $this->assertTrue(Decimal::create(123)->equals(123));
        $this->assertTrue(Decimal::create('456')->equals(456));
        $this->assertTrue(Decimal::create(789)->equals('789'));
        $this->assertTrue(Decimal::create(789)->equals(Decimal::create(789)));
        $this->assertTrue(Decimal::create('333')->equals(new \Decimal\Decimal(333)));
    }

    /** @test */
    public function it_can_compare_decimals_in_the_spaceship_operator_way()
    {
        $this->assertEquals(-1, Decimal::create(123)->compareTo(124));
        $this->assertEquals(-1, Decimal::create(123)->compareTo(new Decimal(124)));
        $this->assertEquals(1, Decimal::create(123)->compareTo(122));
        $this->assertEquals(1, Decimal::create(123)->compareTo(new \Decimal\Decimal(122)));
        $this->assertEquals(0, Decimal::create(123)->compareTo(123));
        $this->assertEquals(0, Decimal::create('123.1')->compareTo(123.1));
    }

    /** @test */
    public function it_can_be_retrieved_as_int()
    {
        $this->assertIsInt(Decimal::create('111')->toInt());
        $this->assertEquals(111, Decimal::create('111')->toInt());
        $this->assertEquals(111, Decimal::create('111.123')->toInt());
        $this->assertEquals(111, Decimal::create('111.999')->toInt());
    }

    /** @test */
    public function it_can_be_retrieved_as_float()
    {
        $this->assertIsFloat(Decimal::create('111')->toFloat());
        $this->assertEquals(111.12, Decimal::create('111.12')->toFloat());
        $this->assertEquals(4.334, Decimal::create('4.334')->toFloat());
        $this->assertEquals(-1.1322, Decimal::create('-1.1322')->toFloat());
    }

    /** @test */
    public function it_can_be_retrieved_as_string()
    {
        $this->assertIsString(Decimal::create('111')->toString());
        $this->assertEquals('111', Decimal::create('111')->toString());
        $this->assertEquals('4.334', Decimal::create('4.334', 4)->toString());
        $this->assertEquals('-1.1322', Decimal::create('-1.1322')->toString());
    }

    /** @test */
    public function numbers_can_be_added()
    {
        $this->assertEquals('111', Decimal::create('100')->add(11)->toString());
        $this->assertEquals('107.33', Decimal::create(107)->add('0.33')->toString());
        $this->assertEquals('111', Decimal::create('100')->add('11')->toString());
        $this->assertEquals('111', Decimal::create('100')->add(new Decimal(11))->toString());
        $this->assertEquals('111', Decimal::create('100')->add(new \Decimal\Decimal(11))->toString());
    }

    /** @test */
    public function numbers_can_be_subtracted()
    {
        $this->assertEquals('100', Decimal::create('123')->sub(23)->toString());
        $this->assertEquals('37', Decimal::create('39')->sub('2')->toString());
        $this->assertEquals('20.51', Decimal::create('20.52')->sub('0.01')->toString());
        $this->assertEquals('111', Decimal::create('122')->sub(new Decimal(11))->toString());
        $this->assertEquals('111', Decimal::create('122')->sub(new \Decimal\Decimal(11))->toString());
    }

    /** @test */
    public function it_doesnt_have_the_floating_point_rounding_problem()
    {
        // Prove that floats suck:
        $this->assertEquals('0.0000000000000000555', sprintf('%.19f', 0.1 + 0.2 - 0.3));
        // Prove that decimals don't:
        $this->assertEquals('0.0000000000000000000', Decimal::sum(['0.1', '0.2', '-0.3'])->toFixed(19));
    }

    /** @test */
    public function numbers_can_be_multiplied()
    {
        $this->assertEquals('100', Decimal::create('10')->mul(10)->toString());
        $this->assertEquals('750', Decimal::create('150')->mul('5')->toString());
        $this->assertEquals('20.60', Decimal::create('1.03')->mul('20')->toString());
        $this->assertEquals('4096', Decimal::create('2048')->mul(new Decimal(2))->toString());
        $this->assertEquals('200', Decimal::create('2')->mul(new \Decimal\Decimal(100))->toString());
    }

    /** @test */
    public function numbers_can_be_divided()
    {
        $this->assertEquals('10', Decimal::create('100')->div(10)->toString());
        $this->assertEquals('150', Decimal::create('750')->div('5')->toString());
        $this->assertEquals('1.03', Decimal::create('20.60')->div('20')->toString());
        $this->assertEquals('2048', Decimal::create('4096')->div(new Decimal(2))->toString());
        $this->assertEquals('0.02', Decimal::create('2')->div(new \Decimal\Decimal(100))->toString());
    }

    /** @test */
    public function modulus_can_be_calculated()
    {
        $this->assertEquals(10 % 5, Decimal::create('10')->mod(5)->toString());
        $this->assertEquals(750 % 123, Decimal::create('750')->mod('123')->toString());
        $this->assertEquals(20.60 % 20, Decimal::create('20.60')->mod('20')->toString());
        $this->assertEquals(11, Decimal::create('27')->mod(new Decimal(16))->toInt());
        $this->assertEquals(0, Decimal::create('30')->mod(new \Decimal\Decimal(3))->toInt());
    }

    /** @test */
    public function remainder_can_be_calculated()
    {
        $this->assertEquals(0, Decimal::create('10')->rem(5)->toInt());
        $this->assertEquals(12, Decimal::create('750')->rem('123')->toInt());
        $this->assertEquals('0.60', Decimal::create('20.60')->rem('20')->toString());
        $this->assertEquals(11, Decimal::create('27')->rem(new Decimal(16))->toInt());
        $this->assertEquals(1, Decimal::create('31')->rem(new \Decimal\Decimal(3))->toInt());
    }

    /** @test */
    public function power_can_be_calculated()
    {
        $this->assertEquals(100000, Decimal::create('10')->pow(5)->toInt());
        $this->assertEquals(256, Decimal::create('2')->pow('8')->toInt());
        $this->assertEquals('189384489400471525707723655.9', Decimal::create('20.60')->pow('20')->toString());
        $this->assertEquals(65536, Decimal::create('2')->pow(new Decimal(16))->toInt());
        $this->assertEquals(29791, Decimal::create('31')->pow(new \Decimal\Decimal(3))->toInt());
    }

    /** @test */
    public function decimal_places_can_be_shifted()
    {
        $this->assertEquals('100.25', Decimal::create('10025')->shift(-2)->toString());
        $this->assertEquals('10025.54', Decimal::create('100.2554')->shift(2)->toString());
    }

    /** @test */
    public function sum_of_a_series_of_numbers_can_be_calculated()
    {
        $this->assertEquals('100.25', Decimal::sum(['100', '0.25'])->toString());
        $this->assertEquals('2.55', Decimal::sum(['100', '0.25', '-100', '2.3'])->toString());
        $this->assertEquals('2.55', Decimal::sum([100, '0.25', -100, '2.3'])->toString());
        $this->assertEquals('2.55', Decimal::sum([new Decimal(100), new \Decimal\Decimal('0.25'), -100, '2.3'])->toString());
    }

    /** @test */
    public function average_of_a_series_of_numbers_can_be_calculated()
    {
        $this->assertEquals('50.00', Decimal::avg(['100', '0.00'])->toString());
        $this->assertEquals('50', Decimal::avg(['100', '0.00'])->toInt());
        $this->assertEquals('0.6375', Decimal::avg(['100', '0.25', '-100', '2.3'])->toString());
        $this->assertEquals('0.6375', Decimal::avg([100, '0.25', -100, '2.3'])->toString());
        $this->assertEquals('0.6375', Decimal::avg([new Decimal(100), new \Decimal\Decimal('0.25'), -100, '2.3'])->toString());
    }
}
