<?php
/**
 * Contains the CheckoutDataFactory interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-26
 *
 */

namespace Vanilo\Checkout\Contracts;

use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;

interface CheckoutDataFactory
{
    // Payment method
    // Shipping method
    // Notes
    // Other fields like "Chosen Gift"
    // Accept terms & conditions
    // Accept GDPR
    // Some shops apply coupons at checkout - still should apply to Cart though
    // Newsletter option
    // Checkbox to register account (maybe even password)
    public function createBillpayer(): Billpayer;

    public function createShippingAddress(): Address;
}
