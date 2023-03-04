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

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Vanilo\Checkout\Contracts\CheckoutDataFactory;
use Vanilo\Checkout\Traits\EmulatesFillAttributes;
use Vanilo\Checkout\Traits\FillsCommonCheckoutAttributes;
use Vanilo\Checkout\Traits\HasCheckoutState;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;

/**
 * Stores & fetches checkout data across http requests.
 * This is a simple and lightweight and variant for
 * cases when having volatile checkout data is ✔
 */
class RequestStore extends BaseCheckoutStore
{
    use HasCheckoutState;
    use EmulatesFillAttributes;
    use FillsCommonCheckoutAttributes;

    protected $state;

    protected array $ownAttributes = ['shipping_method_id', 'payment_method_id', 'ship_to_billing_address'];

    /** @var  Billpayer */
    protected $billpayer;

    /** @var  Address */
    protected $shippingAddress;

    protected ?string $shippingMethodId = null;

    protected bool $shipToBillingAddress = false;

    protected Request $request;

    /** @var array */
    protected $customData = [];

    /**
     * @todo remove the first, $config parameter in v4
     *       it was never in use, but after 5 years
     *       people might be using it, thus a BC
     */
    public function __construct($config, CheckoutDataFactory $dataFactory, Request $request = null)
    {
        parent::__construct($dataFactory);
        $this->request = $request ?? request();
        $this->billpayer = $dataFactory->createBillpayer();
        $this->shippingAddress = $dataFactory->createShippingAddress();
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

    public function clear(): void
    {
        $this->request->replace([]);
    }

    public function offsetExists(mixed $offset)
    {
        return $this->request->has($offset);
    }

    public function offsetUnset(mixed $offset)
    {
        $this->request->offsetUnset($offset);
    }

    protected function readRawDataFromStore(string $key, $default = null): mixed
    {
        return $this->request->input($key, $this->request->old($key) ?? $default);
    }

    protected function writeRawDataToStore(string $key, mixed $data): void
    {
        $this->request->offsetSet($key, $data);
    }
}
