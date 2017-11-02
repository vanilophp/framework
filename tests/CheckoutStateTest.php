<?php
/**
 * Contains the CheckoutStateTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-02
 *
 */

namespace Vanilo\Checkout\Tests;

use Vanilo\Checkout\Models\CheckoutState;

class CheckoutStateTest extends TestCase
{
    /**
     * @test
     */
    public function ready_is_a_submittable_state()
    {
        $this->assertTrue(CheckoutState::READY()->canBeSubmitted());
    }

    /**
     * @test
     */
    public function the_default_state_is_not_submittable()
    {
        $this->assertFalse(CheckoutState::create()->canBeSubmitted());
    }
}
