<?php
/**
 * Contains the Customer interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-26
 *
 */


namespace Vanilo\Contracts;


interface Customer
{
    /**
     * Returns the name of the customer (either company or person's name)
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the first name of the client
     *
     * @return string|null
     */
    public function getFirstName();

    /**
     * Returns the last name of the client
     *
     * @return string|null
     */
    public function getLastName();

    /**
     * Returns the Company name
     *
     * @return string|null
     */
    public function getCompanyName();

    /**
     * Customer's email address
     *
     * @return string|null
     */
    public function getEmail();

    /**
     * Customer's phone number
     *
     * @return string|null
     */
    public function getPhone();

    /**
     * Customer's tax number (VAT id, tax id, etc)
     *
     * @return string|null
     */
    public function getTaxNumber();

}