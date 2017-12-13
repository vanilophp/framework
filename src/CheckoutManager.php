<?php
/**
 * Contains the Checkout Manager class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-13
 *
 */


namespace Vanilo\Checkout;


use Vanilo\Checkout\Contracts\Checkout as CheckoutContract;
use Vanilo\Checkout\Contracts\CheckoutState as CheckoutStateContract;
use Vanilo\Checkout\Contracts\CheckoutStore;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Contracts\CheckoutSubject;

class CheckoutManager implements CheckoutContract
{
    /** @var  CheckoutStore */
    protected $store;

    public function __construct(CheckoutStore $store)
    {
        $this->store = $store;
    }

    /**
     * @inheritDoc
     */
    public function getCart()
    {
        return $this->store->getCart();
    }

    /**
     * @inheritDoc
     */
    public function setCart(CheckoutSubject $cart)
    {
        $this->store->setCart($cart);
    }

    /**
     * @inheritdoc
     */
    public function getState(): CheckoutStateContract
    {
        return $this->store->getState();
    }

    /**
     * @inheritdoc
     */
    public function setState($state)
    {
        $this->store->setState($state);
    }

    /**
     * @inheritdoc
     */
    public function getBillpayer() : Billpayer
    {
        return $this->store->getBillpayer();
    }

    /**
     * @inheritdoc
     */
    public function setBillpayer(Billpayer $billpayer)
    {
        return $this->store->setBillpayer($billpayer);
    }

    /**
     * @inheritdoc
     */
    public function getShippingAddress(): Address
    {
        return $this->store->getShippingAddress();
    }

    /**
     * @inheritDoc
     */
    public function setShippingAddress(Address $address)
    {
        $this->store->setShippingAddress($address);
    }

    /**
     * @inheritdoc
     */
    public function update(array $data)
    {
        $this->store->update($data);
    }

    /**
     * @inheritDoc
     */
    public function total()
    {
        return $this->store->total();
    }
}
