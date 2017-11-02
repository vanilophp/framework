<?php
/**
 * Contains the CheckoutState interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-02
 *
 */


namespace Vanilo\Checkout\Contracts;

interface CheckoutState
{
    /**
     * Returns whether the state represents a state where the checkout can be submitted
     *
     * @return bool
     */
    public function canBeSubmitted();

    /**
     * Returns an array of states that represent an checkout state that is ready to be submitted
     *
     * @return array
     */
    public static function getSubmittableStates();
}
