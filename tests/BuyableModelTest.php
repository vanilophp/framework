<?php
/**
 * Contains the BuyableModelTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-05-10
 *
 */

namespace Vanilo\Support\Tests;

use PHPUnit\Framework\TestCase;
use Vanilo\Contracts\Buyable;
use Vanilo\Contracts\Decimal;
use Vanilo\Support\Tests\Dummies\HeyBuyMe;

class BuyableModelTest extends TestCase
{
    /** @test */
    public function the_trait_implementation_complies_the_contract()
    {
        $buyable = new HeyBuyMe();
        $this->assertInstanceOf(Buyable::class, $buyable);
    }

    /** @test */
    public function the_price_method_returns_a_proper_decimal()
    {
        $buyable = new HeyBuyMe();
        $buyable->price = 12.44;

        $this->assertInstanceOf(Decimal::class, $buyable->getPrice());
        $this->assertEquals('12.44', $buyable->getPrice()->toString());
    }

    /** @test */
    public function the_decimal_price_eliminates_the_float_bug_in_php()
    {
        ini_set('precision', 17);
        $this->assertEquals('0.30000000000000004', (string) (0.1 + 0.2));

        $buyable = new HeyBuyMe();
        $buyable->price = '0.1';
        $buyable->price = $buyable->getPrice()->add('0.2');

        $this->assertInstanceOf(Decimal::class, $buyable->getPrice());
        $this->assertEquals('0.3', $buyable->getPrice()->toString());
    }
}
