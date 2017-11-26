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


use Vanilo\Checkout\Contracts\CheckoutDataFactory;
use Vanilo\Checkout\Contracts\CheckoutStore;
use Vanilo\Checkout\Traits\HasCart;
use Vanilo\Checkout\Traits\HasCheckoutState;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Customer;

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

    /** @var  Customer */
    protected $customer;

    /** @var  CheckoutDataFactory */
    protected $dataFactory;

    public function __construct($config, CheckoutDataFactory $dataFactory)
    {
        $this->dataFactory = $dataFactory;

        $this->billingAddress  = $dataFactory->createBillingAddress();
        $this->shippingAddress = $dataFactory->createShippingAddress();
        $this->customer        = $dataFactory->createCustomer();
    }

    public function update(array $data)
    {
        foreach (array_keys($data) as $key) {
            $method = sprintf('update%s', ucfirst($key));
            if (method_exists($this, $method)) {
                $this->{$method}($data[$key]);
            }
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

    public function getCustomer()
    {
        return $this->customer;
    }

    protected function updateBillingAddress($data)
    {
        $this->billingAddress->fill($data);
    }

    protected function updateShippingAddress($data)
    {
        $this->shippingAddress->fill($data);
    }

    protected function updateCustomer($data)
    {
        $this->customer->fill($data);
    }
}
