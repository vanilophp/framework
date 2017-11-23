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


namespace Vanilo\Checkout;


use Konekt\Client\Contracts\Client;
use Vanilo\Checkout\Contracts\Checkout as CheckoutContract;
use Vanilo\Checkout\Contracts\CheckoutState as CheckoutStateContract;
use Vanilo\Checkout\Contracts\CheckoutStore;
use Vanilo\Contracts\CheckoutSubject;

class CheckoutManager implements CheckoutContract
{
    protected $client;

    /** @var  CheckoutStore */
    protected $store;

    //public function __construct($client = null, $billingAddress = null, $shippingAddress = null)
    public function __construct(CheckoutStore $store)
    {
        $this->store = $store;
        $this->client = app(Client::class);
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

    public function getClient()
    {
        return $this->client;
    }

    public function getBillingAddress()
    {
        return $this->store->getBillingAddress();
    }

    public function getShippingAddress()
    {
        return $this->store->getShippingAddress();
    }

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