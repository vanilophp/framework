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
use Illuminate\Support\Facades\Event;
use Vanilo\Checkout\Contracts\CheckoutDataFactory;
use Vanilo\Checkout\Contracts\CheckoutRequest;
use Vanilo\Checkout\Events\BillpayerChanged;
use Vanilo\Checkout\Events\ShippingAddressChanged;
use Vanilo\Checkout\Traits\EmulatesFillAttributes;
use Vanilo\Checkout\Traits\HasCheckoutState;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Contracts\DetailedAmount;
use Vanilo\Support\Dto\DetailedAmount as DetailedAmountDto;

/**
 * Stores & fetches checkout data across http requests.
 * This is a simple and lightweight and variant for
 * cases when having volatile checkout data is ✔
 */
class RequestStore extends BaseCheckoutStore
{
    use HasCheckoutState;
    use EmulatesFillAttributes;

    protected $state;

    protected array $ownAttributes = ['shipping_method_id', 'payment_method_id', 'ship_to_billing_address'];

    /** @var  Billpayer */
    protected $billpayer;

    protected ?Address $shippingAddress = null;

    protected ?string $shippingMethodId = null;

    protected bool $shipToBillingAddress = false;

    protected DetailedAmount $shippingFees;

    protected DetailedAmount $taxes;

    protected DetailedAmount $promotions;

    protected CheckoutRequest $request;

    /** @var array */
    protected $customData = [];

    public function __construct(CheckoutDataFactory $dataFactory, Request|CheckoutRequest $request = null)
    {
        parent::__construct($dataFactory);
        $this->request = ImplicitCheckoutRequest::from($request ?? request());
        $this->billpayer = $dataFactory->createBillpayer();
        if ($this->request->wantsShipping()) {
            $this->updateShippingAddress($this->request->getShippingAddress());
            $this->shippingAddress = $dataFactory->createShippingAddress();
        }
        $this->taxes = new DetailedAmountDto(0);
        $this->shippingFees = new DetailedAmountDto(0);
        $this->promotions = new DetailedAmountDto(0);
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
        Event::dispatch(new BillpayerChanged($this));
    }

    public function getShippingAddress(): ?Address
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(Address $address): void
    {
        $this->shippingAddress = $address;
        Event::dispatch(new ShippingAddressChanged($this));
    }

    public function removeShippingAddress(): void
    {
        $this->shippingAddress = null;
        Event::dispatch(new ShippingAddressChanged($this));
    }

    public function getShippingAmount(): DetailedAmount
    {
        return $this->shippingFees;
    }

    public function setShippingAmount(float|DetailedAmount $amount): void
    {
        $this->shippingFees = $amount instanceof DetailedAmount ? $amount : new DetailedAmountDto($amount);
    }

    public function getTaxesAmount(): DetailedAmount
    {
        return $this->taxes;
    }

    public function setTaxesAmount(float|DetailedAmount $amount): void
    {
        $this->taxes = $amount instanceof DetailedAmount ? $amount : new DetailedAmountDto($amount);
    }

    public function getPromotionsAmount(): DetailedAmount
    {
        return $this->promotions;
    }

    public function setPromotionsAmount(float|DetailedAmount $amount): void
    {
        $this->promotions = $amount instanceof DetailedAmount ? $amount : new DetailedAmountDto($amount);
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

    public function offsetExists(mixed $offset): bool
    {
        return $this->request->has($offset);
    }

    public function offsetUnset(mixed $offset): void
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
