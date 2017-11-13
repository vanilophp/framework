<?php
/**
 * Contains the Checkout model class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-13
 *
 */


namespace Vanilo\Checkout\Models;


use Vanilo\Checkout\Contracts\Checkout as CheckoutContract;
use Vanilo\Checkout\Contracts\CheckoutState as CheckoutStateContract;
use Vanilo\Contracts\CheckoutSubject;

class Checkout implements CheckoutContract
{
    /** @var  CheckoutSubject */
    protected $cart;

    /** @var  CheckoutStateContract */
    protected $state;

    /**
     * @inheritDoc
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @inheritDoc
     */
    public function setCart(CheckoutSubject $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @inheritdoc
     */
    public function getState(): CheckoutStateContract
    {
        return $this->state instanceof CheckoutStateContract ? $this->state : CheckoutStateProxy::create($this->state);
    }

    /**
     * @inheritdoc
     */
    public function setState(CheckoutStateContract $state)
    {
        $this->state = $state instanceof CheckoutStateContract ? $state : CheckoutStateProxy::create($state);
    }

    /**
     * @inheritDoc
     */
    public function total()
    {
        return $this->cart ? $this->cart->total() : 0;
    }


}