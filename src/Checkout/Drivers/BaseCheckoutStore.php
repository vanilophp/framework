<?php

declare(strict_types=1);

/**
 * Contains the BaseCheckoutStore class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-04
 *
 */

namespace Vanilo\Checkout\Drivers;

use ArrayAccess;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Vanilo\Checkout\Contracts\CheckoutDataFactory;
use Vanilo\Checkout\Contracts\CheckoutState;
use Vanilo\Checkout\Contracts\CheckoutStore;
use Vanilo\Checkout\Events\BillpayerChanged;
use Vanilo\Checkout\Events\ShippingMethodSelected;
use Vanilo\Checkout\Models\CheckoutStateProxy;
use Vanilo\Checkout\Traits\EmulatesFillAttributes;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Contracts\CheckoutSubject;
use Vanilo\Contracts\DetailedAmount;
use Vanilo\Contracts\Dimension;
use Vanilo\Contracts\Shippable;

abstract class BaseCheckoutStore implements CheckoutStore
{
    use EmulatesFillAttributes;

    protected ?CheckoutSubject $cart = null;

    // Attributes that always must be retrieved via their getter & written via their setter methods
    protected array $attributesViaGetterSetter = ['billpayer', 'shipping_address'];

    // Normal attributes that can be saved to the store without conversion
    protected array $attributesPlain = ['shipping_method_id', 'payment_method_id', 'ship_to_billing_address', 'notes'];

    // Attribute aliases (in key) that will be forwarded to their actual attribute (in value)
    protected array $attributeAliases = [
        'shippingAddress' => 'shipping_address',
        'paymentMethod' => 'payment_method_id',
        'shippingMethod' => 'shipping_method_id',
    ];

    public function __construct(
        protected CheckoutDataFactory $factory,
    ) {
    }

    public function getCart(): ?CheckoutSubject
    {
        return $this->cart;
    }

    public function setCart(CheckoutSubject $cart)
    {
        $this->cart = $cart;
    }

    public function update(array $data)
    {
        $toUpdate = Arr::except($data, ['shipping_address', 'shippingAddress']);
        foreach ($toUpdate as $key => $value) {
            $this->updateAttributeFromArray($key, $value);
        }

        if (Arr::get($data, 'ship_to_billing_address')) {
            $shippingAddress = $data['billpayer']['address'];
            $shippingAddress['name'] = $this->getShipToName($this->getBillpayer());
        } else {
            $shippingAddress = $data['shipping_address'] ?? ($data['shippingAddress'] ?? []);
        }

        $this->updateShippingAddress($shippingAddress);
    }

    public function total()
    {
        return $this->getCart()?->total();
    }

    public function itemsTotal(): float
    {
        return $this->getCart()?->getItems()->sum('total');
    }

    public function getState(): CheckoutState
    {
        $rawState = $this->readRawDataFromStore('state');

        return $rawState instanceof CheckoutState ? $rawState : CheckoutStateProxy::create($rawState);
    }

    public function getShipToBillingAddress(bool $default = true): bool
    {
        return (bool) $this->readRawDataFromStore('ship_to_billing_address', $default);
    }

    public function setShipToBillingAddress(bool $value): void
    {
        $this->writeRawDataToStore('ship_to_billing_address', $value);
    }

    public function setBillpayer(Billpayer $billpayer)
    {
        $this->writeRawDataToStore('billpayer', $billpayer);
        Event::dispatch(new BillpayerChanged($this));
    }

    public function getBillpayer(): Billpayer
    {
        $rawData = $this->readRawDataFromStore('billpayer');

        if ($rawData instanceof Billpayer) {
            return $rawData;
        }

        $result = $this->factory->createBillpayer();
        if (is_array($rawData)) {
            $this->fill($result, Arr::except($rawData, 'address'));
            $this->fill($result->getBillingAddress(), $rawData['address'] ?? []);
        }

        return $result;
    }

    public function getShippingMethodId(): null|int|string
    {
        return $this->readRawDataFromStore('shipping_method_id');
    }

    public function setShippingMethodId(null|int|string $shippingMethodId): void
    {
        $old = $this->getShippingMethodId();
        $this->writeRawDataToStore('shipping_method_id', $shippingMethodId);

        if ($old !== $shippingMethodId) {
            Event::dispatch(new ShippingMethodSelected($this, $old));
        }
    }

    public function getPaymentMethodId(): null|int|string
    {
        return $this->readRawDataFromStore('payment_method_id');
    }

    public function setPaymentMethodId(null|int|string $paymentMethodId): void
    {
        $this->writeRawDataToStore('payment_method_id', $paymentMethodId);
    }

    public function getNotes(): ?string
    {
        return $this->readRawDataFromStore('notes');
    }

    public function setNotes(?string $text): void
    {
        $this->writeRawDataToStore('notes', $text);
    }

    public function setState($state)
    {
        $this->writeRawDataToStore('state', $state instanceof CheckoutState ? $state->value() : $state);
    }

    public function weight(): float
    {
        return floatval($this->getCart()->getItems()->sum('weight'));
    }

    public function weightUnit(): string
    {
        return config('vanilo.checkout.default.weight_unit', 'kg');
    }

    public function dimension(): ?Dimension
    {
        // @todo Calculate the dimensions from the items
        return null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($this->isRegularAttribute($offset)) {
            $this->setAttribute($offset, $value);
        } else {
            $this->setCustomAttribute($offset, $value);
        }
    }

    public function offsetGet(mixed $offset): mixed
    {
        if ($this->isRegularAttribute($offset)) {
            return $this->getAttribute($offset);
        } elseif ($this->isAnAliasAttribute($offset)) {
            return $this->getAttribute($this->getTargetOfAlias($offset));
        } else {
            return $this->getCustomAttribute($offset);
        }
    }

    abstract public function clear(): void;

    /** @todo add this to the interface in v4 */
    abstract public function getShippingAmount(): DetailedAmount;

    /** @todo add this to the interface in v4 */
    abstract public function setShippingAmount(float|DetailedAmount $amount): void;

    /** @todo add this to the interface in v4 */
    abstract public function getTaxesAmount(): DetailedAmount;

    /** @todo add this to the interface in v4 */
    abstract public function setTaxesAmount(float|DetailedAmount $amount): void;

    /**
     * @deprecated
     * @todo remove in v4
     */
    protected function getShipToName(Billpayer $billpayer)
    {
        if ($billpayer->isOrganization()) {
            return sprintf(
                '%s (%s)',
                $billpayer->getCompanyName(),
                $billpayer->getFullName()
            );
        }

        return $billpayer->getName();
    }

    protected function updateBillpayer($data): void
    {
        $billpayer = $this->getBillpayer();
        $this->fill($billpayer, Arr::except($data, 'address'));
        $this->fill($billpayer->address, $data['address']);

        $this->setBillpayer($billpayer);
    }

    protected function updateShippingAddress(null|array|Address $data): void
    {
        if (empty($data)) {
            $this->removeShippingAddress();
            return;
        }

        if ($data instanceof Address) {
            $shippingAddress = $data;
        } else {
            $shippingAddress = $this->getShippingAddress() ?? $this->factory->createShippingAddress();
            $this->fill($shippingAddress, $data);
        }

        $this->setShippingAddress($shippingAddress);
    }

    protected function updateShipToBillingAddress($data): void
    {
        $this->setShipToBillingAddress((bool) $data);
    }

    protected function getAttribute(string $name): mixed
    {
        $getter = 'get' . Str::studly($name);

        if (in_array($name, $this->attributesViaGetterSetter)) {
            return $this->{$getter}();
        } elseif (in_array($name, $this->attributesPlain)) {
            return method_exists($this, $getter) ?
                $this->{$getter}() : $this->readRawDataFromStore($name);
        } elseif ($this->isAnAliasAttribute($name)) {
            return $this->getAttribute($this->getTargetOfAlias($name));
        }

        return null;
    }

    protected function setAttribute(mixed $name, mixed $value)
    {
        $setter = 'set' . Str::studly($name);

        if (in_array($name, $this->attributesViaGetterSetter)) {
            $this->{$setter}($value);
        } elseif (in_array($name, $this->attributesPlain)) {
            if (method_exists($this, $setter)) {
                $this->{$setter}($value);
            } else {
                $this->writeRawDataToStore($name, $value);
            }
        } elseif ($this->isAnAliasAttribute($name)) {
            $this->setAttribute($this->getTargetOfAlias($name), $value);
        }
    }

    protected function updateAttributeFromArray(string $name, mixed $value)
    {
        $method = 'update' . Str::studly($name);
        if (method_exists($this, $method)) {
            $this->{$method}($value);
        } else {
            $this->setAttribute($name, $value);
        }
    }

    protected function getTargetOfAlias(string $name): ?string
    {
        return $this->attributeAliases[$name] ?? null;
    }

    protected function isRegularAttribute(string $name): bool
    {
        return
            in_array($name, $this->attributesViaGetterSetter)
            ||
            in_array($name, $this->attributesPlain);
    }

    protected function isAnAliasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributeAliases);
    }

    abstract protected function readRawDataFromStore(string $key, $default = null): mixed;

    abstract protected function writeRawDataToStore(string $key, mixed $data): void;
}
