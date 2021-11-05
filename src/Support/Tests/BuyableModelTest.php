<?php

declare(strict_types=1);

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
    public function the_price_method_returns_a_float_value()
    {
        $buyable = new HeyBuyMe();
        $buyable->price = 12.44;

        $this->assertIsFloat($buyable->getPrice());
        $this->assertEquals(12.44, $buyable->getPrice());
    }
}
