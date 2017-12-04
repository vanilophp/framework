<?php
/**
 * Contains the Contactable interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-04
 *
 */


namespace Vanilo\Contracts;


interface Contactable
{
    /**
     * The contact's email address
     *
     * @return string|null
     */
    public function getEmail();

    /**
     * The contact's phone number
     *
     * @return string|null
     */
    public function getPhone();
}
