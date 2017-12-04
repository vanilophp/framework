<?php
/**
 * Contains the BillingDetails interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-04
 *
 */


namespace Vanilo\Contracts;

/**
 * A customer (either company or individual person) that
 * receives an Invoice
 *
 */
interface BillingSubject extends Customer
{
    /**
     * Returns whether the customer is registered in the EU
     *
     * @return bool
     */
    public function isEuRegistered();

    /**
     * Returns the physical address
     *
     * @return Address
     */
    public function getAddress() : Address;
}
