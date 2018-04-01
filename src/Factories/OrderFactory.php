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
use Vanilo\Order\Contracts\Billpayer;
use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Order\Contracts\OrderNumberGenerator;
use Vanilo\Order\Events\OrderWasCreated;
use Vanilo\Order\Exceptions\CreateOrderException;
use Vanilo\Order\Models\BillpayerProxy;

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

            $order->fill(array_except($data, ['billpayer', 'shippingAddress']));
            $order->number  = $data['number'] ?? $this->orderNumberGenerator->generateNumber($order);
            $order->user_id = $data['user_id'] ?? auth()->id();
            $order->save();

            $this->createBillpayer($order, $data);
            $this->createShippingAddress($order, $data);

            $this->createItems($order,
                array_map(function($item) {
                    // Default quantity is 1 if unspecified
                    $item['quantity'] = $item['quantity'] ?? 1;
                    return $item;
                }, $items)
            );

            $order->save();

        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        DB::commit();

        event(new OrderWasCreated($order));

        return $order;
    }

    protected function createShippingAddress(Order $order, array $data)
    {
        if ($address = isset($data['shippingAddress'])) {
            $order->shippingAddress()->associate(
                $this->createOrCloneAddress($data['shippingAddress'], AddressTypeProxy::SHIPPING())
            );
        }
    }

    protected function createBillpayer(Order $order, array $data)
    {
        if (isset($data['billpayer'])) {

            $address = $this->createOrCloneAddress($data['billpayer']['address'], AddressTypeProxy::BILLING());

            $billpayer = app(Billpayer::class);
            $billpayer->fill(array_except($data['billpayer'], 'address'));
            $billpayer->address()->associate($address);
            $billpayer->save();

            $order->billpayer()->associate($billpayer);
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

    private function createOrCloneAddress($address, AddressType $type = null)
    {
        if ($address instanceof Address) {
            $address = $this->addressToAttributes($address);
        } elseif (!is_array($address)) {
            throw new CreateOrderException(
                sprintf(
                    'Address data is %s but it should be either an Address or an array',
                    gettype($address)
                )
            );
        }

        $type = is_null($type) ? AddressTypeProxy::defaultValue() : $type;
        $address['type'] = $type;
        $address['name'] = empty(array_get($address,'name')) ? '-' : $address['name'];

        return AddressProxy::create($address);
    }

}
