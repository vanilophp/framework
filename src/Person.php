<?php
/**
 * Contains the Person interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-04
 *
 */


namespace Vanilo\Contracts;


interface Person extends Contactable
{
    /**
     * Returns the first name of the person
     *
     * @return string|null
     */
    public function getFirstName();

    /**
     * Returns the last name of the person
     *
     * @return string|null
     */
    public function getLastName();

    /**
     * Returns the full name of the person (in appropriate name order)
     *
     * @return string
     */
    public function getFullName();

}