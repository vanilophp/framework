<?php

declare(strict_types=1);

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

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Konekt\Address\Contracts\AddressType;
use Konekt\Address\Models\AddressProxy;
use Konekt\Address\Models\AddressTypeProxy;
use ReflectionFunction;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Buyable;
use Vanilo\Order\Contracts\Billpayer;
use Vanilo\Order\Contracts\Order;
use Vanilo\Order\Contracts\OrderFactory as OrderFactoryContract;
use Vanilo\Order\Contracts\OrderItem;
use Vanilo\Order\Contracts\OrderNumberGenerator;
use Vanilo\Order\Events\OrderWasCreated;
use Vanilo\Order\Exceptions\CreateOrderException;

class OrderFactory implements OrderFactoryContract
{
    /** @var OrderNumberGenerator */
    protected $orderNumberGenerator;

    /** @var array|int[] Contains the mapping of the original item IDs to the created order item IDs  */
    private array $sourceItemToOrderItemIdMap = [];

    public function __construct(OrderNumberGenerator $generator)
    {
        $this->orderNumberGenerator = $generator;
    }

    public function createFromDataArray(array $data, array $items, array|callable|null $hooks = null, array|callable $itemHooks = null): Order
    {
        if (empty($items)) {
            throw new CreateOrderException(__('Can not create an order without items'));
        }

        DB::beginTransaction();

        try {
            $order = app(Order::class);

            $order->fill(Arr::except($data, ['billpayer', 'shippingAddress']));
            $order->number = $data['number'] ?? $this->orderNumberGenerator->generateNumber($order);
            $order->user_id = $data['user_id'] ?? auth()->id();
            $order->domain ??= request()?->getHost();
            $order->save();

            $this->createBillpayer($order, $data);
            $this->createShippingAddress($order, $data);

            $this->createItems($order, array_map(fn ($item) => $item + ['quantity' => 1], $items), ...Arr::wrap($itemHooks));
            $this->setOrderItemParentRelationships($order, $items);

            foreach (Arr::wrap($hooks) as $hook) {
                $this->callHook($hook, $order, $data, $items);
            }

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
            $billpayer->fill(Arr::except($data['billpayer'], 'address'));
            $billpayer->address()->associate($address);
            $billpayer->save();

            $order->billpayer()->associate($billpayer);
        }
    }

    protected function createItems(Order $order, array $items, callable ...$hooks)
    {
        foreach ($items as $item) {
            $createdOrderItem = $this->createItem($order, $item);
            foreach ($hooks as $hook) {
                $this->callItemHook($hook, $createdOrderItem, $order, $items, $item);
            }
        }
    }

    /**
     * Creates a single item for the given order
     *
     * @param Order $order
     * @param array $item
     */
    protected function createItem(Order $order, array $item): OrderItem
    {
        if ($this->itemContainsABuyable($item)) {
            /** @var Buyable $product */
            $product = $item['product'];
            $item = array_merge($item, [
                'product_type' => $product->morphTypeName(),
                'product_id' => $product->getId(),
                'price' => $product->getPrice(),
                'name' => $product->getName()
            ]);
            unset($item['product']);
        }

        $orderItem = $order->items()->create($item);

        $this->addToSourceItemIdMap($item, $orderItem);

        return $orderItem;
    }

    /**
     * @throws \ReflectionException
     */
    protected function callHook(callable $hook, mixed $order, array $data, array $items): void
    {
        $ref = new ReflectionFunction($hook);
        match ($ref->getNumberOfParameters()) {
            0 => $hook(),
            1 => $hook($order),
            2 => $hook($order, $data),
            default => $hook($order, $data, $items),
        };
    }

    /**
     * @throws \ReflectionException
     */
    protected function callItemHook(callable $hook, OrderItem $orderItem, Order $order, array $sourceItems, array $sourceItem): void
    {
        $ref = new ReflectionFunction($hook);
        match ($ref->getNumberOfParameters()) {
            0 => $hook(),
            1 => $hook($orderItem),
            2 => $hook($orderItem, $order),
            3 => $hook($orderItem, $order, $sourceItems),
            default => $hook($orderItem, $order, $sourceItems, $sourceItem),
        };
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
            'name' => $address->getName(),
            'postalcode' => $address->getPostalCode(),
            'country_id' => $address->getCountryCode(),
            /** @todo Convert Province code to province_id */
            'city' => $address->getCity(),
            'address' => $address->getAddress(),
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

        $type = is_null($type) ? AddressTypeProxy::defaultValue() : $type->value();
        $address['type'] = $type;
        $address['name'] = empty(Arr::get($address, 'name')) ? '-' : $address['name'];

        return AddressProxy::create($address);
    }

    private function addToSourceItemIdMap(array $sourceItem, OrderItem $createdOrderItem): void
    {
        if (isset($sourceItem['id'])) {
            $this->sourceItemToOrderItemIdMap[(string) $sourceItem['id']] = $createdOrderItem->id;
        }
    }

    private function setOrderItemParentRelationships(Order $order, array $sourceItems): void
    {
        foreach ($sourceItems as $sourceItem) {
            if (isset($sourceItem['id']) && isset($sourceItem['parent_id'])) {
                $idOfTheOrderItemThatHasToHaveAParent = $this->sourceItemToOrderItemIdMap[(string) $sourceItem['id']] ?? null;
                $idOfTheParentOrderItem = $this->sourceItemToOrderItemIdMap[(string) $sourceItem['parent_id']] ?? null;

                // If we have the right ID mappings, we can properly set the parent order item relationship based on the source item relationship
                if ($idOfTheOrderItemThatHasToHaveAParent && $idOfTheParentOrderItem) {
                    $orderItem = $order->items()->find($idOfTheOrderItemThatHasToHaveAParent);
                    $orderItem->update(['parent_id' => $idOfTheParentOrderItem]);
                }
            }
        }
    }
}
