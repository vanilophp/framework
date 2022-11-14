<?php

declare(strict_types=1);

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

use Illuminate\Support\Arr;
use Vanilo\Checkout\Contracts\CheckoutDataFactory;
use Vanilo\Checkout\Contracts\CheckoutStore;
use Vanilo\Checkout\Traits\EmulatesFillAttributes;
use Vanilo\Checkout\Traits\FillsCommonCheckoutAttributes;
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
    use HasCheckoutState;
    use HasCart;
    use EmulatesFillAttributes;
    use FillsCommonCheckoutAttributes;

    protected $state;

    /** @var  Billpayer */
    protected $billpayer;

    /** @var  Address */
    protected $shippingAddress;

    /** @var  CheckoutDataFactory */
    protected $dataFactory;

    /** @var array */
    protected $customData = [];

    /**
     * @todo remove the first, $config parameter in v4
     *       it was never in use, but after 5 years
     *       people might be using it, thus a BC
     */
    public function __construct($config, CheckoutDataFactory $dataFactory)
    {
        $this->dataFactory = $dataFactory;
        $this->billpayer = $dataFactory->createBillpayer();
        $this->shippingAddress = $dataFactory->createShippingAddress();
    }

    /**
     * @inheritdoc
     */
    public function update(array $data)
    {
        if (isset($data['billpayer'])) {
            $this->updateBillpayer($data['billpayer'] ??  []);
        }

        if (Arr::get($data, 'ship_to_billing_address')) {
            $shippingAddress = $data['billpayer']['address'];
            $shippingAddress['name'] = $this->getShipToName();
        } else {
            $shippingAddress = $data['shipping_address'] ?? ($data['shippingAddress'] ?? []);
        }

        $this->updateShippingAddress($shippingAddress);

        foreach (Arr::except($data, ['billpayer', 'ship_to_billing_address', 'shipping_address', 'shippingAddress']) as $key => $value) {
            $this->setCustomAttribute($key, $value);
        }
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

    public function setCustomAttribute(string $key, $value): void
    {
        Arr::set($this->customData, $key, $value);
    }

    public function getCustomAttribute(string $key)
    {
        return Arr::get($this->customData, $key);
    }

    public function putCustomAttributes(array $data): void
    {
        $this->customData = $data;
    }

    public function getCustomAttributes(): array
    {
        return $this->customData;
    }
}
