<?php
/**
 * Contains the RequestStore class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-23
 *
 */


namespace Vanilo\Checkout\Drivers;


use Konekt\Address\Contracts\Address;
use Konekt\Address\Models\AddressType;
use Vanilo\Checkout\Contracts\CheckoutStore;
use Vanilo\Checkout\Traits\HasCart;
use Vanilo\Checkout\Traits\HasCheckoutState;

/**
 * Stores & fetches checkout data across http requests.
 * This is a simple and lightweight and variant for
 * cases when having volatile checkout data is ✔
 */
class RequestStore implements CheckoutStore
{
    use HasCheckoutState, HasCart;

    protected $state;

    /** @var  Address */
    protected $billingAddress;

    /** @var  Address */
    protected $shippingAddress;

    public function __construct($config)
    {
        if (!$this->billingAddress) {
            $this->billingAddress = app(Address::class);
            $this->billingAddress->type = AddressType::BILLING;
        }

        if (!$this->shippingAddress) {
            $this->shippingAddress = app(Address::class);
            $this->shippingAddress->type = AddressType::SHIPPING;
        }
    }

    public function update(array $data)
    {
        if (isset($data['billingAddress'])) {
            $this->updateBillingAddress($data['billingAddress']);
        }

        if (isset($data['shippingAddress'])) {
            $this->updateShippingAddress($data['shippingAddress']);
        }
    }

    /**
     * @inheritdoc
     */
    public function total()
    {
        return $this->cart->total();
    }

    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    protected function updateBillingAddress($data)
    {
        $this->billingAddress->country_id = $data['country_id'];
        $this->billingAddress->address    = $data['address'];
        $this->billingAddress->city       = $data['city'];
        $this->billingAddress->name       = array_get($data, 'name');
    }

    protected function updateShippingAddress($data)
    {
        $this->shippingAddress->country_id = $data['country'];
        $this->shippingAddress->address    = $data['address'];
        $this->shippingAddress->city       = $data['city'];
        $this->shippingAddress->name       = array_get($data, 'name');
    }
}
