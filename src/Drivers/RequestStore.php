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
use Vanilo\Checkout\Traits\EmulatesFillAttributes;
use Vanilo\Checkout\Traits\HasCart;
use Vanilo\Checkout\Traits\HasCheckoutState;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;

/**
 * Stores & fetches checkout data across http requests.
 * This is a simple and lightweight and variant for
 * cases when having volatile checkout data is ✔
 */
class RequestStore implements CheckoutStore
{
    use HasCheckoutState, HasCart, EmulatesFillAttributes;

    protected $state;

    /** @var  Billpayer */
    protected $billpayer;

    /** @var  Address */
    protected $shippingAddress;

    /** @var  CheckoutDataFactory */
    protected $dataFactory;

    public function __construct($config, CheckoutDataFactory $dataFactory)
    {
        $this->dataFactory     = $dataFactory;
        $this->billpayer       = $dataFactory->createBillpayer();
        $this->shippingAddress = $dataFactory->createShippingAddress();
    }

    /**
     * @inheritdoc
     */
    public function update(array $data)
    {
        $this->updateBillpayer($data['billpayer'] ??  []);

        if (array_get($data, 'ship_to_billing_address')) {
            $shippingAddress         = $data['billpayer']['address'];
            $shippingAddress['name'] = $this->getShipToName();
        } else {
            $shippingAddress = $data['shippingAddress'] ?? [];
        }

        $this->updateShippingAddress($shippingAddress);
    }

    /**
     * @inheritdoc
     */
    public function total()
    {
        return $this->cart->total();
    }

    /**
     * @inheritdoc
     */
    public function getBillpayer(): Billpayer
    {
        return $this->billpayer;
    }

    /**
     * @inheritdoc
     */
    public function setBillpayer(Billpayer $billpayer)
    {
        $this->billpayer = $billpayer;
    }

    /**
     * @inheritdoc
     */
    public function getShippingAddress(): Address
    {
        return $this->shippingAddress;
    }

    /**
     * @inheritdoc
     */
    public function setShippingAddress(Address $address)
    {
        return $this->shippingAddress = $address;
    }

    /**
     * @inheritdoc
     */
    protected function updateBillpayer($data)
    {
        $this->fill($this->billpayer, array_except($data, 'address'));
        $this->fill($this->billpayer->address, $data['address']);
    }

    /**
     * @inheritdoc
     */
    protected function updateShippingAddress($data)
    {
        $this->fill($this->shippingAddress, $data);
    }

    private function fill($target, array $attributes)
    {
        if (method_exists($target, 'fill')) {
            $target->fill($attributes);
        } else {
            $this->fillAttributes($target, $attributes);
        }
    }

    private function getShipToName()
    {
        if ($this->billpayer->isOrganization()) {
            return sprintf('%s (%s)',
                $this->billpayer->getCompanyName(),
                $this->billpayer->getFullName()
            );
        }

        return $this->billpayer->getName();
    }
}
