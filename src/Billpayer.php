<?php
/**
 * Contains the Billpayer interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-04
 *
 */

namespace Vanilo\Contracts;

/**
 * A customer (either an organization or an individual person) that receives an Invoice
 */
interface Billpayer extends Customer
{
    /**
     * Returns whether the customer is registered in the EU
     */
    public function isEuRegistered(): bool;

    /**
     * Returns the billing address
     */
    public function getBillingAddress(): Address;
}
