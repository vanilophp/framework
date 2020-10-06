<?php

declare(strict_types=1);

/**
 * Contains the BillpayerTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-10-06
 *
 */

namespace Vanilo\Order\Tests;

use Vanilo\Order\Models\Billpayer;

class BillpayerTest extends TestCase
{
    /** @test */
    public function it_returns_proper_bool_types_when_the_model_is_empty()
    {
        $billPayer = new Billpayer();

        $this->assertIsBool($billPayer->isOrganization());
        $this->assertIsBool($billPayer->isEuRegistered());
        $this->assertIsBool($billPayer->isIndividual());
    }
}
