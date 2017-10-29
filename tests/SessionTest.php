<?php
/**
 * Contains the Session test class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-29
 *
 */


namespace Konekt\Cart\Tests;


/**
 * @test
 */
class SessionTest extends TestCase
{
    /**
     * @test
     */
    function a_session_has_no_cart_by_default()
    {
        $this->assertTrue(true);
        //$this->assertFalse(Cart::doesExist());
    }

    public function setUp()
    {
        parent::setUp();

        $this->startSession();
    }


}