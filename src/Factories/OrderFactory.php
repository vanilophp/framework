<?php
/**
 * Contains the OrderFactory class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-30
 *
 */


namespace Vanilo\Order\Factories;


use Illuminate\Support\Facades\DB;
use Konekt\Address\Contracts\AddressType;
use Konekt\Address\Models\AddressProxy;
use Konekt\Address\Models\AddressTypeProxy;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Buyable;
use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Order\Contracts\OrderNumberGenerator;
use Vanilo\Order\Events\OrderWasCreated;
use Vanilo\Order\Exceptions\CreateOrderException;

class OrderFactory implements OrderFactoryContract
{
    /** @var OrderNumberGenerator */
    protected $orderNumberGenerator;

    public function __construct(OrderNumberGenerator $generator)
    {
        $this->orderNumberGenerator = $generator;
    }

    /**
     * @inheritDoc
     */
    public function createFromDataArray(array $data, array $items): Order
    {
        if (empty($items)) {
            throw new CreateOrderException(__('Can not create an order without items'));
        }

        DB::beginTransaction();

        try {
            $order = app(Order::class);

            $order->fill($data);
            $order->number  = $data['number'] ?? $this->orderNumberGenerator->generateNumber($order);
            $order->user_id = $data['user_id'] ?? auth()->id();
            $order->save();

            $this->createAddresses($order, $data);

            $this->createItems($order,
                array_map(function($item) {
                    // Default quantity is 1 if unspecified
                    $item['quantity'] = $item['quantity'] ?? 1;
                    return $item;
                }, $items)
            );

        } catch (\Exception $e) {
            DB::rollBack();
        }

        DB::commit();

        event(new OrderWasCreated($order));

        return $order;
    }

    protected function createAddresses(Order $order, array $data)
    {
        if (isset($data['billingAddress']) && $data['billingAddress'] instanceof Address) {
            $order->billingAddress()->associate(
                $this->cloneAddress($data['billingAddress'], AddressTypeProxy::BILLING())
            );
        }

        if (isset($data['shippingAddress']) && $data['shippingAddress'] instanceof Address) {
            $order->shippingAddress()->associate(
                $this->cloneAddress($data['shippingAddress'], AddressTypeProxy::SHIPPING())
            );
        }
    }

    protected function createItems(Order $order, array $items)
    {
        $that = $this;
        $hasBuyables = collect($items)->contains(function ($item) use ($that) {
            return $that->itemContainsABuyable($item);
        });

        if (!$hasBuyables) { // This is faster
            $order->items()->createMany($items);
        } else {
            foreach ($items as $item) {
               $this->createItem($order, $item);
            }
        }
    }

    /**
     * Creates a single item for the given order
     *
     * @param Order $order
     * @param array $item
     */
    protected function createItem(Order $order, array $item)
    {
        if ($this->itemContainsABuyable($item)) {
            /** @var Buyable $product */
            $product = $item['product'];
            $item = array_merge($item, [
                'product_type' => $product->morphTypeName(),
                'product_id'   => $product->getId(),
                'price'        => $product->getPrice(),
                'name'         => $product->getName()
            ]);
            unset($item['product']);
        }

        $order->items()->create($item);
    }

    /**
     * Returns whether an instance contains a buyable object
     *
     * @param array $item
     *
     * @return bool
     */
    private function itemContainsABuyable(array $item)
    {
        return isset($item['product']) && $item['product'] instanceof Buyable;
    }

    private function addressToAttributes(Address $address)
    {
        return [
            'name'       => $address->getName(),
            'postalcode' => $address->getPostalCode(),
            'country_id' => $address->getCountryCode(),
            /** @todo Convert Province code to province_id */
            'city'       => $address->getCity(),
            'address'    => $address->getAddress(),
        ];
    }

    private function cloneAddress(Address $address, AddressType $type)
    {
        return AddressProxy::create(
            array_merge(
                ['type' => $type],
                $this->addressToAttributes($address)
            )
        );
    }

}
